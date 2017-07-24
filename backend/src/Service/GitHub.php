<?php

namespace Tesonet\Service;

use Github\Client;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class GitHub
 * @package Tesonet\Service
 */
class GitHub
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $user;

    /**
     * GitHub constructor.
     * @param TokenInterface $token
     */
    public function __construct(TokenInterface $token)
    {
        $this->client = new Client();

        if (!empty($token->getCredentials())) {
            $this->client->authenticate($token->getCredentials(), null, Client::AUTH_HTTP_TOKEN);
        }

        $this->user = $token->getUser() == 'anon.' ? true : $this->client->api('currentUser')->show()['login'];
    }

    /**
     * @return mixed
     */
    public function getListRepository()
    {
        return $this->client->api('user')->repositories($this->user);
    }

    /**
     * @param int $repoId
     * @return array
     */
    public function getRepoInfo($repoId)
    {
        return $this->client->repo($repoId)->showById($repoId);
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param int $page
     * @return array
     */
    public function getRepoIssueInfo($repoName, $user = '', $page)
    {
        $userQuery = isset($user) ? $user : $this->user;

        return $this->client->issues()->all($userQuery, $repoName, ['page' => $page]);
    }

    /**
     * @param string $repoName
     * @return array
     */
    public function getIssueHeader($repoName, $user = '', $params)
    {
        $userQuery = isset($user) ? $user : $this->user;

        return (new CustomSearch($this->client))->issueHead(
            '/repos/' . rawurlencode($userQuery) . '/' . rawurlencode($repoName) . '/issues', array_merge(array('page' => 1), $params)
        );
    }

    /**
     * @param string $repoName
     * @return array
     */
    public function getCloseIssueCount($repoName)
    {
        $url = 'repo%3A' . $repoName . '+is%3Aclosed+is%3Aissue';

        return (new CustomSearch($this->client))->issues($url)['total_count'];
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param int $issueId
     * @return array
     */
    public function getIssueData($repoName, $user = '', $issueId)
    {
        $userQuery = isset($user) ? $user : $this->user;

        return $this->client->issues()->show($userQuery, $repoName, $issueId);
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param int $issueId
     * @return array
     */
    public function getIssueComments($repoName, $user = '', $issueId)
    {
        $userQuery = isset($user) ? $user : $this->user;

        return $this->client->issues()->comments()->all($userQuery, $repoName, $issueId);
    }
}