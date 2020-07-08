<?php
declare(strict_types=1);

namespace BastSys\GraphQLBundle\DependencyInjection\Compiler;

/**
 * Class AMutationCompilerPass
 * @package BastSys\GraphQLBundle\DependencyInjection\Compiler
 * @author mirkl
 */
abstract class AMutationCompilerPass extends AFieldCompilerPass
{
    /**
     * AMutationCompilerPass constructor.
     * @param string $mutationTag
     * @param string $mutationFieldServiceId
     */
    public function __construct(string $mutationTag, string $mutationFieldServiceId)
    {
        parent::__construct($mutationTag, $mutationFieldServiceId);
    }

    /**
     * @return string
     */
    public function getMutationTag(): string
    {
        return $this->subFieldTag;
    }
}
