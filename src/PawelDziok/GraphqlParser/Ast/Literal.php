<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;


class Literal
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}