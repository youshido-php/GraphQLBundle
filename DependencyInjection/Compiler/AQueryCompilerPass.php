<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\DependencyInjection\Compiler;

/**
 * Class AQueryCompilerPass
 * @package BastSys\GraphQLBundle\DependencyInjection\Compiler
 * @author mirkl
 */
abstract class AQueryCompilerPass extends AFieldCompilerPass
{
    /**
     * AQueryCompilerPass constructor.
     * @param string $queryTag
     * @param string $queryFieldServiceId
     */
    public function __construct(string $queryTag, string $queryFieldServiceId)
    {
        parent::__construct($queryTag, $queryFieldServiceId);
    }

    /**
     * @return string
     */
    public function getQueryTag(): string
    {
        return $this->subFieldTag;
    }
}
