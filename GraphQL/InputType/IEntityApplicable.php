<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;

interface IEntityApplicable
{
    /**
     * An input type that is can be used to applyOnEntity graphql request on entity
     *
     * @param object $entity
     * @param GraphQLRequest $request
     */
    public function applyOnEntity($entity, GraphQLRequest $request): void;
}
