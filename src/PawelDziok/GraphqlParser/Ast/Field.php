<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser\Ast;


class Field
{
    /** @var string */
    private $name;
    /** @var string */
    private $alias;
    /** @var array  */
    private $params;
    /** @var Field[] */
    private $fields;

    public function __construct($name, $alias = null, $params = [], $fields = [])
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->params = $params;
        $this->fields = $fields;
    }
}