<?php

namespace Youshido\GraphQLBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GraphQLExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $responseHeaders = [];
        if (isset($config['response_headers']) && is_array($config['response_headers'])) {
            foreach ($config['response_headers'] as $responseHeader) {
                $responseHeaders[$responseHeader['name']] = $responseHeader['value'];
            }
        }

        $container->setParameter('youshido.graphql.project_schema', $config['query_schema']);
        $container->setParameter('youshido.graphql.response_headers', $responseHeaders);
        $container->setParameter('youshido.graphql.logger', isset($config['logger']) ? $config['logger'] : null);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
