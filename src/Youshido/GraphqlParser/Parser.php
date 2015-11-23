<?php
/**
 * @author Vasil Portey <portey@gmail.com>
 */

namespace Youshido\GraphqlParser;

use Youshido\Graphql\Request;
use Youshido\GraphqlParser\Ast\Argument;
use Youshido\GraphqlParser\Ast\Field;
use Youshido\GraphqlParser\Ast\Fragment;
use Youshido\GraphqlParser\Ast\FragmentReference;
use Youshido\GraphqlParser\Ast\Literal;
use Youshido\GraphqlParser\Ast\Mutation;
use Youshido\GraphqlParser\Ast\Query;
use Youshido\GraphqlParser\Ast\Reference;
use Youshido\GraphqlParser\Ast\Variable;

class Parser extends Tokenizer
{

    public function parse()
    {
        $request = new Request();

        while (!$this->end()) {
            $tokenType = $this->getCurrentTokenType();

            switch ($tokenType) {
                case Token::TYPE_LBRACE:
                case Token::TYPE_QUERY:
                    $request->addQueries($this->parseBody());
                    break;

                case Token::TYPE_MUTATION:
                    $mutations = $this->parseBody(Token::TYPE_MUTATION);
                    $request->addMutations($mutations);
                    break;

                case Token::TYPE_FRAGMENT:
                    $request->addFragment($this->parseFragment());
                    break;
            }
        }

        return $request;
    }

    public function getCurrentTokenType()
    {
        return $this->lookAhead->getType();
    }

    public function parseBody($token = Token::TYPE_QUERY)
    {
        $fields = [];
        $first  = true;

        if ($this->getCurrentTokenType() == $token) {
            $this->lex();
        }

        $this->lex();

        while (!$this->match(Token::TYPE_RBRACE) && !$this->end()) {
            if ($first) {
                $first = false;
            } else {
                $this->expect(Token::TYPE_COMMA);
            }

            if ($this->match(Token::TYPE_AMP)) {
                $fields[] = $this->parseReference();
            } elseif ($this->match(Token::TYPE_FRAGMENT_REFERENCE)) {
                $this->lex();

                $fields[] = $this->parseFragmentReference();
            } else {
                $fields[] = $this->parseBodyItem($token);
            }
        }

        $this->expect(Token::TYPE_RBRACE);

        return $fields;
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

    public function parseFragmentReference()
    {
        $name = $this->parseIdentifier();

        return new FragmentReference($name);
    }

    public function parseIdentifier()
    {
        return $this->expect(Token::TYPE_IDENTIFIER)->getData();
    }

    public function parseBodyItem($type = Token::TYPE_QUERY)
    {
        $name   = $this->parseIdentifier();
        $alias  = null;

        if ($this->eat(Token::TYPE_COLON)) {
            $alias = $name;
            $name  = $this->parseIdentifier();
        }

        $params = $this->match(Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];

        if ($this->match(Token::TYPE_LBRACE)) {
            $fields = $this->parseBody();

            if ($type == Token::TYPE_QUERY) {
                return new Query($name, $alias, $params, $fields);
            } else {
                return new Mutation($name, $alias, $params, $fields);
            }
        } else {
            return new Field($name, $alias);
        }
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

    public function parseFragment()
    {
        $this->lex();
        $name = $this->parseIdentifier();

        $this->eat(Token::TYPE_ON);

        $model  = $this->parseIdentifier();
        $fields = $this->parseBody();

        return new Fragment($name, $model, $fields);
    }

    public function eatIdentifier()
    {
        $token = $this->eat(Token::TYPE_IDENTIFIER);

        return $token ? $token->getData() : null;
    }
}