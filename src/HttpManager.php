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

    /**
     * 获取 driver
     * @param string $name
     * @return Client
     */
    protected function getDriver(string $name): Client
    {
        return $this->channels[$name] ?? $this->resolve($name);
    }

    /**
     * 构建 client
     * @param string $name
     * @return Client
     */
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

    /**
     * 获取channels配置
     * @param $name
     * @return mixed
     */
    protected function configurationFor($name)
    {
        return $this->config->get("http.channels.{$name}");
    }

    /**
     * 过滤空值
     * @param array $input
     * @return array
     */
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

    /**
     * 发送请求
     * @param Client $driver
     * @param $url
     * @param string $method
     * @param array $options
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

    /**
     * 合并client配置
     * @param $params
     * @return array
     */
    private function getHttpRequestOptions($params): array
    {
        return array_merge($this->httpRequestBaseOptions, $params);
    }

    /**
     * 构建url
     * @param array $config
     * @param string $path
     * @return string
     */
    protected function buildUrl(array $config, string $path = ''): string
    {
        return ($config['ssl'] ? 'https' : 'http') . '://' . $config['host'] . ':' . $config['port'] . $config['prefix'] . $path;
    }

    /**
     * 构建 middleware
     * @param array $config
     * @return HandlerStack
     */
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

    /**
     * 设置公用client配置
     * @param string $name
     * @param $config
     * @return void
     */
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
