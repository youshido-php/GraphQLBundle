<?php
/**
 * @author Paweł Dziok <pdziok@gmail->com>
 */

namespace PawelDziok\GraphqlParser;

use PawelDziok\GraphqlParser\Ast\Argument;
use PawelDziok\GraphqlParser\Ast\Field;
use PawelDziok\GraphqlParser\Ast\Literal;
use PawelDziok\GraphqlParser\Ast\Query;
use PawelDziok\GraphqlParser\Ast\Reference;
use PawelDziok\GraphqlParser\Ast\Variable;

class Parser extends Tokenizer
{
    public function match($type)
    {
        return $this->lookAhead->getType() === $type;
    }

    public function eat($type)
    {
        if ($this->match($type)) {
            return $this->lex();
        }

        return null;
    }

    public function expect($type)
    {
        if ($this->match($type)) {
            return $this->lex();
        }

        throw $this->createUnexpected($this->lookAhead);
    }

    public function parseQuery()
    {
        return new Query($this->parseFieldList());
    }

    public function parseIdentifier()
    {
        return $this->expect(Token::TYPE_IDENTIFIER)->getData();
    }

    public function parseFieldList()
    {
        $this->expect(Token::TYPE_LBRACE);

        $fields = [];
        $first = true;

        while (!$this->match(Token::TYPE_RBRACE) && !$this->end()) {
            if ($first) {
                $first = false;
            } else {
                $this->expect(Token::TYPE_COMMA);
            }

            if ($this->match(Token::TYPE_AMP)) {
                $fields[] = $this->parseReference();
            } else {
                $fields[] = $this->parseField();
            }
        }

        $this->expect(Token::TYPE_RBRACE);
        return $fields;
    }

    public function parseField()
    {
        $name = $this->parseIdentifier();
        $params = $this->match(Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];
        $alias = null;
        if ($this->eat(Token::TYPE_COLON)) {
            $alias = $name;
            $name = $this->parseIdentifier();
        }
        $fields = $this->match(Token::TYPE_LBRACE) ? $this->parseFieldList() : [];

        return new Field($name, $alias, $params, $fields);
    }

    public function parseArgumentList()
    {
        $args = [];
        $first = true;

        $this->expect(Token::TYPE_LPAREN);

        while (!$this->match(Token::TYPE_RPAREN) && !$this->end()) {
            if ($first) {
                $first = false;
            } else {
                $this->expect(Token::TYPE_COMMA);
            }

            $args[] = $this->parseArgument();
        }

        $this->expect(Token::TYPE_RPAREN);
        return $args;
    }

    public function parseArgument()
    {
        $name = $this->parseIdentifier();
        $this->expect(Token::TYPE_COLON);
        $value = $this->parseValue();

        return new Argument($name, $value);
    }

    public function parseValue()
    {
        switch ($this->lookAhead->getType()) {
            case Token::TYPE_AMP:
                return $this->parseReference();

            case Token::TYPE_LT:
                return $this->parseVariable();

            case Token::TYPE_NUMBER:
            case Token::TYPE_STRING:
                return new Literal($this->lex()->getData());

            case Token::TYPE_NULL:
            case Token::TYPE_TRUE:
            case Token::TYPE_FALSE:
                return new Literal(json_encode($this->lex()->getData()));
        }

        throw $this->createUnexpected($this->lookAhead);
    }

    public function parseReference()
    {
        $this->expect(Token::TYPE_AMP);

        if ($this->match(Token::TYPE_NUMBER) || $this->match(Token::TYPE_IDENTIFIER)) {
            return new Reference($this->lex()->getData());
        }

        throw $this->createUnexpected($this->lookAhead);
    }

    public function parseVariable()
    {
        $this->expect(Token::TYPE_LT);
        $name = $this->expect(Token::TYPE_IDENTIFIER)->getData();
        $this->expect(Token::TYPE_GT);

        return new Variable($name);
    }
}