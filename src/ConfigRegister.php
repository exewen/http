<?php

declare(strict_types=1);

namespace Exewen\Http;

use Exewen\Http\Contract\HttpClientInterface;
use Exewen\Http\Middleware\HeaderNacosMiddleware;
use Exewen\Http\Middleware\LogMiddleware;

class ConfigRegister
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                HttpClientInterface::class => HttpClient::class,
            ],

            'http' => [
                'channels' => [
                    'nacos' => [
                        'ssl' => false,
                        'host' => '127.0.0.1',
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
                    ]
                ]

            ],

        ];
    }
}
