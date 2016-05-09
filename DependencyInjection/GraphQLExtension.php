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
    private $config = [];

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->config  = $this->processConfiguration($configuration, $configs);

        $responseHeaders = [];
        foreach ($this->getConfig('response_headers', $this->getDefaultHeaders()) as $responseHeader) {
            $responseHeaders[$responseHeader['name']] = $responseHeader['value'];
        }

        $container->setParameter('youshido.graphql.schema_class', $this->getConfig('schema_class', null));
        $container->setParameter('youshido.graphql.response_headers', $responseHeaders);
        $container->setParameter('youshido.graphql.logger', $this->getConfig('logger', null));

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function getDefaultHeaders()
    {
        return [
            ['name' => 'Access-Control-Allow-Origin', 'value' => '*'],
        ];
    }

    private function getConfig($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

}
