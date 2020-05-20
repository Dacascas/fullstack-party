<?php

namespace Tesonet\Controller;

use Silex\Application\TwigTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tesonet\Service\GitHub;
use Tesonet\Service\GitHubPagianator;

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
     * GithubController constructor.
     * @param GitHub $gitHubService
     * @param \Twig_Environment $twig
     */
    public function __construct(GitHub $gitHubService, \Twig_Environment $twig)
    {
        $this->gitHub = $gitHubService;
        $this->twig = $twig;
    }

    /**
     * @return JsonResponse
     */
    public function listReposAction()
    {
        return $this->twig->render('list_repo.twig', ['repos' => $this->gitHub->getListRepository()]);
    }

    /**
     * @param $repoId
     * @param int $page
     * @return string
     */
    public function showIssuesAction($repoId, $page = 1)
    {
        /**
         * test set
         */
        $repoId = 458058;
        $userName = 'symfony';

        $repo = $this->gitHub->getRepoInfo($repoId);

        $paginator = new GitHubPagianator(
            $this->gitHub->getIssueHeader($repo['name'], $userName, ['page' => $page]),
            $page
        );

        return $this->twig->render(
            'show_repo_issues.twig',
            [
                'issues' => $this->gitHub->getRepoIssueInfo($repo['name'], $userName, $page),
                'openCount' => $repo['open_issues_count'],
                'closeCount' => $this->gitHub->getCloseIssueCount($repo['full_name']),
                'paginator' => $paginator->parse(),
                'repoId' => $repoId
            ]
        );
    }

    /**
     * @param $issueId
     * @return string
     */
    public function showIssueAction($repoId, $issueId)
    {
        /**
         * test set
         */
        $repoId = 458058;
        $userName = 'symfony';

        $repo = $this->gitHub->getRepoInfo($repoId);

        $issue = $this->gitHub->getIssueData($repo['name'], $userName, $issueId);
        $comments = $this->gitHub->getIssueComments($repo['name'], $userName, $issueId);

        return $this->twig->render('show_repo_issue.twig', ['issue' => $issue, 'comments' => $comments]);
    }
}
