<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser\Ast;


class Query {

    private $fieldList;

    public function __construct($fieldList = [])
    {
        $this->fieldList = $fieldList;
    }


}