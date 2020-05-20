<?php

$app->get('/ping', 'controller.ping:pingAction');

$app->match('/logout', function () {})->bind('logout');

$app->get('/', function(\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    return $app['twig']->render('index.twig', array(
        'login_paths' => $app['oauth.login_paths'],
        'logout_path' => $app['url_generator_logout'],
        'error' => $app['security.last_error']($request)
    ));
});

$app->get('/repos', 'controller.github:listReposAction')->bind('repos');
$app->get('/repo/{repoId}/issues', 'controller.github:showIssuesAction')->bind('repo');
$app->get('/repo/{repoId}/issues{page}', 'controller.github:showIssuesAction')->bind('repoPage');
$app->get('/repo/{repoId}/issues/{issueId}', 'controller.github:showIssueAction')->bind('issue');

$app['url_generator_logout'] = function () use ($app) {
    return $app['url_generator']->generate('logout', array(
        '_csrf_token' => $app['oauth.csrf_token']('logout')
    ));
};