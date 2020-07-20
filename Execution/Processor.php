<?php
declare(strict_types=1);

namespace Youshido\GraphQLBundle\Execution;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Youshido\GraphQL\Execution\Context\ExecutionContextInterface;
use Youshido\GraphQL\Execution\Processor as BaseProcessor;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Field\Field;
use Youshido\GraphQL\Field\FieldInterface;
use Youshido\GraphQL\Parser\Ast\Field as AstField;
use Youshido\GraphQL\Parser\Ast\Interfaces\FieldInterface as AstFieldInterface;
use Youshido\GraphQL\Parser\Ast\Query;
use Youshido\GraphQL\Parser\Ast\Query as AstQuery;
use Youshido\GraphQL\Type\TypeService;
use Youshido\GraphQLBundle\Event\ResolveEvent;
use Youshido\GraphQLBundle\Security\Manager\SecurityManagerInterface;

/**
 * Class Processor
 * @package Youshido\GraphQLBundle\Execution
 * @author mirkl
 */
class Processor extends BaseProcessor
{

    /** @var  LoggerInterface */
    private $logger;

    /** @var  SecurityManagerInterface */
    private $securityManager;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param ExecutionContextInterface $executionContext
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(ExecutionContextInterface $executionContext, EventDispatcherInterface $eventDispatcher)
    {
        $this->executionContext = $executionContext;
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct($executionContext->getSchema());
    }

    /**
     * @param SecurityManagerInterface $securityManger
     *
     * @return Processor
     */
    public function setSecurityManager(SecurityManagerInterface $securityManger)
    {
        $this->securityManager = $securityManger;

        return $this;
    }

    /**
     * @param $payload
     * @param array $variables
     * @param array $reducers
     * @return void|BaseProcessor
     */
    public function processPayload($payload, $variables = [], $reducers = [])
    {
        if ($this->logger) {
            $this->logger->debug(sprintf('GraphQL query: %s', $payload), (array)$variables);
        }

        parent::processPayload($payload, $variables);
    }

    /**
     * @param AstQuery $query
     * @return array
     */
    protected function resolveQuery(Query $query)
    {
        $this->assertClientHasOperationAccess($query);

        return parent::resolveQuery($query);
    }

    /**
     * @param ResolveEvent $event
     * @param $name
     */
    private function dispatchResolveEvent(ResolveEvent $event, $name){
        $this->eventDispatcher->dispatch($event, $name);
    }

    /**
     * @param FieldInterface $field
     * @param AstFieldInterface $ast
     * @param null $parentValue
     * @return mixed|null
     * @throws \Exception
     */
    protected function doResolve(FieldInterface $field, AstFieldInterface $ast, $parentValue = null)
    {
        /** @var AstQuery|AstField $ast */
        $arguments = $this->parseArgumentsValues($field, $ast);
        $astFields = $ast instanceof AstQuery ? $ast->getFields() : [];

        $event = new ResolveEvent($field, $astFields);
        $this->dispatchResolveEvent($event, 'graphql.pre_resolve');

        $resolveInfo = $this->createResolveInfo($field, $astFields);
        $this->assertClientHasFieldAccess($resolveInfo);

        if (in_array('Symfony\Component\DependencyInjection\ContainerAwareInterface', class_implements($field))) {
            /** @var $field ContainerAwareInterface */
            $field->setContainer($this->executionContext->getContainer()->getSymfonyContainer());
        }

        if (($field instanceof AbstractField) && ($resolveFunc = $field->getConfig()->getResolveFunction())) {
            $result = $resolveFunc($parentValue, $arguments, $resolveInfo);
        } elseif ($field instanceof Field) {
            $result = TypeService::getPropertyValue($parentValue, $field->getName());
        } else {
            $result = $field->resolve($parentValue, $arguments, $resolveInfo);
        }

        $event = new ResolveEvent($field, $astFields, $result);
        $this->dispatchResolveEvent($event, 'graphql.post_resolve');
        return $event->getResolvedValue();
    }

    /**
     * @param AstQuery $query
     */
    private function assertClientHasOperationAccess(Query $query)
    {
        if ($this->securityManager->isSecurityEnabledFor(SecurityManagerInterface::RESOLVE_ROOT_OPERATION_ATTRIBUTE)
            && !$this->securityManager->isGrantedToOperationResolve($query)
        ) {
            throw $this->securityManager->createNewOperationAccessDeniedException($query);
        }
    }

    /**
     * @param ResolveInfo $resolveInfo
     */
    private function assertClientHasFieldAccess(ResolveInfo $resolveInfo)
    {
        if ($this->securityManager->isSecurityEnabledFor(SecurityManagerInterface::RESOLVE_FIELD_ATTRIBUTE)
            && !$this->securityManager->isGrantedToFieldResolve($resolveInfo)
        ) {
            throw $this->securityManager->createNewFieldAccessDeniedException($resolveInfo);
        }
    }

    /**
     * @param null $logger
     */
    public function setLogger($logger = null)
    {
        $this->logger = $logger;
    }
}
