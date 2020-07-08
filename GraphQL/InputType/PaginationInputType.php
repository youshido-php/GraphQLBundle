<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Type\Scalar\IntType;

/**
 * Class PaginationInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author  mirkl
 */
class PaginationInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'limit' => [
                'type' => new IntType(),
                'description' => 'Maximum amount of items that should be returned'
            ],
            'offset' => [
                'type' => new IntType(),
                'description' => 'Amount of items from the start that should be skipped'
            ]
        ]);
    }
}
