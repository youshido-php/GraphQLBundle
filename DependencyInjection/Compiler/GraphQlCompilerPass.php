<?php

namespace Youshido\GraphQLBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Youshido\GraphQLBundle\Security\Voter\BlacklistVoter;
use Youshido\GraphQLBundle\Security\Voter\WhitelistVoter;

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

        $this->processSecurityGuard($container);
    }

    private function processSecurityGuard(ContainerBuilder $container)
    {
        $guardConfig = $container->getParameter('youshido.graphql.security.guard_config');
        $whiteList   = $container->getParameter('youshido.graphql.security.white_list');
        $blackList   = $container->getParameter('youshido.graphql.security.black_list');

        if ((!$guardConfig['field'] && !$guardConfig['operation']) && ($whiteList || $blackList)) {
            if ($whiteList && $blackList) {
                throw new \RuntimeException('Configuration error: Only one white or black list allowed');
            }

            $this->addListVoter($container, BlacklistVoter::class, $blackList);
            $this->addListVoter($container, WhitelistVoter::class, $whiteList);
        }
    }

    private function addListVoter(ContainerBuilder $container, $voterClass, array $list)
    {
        if ($list) {
            $container
                ->getDefinition('youshido.graphql.security.voter')
                ->setClass($voterClass)
                ->addMethodCall('setEnabled', [true])
                ->addMethodCall('setList', [$list]);

            $container->setParameter('youshido.graphql.security.guard_config', [
                'operation' => true,
                'field'     => false
            ]);
        }
    }
}
