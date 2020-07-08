<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation;

use BastSys\GraphQLBundle\GraphQL\ScalarType\RegExpStringType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\TDynamicName;

/**
 * Class ZipType
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation
 * @author mirkl
 */
class ZipType extends RegExpStringType
{
    use TDynamicName;

    /**
     * Zip reg exp
     */
    const zipRE = '/^\d{5,}$/';

    /**
     * ZipType constructor.
     */
    public function __construct()
    {
        parent::__construct(self::zipRE);
    }
}
