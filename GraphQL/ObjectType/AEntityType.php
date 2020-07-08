<?php

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use BastSys\GraphQLBundle\GraphQL\ScalarType\UuidType;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\AbstractScalarType;

/**
 * Class AEntityType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 */
abstract class AEntityType extends AbstractObjectType
{
    /**
     * @param ObjectTypeConfig $config
     * @throws ConfigurationException
     */
    public function build($config)
    {
        $config->addField('id', [
            'type' => new NonNullType($this->getIdType()),
            'description' => 'Id that can be used to perform operations with this entity'
        ]);
    }

    /**
     * Override to set custom id type
     *
     * @return AbstractScalarType
     */
    protected function getIdType(): AbstractScalarType
    {
        return new UuidType();
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $name = $this->getName();

        return "$name entity that is identified by field 'id'.";
    }
}
