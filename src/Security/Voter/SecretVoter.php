<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Secret;
use App\Repository\SecretRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

use function in_array;

class SecretVoter extends Voter
{
    private SecretRepository $secretRepository;

    public function __construct(SecretRepository $secretRepository)
    {
        $this->secretRepository = $secretRepository;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        return $attribute === 'SECRET_SHOW';
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
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
