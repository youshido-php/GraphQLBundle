<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;

class Variable
{

    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }


}