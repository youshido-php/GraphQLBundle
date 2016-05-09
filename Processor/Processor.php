<?php
/**
 * Date: 30.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Processor;


use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\GraphQL\Processor as BaseProcessor;

class Processor extends BaseProcessor implements ContainerAwareInterface
{

    /** @var  ContainerInterface */
    protected $container;

    /** @var  LoggerInterface */
    protected $logger;

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function processRequest($queryString, $variables = [])
    {
        if ($this->logger) {
            $this->logger->debug(sprintf('GraphQL query: %s', $queryString), (array) $variables);
        }

        parent::processRequest($queryString, $variables);
    }

    /**
     * @inheritdoc
     */
    protected function resolveValue($field, $contextValue, $query)
    {
        if (in_array('Symfony\Component\DependencyInjection\ContainerAwareInterface', class_implements($field->getType()))) {
            /** @var $queryType ContainerAwareInterface */
            $field->getType()->setContainer($this->container);
        }

        return parent::resolveValue($field, $contextValue, $query);
    }

    public function setLogger($loggerAlias)
    {
        $this->logger = $loggerAlias ? $this->container->get($loggerAlias) : null;
    }
}