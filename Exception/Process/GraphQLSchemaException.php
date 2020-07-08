<?php

namespace BastSys\GraphQLBundle\Exception\Process\GraphQL;

/**
 * Class GraphQLSchemaException
 * @package BastSys\GraphQLBundle\Exception\Process\GraphQL
 * @author mirkl
 *
 * Thrown when schema is invalid
 */
class GraphQLSchemaException extends GraphQLException
{
    /**
     * GraphQLSchemaException constructor.
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 500);
    }
}
