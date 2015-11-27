<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\Object\ObjectType;

class ListType extends ObjectType
{

    /** @var TypeInterface */
    protected $type;

    /** @var callable */
    protected $resolveFunction;

    /** @var  string */
    protected $name;

    public function resolve($value = null, $args = [])
    {
        if ($this->resolveFunction && is_callable($this->resolveFunction)) {
            return call_user_func($this->resolveFunction, [$value, $args]);
        }

        return null;
    }

    public function getFields(ListBuilderInterface $builder)
    {
        $this->type->getFields($builder);
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getName()
    {
        return 'List';
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param callable $resolveFunction
     */
    public function setResolveFunction($resolveFunction)
    {
        $this->resolveFunction = $resolveFunction;
    }

    /**
     * @param TypeInterface $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}