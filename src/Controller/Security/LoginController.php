<?php

declare(strict_types=1);

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function __construct(private readonly AuthenticationUtils $authenticationUtils)
    {
    }

    #[Route(path: '/', name: 'login')]
    public function login(): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        if ($this->getUser() instanceof UserInterface) {
            return $this->redirectToRoute('admin');
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'page_title' => 'Iniciar sesión en LOCK',
        ]);
    }
}
