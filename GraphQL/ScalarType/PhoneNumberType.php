<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

/**
 * Class PhoneNumberType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation
 * @author mirkl
 */
class PhoneNumberType extends RegExpStringType
{
    /**
     *
     */
    const RE = '/^[()\d +\-]{6,}$/';

    /**
     * PhoneNumberType constructor.
     */
    public function __construct()
    {
        parent::__construct(self::RE);
    }
}
