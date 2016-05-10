<?php
/**
 * Date: 30.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Type\ORM;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Validator\Exception\ResolveException;

abstract class AbstractDoctrineORMObjectType extends AbstractObjectType implements ContainerAwareInterface
{

    /** @var ContainerInterface */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function resolve($value = null, $args = [], $type = null)
    {
        return $this->container->get('doctrine')->getRepository($this->getEntityClass())->find($args['id']);
    }

    /**
     * @throws ResolveException
     *
     * @return String
     */
    abstract function getEntityClass();

    public function build($config)
    {
        $config->addArgument('id', 'int', ['required' => true]);
    }

}