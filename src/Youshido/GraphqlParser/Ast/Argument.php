<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;


class Argument
{

    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }
}