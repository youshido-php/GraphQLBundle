<?php
/**
 * Date: 16.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

require_once dirname(__FILE__).'/vendor/autoload.php';

use \Youshido\GraphqlParser\Parser;

$sourceQuery = '
    query {
      users(count: 10, test: <id>) {
        title,
        com: comments {
            id,
            body
        }
      }
    }
';

$sourceSimpleQuery = '
    {
      recentPosts(count: 10, test: <id>) {
        title,
        comments {
            id,
            body
        }
      }
    }
';

$sourceMutation = '
    mutation {
      createUser(login: 10) {
        login
      }
    }
';

$sourceFragment = '
    fragment test on User {
        id,
        login,
        comments {
            id,
            body
        }
    }
';

$sourceQueryFragment = '
    {
        users {
            ...test
        }
    }

    fragment test on User {
        id,
        login,
        comments {
            id,
            body
        }
    }
';

$parser = new Parser($sourceQueryFragment);
$parsed = $parser->parse();

$a = 'asd';