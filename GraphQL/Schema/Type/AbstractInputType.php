<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type;


use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\Input\AbstractInput;

abstract class AbstractInputType extends AbstractInput implements TypeInterface
{

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getFields(ListBuilderInterface $builder)
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getArguments(ListBuilderInterface $builder)
    {
        return [];
    }

}