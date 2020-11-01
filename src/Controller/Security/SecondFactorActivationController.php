<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\QrCode\QrCodeGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function base64_encode;

/**
 * @Route(path="/2fa_activation", name="2fa_login_activation", methods={"GET", "POST"})
 */
class SecondFactorActivationController extends AbstractController
{
    private QrCodeGenerator $qrCodeGenerator;
    private UserRepository $userRepository;
    private EntityManagerInterface $em;
    private string $assetsPath;
    private GoogleAuthenticatorInterface $googleAuthenticator;

    public function __construct(
        QrCodeGenerator $qrCodeGenerator,
        GoogleAuthenticatorInterface $googleAuthenticator,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        string $assetsPath
    ) {
        $this->qrCodeGenerator     = $qrCodeGenerator;
        $this->userRepository      = $userRepository;
        $this->em                  = $em;
        $this->assetsPath          = $assetsPath;
        $this->googleAuthenticator = $googleAuthenticator;
    }

    public function __invoke(Request $request): Response
    {
        $user               = $this->findUserToBeActivated($request);
        $qrcodeEncodedImage = $this->generateQrCode($user);

        $form = $this->createActivationForm($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->getData()['code'] ?? '';

            $codeIsValid = $this->googleAuthenticator->checkCode($user, $code);

            if (! $codeIsValid) {
                $form->get('code')->addError(new FormError('El código no es válido'));

                return $this->render('security/2fa_activation.html.twig', [
                    'qrcode' => $qrcodeEncodedImage,
                    'form' => $form->createView(),
                ]);
            }

            $user->setGoogleActivationSecret(null);
            $this->em->flush();
            $this->addFlash('success', 'El código ha sido activado');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/2fa_activation.html.twig', [
            'qrcode' => $qrcodeEncodedImage,
            'form' => $form->createView(),
        ]);
    }

    private function findUserToBeActivated(Request $request): User
    {
        $code = $request->get('code');

        if (! $code) {
            throw new BadRequestException('Missing code parameter');
        }

        $user = $this->userRepository->findOneBy(['googleActivationSecret' => $code]);
        if (! $user) {
            throw new BadRequestException('Invalid code parameter');
        }

        return $user;
    }

    private function generateQrCode(User $user): string
    {
        $qrCode = $this->qrCodeGenerator->getGoogleAuthenticatorQrCode($user);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH());
        $qrCode->setLogoPath($this->assetsPath . '/images/logo-cuadrado-fondo-blanco.png');
        $qrCode->setLogoHeight(100);
        $qrCode->setLogoWidth(100);

        return base64_encode($qrCode->writeString());
    }

    private function createActivationForm(Request $request): FormInterface
    {
        $form = $this->createFormBuilder()
            ->add('code', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['placeholder' => 'Introduzca el código'],
            ])
            ->getForm();
        $form->handleRequest($request);

        return $form;
    }
}
