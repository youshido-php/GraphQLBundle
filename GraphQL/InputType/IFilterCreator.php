<?php

namespace BastSys\GraphQLBundle\GraphQL\InputType;

use BastSys\GraphQLBundle\GraphQL\GraphQLRequest;
use BastSys\UtilsBundle\Model\ListStructure\Input\AFilter;

/**
 * Interface IFilterCreator
 * @package BastSys\GraphQLBundle\GraphQL\InputType
 * @author mirkl
 */
interface IFilterCreator
{
    /**
     * Creates a filter from GraphQL request
     *
     * @param GraphQLRequest $request
     * @return AFilter
     */
    public function createFilter(GraphQLRequest $request): AFilter;
}
