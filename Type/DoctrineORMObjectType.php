<?php
/**
 * Date: 30.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Type;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\GraphQL\Type\Config\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\ObjectType;
use Youshido\GraphQL\Validator\Exception\ResolveException;

class DoctrineORMObjectType extends ObjectType implements ContainerAwareInterface
{

    /** @var ContainerInterface */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function resolve($value = null, $args = [])
    {
        return $this->container->get('doctrine')->getRepository($this->getEntityClass())
            ->find($args['id']);
    }

    public function buildArguments(ObjectTypeConfig $config)
    {
        $config
            ->addArgument('id', 'int', [
                'required' => true
            ]);
    }

    public function getEntityClass()
    {
        throw new ResolveException('You must specified entity class to use this object type');
    }
}