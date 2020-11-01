<?php

namespace App\Security\Voter;

use App\Entity\Secret;
use App\Repository\SecretRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SecretVoter extends Voter
{
    /**
     * @var SecretRepository
     */
    private SecretRepository $secretRepository;

    public function __construct(SecretRepository $secretRepository)
    {
        $this->secretRepository = $secretRepository;
    }

    protected function supports($attribute, $subject)
    {
        return $attribute === 'SECRET_SHOW';
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        if (! $subject instanceof Secret) {
            return false;
        }

        return $this->secretRepository->userHasAccessToSecret($subject, $user);
    }
}
