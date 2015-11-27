<?php
/**
 * Date: 27.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema;


use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;

interface SchemaInterface
{

    public function getFields(ListBuilderInterface $builderInterface);

}