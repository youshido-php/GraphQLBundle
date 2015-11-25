<?php
/**
 * Date: 25.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Helper;


use Youshido\GraphQLBundle\GraphQL\Request;

interface HelperInterface
{

    public function process(Request $request);

}