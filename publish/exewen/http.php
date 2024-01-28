<?php

declare(strict_types=1);

use Exewen\Http\Middleware\HeaderNacosMiddleware;
use Exewen\Http\Middleware\LogMiddleware;

return [
    'channels' => [
        'nacos' => [
            'ssl' => false,
            'host' => '10.0.2.143',
            'port' => '8848',
            'prefix' => null,
            'connect_timeout' => 3,
            'timeout' => 3,
            'handler' => [
                HeaderNacosMiddleware::class,
                LogMiddleware::class,
            ],
            'extra' => [
                'header' => [
                    'identity_key' => 'xxx',
                    'identity_value' => 'xxx',
                ],
            ]
        ],
    ]

];