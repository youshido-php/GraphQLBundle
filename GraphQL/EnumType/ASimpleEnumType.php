<?php

namespace BastSys\GraphQLBundle\GraphQL\EnumType;

use Youshido\GraphQL\Type\Enum\AbstractEnumType;

/**
 * Class ASimpleEnumType
 *
 * Creates an enum type, without specified descriptions. Use this type for primitive enums.
 *
 * @package BastSys\GraphQLBundle\GraphQL\EnumType
 * @author  mirkl
 */
abstract class ASimpleEnumType extends AbstractEnumType
{
    /**
     * @var string[]|int[]
     */
    private $values;

    /**
     * ASimpleEnumType constructor.
     *
     * @param array $values contains only values that are used both as a name and a value of a single enum object
     */
    public function __construct(array $values)
    {
        $this->values = $values;

        parent::__construct();
    }

    /**
     * @return array
     */
    public final function getValues()
    {
        $result = [];
        foreach ($this->values as $value) {
            $result[] = [
                'name' => $value,
                'value' => $value
            ];
        }

        return $result;
    }
}
