<?php
/**
 * @author Paweł Dziok <pdziok@gmail->com>
 */

namespace Youshido\GraphqlParser;

use Youshido\GraphqlParser\Ast\Argument;
use Youshido\GraphqlParser\Ast\Field;
use Youshido\GraphqlParser\Ast\Literal;
use Youshido\GraphqlParser\Ast\Query;
use Youshido\GraphqlParser\Ast\Reference;
use Youshido\GraphqlParser\Ast\Variable;

class Parser extends Tokenizer
{
    public function parseQuery()
    {
        $queries = $this->parseQueries();

        return $queries;
    }

    public function parseQueries()
    {
        $isNamedQuery = true;
        if (Token::TYPE_QUERY != $this->eatIdentifier()) {
            $this->expect(Token::TYPE_LBRACE);
            $isNamedQuery = false;
        }

        $fields = [];
        $first  = true;

        while (!$this->match(Token::TYPE_RBRACE) && !$this->end()) {
            if ($first) {
                $first = false;
            } else {
                $this->expect(Token::TYPE_COMMA);
            }

            if ($this->match(Token::TYPE_AMP)) {
                $fields[] = $this->parseReference();
            } else {
                $fields[] = $this->parseField($isNamedQuery);
            }
        }

        if ($isNamedQuery) {
            $this->expect(Token::TYPE_END);
        } else {
            $this->expect(Token::TYPE_RBRACE);
        }

        return $fields;
    }

    public function eatIdentifier()
    {
        $token = $this->eat(Token::TYPE_IDENTIFIER);

        return $token ? $token->getData() : null;
    }

    public function eat($type)
    {
        if ($this->match($type)) {
            return $this->lex();
        }

        return null;
    }

    public function match($type)
    {
        return $this->lookAhead->getType() === $type;
    }

    public function expect($type)
    {
        if ($this->match($type)) {
            return $this->lex();
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

    public function parseField($isNamedQuery = false)
    {
        $name   = $this->parseIdentifier();
        $alias  = null;
        $params = $this->match(Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];

        if ($this->eat(Token::TYPE_COLON)) {
            $alias = $name;
            $name  = $this->parseIdentifier();
        }

        if ($this->match(Token::TYPE_LBRACE)) {
            $fields = $this->parseQueries();

            $query = new Query($name, $alias, $params, $fields);
            $query->setIsNamed($isNamedQuery);

            return $query;
        } else {
            return new Field($name, $alias);
        }
    }

    public function parseIdentifier()
    {
        return $this->expect(Token::TYPE_IDENTIFIER)->getData();
    }

    public function parseArgumentList()
    {
        $args  = [];
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

    public function parseVariable()
    {
        $this->expect(Token::TYPE_LT);
        $name = $this->expect(Token::TYPE_IDENTIFIER)->getData();
        $this->expect(Token::TYPE_GT);

        return new Variable($name);
    }
}