<?php

namespace App\Security\Voter;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[AutoconfigureTag(name: 'security.voter', attributes: ['priority' => 999])]
class AdminVoter implements VoterInterface
{
    /**
     * @inheritDoc
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return self::ACCESS_DENIED;
        }

        if (\in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
