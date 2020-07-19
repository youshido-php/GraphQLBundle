<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation;

use BastSys\GraphQLBundle\GraphQL\ScalarType\RegExpStringType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\TDynamicName;

/**
 * Class CityType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation
 * @author mirkl
 */
class CityType extends RegExpStringType
{
    use TDynamicName;

    /**
     *
     */
    const cityRE = '/^[\w ]+$/';

    /**
     * CityType constructor.
     */
    public function __construct()
    {
        parent::__construct(self::cityRE, true);
    }
}
