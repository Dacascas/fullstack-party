<?php

$app->register(new \Silex\Provider\ServiceControllerServiceProvider())
    ->register(new Gigablah\Silex\OAuth\OAuthServiceProvider(), array(
    'oauth.services' => array(
        'GitHub' => array(
            'key' => GITHUB_API_KEY,
            'secret' => GITHUB_API_SECRET,
            'scope' => array('user:email'),
            'user_endpoint' => 'https://api.github.com/user'
        )
    )))
    ->register(new Silex\Provider\RoutingServiceProvider())
    ->register(new Silex\Provider\FormServiceProvider())
    ->register(new Silex\Provider\SessionServiceProvider(), array(
        'session.storage.save_path' => __DIR__ . '/../cache'
    ))
    ->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__ . '/../views'
    ))
    ->register(new Silex\Provider\SecurityServiceProvider(), array(
        'security.firewalls' => array(
            'default' => array(
                'pattern' => '^/',
                'anonymous' => true,
                'oauth' => array(
                    'failure_path' => '/',
                    'with_csrf' => true
                ),
                'logout' => array(
                    'logout_path' => '/logout',
                    'with_csrf' => true
                ),
                'users' => new Gigablah\Silex\OAuth\Security\User\Provider\OAuthInMemoryUserProvider()
            )
        ),
        'security.access_rules' => array(
            array('^/auth', 'ROLE_USER')
        )
    ));