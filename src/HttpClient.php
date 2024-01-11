<?php

declare(strict_types=1);

namespace Exewen\Http;

use Exewen\Http\Contract\HttpClientInterface;

class HttpClient extends HttpManager implements HttpClientInterface
{

    public function get(string $driver, string $url, array $params = [], array $header = [], array $options = []): string
    {
        if (!empty($params)) {
            $options['query'] = $this->filter($params);
        }
        if (!empty($header)) {
            $options['headers'] = $header;
        }
        $response = $this->sendRequest($this->getDriver($driver), $url, 'GET', $options);

        return $response->getBody()->getContents();
    }

    public function post(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string
    {
        if (!empty($params)) {
            $options[$type] = $this->filter($params);
        }
        if (!empty($header)) {
            $options['headers'] = $header;
        }
        $response = $this->sendRequest($this->getDriver($driver), $url, 'POST', $options);

        return $response->getBody()->getContents();
    }

    public function put(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string
    {
        if (!empty($params)) {
            $options[$type] = $this->filter($params);
        }
        if (!empty($header)) {
            $options['headers'] = $header;
        }

        $response = $this->sendRequest($this->getDriver($driver), $url, 'PUT', $options);

        return $response->getBody()->getContents();
    }


}
