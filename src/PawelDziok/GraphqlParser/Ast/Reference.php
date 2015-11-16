<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser\Ast;


class Reference
{

    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}