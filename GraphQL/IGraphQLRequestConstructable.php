<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL;

/**
 * Interface IGraphQLRequestConstructable
 * @package BastSys\GraphQLBundle\GraphQL
 * @author mirkl
 */
interface IGraphQLRequestConstructable
{
    /**
     * Construct this entity with GraphQLRequest
     *
     * @param GraphQLRequest $request
     *
     * @return IGraphQLRequestConstructable instance
     */
    public static function constructFromGraphQLRequest(GraphQLRequest $request);
}
