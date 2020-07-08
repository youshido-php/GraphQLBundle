<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

abstract class ATranslationInputType extends AInputObjectType
{
    /**
     * @param InputObjectTypeConfig $config
     *
     * @throws ConfigurationException
     */
    public final function build($config)
    {
        $config->addField('locale', new NonNullType(new StringType()));

        $this->addTranslationFields($config);
    }

    /**
     * @param InputObjectTypeConfig $config
     */
    abstract public function addTranslationFields($config): void;

    public function getDescription()
    {
        return 'A field that creates or updates one particular translation';
    }
}
