<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;


class FragmentReference
{

    protected  $name;

    /**
     * FragmentReference constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

}