<?php
/**
 * Date: 29.08.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Security\Manager;


use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Youshido\GraphQL\Execution\ResolveInfo;

class DefaultSecurityManager implements SecurityManagerInterface
{

    /** @var bool */
    private $enabled = false;

    /** @var  AuthorizationCheckerInterface */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return bool
     */
    public function isSecurityEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param ResolveInfo $resolveInfo
     *
     * @return bool
     */
    public function isGrantedToResolve(ResolveInfo $resolveInfo)
    {
        return $this->authorizationChecker->isGranted(SecurityManagerInterface::RESOLVE_ATTRIBUTE, $resolveInfo);
    }

    /**
     * @param ResolveInfo $resolveInfo
     *
     * @return mixed
     *
     * @throw \Exception
     */
    public function createNewAccessDeniedException(ResolveInfo $resolveInfo)
    {
        return new AccessDeniedException();
    }
}