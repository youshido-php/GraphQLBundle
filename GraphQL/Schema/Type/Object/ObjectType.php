<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Object;


use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

abstract class ObjectType implements TypeInterface
{

    /**
     * @inheritdoc
     */
    public function getFields(ListBuilderInterface $builder)
    {

    }

    /**
     * @inheritdoc
     */
    public function getArguments(ListBuilderInterface $builder)
    {

    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @param null  $value
     * @param array $args
     *
     * @return mixed
     */
    public function resolve($value = null, $args = [])
    {
        return null;
    }
}