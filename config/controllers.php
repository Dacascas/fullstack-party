<?php

$app['controller.ping'] = function () use ($appConfig) {
    return new Controller\PingController($appConfig['github_reader.api_versioning']);
};

$app['controller.github'] = function () use ($app) {
    return new Controller\GithubController($app['github.proxy'], $app['twig'], 'symfony');
};