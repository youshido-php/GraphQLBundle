<?php
/**
 * Date: 30.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Processor;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\GraphQL\Type\Object\ObjectType;

class Processor extends \Youshido\GraphQL\Processor implements ContainerAwareInterface
{

    /** @var  ContainerInterface */
    protected $container;

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    protected function resolveValue($field, $contextValue, $query)
    {
        if (in_array('Symfony\Component\DependencyInjection\ContainerAwareInterface', class_implements($field->getType()))) {
            /** @var $queryType ContainerAwareInterface */
            $field->getType()->setContainer($this->container);
        }

        return parent::resolveValue($field, $contextValue, $query);
    }
}