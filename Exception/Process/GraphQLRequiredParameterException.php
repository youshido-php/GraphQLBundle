<?php

namespace BastSys\GraphQLBundle\Exception\Process\GraphQL;

/**
 * Class GraphQLRequiredParameterException
 * @package BastSys\GraphQLBundle\Exception\Process\GraphQL
 * @author  mirkl
 */
class GraphQLRequiredParameterException extends GraphQLException
{
    /**
     * GraphQLRequiredParameterException constructor.
     *
     * @param string $parameterName
     */
    public function __construct(string $parameterName)
    {
        parent::__construct("Parameter '$parameterName' is required for this operation (process error)", 400);
    }
}
