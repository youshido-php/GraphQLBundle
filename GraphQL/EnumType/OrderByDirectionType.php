<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\EnumType;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class OrderByDirectionType
 * @package BastSys\GraphQLBundle\GraphQL\EnumType
 * @author mirkl
 */
class OrderByDirectionType extends AbstractEnumType
{
    /**
     * @return array
     */
    public function getValues()
    {
        return [
            [
                'name' => 'ASC',
                'value' => 'ASC',
                'description' => 'e. g. 1, 2, 3, ...'
            ],
            [
                'name' => 'DESC',
                'value' => 'DESC',
                'description' => 'e. g. 3, 2, 1, ...'
            ]
        ];
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Direction how the items in database should be ordered before search is performed";
    }
}
