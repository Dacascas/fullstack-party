<?php

$app['github.proxy'] = function () use ($app) {
    $token = (isset($app['security.token_storage'])) ? $app['security.token_storage']->getToken() :
        $app['security']->getToken();

    return new Service\GitHub($token);
};