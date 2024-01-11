<?php

namespace Exewen\Http\Middleware;

use Psr\Http\Message\RequestInterface;

class HeaderNacosMiddleware
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $identityKey = $this->config['extra']['header']['identity_key'];
            $identityValue = $this->config['extra']['header']['identity_value'];
            $modifiedRequest = $request->withHeader($identityKey, $identityValue);
            return $handler($modifiedRequest, $options);
        };
    }


}