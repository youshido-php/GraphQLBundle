<?php

namespace BastSys\GraphQLBundle\Exception\Process\GraphQL;

class GraphQLRequiredTranslationParameterException extends GraphQLException
{
    public function __construct(string $parameterName)
    {
        parent::__construct("Parameter '$parameterName' is required at least for one translation", 400);
    }
}
