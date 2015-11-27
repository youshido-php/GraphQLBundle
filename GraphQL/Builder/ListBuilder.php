<?php
/**
 * Date: 27.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Builder;


use Youshido\GraphQLBundle\GraphQL\Schema\Field;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

class ListBuilder implements ListBuilderInterface
{

    protected $fields = [];

    public function add($name, TypeInterface $type, $options = [])
    {
        $field = new Field();
        $field
            ->setName($name)
            ->setType($type)
            ->setOptions($options);

        $this->fields[$name] = $field;
    }

    public function get($name)
    {
        if ($this->has($name)) {
            return $this->fields[$name];
        }

        return null;
    }

    public function has($name)
    {
        return array_key_exists($name, $this->fields);
    }

    public function all()
    {
        return $this->fields;
    }
}