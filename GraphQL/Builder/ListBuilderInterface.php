<?php
/**
 * Date: 27.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Builder;


use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

interface ListBuilderInterface
{

    public function add($name, TypeInterface $type, $options = []);

    public function get($name);

    public function all();
}