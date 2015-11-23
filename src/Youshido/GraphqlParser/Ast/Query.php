<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace Youshido\GraphqlParser\Ast;


class Query
{

    /** @var string */
    protected $name;

    /** @var string */
    protected $alias;

    /** @var array */
    protected $arguments;

    /** @var Field[]|Query[] */
    protected $children;

    public function __construct($name, $alias = null, $arguments = [], $children = [])
    {
        $this->name      = $name;
        $this->alias     = $alias;
        $this->arguments = $arguments;
        $this->children  = $children;
    }
}