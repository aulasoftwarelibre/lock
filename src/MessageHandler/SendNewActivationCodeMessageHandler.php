<?php

declare(strict_types=1);

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
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
        private readonly MailerInterface $mailer,
        private readonly GoogleAuthenticatorInterface $googleAuthenticator,
        private readonly string $assetsPath,
        private readonly string $mailFrom,
    ) {
    }

    public function __invoke(SendNewActivationCodeMessage $message): void
    {
        $user = $this->userRepository->findOneBy(['username' => $message->getUsername()]);

        if (! $user instanceof User) {
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
            ->context(['user' => $user]);

        $this->mailer->send($email);
    }

    private function revokeUserSecrets(User $user): void
    {
        $user->setGoogleActivationSecret(
            $this->googleAuthenticator->generateSecret(),
        );
        $user->setGoogleAuthenticatorSecret(
            $this->googleAuthenticator->generateSecret(),
        );
        $this->em->flush();
    }
}
