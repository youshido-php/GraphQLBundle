<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


interface TypeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param null  $value
     * @param array $args
     *
     * @return mixed
     */
    public function resolve($value = null, $args = []);

    /**
     * @return array
     */
    public function getArguments();

    /**
     * @return array
     */
    public function getFields();
}