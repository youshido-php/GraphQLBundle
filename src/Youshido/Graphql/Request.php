<?php
/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\Graphql;


class Request
{

    private $queries = [];

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    public function addQueries($queries)
    {
        foreach ($queries as $query) {
            $this->queries[] = $query;
        }
    }

}