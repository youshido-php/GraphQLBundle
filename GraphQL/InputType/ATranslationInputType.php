<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use Youshido\GraphQL\Config\Object\InputObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class ATranslationInputType
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author mirkl
 */
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'A field that creates or updates one particular translation';
    }
}
