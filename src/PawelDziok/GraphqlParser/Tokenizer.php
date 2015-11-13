<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser;

class Tokenizer
{
    protected $source;
    protected $pos = 0;
    protected $line = 1;
    protected $lineStart = 0;
    protected $lookAhead;

    public function __construct($source)
    {
        $this->source    = $source;
        $this->lookAhead = $this->next();
    }

    public function next()
    {
        $this->skipWhitespace();

        $line      = $this->line;
        $lineStart = $this->lineStart;
        $token     = $this->scan();

        $token->line   = $line;
        $token->column = $this->pos - $lineStart;

        return $token;
    }

    public function skipWhitespace()
    {
        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];
            if ($ch === ' ' || $ch === "\t") {
                $this->pos++;
            } elseif ($ch === "\r") {
                $this->pos++;
                if ($this->source[$this->pos] === "\n") {
                    $this->pos++;
                }
                $this->line++;
                $this->lineStart = $this->pos;
            } elseif ($ch === "\n") {
                $this->pos++;
                $this->line++;
                $this->lineStart = $this->pos;
            } else {
                break;
            }
        }
    }

    public function scan()
    {
        if ($this->pos >= strlen($this->source)) {
            return new Token(Token::TYPE_END);
        }

        $ch = $this->source[$this->pos];
        switch ($ch) {
            case '(':
                ++$this->pos;

                return new Token(Token::TYPE_LPAREN);
            case ')':
                ++$this->pos;

                return new Token(Token::TYPE_RPAREN);
            case '{':
                ++$this->pos;

                return new Token(Token::TYPE_LBRACE);
            case '}':
                ++$this->pos;

                return new Token(Token::TYPE_RBRACE);
            case '<':
                ++$this->pos;

                return new Token(Token::TYPE_LT);
            case '>':
                ++$this->pos;

                return new Token(Token::TYPE_GT);
            case '&':
                ++$this->pos;

                return new Token(Token::TYPE_AMP);
            case ',':
                ++$this->pos;

                return new Token(Token::TYPE_COMMA);
            case ':':
                ++$this->pos;

                return new Token(Token::TYPE_COLON);
        }

        if ($ch === '_' || $ch === '$' || 'a' <= $ch && $ch <= 'z' || 'A' <= $ch && $ch <= 'Z') {
            return $this->scanWord();
        }

        if ($ch === '-' || '0' <= $ch && $ch <= '9') {
            return $this->scanNumber();
        }

        if ($ch === '"') {
            return $this->scanString();
        }

        throw $this->createIllegal();
    }

    public function scanWord()
    {
        $start = $this->pos;
        $this->pos++;

        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];

            if ($ch === '_' || $ch === '$' || 'a' <= $ch && $ch <= ('z') || 'A' <= $ch && $ch <= 'Z' || '0' <= $ch && $ch <= '9') {
//            if (preg_match('/[_\$a-zA-Z0-9]/', $ch)) {
                $this->pos++;
            } else {
                break;
            }
        }

        $value = substr($this->source, $start, $this->pos - $start);

        return new Token($this->getKeyword($value), $value);
    }

    public function getKeyword($name)
    {
        switch ($name) {
            case 'null':
                return Token::TYPE_NULL;
            case 'true':
                return Token::TYPE_TRUE;
            case 'false':
                return Token::TYPE_FALSE;
            case 'as':
                return Token::TYPE_AS;
        }

        return Token::TYPE_IDENTIFIER;
    }

    public function scanNumber()
    {
        $start = $this->pos;

        if ($this->source[$this->pos] === '-') {
            $this->pos++;
        }

        $this->skipInteger();

        if ($this->source[$this->pos] === '->') {
            $this->pos++;
            $this->skipInteger();
        }

        $ch = $this->source[$this->pos];
        if ($ch === 'e' || $ch === 'E') {
            $this->pos++;

            $ch = $this->source[$this->pos];
            if ($ch === '+' || $ch === '-') {
                $this->pos++;
            }

            $this->skipInteger();
        }

        $value = (float)substr($this->source, $start, $this->pos);

        return new Token(Token::TYPE_NUMBER, $value);
    }

    public function skipInteger()
    {
        $start = $this->pos;

        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];
            if ('0' <= $ch && $ch <= '9') {
                $this->pos++;
            } else {
                break;
            }
        }

        if ($this->pos - $start === 0) {
            throw $this->createIllegal();
        }
    }

    public function createIllegal()
    {
        return $this->pos < strlen($this->source)
            ? $this->createError("Unexpected {$this->source[$this->pos]}")
            : $this->createError('Unexpected end of input');
    }

    public function createError($message)
    {
        return new SyntaxErrorException($message . " ({$this->line}:{$this->getColumn()})");
    }

    public function getColumn()
    {
        return $this->pos - $this->lineStart;
    }

    public function scanString()
    {
        $this->pos++;

        $value = '';
        while ($this->pos < strlen($this->source)) {
            $ch = $this->source[$this->pos];
            if ($ch === '"') {
                $this->pos++;

                return new Token(Token::TYPE_STRING, $value);
            }

            if ($ch === "\r" || $ch === "\n") {
                break;
            }

            $value .= $ch;
            $this->pos++;
        }

        throw $this->createIllegal();
    }

    public function end()
    {
        return $this->lookAhead->getType() === Token::TYPE_END;
    }

    public function peek()
    {
        return $this->lookAhead;
    }

    public function lex()
    {
        $prev            = $this->lookAhead;
        $this->lookAhead = $this->next();

        return $prev;
    }

    public function createUnexpected(Token $token)
    {
        switch ($token) {
            case Token::TYPE_END:
                return $this->createError('Unexpected end of input');
            case Token::TYPE_NUMBER:
                return $this->createError('Unexpected number');
            case Token::TYPE_STRING:
                return $this->createError('Unexpected string');
            case Token::TYPE_IDENTIFIER:
                return $this->createError('Unexpected identifier');
        }
    }
}