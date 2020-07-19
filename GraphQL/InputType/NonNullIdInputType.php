<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class NonNullIdInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author mirkl
 */
class NonNullIdInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     * @throws ConfigurationException
     */
    public function build($config)
    {
        $config->addField('id', [
            'type' => new NonNullType(new StringType()),
            'description' => 'An id than can be either Uuid or String'
        ]);
    }
}
