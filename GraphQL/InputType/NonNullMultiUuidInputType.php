<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\ObjectType\NonEmptyListType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\UuidType;
use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Exception\ValidationException;
use Youshido\GraphQL\Type\NonNullType;

/**
 * Class NonNullMultiUuidInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author mirkl
 */
class NonNullMultiUuidInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     * @throws ConfigurationException
     * @throws ValidationException
     */
    public function build($config)
    {
        $config->addField('id', new NonNullType(
            new NonEmptyListType(
                new UuidType()
            )
        ));
    }
}
