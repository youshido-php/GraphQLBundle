<?php
/**
 * Date: 30.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Execution;


use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\GraphQL\Execution\Processor as BaseProcessor;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Parser\Ast\Query;

class Processor extends BaseProcessor implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    /** @var  LoggerInterface */
    protected $logger;

    /**
     * @inheritdoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function processPayload($queryString, $variables = [])
    {
        if ($this->logger) {
            $this->logger->debug(sprintf('GraphQL query: %s', $queryString), (array) $variables);
        }

        parent::processPayload($queryString, $variables);
    }

    /**
     * @inheritdoc
     */
    protected function resolveFieldValue(AbstractField $field, $contextValue, Query $query)
    {
        if (in_array('Symfony\Component\DependencyInjection\ContainerAwareInterface', class_implements($field))) {
            $field->setContainer($this->container);
        }

        return parent::resolveFieldValue($field, $contextValue, $query);
    }

    public function setLogger($loggerAlias)
    {
        $this->logger = $loggerAlias ? $this->container->get($loggerAlias) : null;
    }
}