<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;

/**
 * Interface IEntityCreatable
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author mirkl
 */
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
