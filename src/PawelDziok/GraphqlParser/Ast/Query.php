<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser\Ast;


class Query
{

    /** @var string */
    private $name;

    /** @var string */
    private $alias;

    /** @var array */
    private $params;

    /** @var Field[]|Query[] */
    private $children;

    private $isNamed = false;

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