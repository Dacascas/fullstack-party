<?php

namespace Tesonet\Service;

use Github\Api\AbstractApi;

class CustomSearch extends AbstractApi
{
    /**
     * @param $filter
     * @return array|string
     */
    public function issues($filter)
    {
        return $this->get("/search/issues?q=" . $filter);
    }

    /**
     * @param $filter
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function issueHead($filter, $params)
    {
        return $this->head($filter, $params)->getHeader('Link');
    }
}