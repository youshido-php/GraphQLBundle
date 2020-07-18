<?php

namespace BastSys\GraphQLBundle\Exception\Process;

/**
 * Class GraphQLRequiredTranslationParameterException
 * @package BastSys\GraphQLBundle\Exception\Process
 * @author mirkl
 */
class GraphQLRequiredTranslationParameterException extends GraphQLException
{
    /**
     * GraphQLRequiredTranslationParameterException constructor.
     * @param string $parameterName
     */
    public function __construct(string $parameterName)
    {
        parent::__construct("Parameter '$parameterName' is required at least for one translation", 400);
    }
}
