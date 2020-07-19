<?php
declare(strict_types=1);
/**
 * This file is a part of PhpStorm project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 9/23/16 10:08 PM
 */

namespace Youshido\GraphQLBundle\Execution\Container;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Execution\Container\ContainerInterface;

/**
 * Class SymfonyContainer
 * @package Youshido\GraphQLBundle\Execution\Container
 * @author mirkl
 */
class SymfonyContainer implements ContainerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param string $id
     * @return mixed|object|null
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * @param string $id
     * @param mixed $value
     * @return $this|mixed
     */
    public function set($id, $value)
    {
        $this->container->set($id, $value);
        return $this;
    }

    /**
     * @param string $id
     * @return mixed|void
     */
    public function remove($id)
    {
        throw new \RuntimeException('Remove method is not available for Symfony container');
    }

    /**
     * @param string $id
     * @return bool|mixed
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function initialized($id)
    {
        return $this->container->initialized($id);
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParameter($name, $value)
    {
        $this->container->setParameter($name, $value);
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasParameter($name)
    {
        return $this->container->hasParameter($name);
    }

    /**
     * Exists temporarily for ContainerAwareField that is to be removed in 1.5
     * @return mixed
     */
    public function getSymfonyContainer()
    {
        return $this->container;
    }

}
