<?php
declare(strict_types=1);

namespace Exewen\Http\Contract;

interface HttpClientInterface
{
    /**
     * get  请求
     * 
     * @param string $driver
     * @param string $url
     * @param array $params
     * @param array $header
     * @param array $options
     * @return string
     */
    public function get(string $driver, string $url, array $params = [], array $header = [], array $options = []): string;

    /**
     * post 请求
     * 
     * @param string $driver
     * @param string $url
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type
     * @return string
     */
    public function post(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string;

    /**
     * put 请求
     * 
     * @param string $driver
     * @param string $url
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type
     * @return string
     */
    public function put(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string;

}