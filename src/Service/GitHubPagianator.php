<?php

namespace Tesonet\Service;

class GitHubPagianator
{
    /**
     * @var int
     */
    private $current;

    /**
     * @var array
     */
    private $headerLink;

    /**
     * GitHubPagianator constructor.
     * @param array $headers
     */
    public function __construct(array $headers, $current)
    {
        $this->headerLink = $headers;
        $this->current = (int)$current;
    }

    /**
     * @return array
     */
    public function parse()
    {
        $page = [
            'current' => $this->current
        ];

        $http = new HTTP2();
        $linkLines = $http->parseLinks($this->headerLink);

        foreach ($linkLines as $linkLine) {
            $page[$linkLine['rel'][0]] = $this->getPage($linkLine['_uri']);
        }

        return $this->ordering($page);
    }

    private function ordering($page)
    {
        $newPageSet = [];

        $sequence = [
            'first',
            'prev',
            'current',
            'next',
            'last'
        ];

        foreach ($sequence as $item) {
            if (isset($page[$item])) {
                $newPageSet[$item] = $page[$item];
            }
        }

        return $newPageSet;
    }

    private function getPage($link)
    {
        preg_match('/page=(\d*)$/', $link, $matches);

        return $matches[1];
    }
}