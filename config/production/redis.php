<?php
return [
    'default' => [
        'host'     => env('DEFAULT_REDIS_HOST', '127.0.0.1'),
        'port'     => env('DEFAULT_REDIS_PORT', 6379),
        'password' => env('DEFAULT_REDIS_PASSWORD', null),
        'database' => env('DEFAULT_REDIS_DATABASE', 0), //默认采用database=0
    ],
];
