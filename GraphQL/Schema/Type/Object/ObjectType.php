<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Object;


use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

class ObjectType implements TypeInterface
{

    /** @var  string */
    private $name;

    /** @var array */
    private $options = [];

    public function __construct($name, $options = [])
    {
        $this->name    = $name;
        $this->options = $options;
    }

    /**
     * @param null  $value
     * @param array $args
     * @param null  $parent
     *
     * @return mixed
     */
    public function resolve($value = null, $args = [], $parent = null)
    {
        if (array_key_exists('resolve', $this->options) && is_callable($this->options['resolve'])) {
            return call_user_func($this->options['resolve'], [$value, $args, $parent]);
        }

        return null;
    }
}