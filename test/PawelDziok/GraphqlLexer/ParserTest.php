<?php
/**
 * @author Paweł Dziok <pdziok@gmail.com>
 */

namespace PawelDziok\GraphqlParser;

use PawelDziok\GraphqlParser\Ast\Argument;
use PawelDziok\GraphqlParser\Ast\Field;
use PawelDziok\GraphqlParser\Ast\Literal;
use PawelDziok\GraphqlParser\Ast\Query;
use PawelDziok\GraphqlParser\Ast\Variable;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getSampleData
     */
    public function testLexingSampleQueries($rawQuery, $parsedQuery)
    {
        $parser    = new Parser($rawQuery);
        $structure = $parser->parseQuery();

        $this->assertEquals($parsedQuery, $structure);
    }

    /**
     * @dataProvider  getMultipleSampleData
     */
    public function testMultipleLexingQueries($rawQuery, $parsedQuery)
    {
        $parser    = new Parser($rawQuery);
        $structure = $parser->parseQuery();

        $this->assertEquals($parsedQuery, $structure);
    }

    public function getMultipleSampleData()
    {
        return [
            [
                '
               {
                    user {
                        nickname
                    },
                    posts {
                        id
                    }
               }
              ',
                [
                    new Query(new Field('user', null, [], [new Field('nickname')])),
                    new Query(new Field('posts', null, [], [new Field('id')])),
                ]
            ]
        ];
    }

    public function getSampleData()
    {
        return [
            [
                '{
                user(id: <id>) {
                  id,
                  nickname,
                  avatar(width: 80, height: 80) {
                    url(protocol: "https")
                  },
                  posts(first: <count>) {
                    count,
                    edges {
                      post: node {
                        id,
                        title,
                        published_at
                      }
                    }
                  }
                }
              }',
                [new Query(
                    new Field(
                        'user',
                        null,
                        [
                            new Argument('id', new Variable('id'))
                        ],
                        [
                            new Field('id'),
                            new Field('nickname'),
                            new Field(
                                'avatar',
                                null,
                                [
                                    new Argument('width', new Literal('80')),
                                    new Argument('height', new Literal('80'))
                                ],
                                [
                                    new Field(
                                        'url',
                                        null,
                                        [
                                            new Argument('protocol', new Literal('https'))
                                        ]
                                    )
                                ]
                            ),
                            new Field(
                                'posts',
                                null,
                                [
                                    new Argument('first', new Variable('count')),
                                ],
                                [
                                    new Field('count'),
                                    new Field(
                                        'edges',
                                        null,
                                        [],
                                        [
                                            new Field(
                                                'node',
                                                'post',
                                                [],
                                                [
                                                    new Field('id'),
                                                    new Field('title'),
                                                    new Field('published_at')
                                                ]
                                            )
                                        ]
                                    )
                                ]
                            )
                        ]
                    )
                )]
            ]
        ];
    }
}
