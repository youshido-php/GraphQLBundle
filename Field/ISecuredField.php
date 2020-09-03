<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\Field;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Youshido\GraphQL\Field\FieldInterface;

/**
 * Interface ISecuredField
 * @package BastSys\GraphQLBundle\Field
 * @author mirkl
 */
interface ISecuredField extends FieldInterface
{
    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function isGranted(TokenInterface $token): bool;
}
