<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Helper;


use Youshido\GraphQLBundle\GraphQL\Request;

class HelpersContainer implements HelperInterface
{

    /** @var HelperInterface[] */
    private $helpers = [];

    public function addHelper(HelperInterface $helper)
    {
        $this->helpers[] = $helper;
    }

    public function process(Request $request)
    {
        foreach ($this->helpers as $helper) {
            $helper->process($request);
        }
    }

}