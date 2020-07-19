<?php
declare(strict_types=1);
/**
 * Date: 9/12/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Youshido\GraphQLBundle\Security\Manager\SecurityManagerInterface;

/**
 * Class AbstractListVoter
 * @package Youshido\GraphQLBundle\Security\Voter
 */
abstract class AbstractListVoter extends Voter
{

    /** @var string[] */
    private $list = [];

    /** @var bool */
    private $enabled = false;

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return $this->enabled && $attribute == SecurityManagerInterface::RESOLVE_ROOT_OPERATION_ATTRIBUTE;
    }

    /**
     * @param TokenInterface $token
     * @return bool
     */
    protected function isLoggedInUser(TokenInterface $token)
    {
        return is_object($token->getUser());
    }

    /**
     * @param array $list
     */
    public function setList(array  $list)
    {
        $this->list = $list;
    }

    /**
     * @return \string[]
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param $query
     * @return bool
     */
    protected function inList($query)
    {
        return in_array($query, $this->list);
    }

    /**
     * @param $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
}
