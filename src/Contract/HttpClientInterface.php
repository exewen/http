<?php
declare(strict_types=1);

namespace Exewen\Http\Contract;


interface HttpClientInterface
{
    public function get(string $driver, string $url, array $params = [], array $header = [], array $options = []): string;

    public function post(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string;

    public function put(string $driver, string $url, array $params = [], array $header = [], array $options = [], string $type = 'form_params'): string;

}