<?php

namespace BastSys\GraphQLBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Youshido\GraphQL\Parser\Ast\Query;

/**
 * Class AFreeOperationVoter
 * @package BastSys\GraphQLBundle\Security\Voter\GraphQL
 * @author mirkl
 */
abstract class AFreeOperationVoter extends Voter
{
    /** @var string[] */
    protected array $operations = ['__schema'];

    /**
     * Adds a free operation to the storage
     *
     * @param string $operation
     * @internal insert through container
     */
    public function addFreeOperation(string $operation): void
    {
        $this->operations[] = $operation;
    }

    /**
     * Checks whether given operation is free
     *
     * @param string $operation
     *
     * @return bool
     */
    public function isFreeOperation(string $operation): bool
    {
        return in_array($operation, $this->operations);
    }

    /**
     * @param string $attribute
     * @param $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof Query) {
            return false;
        }
        return in_array($subject->getName(), $this->operations);
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return true; // All free operations are supported & accepted
    }

}
