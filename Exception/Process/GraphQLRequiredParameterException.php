<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\Exception\Process;

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
