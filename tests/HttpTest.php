<?php
declare(strict_types=1);

namespace ExewenTest\Http;

use Exewen\Di\Container;
use Exewen\Http\Contract\HttpClientInterface;
use Exewen\Http\HttpProvider;
use Exewen\Logger\LoggerProvider;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
//    private Container $app;
    private $app;
    private $serviceName = 'pms-user';
    private $dataId = 'pms-user.env';
    private $group = 'DEFAULT_GROUP';
    private $namespaceId = 'prd';

    public function __construct()
    {
        parent::__construct();
        !defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));

        $app = new Container();
        // 服务注册
        $app->setProviders([
            LoggerProvider::class,
            HttpProvider::class
        ]);
        $this->app = $app;
    }

    public function testGet()
    {
        /** @var HttpClientInterface $http */
        $http = $this->app->get(HttpClientInterface::class);
        $response = $http->get('nacos', '/nacos/v1/cs/configs', [
            'dataId' => $this->dataId,
            'group' => $this->group,
            'tenant' => $this->namespaceId,
        ]);
        $this->assertNotEmpty($response);
    }

    public function testPost()
    {
        /** @var HttpClientInterface $http */
        $http = $this->app->get(HttpClientInterface::class);
        $response = $http->post('nacos', '/nacos/v1/ns/instance', [
            'namespaceId' => $this->namespaceId,
            'serviceName' => $this->serviceName,
            'groupName' => $this->group,
            'ip' => '10.0.2.143',
            'port' => 8081,
            'metadata' => json_encode([
                'ver' => "1.0.0"
            ], JSON_UNESCAPED_UNICODE),
        ]);
        $this->assertNotEmpty($response);
    }


}