<?php
/**
 * Date: 16.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

require_once dirname(__FILE__).'/vendor/autoload.php';

use \PawelDziok\GraphqlParser\Parser;

$source = '
{
  latestPost: latestPost {
    title,
    cars (order: 123){
        name
    }
  },

  authorNames: authors {
    name
  },

  authorIds: authors {
    _id
  }
}
';

$parser = new Parser($source);
$parsed = $parser->parseQuery();