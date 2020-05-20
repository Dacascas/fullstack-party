<?php

namespace Controller;

use Silex\Application\TwigTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Service\GitHub;
use Service\GitHubPaginator;

/**
 * Class GithubController
 * @package Tesonet\Controller
 */
class GithubController
{
    use TwigTrait;

    /**
     * @var GitHub
     */
    private $gitHub;

    /**
     * @var string
     */
    private $organization;

    /**
     * GithubController constructor.
     * @param GitHub $gitHubService
     * @param \Twig_Environment $twig
     * @param string $organization
     */
    public function __construct(GitHub $gitHubService, \Twig_Environment $twig, string $organization)
    {
        $this->gitHub = $gitHubService;
        $this->twig = $twig;
        $this->organization = $organization;
    }

    /**
     * @return JsonResponse
     */
    public function listReposAction(): JsonResponse
    {
        return $this->twig->render('list_repo.twig', ['repos' => $this->gitHub->getListRepository()]);
    }

    /**
     * @param int $repoId
     * @param int $page
     * @return string
     */
    public function showIssuesAction(int $repoId, int $page = 1): string
    {
        $repo = $this->gitHub->getRepoInfo($repoId);

        $paginator = new GitHubPaginator(
            $this->gitHub->getIssueHeader($repo['name'], $this->organization, ['page' => $page]),
            $page
        );

        return $this->twig->render(
            'show_repo_issues.twig',
            [
                'issues' => $this->gitHub->getRepoIssueInfo($repo['name'], $this->organization, $page),
                'openCount' => $repo['open_issues_count'],
                'closeCount' => $this->gitHub->getCloseIssueCount($repo['full_name']),
                'paginator' => $paginator->parse(),
                'repoId' => $repoId
            ]
        );
    }

    /**
     * @param int $repoId
     * @param int $issueId
     * @return string
     */
    public function showIssueAction(int $repoId, int $issueId): string
    {
        $repo = $this->gitHub->getRepoInfo($repoId);

        $issue = $this->gitHub->getIssueData($repo['name'], $this->organization, $issueId);
        $comments = $this->gitHub->getIssueComments($repo['name'], $this->organization, $issueId);

        return $this->twig->render('show_repo_issue.twig', ['issue' => $issue, 'comments' => $comments]);
    }
}
