<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\StringType;

class UuidType extends StringType
{
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
