<?php

namespace BastSys\GraphQLBundle\GraphQL;

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
