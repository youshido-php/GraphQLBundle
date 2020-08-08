<?php

namespace BastSys\GraphQLBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\GraphQLBundle\DependencyInjection\Compiler\GraphQlCompilerPass;
use Youshido\GraphQLBundle\DependencyInjection\GraphQLExtension;

/**
 * Class GraphQLBundle
 * @package BastSys\GraphQLBundle
 * @author mirkl
 */
class GraphQLBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GraphQlCompilerPass());
        $container->addCompilerPass(
            new RegisterListenersPass(
                'graphql.event_dispatcher',
                'graphql.event_listener',
                'graphql.event_subscriber'
            ),
            PassConfig::TYPE_BEFORE_REMOVING
        );
    }

    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface|GraphQLExtension|null
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new GraphQLExtension();
        }

        return $this->extension;
    }

}
