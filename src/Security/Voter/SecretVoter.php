<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Secret;
use App\Entity\User;
use App\Repository\SecretRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SecretVoter extends Voter
{
    public function __construct(
        private SecretRepository $secretRepository,
        private AccessDecisionManagerInterface $decisionManager
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === 'SECRET_SHOW';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof User) {
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        if (! $subject instanceof Secret) {
            return false;
        }

        return $this->secretRepository->userHasAccessToSecret($subject, $user);
    }
}
