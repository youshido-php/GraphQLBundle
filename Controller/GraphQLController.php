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
     * @Route("/graphql")
     */
    public function apiAction(Request $request)
    {
        $query     = $request->get('query', null);
        $variables = $request->get('variables', null);

        $variables = json_decode($variables, true) ?: [];

        $processor = $this->get('youshido.graphql.processor');

        $processor->processQuery($query, $variables);

        return new JsonResponse($processor->getResponseData());
    }

}