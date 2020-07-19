<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class EmailAddressType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author  mirkl
 */
class EmailAddressType extends StringType
{
    /** @var string */
    const EMAIL_REGEX = '/^[-_\w.]+@[-_\w.]+\.\w{2,}$/';

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Represents e-mail address. Must match '" . self::EMAIL_REGEX . "' " . parent::getDescription();
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        return $value === null ||
            (is_string($value) && preg_match(self::EMAIL_REGEX, $value));
    }

    /**
     * @param null $value
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        $error = parent::getValidationError($value);
        if ($error) {
            return $error;
        }

        return 'Invalid e-mail address format';
    }
}
