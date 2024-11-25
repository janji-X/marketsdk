<?php
/**
 * Created by PhpStorm.
 * User: smoney
 * Date: 4/16
 * Time: 23:52
 */

namespace Controller\Sdk\Client;

use Core\Config;
use Library\Logger;


class Curl
{
    static $errorMsg = '';

    public static function lastError()
    {
        return self::$errorMsg;
    }

    private static function clearErrorMsg()
    {
        self::$errorMsg = '';
    }

    /**
     * 以post方式提交data到对应的接口url
     * @param string $url
     * @param $data
     * @param $second
     * @param array $header
     * @param $isJson
     * @return bool|string
     */
    public static function postCurl(string $url, $data = array(), $second = 10000, array $header = [], $isJson = false)
    {
        self::clearErrorMsg();
        $start_time = floor(1000 * microtime(true));
        $ch = curl_init();

        $headers = array_merge([
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Expect:",
        ], $header);

        //如果是json数据
        if ($isJson) {
            $data = json_encode($data);

            $headers = array_merge($headers, [
                "Content-Type: application/json; charset=utf-8",
                "Content-Length:" . strlen($data),
            ]);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //设置过期时间毫秒
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);    //注意，毫秒超时一定要设置这个
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $second); //超时时间200毫秒 低版本 毫秒小于 存在bug

        curl_setopt($ch, CURLOPT_URL, $url);

        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //运行curl
        $output = curl_exec($ch);
        $diff_time = ceil(1000 * microtime(true)) - $start_time;

        //返回结果
        if ($output) {
            curl_close($ch);

            return $output;
        }

        $error = curl_error($ch);
        $errNo = curl_errno($ch);
        self::$errorMsg = "{$error} ({$errNo})";

        curl_close($ch);


        return false;
    }

    /**
     * @param string $url
     * @param $data
     * @param $second
     * @param $isJson
     * @param $proxyType
     * @return bool|string
     * @throws \Exception
     */
    public static function proxyPostCurl(string $url, $data = array(), $second = 10000, $isJson = false, $proxyType = false)
    {
        self::clearErrorMsg();
        $start_time = floor(1000 * microtime(true));
        $ch = curl_init();

        $agent = array(
            'Mozilla/5.0 (Linux; U; Android 8.1.0; zh-cn; BLA-AL00 Build/HUAWEIBLA-AL00) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 MQQBrowser/8.9 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 9; zh-CN; BND-AL10 Build/HONORBND-AL10) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.108 UCBrowser/12.5.6.1036 Mobile Safari/537.36',
        );

        $headers = array(
            //"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
            //"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36",
            "User-Agent: " . $agent[rand(0, count($agent) - 1)],
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Expect:",
        );

        //如果是json数据
        if ($isJson) {
            $data = json_encode($data);

            $headers = array_merge($headers, [
                "Content-Type: application/json; charset=utf-8",
                "Content-Length:" . strlen($data),
            ]);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //设置过期时间毫秒
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);    //注意，毫秒超时一定要设置这个
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $second); //超时时间200毫秒 低版本 毫秒小于 存在bug

        //代理 是否走代理
        if ($proxyType) {
            curl_setopt($ch, CURLOPT_PROXY, Config::getConfig($proxyType));
        } else {
            curl_setopt($ch, CURLOPT_PROXY, Config::getConfig('forward_proxy_host'));
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        //运行curl
        $output = curl_exec($ch);
        $diff_time = ceil(1000 * microtime(true)) - $start_time;

        //返回结果
        if ($output) {
            curl_close($ch);

            return $output;
        }

        $error = curl_error($ch);
        $errNo = curl_errno($ch);
        self::$errorMsg = "{$error} ({$errNo})";

        curl_close($ch);

        return false;
    }

    /**
     * get方式请求
     * @param $url
     * @param array $data
     * @param array $aheader
     * @return mixed
     * @author soosoogoo
     */
    public static function getCurl($url, $data = array(), $aheader = array())
    {
        self::clearErrorMsg();

        $data && $url = $url . '?' . http_build_query($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $aheader);

//        curl_setopt($curl, CURLOPT_PROXY, Config::getConfig('forward_proxy_host'));

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $error = curl_error($curl);
        $errNo = curl_errno($curl);
        curl_close($curl);
        self::$errorMsg = "{$error} ({$errNo})";

        return $output;
    }

}
