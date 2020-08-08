<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation;

use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\FloatType;

/**
 * Class PriceType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation
 * @author mirkl
 */
class PriceType extends AbstractObjectType
{
    /**
     * @param \Youshido\GraphQL\Config\Object\ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'value' => [
                'type' => new FloatType(),
                'description' => 'Price value'
            ],
            'currency' => [
                'type' => new CurrencyType(),
                'description' => 'Price currency'
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Represents a price that is defined by its value and currency";
    }

}
