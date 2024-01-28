<?php
declare(strict_types=1);

namespace Exewen\Http;

use Exewen\Di\ServiceProvider;
use Exewen\Http\Contract\HttpClientInterface;

class HttpProvider extends ServiceProvider
{

    /**
     * 服务注册
     * @return void
     */
    public function register()
    {
        $this->container->singleton(HttpClientInterface::class);
    }

}