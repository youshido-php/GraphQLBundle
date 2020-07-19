<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Youshido\GraphQL\Execution\Container\ContainerInterface;
use Youshido\GraphQL\Schema\AbstractSchema;
use Youshido\GraphQLBundle\Execution\Context\ExecutionContext;
use Youshido\GraphQLBundle\Execution\Processor;
use Youshido\GraphQLBundle\Security\Manager\SecurityManagerInterface;

/**
 * Class ProcessorFactory
 * @package BastSys\GraphQLBundle\GraphQL
 * @author mirkl
 */
class ProcessorFactory
{
    /** @var ContainerInterface */
    private $container;
    /** @var SecurityManagerInterface */
    private $securityManager;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * ProcessorFactory constructor.
     * @param ContainerInterface $container
     * @param EventDispatcherInterface $eventDispatcher
     * @param SecurityManagerInterface $securityManager
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $eventDispatcher, SecurityManagerInterface $securityManager)
    {
        $this->container = $container;
        $this->securityManager = $securityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Creates processor
     *
     * @param AbstractSchema $schema
     * @return Processor
     */
    public function createProcessor(AbstractSchema $schema): Processor
    {
        $executionContext = new ExecutionContext($schema);
        $executionContext->setContainer($this->container);

        $processor = new Processor($executionContext, $this->eventDispatcher);
        $processor->setSecurityManager($this->securityManager);

        return $processor;
    }
}
