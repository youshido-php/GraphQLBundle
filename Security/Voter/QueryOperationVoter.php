<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Parser\Ast\Query;
use Youshido\GraphQLBundle\Security\Manager\SecurityManagerInterface;

/**
 * Class QueryOperationVoter
 * @package BastSys\GraphQLBundle\Security\Voter
 * @author mirkl
 */
abstract class QueryOperationVoter extends Voter
{
    /**
     * @var FieldInterface
     */
    private FieldInterface $field;

    /**
     * QueryOperationVoter constructor.
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        return $attribute === SecurityManagerInterface::RESOLVE_ROOT_OPERATION_ATTRIBUTE &&
            $subject instanceof Query &&
            $subject->getName() === $this->field->getName();
    }

    /**
     * @param string $attribute
     * @param Query $subject
     * @param TokenInterface $token
     * @return bool
     */
    abstract protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token);

}
