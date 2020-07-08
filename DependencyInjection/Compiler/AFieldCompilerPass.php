<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AFieldCompilerPass
 * @package BastSys\GraphQLBundle\DependencyInjection\Compiler
 * @author mirkl
 */
abstract class AFieldCompilerPass implements CompilerPassInterface
{
    protected string $subFieldTag;
    private string $rootFieldServiceId;

    public function __construct(string $subFieldTag, string $rootFieldServiceId)
    {
        $this->subFieldTag = $subFieldTag;
        $this->rootFieldServiceId = $rootFieldServiceId;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $queryIds = $container->findTaggedServiceIds($this->subFieldTag);

        $queryType = $container->findDefinition($this->rootFieldServiceId);
        foreach ($queryIds as $queryId => $tags) {
            $queryType->addMethodCall('addField', [new Reference($queryId)]);
        }
    }

}
