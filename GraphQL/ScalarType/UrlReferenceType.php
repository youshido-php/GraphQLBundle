<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class UrlReferenceType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author  mirkl
 */
class UrlReferenceType extends StringType
{
    /** @var string */
    const URL_REFERENCE_REGEX = '/^[a-z0-9\-]{1,64}$/';

    /**
     * @return false|string
     */
    public function getName()
    {
        return 'UrlReferenceType';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $regex = self::URL_REFERENCE_REGEX;
        return "Represents url reference used to access entity in url. Must match /$regex/. " . parent::getDescription();
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function isValidValue($value)
    {
        return $value === null ||
            (is_string($value) && preg_match(self::URL_REFERENCE_REGEX, $value));
    }
}
