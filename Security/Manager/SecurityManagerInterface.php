<?php

namespace Youshido\GraphQLBundle\Security\Manager;

use Youshido\GraphQL\Execution\ResolveInfo;

/**
 * Date: 29.08.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
interface SecurityManagerInterface
{

    const RESOLVE_ATTRIBUTE = 'RESOLVE';

    /**
     * @return bool
     */
    public function isSecurityEnabled();

    /**
     * @param ResolveInfo $resolveInfo
     *
     * @return bool
     */
    public function isGrantedToResolve(ResolveInfo $resolveInfo);

    /**
     * @param ResolveInfo $resolveInfo
     *
     * @return mixed
     *
     * @throw \Exception
     */
    public function createNewAccessDeniedException(ResolveInfo $resolveInfo);
}