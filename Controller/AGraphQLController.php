<?php

namespace BastSys\GraphQLBundle\Controller;

use BastSys\GraphQLBundle\Exception\Process\GraphQL\GraphQLException;
use BastSys\GraphQLBundle\GraphQL\ASchema;
use BastSys\GraphQLBundle\GraphQL\ProcessorFactory;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use Youshido\GraphQL\Exception\ResolveException;

/**
 * Class AGraphQLController
 * @package Youshido\GraphQLBundle\Controller
 * @author mirkl
 */
abstract class AGraphQLController extends AbstractController
{
    /** @var ASchema */
    private $schema;

    /**
     * @var ProcessorFactory
     */
    private ProcessorFactory $processorFactory;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var ContainerInterface
     */
    private ContainerInterface $serviceContainer;

    /**
     * AGraphQLController constructor.
     * @param ASchema $schema
     */
    public function __construct(ASchema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param ProcessorFactory $processorFactory
     * @required
     */
    public function setProcessorFactory(ProcessorFactory $processorFactory): void
    {
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param RequestStack $requestStack
     * @required
     */
    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param Container $serviceContainer
     * @required
     */
    public function setServiceContainer(Container $serviceContainer): void
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @Route("/graphql", name="graphql", methods={"GET", "POST"})
     * @param Request $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function handle(Request $request)
    {
        if ($request->getMethod() == 'OPTIONS') {
            return $this->createEmptyResponse();
        }

        list($queries, $isMultiQueryRequest) = $this->getPayload();

        $queryResponses = array_map(function ($queryData) {
            return $this->executeQuery($queryData['query'], $queryData['variables']);
        }, $queries);

        $response = new JsonResponse($isMultiQueryRequest ? $queryResponses : $queryResponses[0], 200, $this->getResponseHeaders());

        if ($this->serviceContainer->getParameter('graphql.response.json_pretty')) {
            $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        }

        return $response;
    }

    /**
     * @return JsonResponse
     */
    private function createEmptyResponse()
    {
        return new JsonResponse([], 200, $this->getResponseHeaders());
    }

    /**
     * @return mixed
     */
    private function getResponseHeaders()
    {
        $headers = $this->serviceContainer->getParameter('graphql.response.headers');
        return $headers;
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    private function getPayload()
    {
        $request = $this->requestStack->getCurrentRequest();
        $query = $request->get('query', null);
        $variables = $request->get('variables', []);
        $isMultiQueryRequest = false;
        $queries = [];

        $variables = is_string($variables) ? json_decode($variables, true) ?: [] : [];

        $content = $request->getContent();

        if (!empty($content)) {
            // GraphQL format
            if ($request->headers->has('Content-Type') && 'application/graphql' == $request->headers->get('Content-Type')) {
                dump('graphql process');
                $queries[] = [
                    'query' => $content,
                    'variables' => [],
                ];
            } else {
                // JSON format
                $params = json_decode($content, true);

                if ($params) {
                    // check for a list of queries
                    if (isset($params[0]) === true) {
                        $isMultiQueryRequest = true;
                    } else {
                        $params = [$params];
                    }

                    foreach ($params as $queryParams) {
                        $query = isset($queryParams['query']) ? $queryParams['query'] : $query;

                        if (isset($queryParams['variables'])) {
                            if (is_string($queryParams['variables'])) {
                                $variables = json_decode($queryParams['variables'], true) ?: $variables;
                            } else {
                                $variables = $queryParams['variables'];
                            }

                            $variables = is_array($variables) ? $variables : [];
                        }

                        $queries[] = [
                            'query' => $query,
                            'variables' => $variables,
                        ];
                    }
                }
            }
        } else {
            $queries[] = [
                'query' => $query,
                'variables' => $variables,
            ];
        }

        return [$queries, $isMultiQueryRequest];
    }

    /**
     * @param $query
     * @param $variables
     * @return array|JsonResponse
     * @throws Exception
     */
    private function executeQuery($query, $variables)
    {
        $processor = $this->processorFactory->createProcessor($this->schema);
        $processor->processPayload($query, $variables);
        $errors = $processor->getExecutionContext()->getErrors();

        // all api errors should terminate request
        foreach ($errors as $error) {
            $code = $error->getCode();
            /** @var InvalidArgumentException|ResolveException $error */
            if (!$code || $code >= 500) {
                try {
                    throw $error;
                } catch (InvalidArgumentException|ResolveException $ex) {
                    // ignore these errors
                } catch (Throwable $ex) {
                    throw new GraphQLException('GraphQL exception', 500, $ex);
                }
            }
        }

        return $processor->getResponseData();
    }
}
