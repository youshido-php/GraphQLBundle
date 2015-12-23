<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GraphQLController extends Controller
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/graphql")
     */
    public function apiAction(Request $request)
    {
        $query     = $request->get('query', null);
        $variables = $request->get('variables', null);

        $variables = json_decode($variables, true) ?: [];

        $content = $this->get("request")->getContent();
        if (!empty($content)) {
            $params = json_decode($content, true);

            if ($params) {
                $query     = isset($params['query']) ? $params['query'] : $query;
                $variables = isset($params['variables']) ? $params['variables'] : $variables;
            }
        }

        $processor = $this->get('youshido.graphql.processor');

        $processor->processQuery($query, $variables);

        return new JsonResponse($processor->getResponseData(), 200, [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Headers' => '*'
        ]);
    }

}