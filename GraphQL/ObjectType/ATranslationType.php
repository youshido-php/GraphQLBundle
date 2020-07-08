<?php

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

abstract class ATranslationType extends AbstractObjectType
{
    /**
     * @param ObjectTypeConfig $config
     * @throws ConfigurationException
     */
    public final function build($config)
    {
        $config->addField('locale', [
            'description' => 'Locale of this translation object',
            'type' => new StringType()
        ]);
        $this->addTranslationFields($config);
    }

    /**
     * Builds the translation fields for this type
     *
     * @param ObjectTypeConfig $config
     */
    abstract public function addTranslationFields($config): void;

    public function getDescription()
    {
        return 'Translation object for one particular locale';
    }
}
