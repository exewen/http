<?php

namespace Exewen\Http\Middleware;

use Exewen\Di\Context\ApplicationContext;
use Exewen\Logger\Contract\LoggerInterface;
use Exewen\Logger\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogMiddleware
{
//    private LoggerInterface $logger;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(string $channel, array $config)
    {
        $this->logger = ApplicationContext::getContainer()->get(LoggerInterface::class);
    }

    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            // 打印请求原始参数
            $start = microtime(true);
            // 发送请求并获取响应
            return $handler($request, $options)->then(function (ResponseInterface $response) use ($request, $start) {
                $this->logSaveOk($request, $response, $start);
                return $response;
            })->otherwise(function (\Exception $exception) use ($request, $start) {
                $errorMsg = 'code:' . $exception->getCode() . ' msg:' . $exception->getMessage() . ' trace:' . $exception->getTraceAsString();
                $this->logSaveError($request, $errorMsg, $start);
                throw $exception;
            });
        };
    }

    /**
     * 记录成功日志
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $start
     * @return void
     */
    protected function logSaveOk(RequestInterface $request, ResponseInterface $response, $start)
    {
        $cost = round(microtime(true) - $start, 3); // 保留3位小数点
        $request->getBody()->rewind();
        $log     = [
            'req_cost'          => $cost,
            'method'            => $request->getMethod(),
            'url'               => $this->getLogUrl($request),
            'header'            => $this->getLogHeader($request),
            'request_contents'  => $request->getBody()->getContents(),
            'response_code'     => $response->getStatusCode(),
            'response_cost'     => $cost,
            'response_header'   => $this->getLogResponseHeader($response),
            'response_contents' => $response->getBody()->getContents(),
        ];
        $response->getBody()->rewind();
        $this->logger->request(json_encode($log, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 记录失败日志
     * @param RequestInterface $request
     * @param $errorMsg
     * @param $start
     * @return void
     */
    protected function logSaveError(RequestInterface $request, $errorMsg, $start)
    {
        $cost = round(microtime(true) - $start, 3); // 保留3位小数点
        $request->getBody()->rewind();
        $log = [
            'req_cost'         => $cost,
            'method'           => $request->getMethod(),
            'url'              => $this->getLogUrl($request),
            'header'           => $this->getLogHeader($request),
            'request_contents' => $request->getBody()->getContents(),
            'response_cost'    => $cost,
            'error_message'    => $errorMsg,
        ];
        $this->logger->error(json_encode($log, JSON_UNESCAPED_UNICODE));
    }


    /**
     * 获取日志log url
     * @param RequestInterface $request
     * @return string
     */
    protected function getLogUrl(RequestInterface $request): string
    {
        $uri         = $request->getUri();
        $fullUrl     = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
        $queryString = $uri->getQuery();
        if (!empty($queryString)) {
            $fullUrl .= '?' . $queryString;
        }
        return $fullUrl;
    }

    /**
     * 获取日志log headers
     * @param RequestInterface $request
     * @return string
     */
    protected function getLogHeader(RequestInterface $request): string
    {
        return json_encode($request->getHeaders(), JSON_UNESCAPED_UNICODE);
    }

    /**
     * 取日志log response headers
     * @param ResponseInterface $response
     * @return string
     */
    protected function getLogResponseHeader(ResponseInterface $response): string
    {
        return json_encode($response->getHeaders(), JSON_UNESCAPED_UNICODE);
    }

}