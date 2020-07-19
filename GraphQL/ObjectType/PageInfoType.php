<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\IntType;

/**
 * Class PageInfoType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 * @author mirkl
 */
class PageInfoType extends AbstractObjectType
{
    /**
     * @param \Youshido\GraphQL\Config\Object\ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'limit' => [
                'type' => new IntType(),
                'description' => 'How many items for this page were requested'
            ],
            'offset' => [
                'type' => new IntType(),
                'description' => 'How many items from the list beginning were skipped'
            ],
            'hasNextPage' => [
                'type' => new BooleanType(),
                'description' => 'Whether next page will contain items'
            ]
        ]);
    }
}
