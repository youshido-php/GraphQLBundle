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
    protected $params;

    /** @var Field[]|Query[] */
    protected $children;

    protected $isNamed = false;

    public function __construct($name, $alias = null, $params = [], $children = [])
    {
        $this->name     = $name;
        $this->alias    = $alias;
        $this->params   = $params;
        $this->children = $children;
    }

    /**
     * @param boolean $isNamed
     *
     * @return Query
     */
    public function setIsNamed($isNamed)
    {
        $this->isNamed = $isNamed;

        return $this;
    }


}