<?php

declare(strict_types=1);

namespace Exewen\Http;

use Exewen\Http\Contract\HttpClientInterface;

class HttpClient extends HttpManager implements HttpClientInterface
{

    /**
     * get
     * @param string $driver
     * @param string $url
     * @param array $params
     * @param array $header
     * @param array $options
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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

    /**
     * post
     * @param string $driver
     * @param string $url
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'json'): string
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

    /**
     * put
     * @param string $driver
     * @param string $url
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'json'): string
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
