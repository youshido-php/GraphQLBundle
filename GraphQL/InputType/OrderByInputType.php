<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\EnumType\OrderByDirectionType;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class OrderByInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author  mirkl
 */
class OrderByInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     *
     * @throws ConfigurationException
     */
    public function build($config)
    {
        $config->addFields([
            'field' => [
                'type' => new NonNullType(new StringType()),
                'description' => 'Field which is used in order by',
            ],
            'direction' => new OrderByDirectionType(),
        ]);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Input that determines how are items sorted before search is performed';
    }
}
