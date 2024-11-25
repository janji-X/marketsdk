<?php
/**
 * Created by PHPSTORM.
 * User: ATom
 * Date: 2019/10/19
 * Time: 17:00
 */

namespace Controller\Sdk\Client;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Library\Logger;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    private static $client_handle = null;
    private static $debug = false; //是否开启调试日志
    private static $timeout = 3; //超时时间(秒)
    private static $headers = [];
    private static $selfHandler = null; //当前类句柄
    private static $proxyList = ''; //请求代理设置
    private static $proxySwitch = false; //请求代理开关
    protected static $retryTimes = 3; //重试次数
    protected static $httpStatusCodes = [200]; //正常数据返回的[HTTP Status Code]

    /**
     * 获取当前类的句柄
     * @return HttpClient|null
     */
    public static function getSelfHandler()
    {
        if (is_null(self::$selfHandler)) {
            self::$selfHandler = new static();
        }
        return self::$selfHandler;
    }

    /**
     * 重置相关配置状态
     */
    private static function restoreConfig()
    {
        self::$debug = false;
        self::$timeout = 3;
        self::$headers = [];
        self::$proxySwitch = true;
        self::$retryTimes = 3;
        self::$httpStatusCodes = [200];
    }

    /**
     * 随机获取一个代理配置
     * @return mixed
     * @throws Exception
     */
    private static function getRandProxy()
    {
        $proxy_list = explode(',', self::$proxyList);
        $proxy_list = array_filter($proxy_list);
        if (empty($proxy_list)) {
            throw new Exception('Http client proxy_list is empty.');
        }

        return $proxy_list[array_rand($proxy_list, 1)];
    }

    /**
     * 获取请求句柄
     * @return Client|null
     */
    protected static function getClientHandle()
    {
//        if (is_null(self::$client_handle)) {
//            self::$client_handle = new Client();
//        }
//
//        return self::$client_handle;

        // 复用句柄会产生莫名奇怪的错误【Response Status Code:403】
        return new Client();
    }

    /**
     * 获取请求参数
     * @return array
     * @throws Exception
     */
    protected static function getOptions()
    {
        $options = [
            'debug' => self::$debug,
            'timeout' => self::$timeout,
            'headers' => self::$headers,
        ];
        if (self::$proxySwitch) {
            $options['proxy'] = self::getRandProxy();
            self::setProxySwitch(true);
        }


        return $options;
    }

    /**
     * 设置请求代理
     * @param $proxyList
     * @return mixed
     */
    public static function setProxyList($proxyList)
    {
        self::$proxyList = $proxyList;

        return self::getSelfHandler();
    }

    /**
     * 设置是否开启DEBUG
     * @param bool $debug
     * @return HttpClient|null
     */
    public static function setDebug($debug = false)
    {
        self::$debug = $debug;
        return self::getSelfHandler();
    }

    /**
     * 设置是否请求代理
     * @param bool $proxySwitch
     * @return HttpClient|null
     */
    public static function setProxySwitch($proxySwitch = true)
    {
        self::$proxySwitch = $proxySwitch;
        return self::getSelfHandler();
    }

    /**
     * 设置请求超时时间
     * @param int $second
     * @return HttpClient|null
     */
    public static function setTimeout($second = 3)
    {
        self::$timeout = $second;
        return self::getSelfHandler();
    }

    /**
     * 设置正常数据返回的[HTTP Status Code]
     * @param array $httpStatusCodes
     * @return HttpClient|null
     */
    public static function setHttpStatusCodes(array $httpStatusCodes = [200])
    {
        self::$httpStatusCodes = $httpStatusCodes;
        return self::getSelfHandler();
    }

    /**
     * 设置请求头信息
     * @param array $headers
     * @return HttpClient|null
     */
    public static function setHeaders(array $headers = [])
    {
        self::$headers = $headers;
        return self::getSelfHandler();
    }

    /**
     * 设置重试次数
     * @param int $retryTimes
     * @return HttpClient|null
     */
    public static function setRetryTimes($retryTimes = 3)
    {
        self::$retryTimes = $retryTimes;
        return self::getSelfHandler();
    }

    /**
     * post请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @param bool $proxy_switch
     * @return string
     * @throws Exception
     */
    public static function post(string $url, array $data = [], int $timeout = 0, bool $proxy_switch = null)
    {
        $timeout && self::setTimeout($timeout);
        !is_null($proxy_switch) && self::setProxySwitch($proxy_switch);

        $options = self::getOptions();
        $options['form_params'] = $data;
        $retry_times = 0;
        $start_time = floor(1000 * microtime(true));
        do {
            $retry_times++;
            try {
                $response = self::getClientHandle()->post($url, $options);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $msg = "retry_times: {$retry_times}, status_code: " . $e->getResponse()->getStatusCode() . ", url: {$url}";
            }
            if (in_array($response->getStatusCode(), self::$httpStatusCodes)) {
                break;
            }
        } while ($retry_times < self::$retryTimes);

        $response_str = self::formatResponse($response);
        $diff_time = ceil(1000 * microtime(true)) - $start_time;

        return $response_str;
    }

    /**
     * get请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @param bool|null $proxy_switch
     * @return string
     * @throws Exception
     */
    public static function get(string $url, array $data = [], int $timeout = 0, bool $proxy_switch = null)
    {
        $timeout && self::setTimeout($timeout);
        !is_null($proxy_switch) && self::setProxySwitch($proxy_switch);

        $options = self::getOptions();
        $options['query'] = $data;
        $retry_times = 0;
        $start_time = floor(1000 * microtime(true));
        do {
            $retry_times++;
            try {
                $response = self::getClientHandle()->get($url, $options);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $msg = "retry_times: {$retry_times}, status_code: " . $e->getResponse()->getStatusCode() . ", url: {$url}";
            }

            if (in_array($response->getStatusCode(), self::$httpStatusCodes)) {
                break;
            }
        } while ($retry_times < self::$retryTimes);

        $response_str = self::formatResponse($response);
        $diff_time = ceil(1000 * microtime(true)) - $start_time;

        return $response_str;
    }

    /**
     * delete请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @param bool $proxy_switch
     * @return string
     * @throws Exception
     */
    public static function delete(string $url, array $data = [], int $timeout = 0, bool $proxy_switch = null)
    {
        $timeout && self::setTimeout($timeout);
        !is_null($proxy_switch) && self::setProxySwitch($proxy_switch);

        $options = self::getOptions();
        $options['query'] = $data;
        $retry_times = 0;
        $start_time = floor(1000 * microtime(true));
        do {
            $retry_times++;
            try {
                $response = self::getClientHandle()->delete($url, $options);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $msg = "retry_times: {$retry_times}, status_code: " . $e->getResponse()->getStatusCode() . ", url: {$url}";
            }
            if (in_array($response->getStatusCode(), self::$httpStatusCodes)) {
                break;
            }
        } while ($retry_times < self::$retryTimes);

        $response_str = self::formatResponse($response);
        $diff_time = ceil(1000 * microtime(true)) - $start_time;

        return $response_str;
    }

    /**
     * stream数据流请求
     * @param string $url
     * @param array $data
     * @param int $timeout
     * @param bool|null $proxy_switch
     * @param string $request_method
     * @return string
     * @throws Exception|GuzzleException
     */
    public static function stream(string $url, array $data, int $timeout = 0, bool $proxy_switch = null, string $request_method = 'POST')
    {
        $timeout && self::setTimeout($timeout);
        !is_null($proxy_switch) && self::setProxySwitch($proxy_switch);

        $options = self::getOptions();
        $options += $data;
        $retry_times = 0;
        $start_time = floor(1000 * microtime(true));
        do {
            $retry_times++;
            try {
                $response = self::getClientHandle()->request($request_method, $url, $options);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $msg = "retry_times: {$retry_times}, status_code: " . $e->getResponse()->getStatusCode() . ", url: {$url}\n";
            }
            if (in_array($response->getStatusCode(), self::$httpStatusCodes)) {
                break;
            }
        } while ($retry_times < self::$retryTimes);

        $response_str = self::formatResponse($response);
        $diff_time = ceil(1000 * microtime(true)) - $start_time;

        return $response_str;
    }

    /**
     * json POST数据流请求
     * @param string $url
     * @param string|array $data
     * @param int $timeout
     * @param bool|null $proxy_switch
     * @return string
     * @throws Exception|GuzzleException
     */
    public static function json(string $url, $data, int $timeout = 0, bool $proxy_switch = null)
    {
        return self::stream($url, ['json' => $data], $timeout, $proxy_switch);
    }

    /**
     * json PUT数据流请求
     * @param string $url
     * @param string|array $data
     * @param int $timeout
     * @param bool|null $proxy_switch
     * @return string
     * @throws Exception|GuzzleException
     */
    public static function jsonPut(string $url, $data, int $timeout = 0, bool $proxy_switch = null)
    {
        return self::stream($url, ['json' => $data], $timeout, $proxy_switch, 'PUT');
    }

    /**
     * 检测返回结果
     * @param ResponseInterface $response
     * @return string
     * @throws Exception
     */
    protected static function formatResponse(ResponseInterface $response): string
    {
        $status_code = $response->getStatusCode();
        $http_status_codes = self::$httpStatusCodes;
        self::restoreConfig(); // 重置相关配置状态
        if (!in_array($response->getStatusCode(), $http_status_codes)) {
            throw new Exception('Response Status Code:' . $status_code, $status_code);
        }

        return (string)$response->getBody();
    }
}
