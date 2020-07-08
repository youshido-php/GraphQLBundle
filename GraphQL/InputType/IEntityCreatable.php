<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;

interface IEntityCreatable
{
    /**
     * An input type that is able to create entity from given request
     *
     * @param GraphQLRequest $request
     *
     * @return object
     */
    function create(GraphQLRequest $request);
}
