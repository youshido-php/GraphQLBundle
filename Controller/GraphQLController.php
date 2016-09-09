<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Youshido\GraphQL\Validator\Exception\ConfigurationException;
use Youshido\GraphQLBundle\Execution\Processor;

class GraphQLController extends Controller
{

    /**
     * @Route("/graphql")
     *
     * @throws ConfigurationException
     *
     * @return JsonResponse
     */
    public function defaultAction()
    {
        list($query, $variables) = $this->getPayload();

        $schemaClass = $this->getParameter('youshido.graphql.schema_class');
        if (!$schemaClass || !class_exists($schemaClass)) {
            return $this->json([
                ['message' => 'Schema class ' . $schemaClass . ' does not exist']
            ]);
        }

        if (!$this->get('service_container')->initialized('youshido.graphql.schema')) {
            $schema = new $schemaClass();
            if ($schema instanceof ContainerAwareInterface) {
                $schema->setContainer($this->get('service_container'));
            }
            $this->get('service_container')->set('youshido.graphql.schema', $schema);
        }

        /** @var Processor $processor */
        $processor = $this->get('youshido.graphql.processor');
        $processor->processPayload($query, $variables);

        $response = $this->json($processor->getResponseData(), 200, $this->getParameter('youshido.graphql.response_headers'));

        if ($this->get('kernel')->getEnvironment() == 'dev') {
            $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        }
        return $response;
    }

    private function getPayload()
    {
        $request   = $this->get('request_stack')->getCurrentRequest();
        $query     = $request->get('query', null);
        $variables = $request->get('variables', null);

        $variables = json_decode($variables, true) ?: [];

        $content = $request->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true);

            if ($params) {
                $query     = isset($params['query']) ? $params['query'] : $query;
                $variables = isset($params['variables']) ? $params['variables'] : $variables;
            }
        }

        return [$query, $variables];
    }

}
