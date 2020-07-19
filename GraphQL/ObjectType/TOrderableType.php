<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Scalar\IntType;

/**
 * Trait TOrderableType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 * @author mirkl
 */
trait TOrderableType
{
    /**
     * @param ObjectTypeConfig $config
     */
    protected function buildOrderable(ObjectTypeConfig $config)
    {
        $config->addFields([
            'order' => [
                'type' => new IntType(),
                'description' => 'Orderable item order'
            ]
        ]);
    }
}
