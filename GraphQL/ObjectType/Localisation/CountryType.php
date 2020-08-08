<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation;

use BastSys\GraphQLBundle\GraphQL\ObjectType\ATranslatableEntityType;
use BastSys\GraphQLBundle\GraphQL\ObjectType\ATranslationType;
use BastSys\GraphQLBundle\GraphQL\ScalarType\Localisation\Alpha2Type;
use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Scalar\StringType;

class CountryType extends ATranslatableEntityType
{
    /**
     * @return ATranslationType
     */
    protected function getTranslationType(): ATranslationType
    {
        return new CountryTranslationType();
    }

    /**
     * @param ObjectTypeConfig $config
     */
    protected function buildTranslatable($config): void
    {
        $config->removeField('id');
        $config->addFields([
            'id' => [
                'type' => new Alpha2Type(),
                'description' => 'Id represented by alpha2'
            ],
            'alpha2' => [
                'type' => new Alpha2Type(),
                'description' => "2 character country code (e.g. 'CZ')'"
            ],
            'alpha3' => [
                'type' => new StringType(),
                'description' => "3 character country code (e.g. 'CZE')"
            ],
            'code' => [
                'type' => new StringType(),
                'description' => "3 digit character code (e.g. '203')"
            ],
            'defaultCurrency' => [
                'type' => new CurrencyType(),
                'description' => 'The most used currency in the country'
            ],
            'ownTranslationName' => [
                'type' => new StringType(),
                'description' => 'Name of this country translated to its main language'
            ],
            'flagLink' => [
                'type' => new StringType(),
                'description' => 'Country flag thumbnail image'
            ]
        ]);
    }

}

