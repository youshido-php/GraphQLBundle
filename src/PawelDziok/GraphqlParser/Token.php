<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser;

class Token
{
    // Special
    const TYPE_END = 'end';
    const TYPE_IDENTIFIER = 'identifier';
    const TYPE_NUMBER = 'number';
    const TYPE_STRING = 'string';

    // Punctuators
    const TYPE_LT = '<';
    const TYPE_GT = '>';
    const TYPE_LBRACE = '{';
    const TYPE_RBRACE = '}';
    const TYPE_LPAREN = '(';
    const TYPE_RPAREN = ')';
    const TYPE_COLON = ' = ';
    const TYPE_COMMA = ',';
    const TYPE_AMP = '&';

    // Keywords
    const TYPE_NULL = 'null';
    const TYPE_TRUE = 'true';
    const TYPE_FALSE = 'false';
    /** @deprecated */
    const TYPE_AS = 'as';

    private $data;
    private $type;

    public function __construct($type, $data = null)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getType()
    {
        return $this->type;
    }

    public function toString()
    {
        return "<" . $this->getData() . ", " . $this->getType() . ">";
    }
}