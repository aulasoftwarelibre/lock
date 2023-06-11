<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public function __construct(
        private readonly AccessDecisionManagerInterface $decisionManager,
    ) {
    }

    /** @param User $subject */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === 'USER_EDIT';
    }

    /** @param User $subject */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        if (! $subject instanceof User) {
            return false;
        }

        return $subject->getUsername() === $user->getUsername();
    }
}
