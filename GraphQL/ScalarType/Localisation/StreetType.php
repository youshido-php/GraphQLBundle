<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation;

use BastSys\GraphQLBundle\GraphQL\ScalarType\RegExpStringType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\TDynamicName;

/**
 * Class StreetType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation
 * @author mirkl
 */
class StreetType extends RegExpStringType
{
    use TDynamicName;

    /**
     * City reg exp
     */
    const streetRE = CityType::cityRE;

    /**
     * StreetType constructor.
     */
    public function __construct()
    {
        parent::__construct(self::streetRE, true);
    }
}
