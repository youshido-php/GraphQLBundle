<?php
/**
 * Date: 27.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Builder;


use Youshido\GraphQLBundle\GraphQL\Schema\Field;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\TypeInterface;

interface ListBuilderInterface
{

    /**
     * @param               $name
     * @param TypeInterface $type
     * @param array         $options
     *
     * @return  ListBuilderInterface
     */
    public function add($name, TypeInterface $type, $options = []);

    /**
     * @param $name
     * @return Field|null
     */
    public function get($name);

    /**
     * @return Field[]
     */
    public function all();

    /**
     * @param $name
     *
     * @return Field
     */
    public function has($name);
}