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
     * @Route("/graph")
     */
    public function apiAction(Request $request)
    {
        $query     = $request->get('query', null);
        $arguments = $request->get('arguments', null);

        $arguments = json_decode($arguments, true) ?: [];

        $processor = $this->get('youshido.graphql.processor');

        $processor->process($query, $arguments);

        return new JsonResponse($processor->getResponseData());
    }

}