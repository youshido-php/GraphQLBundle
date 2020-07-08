<?php

namespace BastSys\GraphQLBundle\GraphQL\EnumType;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

class OrderByDirectionType extends AbstractEnumType
{
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

    public function getDescription()
    {
        return "Direction how the items in database should be ordered before search is performed";
    }
}
