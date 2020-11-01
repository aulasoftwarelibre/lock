<?php

namespace App\Security\Voter;

use App\Entity\OrganizationUnit;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OrganizationUnitVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'ORGANIZATION_UNIT_MEMBER';
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

        if (! $subject instanceof OrganizationUnit) {
            return false;
        }

        return $subject->getMembers()->filter(
            fn (User $member) => $member->getUsername() === $user->getUsername()
        )->count() !== 0;
    }
}
