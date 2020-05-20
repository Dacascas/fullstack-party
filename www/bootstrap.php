<?php

$app = new Silex\Application();

$app['debug'] = $appConfig['debug'];

$app->before(function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $token =  (isset($app['security.token_storage'])) ? $app['security.token_storage']->getToken() :
        $app['security']->getToken();

    $app['user'] = null;

    if ($token && !$app['security.trust_resolver']->isAnonymous($token)) {
        $app['user'] = $token->getUser();
        $app['token'] = $token;
    }
});
require __DIR__ . '/config/register.php';
require __DIR__ . '/config/services.php';
require __DIR__ . '/config/controllers.php';
require __DIR__ . '/config/routes.php';

return $app;
