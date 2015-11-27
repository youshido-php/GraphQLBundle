<?php
/**
 * Date: 26.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\GraphQL\Schema\Type\Object;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Youshido\GraphQLBundle\GraphQL\Builder\ListBuilderInterface;
use Youshido\GraphQLBundle\GraphQL\Schema\Type\Scalar\IntType;

abstract class DoctrineORMObjectType extends ObjectType implements ContainerAwareInterface
{

    public function getFields(ListBuilderInterface $builder)
    {

    }

    public function getArguments(ListBuilderInterface $builder)
    {
        $builder->add('id', new IntType(), ['required' => true]);
    }

    /**
     * @param null  $value
     * @param array $args
     *
     * @return mixed
     */
    public function resolve($value = null, $args = [])
    {
        return $this->container->get('doctrine')
            ->getRepository($this->getEntityClass())
            ->find($args['id']);
    }

    abstract function getEntityClass();

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'DoctrineORMObject';
    }
}