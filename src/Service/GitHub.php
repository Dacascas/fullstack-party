<?php

declare(strict_types=1);

namespace Service;

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

        $this->user = $token->getUser() === 'anon.' ? true : $this->client->api('currentUser')->show()['login'];
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
    public function getRepoInfo(int $repoId): array
    {
        return $this->client->repo($repoId)->showById($repoId);
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param int $page
     * @return array
     */
    public function getRepoIssueInfo(string $repoName, string $user, int $page): array
    {
        return $this->client->issues()->all($user ?? $this->user, $repoName, ['page' => $page]);
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param $params
     * @return array
     */
    public function getIssueHeader(string $repoName, string $user, $params): array
    {
        $userQuery = \rawurlencode($user ?? $this->user);

        return (new CustomSearch($this->client))->issueHead(
            '/repos/' . $userQuery . '/' . rawurlencode($repoName) . '/issues',
            \array_merge(
                ['page' => 1],
                $params
            )
        );
    }

    /**
     * @param string $repoName
     * @return array
     */
    public function getCloseIssueCount(string $repoName): array
    {
        return (new CustomSearch($this->client))->issues($this->getFilter($repoName))['total_count'];
    }

    /**
     * @param string $repoName
     * @return string
     */
    private function getFilter(string $repoName): string
    {
        return 'repo%3A' . $repoName . '+is%3Aclosed+is%3Aissue';
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param int $issueId
     * @return array
     */
    public function getIssueData(string $repoName, string $user, int $issueId): array
    {
        return $this->client->issues()->show($user ?? $this->user, $repoName, $issueId);
    }

    /**
     * @param string $repoName
     * @param string $user
     * @param int $issueId
     * @return array
     */
    public function getIssueComments(string $repoName, string $user, int $issueId): array
    {
        return $this->client->issues()->comments()->all($user ?? $this->user, $repoName, $issueId);
    }
}