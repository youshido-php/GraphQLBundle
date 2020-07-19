<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\Controller;

use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AGraphQLExplorerController
 * @package BastSys\GraphQLBundle\Controller
 * @author mirkl
 */
abstract class AGraphQLExplorerController extends AbstractController
{
    /**
     * Gets graphql url
     *
     * @return string
     */
    abstract protected function getGrahpQLUrl(): string;

    /**
     * @Route("/graphql/explorer", name="graphql_explorer", methods={"GET"})
     *
     * @return Response
     */
    public function handle()
    {
        $view = $this->renderView('@GraphQL/explorer.html.twig', [
            'graphQLUrl' => $this->getGrahpQLUrl()
        ]);

        $response = new Response($view, 200);

        $date = DateTime::createFromFormat('U', strtotime('tomorrow'), new DateTimeZone('UTC'));
        $response->setExpires($date);
        $response->setPublic();

        return $response;
    }
}
