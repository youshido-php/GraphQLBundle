<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Youshido\GraphQL\Validator\Exception\ConfigurationException;

class GraphQLController extends Controller
{

    /**
     * @Route("/graphql")
     *
     * @throws ConfigurationException
     * @return JsonResponse
     */
    public function defaultAction()
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

        $processor = $this->get('youshido.graphql.processor');
        if ($this->container->hasParameter('youshido.graphql.schema_class')) {
            $schemaClass = $this->getParameter('youshido.graphql.schema_class');
            if (!class_exists($schemaClass)) {
                throw new ConfigurationException('Schema class ' . $schemaClass . ' does not exist');
            }
            $processor->setSchema(new $schemaClass());
        }
        $processor->processRequest($query, $variables);

        return new JsonResponse($processor->getResponseData(), 200, $this->getParameter('youshido.graphql.response_headers'));
    }

}
