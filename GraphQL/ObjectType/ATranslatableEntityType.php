<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType;

use BastSys\LocaleBundle\Entity\Translation\ITranslatable;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Exception\ConfigurationException;
use Youshido\GraphQL\Type\ListType\ListType;

/**
 * Class ATranslatableEntityType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType
 * @author mirkl
 */
abstract class ATranslatableEntityType extends AEntityType
{
    /**
     * @param ObjectTypeConfig $config
     * @throws ConfigurationException
     */
    public final function build($config)
    {
        parent::build($config);

        $this->buildTranslatable($config);

        $translationType = $this->getTranslationType();

        $config->addField('translations', new ListType($translationType));

        // adding translation fields that are translated to current locale
        foreach ($translationType->getFields() as $field) {
            $fieldName = $field->getName();

            if ($fieldName !== 'locale') {
                $config->addField($fieldName, [
                    'type' => $field->getType(),
                    'description' => "Translated $fieldName to current locale",
                    'resolve' => function (ITranslatable $translatable) use ($fieldName) {
                        return $translatable->getTranslatedField($fieldName);
                    }
                ]);
            }
        }
    }

    /**
     * @param ObjectTypeConfig $config
     */
    abstract protected function buildTranslatable($config): void;

    /**
     * Gets type that is inserted to this translatable type as translations list field and its fields are also inserted
     *
     * @return ATranslationType
     */
    abstract protected function getTranslationType(): ATranslationType;

    /**
     * @return string
     */
    public function getDescription()
    {
        return parent::getDescription() . ' ' .
            'Represents translatable type. 
            Contains the same fields as its translation. 
            If requested, these fields are returned in current locale.';
    }
}
