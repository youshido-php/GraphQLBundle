<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use BastSys\LocaleBundle\Entity\Translation\ITranslation;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class ATranslationType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 * @author mirkl
 */
abstract class ATranslationType extends AbstractObjectType
{
    /**
     * @param ObjectTypeConfig $config
     * @throws ConfigurationException
     */
    public final function build($config)
    {
        $config->addField('locale', [
            'type' => new NonNullType(
                new StringType()
            ),
            'description' => 'Locale of this translation object',
            'resolve' => function (ITranslation $translation) {
                return $translation->getLanguage()->getCode();
            }
        ]);
        $this->addTranslationFields($config);
    }

    /**
     * Builds the translation fields for this type
     *
     * @param ObjectTypeConfig $config
     */
    abstract public function addTranslationFields($config): void;

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Translation object for one particular locale';
    }
}
