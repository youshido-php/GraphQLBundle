<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser\Ast;


class Literal
{

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}