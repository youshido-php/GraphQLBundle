<?php

namespace BastSys\GraphQLBundle\GraphQL\ScalarType;

use BastSys\UtilsBundle\Model\Strings;

/**
 * Trait TDynamicName
 *
 * Provides dynamic getName for scalar types
 *
 * @package BastSys\GraphQLBundle\GraphQL\ScalarType
 * @author mirkl
 */
trait TDynamicName
{
    /**
     * Dynamically creates a name for the class using this fragment
     *
     * @return string
     */
    public function getName()
    {
        return str_replace('Type', '', Strings::getSimpleClassName(get_class($this)));
    }
}
