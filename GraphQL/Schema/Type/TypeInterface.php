<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;

interface TypeInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param null  $value
     * @param array $args
     *
     * @return mixed
     */
    public function resolve($value = null, $args = []);

    /**
     * @param ListBuilderInterface $builder
     */
    public function getArguments(ListBuilderInterface $builder);

    /**
     * @param ListBuilderInterface $builder
     */
    public function getFields(ListBuilderInterface $builder);
}