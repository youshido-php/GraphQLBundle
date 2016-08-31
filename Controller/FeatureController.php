<?php
/**
 * Date: 31.08.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FeatureController extends Controller
{

    /**
     * @Cache(expires="tomorrow", public=true)
     * @Route("/explorer")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function explorerAction()
    {
        return $this->render('GraphQLBundle:Feature:explorer.html.twig', [
            'graphQLUrl' => $this->generateUrl('youshido_graphql_graphql_default')
        ]);
    }

}