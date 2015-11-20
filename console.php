<?php
/**
 * Date: 16.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

require_once dirname(__FILE__).'/vendor/autoload.php';

use \Youshido\GraphqlParser\Parser;

$source = '
query getFewPosts {
  recentPosts(count: 10, test: <id>) {
    title
  }
}
';

$parser = new Parser($source);
$parsed = $parser->parseQuery();

$a = 'asd';