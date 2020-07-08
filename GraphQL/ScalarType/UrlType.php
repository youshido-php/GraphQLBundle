<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

/**
 * Class UrlType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
class UrlType extends RegExpStringType
{
    /**
     * Url reg exp
     */
    const URL_RE = '/https?:\/\/.{4,2048}/';

    /**
     * UrlType constructor.
     */
    public function __construct()
    {
        parent::__construct(self::URL_RE);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return parent::getDescription() . ' Represents an url.';
    }
}
