<?php

declare(strict_types=1);

namespace App\MessageHandler\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use AulaSoftwareLibre\OAuth2\ClientBundle\Message\FindUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class FindUserMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(FindUserMessage $findUserMessage): User
    {
        $username = $findUserMessage->getUsername();

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (! $user instanceof User) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
