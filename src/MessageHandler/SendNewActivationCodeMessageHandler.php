<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\SendNewActivationCodeMessage;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendNewActivationCodeMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;
    private MailerInterface $mailer;
    private GoogleAuthenticatorInterface $googleAuthenticator;
    private string $assetsPath;
    private string $mailFrom;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        MailerInterface $mailer,
        GoogleAuthenticatorInterface $googleAuthenticator,
        string $assetsPath,
        string $mailFrom
    )
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->googleAuthenticator = $googleAuthenticator;
        $this->assetsPath = $assetsPath;
        $this->mailFrom = $mailFrom;
    }

    public function __invoke(SendNewActivationCodeMessage $message)
    {
        $user = $this->userRepository->findOneBy(['username' => $message->getUsername()]);

        if (!$user instanceof User) {
            return;
        }

        $this->revokeUserSecrets($user);
        $this->sendEmail($user);
    }

    private function sendEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from($this->mailFrom)
            ->to($user->getEmail())
            ->subject('[AulaSL] Código QR de Lock')
            ->embedFromPath($this->assetsPath . '/images/logo-horizontal-transparente.png', 'logo')
            ->embedFromPath($this->assetsPath . '/images/icon_facebook.png', 'facebook')
            ->embedFromPath($this->assetsPath . '/images/icon_instagram.png', 'instagram')
            ->embedFromPath($this->assetsPath . '/images/icon_telegram.png', 'telegram')
            ->embedFromPath($this->assetsPath . '/images/icon_twitter.png', 'twitter')
            ->embedFromPath($this->assetsPath . '/images/icon_youtube.png', 'youtube')
            ->htmlTemplate('mail/qrcode_message.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }

    /**
     * @param User $user
     */
    private function revokeUserSecrets(User $user): void
    {
        $user->setGoogleActivationSecret(
            $this->googleAuthenticator->generateSecret()
        );
        $user->setGoogleAuthenticatorSecret(
            $this->googleAuthenticator->generateSecret()
        );
        $this->em->flush();
    }
}
