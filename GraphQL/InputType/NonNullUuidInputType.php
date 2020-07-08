<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\ScalarType\UuidType;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;

/**
 * Class NonNullIdInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author  mirkl
 */
class NonNullUuidInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     *
     * @throws ConfigurationException
     */
    public function build($config)
    {
        $config->addFields([
            'id' => new NonNullType(new UuidType())
        ]);
    }
}
