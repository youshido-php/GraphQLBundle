<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Scalar\IntType;

/**
 * Trait TIndexableType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 * @author mirkl
 */
trait TIndexableType
{
    /**
     * @param ObjectTypeConfig $config
     */
    protected function buildIndexable(ObjectTypeConfig $config)
    {
        $config->addFields([
            'index' => [
                'type' => new IntType(),
                'description' => 'Index item order'
            ]
        ]);
    }
}
