<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\AbstractScalarType;

class ValidPasswordType extends AbstractScalarType
{
    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Represents a string type that is:
                at least 5 characters long, 
                has at least one upper case character,
                has at least one lower case character and
                has at least one digit character';
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        if ($value === null) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        $length = strlen($value);
        if (!($length > 5 && $length < 255)) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $value)) {
            return false;
        }

        if (!preg_match('/\\d/', $value)) {
            return false;
        }

        return true;
    }

    /**
     * @param null $value
     *
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            return 'Password must be as string';
        }

        $length = strlen($value);
        if (!($length > 5)) {
            return 'Password must be at least 5 characters long';
        }

        if (!preg_match('/[A-Z]/', $value)) {
            return 'Password must contain at least one upper case character';
        }

        if (!preg_match('/[a-z]/', $value)) {
            return 'Password must contain at least one lower case character';
        }

        if (!preg_match('/\\d/', $value)) {
            return 'Password must contain at least one digit character';
        }

        return null;
    }
}
