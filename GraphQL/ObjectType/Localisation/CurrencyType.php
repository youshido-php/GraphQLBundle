<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation;

use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

/**7
 * Class CurrencyType
 * @package BastSys\GraphQLBundle\GraphQL\ObjectType\Localisation
 * @author mirkl
 */
class CurrencyType extends AbstractObjectType
{
    /**
     * @param \Youshido\GraphQL\Config\Object\ObjectTypeConfig $config
     * @throws \Youshido\GraphQL\Exception\ConfigurationException
     */
    public function build($config)
    {
        $config->addFields([
            'id' => [
                'type' => new NonNullType(new StringType()),
                'description' => 'Currency code'
            ],
            'code' => [
                'type' => new NonNullType(new StringType()),
                'description' => "Code of the currency - such as 'CZK' or 'EUR'",
            ],
            'pattern' => [
                'type' => new NonNullType(new StringType()),
                'description' => 'Pattern how the currency should be shown. Has variable {value} for currency',
            ],
        ]);
    }
}
