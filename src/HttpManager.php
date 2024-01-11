<?php

namespace Exewen\Http;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Http\Exception\HttpClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;

class HttpManager
{
    protected array $channels = [];
    private ConfigInterface $config;

    private array $httpRequestBaseOptions = [];

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    protected function driver($driver = null): Client
    {
        if (empty($driver)) {
            throw new HttpClientException("HttpManager driver is empty");
        }
        return $this->getDriver($driver);
    }

    protected function getDriver(string $name): Client
    {
        return $this->channels[$name] ?? $this->resolve($name);
    }

    protected function resolve(string $name): Client
    {
        $config = $this->configurationFor($name);
        if (is_null($config)) {
            throw new HttpClientException("http driver [{$name}] is not defined.");
        }
        $this->initHttpRequestBaseOptions($name, $config);

        $stack = $this->getHandlerStack($config);

        return $this->channels[$name] = new Client([
            'handler' => $stack,
            // Base URI 用于相对请求
            'base_uri' => $this->buildUrl($config),
            // 您可以设置任意数量的默认请求选项。
            'timeout' => $config['timeout'],
        ]);

    }

    protected function configurationFor($name)
    {
        return $this->config->get("http.channels.{$name}");
    }

    protected function filter(array $input): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    protected function sendRequest(Client $driver, $url, string $method, array $options = []): ResponseInterface
    {
        $options = $this->getHttpRequestOptions($options);
        $response = $driver->request($method, $url, $options);
        if ($response->getStatusCode() !== 200) {
            $errorMsg = sprintf("response error(%d):%s url:%s method:%s options:%s", $response->getStatusCode(), $response->getReasonPhrase(), $url, $method, json_encode($options));
            throw new HttpClientException($errorMsg);
        }
        return $response;
    }

    private function getHttpRequestOptions($params): array
    {
        return array_merge($this->httpRequestBaseOptions, $params);
    }

    protected function buildUrl(array $config, string $path = ''): string
    {
        return ($config['ssl'] ? 'https' : 'http') . '://' . $config['host'] . ':' . $config['port'] . $config['prefix'] . $path;
    }

    private function getHandlerStack(array $config): HandlerStack
    {
        $handler = $config['handler'] ?? [];

        if (!empty($handler)) {
            $stack = new HandlerStack();
            $stack->setHandler(new CurlHandler());
            foreach ($handler as $middleware) {
                $stack->push(new $middleware($config), $middleware);
            }
        } else {
            $stack = HandlerStack::create();
        }
        return $stack;
    }

    private function initHttpRequestBaseOptions(string $name, $config)
    {
        $this->httpRequestBaseOptions[$name] = [
            'connect_timeout' => $config['connect_timeout'],
            'timeout' => $config['timeout'], //请求超时时间
            'verify' => false,
            'debug' => false,
        ];
    }

}
