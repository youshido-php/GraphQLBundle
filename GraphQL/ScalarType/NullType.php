<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\AbstractScalarType;

/**
 * Class NullType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
class NullType extends AbstractScalarType
{
    /**
     * @param $value
     * @return bool
     */
    public function isValidValue($value)
    {
        return $value === null;
    }

    /**
     * @param null $value
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        return 'Value is not null';
    }
}
