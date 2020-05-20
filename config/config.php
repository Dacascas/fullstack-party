<?php

define('GITHUB_API_KEY', '0098d31d7a3c71906297');
define('GITHUB_API_SECRET', '79db75442577303ba1fad321b1447b13c39a746e');

return [
    'debug' => true,
    'log.level' => 'notice',
    'log.directory' => __DIR__ . '/../var/logs/',
    'cache_dir' => __DIR__ . '/../var/cache/prod',

    'tesonet.api_versioning' => [
        'product' => 'Tesonet test task',
        'environment' => 'prod'
    ]
];
