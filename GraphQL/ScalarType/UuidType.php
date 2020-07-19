<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class UuidType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
class UuidType extends StringType
{
    /**
     * @return false|string
     */
    public function getName()
    {
        return 'Uuid';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Represents unique ID in the system. Has always 36 characters. ' . parent::getDescription();
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        return $value === null ||
            (is_string($value) && strlen($value) === 36);
    }
}
