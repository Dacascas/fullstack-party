<?php

$app['controller.ping'] = function () use ($appConfig) {
    return new \Tesonet\Controller\PingController($appConfig['tesonet.api_versioning']);
};

$app['controller.github'] = function () use ($app) {
    return new \Tesonet\Controller\GithubController($app['github.proxy'], $app['twig']);
};