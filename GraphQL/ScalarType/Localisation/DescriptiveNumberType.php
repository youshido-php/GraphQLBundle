<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation;

use BastSys\GraphQLBundle\GraphQL\ScalarType\RegExpStringType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\TDynamicName;

/**
 * Class DescriptiveNumberRE
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation
 * @author mirkl
 */
class DescriptiveNumberType extends RegExpStringType
{
    use TDynamicName;

    /**
     *  Descriptive number reg exp
     */
    const descriptiveNumberRE = '/^[\d\w\/\-]+$/';

    /**
     * DescriptiveNumberRE constructor.
     */
    public function __construct()
    {
        parent::__construct(self::descriptiveNumberRE);
    }
}
