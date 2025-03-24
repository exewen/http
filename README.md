## 安装组件
```sh
composer require exewen/http
```
## 复制配置
```sh
cp -rf ./publish/exewen /your_project/config
``` 
## 初始化
```php
!defined('BASE_PATH_PKG') && define('BASE_PATH_PKG', dirname(__DIR__, 1));
``` 
## 请求
```php
# 初始化 DI
$app      = ApplicationContext::getContainer();
$app->setProviders([LoggerProvider::class,LoggerProvider::class]);
$this->app = $app;
/** @var HttpClientInterface $http */
$http = $this->app->get(HttpClientInterface::class);

# get
$response = $http->get('nacos', '/nacos/v1/cs/configs', [
    'dataId' => $this->dataId,
    'group' => $this->group,
    'tenant' => $this->namespaceId,
]);

# post
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
```