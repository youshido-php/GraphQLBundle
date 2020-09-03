<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Parser\Ast\Mutation;
use Youshido\GraphQL\Parser\Ast\Query;

/**
 * Class FreeFieldVoter
 * @package BastSys\GraphQLBundle\Security\Voter\GraphQL
 * @author mirkl
 */
class FreeFieldVoter extends Voter
{
    /** @var string[] */
    protected array $freeFieldNames = ['__schema'];

    /**
     * Adds a free operation to the storage
     *
     * @param FieldInterface $field
     * @internal insert through container
     */
    public function addFreeOperation(FieldInterface $field): void
    {
        $this->freeFieldNames[] = $field->getName();
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
        return in_array($operation, $this->freeFieldNames);
    }

    /**
     * @param string $attribute
     * @param $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        if($subject instanceof Query || $subject instanceof Mutation) {
            return in_array($subject->getName(), $this->freeFieldNames);
        }

        return false;
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
