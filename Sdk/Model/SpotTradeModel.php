<?php
/**
 * Topcredit现货交易相关接口
 * Created by PhpStorm.
 * User: ATom
 * Date: 2021/02/23
 * Time: 01:15
 */

namespace Controller\Sdk\Model;


use Controller\Sdk\Client\HttpClient;
use Exception;

class SpotTradeModel extends HttpBaseModel
{
    // 接口默认请求超时时间[单位:秒]
    public static $timeout = 3;

    // 请求URI配置[固定配置]
    public static $request_uri = [
        'default' => '/',
        'getUserAssets' => 'Property/getUserProperty', // 获取用户资产信息
        'symbolList' => '/First/Business/symbolList', // 交易对列表
        'itemList' => '/First/Business/itemList', // 币种列表
        'depth' => '/First/Business/depth', // 深度
        'order' => '/First/Business/order', // 成交单
        'kLine' => '/First/Business/kLine', // k线
        'tickerInfo' => '/First/Business/tickerInfo', // 币币行情
        'dealMarketOrder' => '/Deal/dealMarketOrder', // 提交市价委托单
        'dealLimitOrder' => '/Deal/dealLimitOrder', // 提交限价委托单
        'dealDetail' => '/Deal/dealDetail', // 委托详情
        'getDealList' => '/Deal/getDealList', // 委托列表
        'getHistoryDealList' => '/Deal/getHistoryDealList', // 历史委托列表
        'cancelDeal' => '/Deal/cancelDeal', // 撤销委托单
        'batchCancelDeal' => '/Deal/batchCancelDeal', // 批量撤销委托单
    ];

    // 字段键名映射关系[固定配置]
    public static $fields_map = [
        // '我方键名' => '对方键名'
        'symbol' => 'symbol',
        'side' => 'side',
        'number' => 'number',
        'price' => 'price',
        'client_user_id' => 'client_user_id',
    ];

    // 字段键值映射关系[固定配置]
    public static $field_values_map = [
        // '我方键名' => ['我方键值' => '对方键值']
    ];

    private static $url = "";
    private static $api_key = "";
    private static $api_secret = "";

    public function __construct($config)
    {
        self::$url = $config['url'];
        self::$api_key = $config['api_key'];
        self::$api_secret = $config['api_secret'];

    }

    // 获取用户资产信息

    /**
     * @throws Exception
     */
    public static function getUserAssets($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    /**
     * @throws Exception
     */
    public static function symbolList($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    /**
     * @throws Exception
     */
    public static function itemList($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    /**
     * @throws Exception
     */
    public static function depth($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    /**
     * @throws Exception
     */
    public static function order($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    /**
     * @throws Exception
     */
    public static function kLine($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    /**
     * @throws Exception
     */
    public static function tickerInfo($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);
        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }


    // 市价单下单
    public static function dealMarketOrder($symbol, $side, $number, $total_money, $client_user_id, $ext = [])
    {
        $attribute = $ext;
        $attribute['symbol'] = $symbol;
        $attribute['type'] = $side;
        $attribute['amount'] = $number;
        $attribute['total_money'] = $total_money;
        $attribute['diy_user_id'] = $client_user_id;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    // 限价单下单
    public static function dealLimitOrder($symbol, $side, $number, $price, $client_user_id, $ext = [])
    {
        $attribute = $ext;
        $attribute['symbol'] = $symbol;
        $attribute['type'] = $side;
        $attribute['amount'] = $number;
        $attribute['price'] = $price;
        $attribute['diy_user_id'] = $client_user_id;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    // 委托单详情
    public static function dealDetail($ext = [])
    {
        $attribute = $ext;

        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    public static function getDealList($ext = [])
    {
        $attribute = $ext;

        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    public static function getHistoryDealList($ext = [])
    {
        $attribute = $ext;

        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }



    public static function cancelDeal($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }

    // 批量撤单接口
    public static function batchCancelDeal($ext = [])
    {
        $attribute = $ext;
        $pending_data = static::encodeDataValue($attribute);
        $pending_data = static::encodeDataKey($pending_data);
        $response_str = HttpClient::post(static::getRequestUrl(__FUNCTION__),
            static::getRequestData($pending_data),
            static::$timeout, static::$proxy_switch);

        $response_data = static::getFormatResponseData($response_str);

        return $response_data ?? [];
    }


    // 获取请求URL
    public static function getRequestUrl($function, array $attribute = [])
    {
        $url = self::$url;
        $uri = static::$request_uri[$function] ?? static::$request_uri['default'];
        $url = rtrim($url, '/') . '/' . $uri;
        if (!empty($attribute)) {
            $url = sprintf($url, ...array_values($attribute));
        }

        return $url;
    }

    // 获取签名请求数据
    public static function getRequestData(array $pending_data = [])
    {
        $pending_data['api_key'] = self::$api_key;
        $pending_data['random_int_str'] = rand(111111, 999999);
        $pending_data['time'] = time();
        $api_secret = self::$api_secret;

        // 生成签名字符串
        $data_str = '';
        ksort($pending_data);
        foreach ($pending_data as $k => $v) {
            if (is_array($v)) {
                $tmp_data = [];
                foreach ($v as $k2 => $v2) {
                    $tmp_data[] = "{$k}[{$k2}]={$v2}";
                }
                $data_str .= implode('&', $tmp_data) . '&';
            } else {
                $data_str .= $k . '=' . $v . "&";
            }
        }

        $sign_str = trim($data_str, "&") . $api_secret;
        $pending_data['sign'] = md5($sign_str);


        return $pending_data;
    }

}