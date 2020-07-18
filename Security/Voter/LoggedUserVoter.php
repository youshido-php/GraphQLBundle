<?php

namespace BastSys\GraphQLBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LoggedUserVoter
 *
 * Verifies only if there is a logged UserInterface in token
 *
 * @package BastSys\GraphQLBundle\Security\Voter\GraphQL
 * @author mirkl
 */
class LoggedUserVoter extends Voter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return $token->getUser() instanceof UserInterface;
    }

}
