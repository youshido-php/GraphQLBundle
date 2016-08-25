<?php

namespace Youshido\GraphQLBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\GraphQLBundle\DependencyInjection\Compiler\GraphQlCompilerPass;
use Youshido\GraphQLBundle\DependencyInjection\CompilerPass\HelperContainerCompilerPass;
use Youshido\GraphQLBundle\DependencyInjection\CompilerPass\PreValidatorContainerCompilerPass;

class GraphQLBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new GraphQlCompilerPass());
    }


}
