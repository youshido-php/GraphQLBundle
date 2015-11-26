<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


class ListType implements TypeInterface
{

    /** @var TypeInterface */
    protected $type;

    public function __construct(TypeInterface $type)
    {
        $this->type = $type;
    }

    public function resolve($value = null, $args = [], $parent = null)
    {
        $value = (array)$value;

        $result = [];
        foreach ($value as $valueItem) {
            $result[] = $this->resolve($valueItem);
        }

        return $result;
    }
}