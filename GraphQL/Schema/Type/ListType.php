<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


use Youshido\GraphQLBundle\GraphQL\Schema\Type\Object\ObjectType;

class ListType extends ObjectType
{

    /** @var TypeInterface */
    protected $type;

    public function resolve($value = null, $args = [])
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'List';
    }
}