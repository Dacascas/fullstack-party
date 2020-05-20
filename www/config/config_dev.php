<?php

use Monolog\Logger;

$mainConfig = include __DIR__ . '/config.php';

return array_merge($mainConfig, [
    'debug' => true,
    'log.level' => Logger::DEBUG,
    'cache_dir' => __DIR__ . '/../var/cache/dev',

    'github_reader.api_versioning' => [
        'product' => 'Github reader',
        'environment' => 'dev'
    ]
]);
