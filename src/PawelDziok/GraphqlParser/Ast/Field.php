<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;


class Field
{
    /** @var string */
    private $name;

    public function __construct($name)
    {
        $this->name   = $name;
    }
}