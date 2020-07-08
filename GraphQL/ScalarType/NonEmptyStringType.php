<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Type for string that must contain at least 1 character
 *
 * Class NonEmptyStringType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 */
class NonEmptyStringType extends StringType
{
    /**
     * @return bool|string
     */
    public function getName()
    {
        return 'NonEmptyString';
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        return parent::isValidValue($value) && ($value === null || !empty($value));
    }

    /**
     * @param null $value
     *
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        $error = parent::getValidationError($value);

        if ($error) {
            return $error;
        }

        if ($value !== null && empty($value)) {
            return 'NonEmptyStringType must contain at least 1 character';
        }

        return null;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return parent::getDescription() . ' This variation of StringType must contain at least one character.';
    }
}
