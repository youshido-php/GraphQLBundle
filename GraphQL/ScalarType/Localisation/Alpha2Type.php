<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation;

use BastSys\GraphQLBundle\GraphQL\ScalarType\TDynamicName;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class Alpha2Type
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author  mirkl
 */
class Alpha2Type extends StringType
{
    use TDynamicName;

    const alpha2RE = '/^[A-Z]{2}$/';

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        return parent::isValidValue($value) &&
            (is_null($value) || preg_match(self::alpha2RE, $value));
    }

    /**
     * @param null $value
     * @return string|null
     */
    public function getValidationError($value = null)
    {
        return "Wrong value '$value'";
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return parent::getDescription() . ' Represents country alpha2 code, matches ' . self::alpha2RE . '.';
    }
}
