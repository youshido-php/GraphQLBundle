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

    public function getArguments()
    {
        return [];
    }

    public function resolve($value = null, $args = [])
    {
        return [];
    }
}