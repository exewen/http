<?php
declare(strict_types=1);

namespace Exewen\Http\Contract;

use Exewen\Http\Constants\HttpEnum;

interface HttpClientInterface
{
    /**
     * 请求数据(幂等)
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @return string
     */
    public function get(string $driver, string $uri, array $params = [], array $header = [], array $options = []): string;

    /**
     * 获取有关资源的信息（例如资源的大小、最后修改时间等 类似于 GET 请求，但只返回响应头，不返回响应体）
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @return string
     */
    public function head(string $driver, string $uri, array $params = [], array $header = [], array $options = []): string;

    /**
     * 提交数据(表单提交或上传文件 通常不是幂等)
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type json(json) form_params(x-www-form-urlencoded) multipart(multipart/form-data) query headers body
     * @return string
     */
    public function post(string $driver, string $uri, array $params = [], array $header = [], array $options = [], string $type = HttpEnum::TYPE_JSON): string;

    /**
     * 更新某个资源的全部内容 （幂等）
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type
     * @return string
     */
    public function put(string $driver, string $uri, array $params = [], array $header = [], array $options = [], string $type = HttpEnum::TYPE_JSON): string;

    /**
     * 对资源进行部分更新
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @param string $type
     * @return string
     */
    public function patch(string $driver, string $uri, array $params = [], array $header = [], array $options = [], string $type = HttpEnum::TYPE_JSON): string;

    /**
     * 删除指定资源
     * 通常不带有请求体
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @return string
     */
    public function delete(string $driver, string $uri, array $params = [], array $header = [], array $options = []): string;

    /**
     * 查询服务器支持的 HTTP 方法或选项
     * 通常不带有请求体
     * @param string $driver
     * @param string $uri
     * @param array $params
     * @param array $header
     * @param array $options
     * @return string
     */
    public function options(string $driver, string $uri, array $params = [], array $header = [], array $options = []): string;


}