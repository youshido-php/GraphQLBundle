<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQLBundle\Parser\Ast;


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