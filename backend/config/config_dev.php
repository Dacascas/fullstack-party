<?php

use Monolog\Logger;

$mainConfig = include __DIR__ . '/config.php';

return array_merge($mainConfig, [
    'debug' => true,
    'log.level' => Logger::DEBUG,
    'cache_dir' => __DIR__ . '/../var/cache/dev',

    'tesonet.api_versioning' => [
        'product' => 'Tesonet test task',
        'environment' => 'dev'
    ]
]);
