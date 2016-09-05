<?php

namespace Youshido\GraphQLBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Date: 25.08.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
class GraphQlCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($loggerAlias = $container->getParameter('youshido.graphql.logger')) {
            if (strpos($loggerAlias, '@') === 0) {
                $loggerAlias = substr($loggerAlias, 1);
            }

            if (!$container->has($loggerAlias)) {
                throw new \RuntimeException(sprintf('Logger "%s" not found', $loggerAlias));
            }

            $container->getDefinition('youshido.graphql.processor')->addMethodCall('setLogger', [new Reference($loggerAlias)]);
        }
    }
}