<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\DependencyInjection\CompilerPass;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PreValidatorContainerCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('youshido.graphql.pre_validator_container')) {
            return;
        }

        $definition = $container->findDefinition(
            'youshido.graphql.pre_validator_container'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'youshido.graphql.pre_validator'
        );

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addPreValidator', [new Reference($id)]);
        }
    }
}