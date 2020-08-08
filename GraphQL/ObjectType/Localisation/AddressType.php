<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation;

use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\BooleanType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**
 * Class AddressType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation
 * @author mirkl
 */
class AddressType extends AbstractObjectType
{
    /**
     * @param ObjectTypeConfig $config
     */
    public function build($config)
    {
        $config->addFields([
            'city' => [
                'type' => new StringType(),
                'description' => 'e.g. Brno'
            ],
            'street' => [
                'type' => new StringType(),
                'description' => 'e.g. Jírova'
            ],
            'descriptiveNumber' => [
                'type' => new StringType(),
                'description' => 'e.g. 2179/45'
            ],
            'zip' => [
                'type' => new StringType(),
                'description' => 'e.g. 62800'
            ],
            'country' => [
                'type' => new CountryType(),
                'description' => 'Address country'
            ],
            'isValid' => [
                'type' => new BooleanType(),
                'description' => 'Whether address is valid'
            ]
        ]);
    }

}
