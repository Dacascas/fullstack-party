<?php
declare(strict_types=1);

namespace Service;

use Github\Api\AbstractApi;
use Psr\Http\Message\ResponseInterface;

/**
 * Class CustomSearch
 * @package Service
 */
class CustomSearch extends AbstractApi
{
    /**
     * @param $filter
     * @return array|string
     */
    public function issues($filter)
    {
        return $this->get('/search/issues?q=' . $filter);
    }

    /**
     * @param $filter
     * @return ResponseInterface
     */
    public function issueHead($filter, $params): ResponseInterface
    {
        return $this->head($filter, $params)->getHeader('Link');
    }
}