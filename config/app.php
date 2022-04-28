<?php

return [
    'drivers' => [
        'redis' => \Core\Drivers\Redis::class,
        'memcached' => \Core\Drivers\Memcache::class
    ],
    'parameters' => [
        'redis' => [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379
        ],
        'memcached' => [

        ]
    ]
];
