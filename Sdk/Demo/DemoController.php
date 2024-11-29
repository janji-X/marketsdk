<?php

namespace Controller\Sdk\Demo;


use Controller\Sdk\Model\SpotTradeModel;
use Exception;

class DemoController
{

    private static SpotTradeModel $spotTradeModel;
    private static int $symbol_id;
    private static string $symbol;

    public function __construct()
    {
        // TODO 替换接口地址
        $url = "https://testapi.oihqfjapi.online";
        $url = "http://10.170.0.17:8990";
        // TODO 替换api_key
        $api_key = "bNDe0dspSXubd2BvJCj9U4uM19dSDS";
        // TODO 替换api_secret
        $api_secret = "wMRoKgpGuSVK6mNIJHpEWGMACC1avXwB872iFeE7EB68INUTPo";
        // TODO 替换为做市交易对的id
        self::$symbol_id = 1213;
        // TODO 替换为做市交易对的名称
        self::$symbol = 'ppt_usdt';

        $config = compact('url', 'api_key', 'api_secret');
        self::$spotTradeModel = new SpotTradeModel($config);
    }

    /**
     * 获取用户资产信息
     * @return array|mixed
     * @throws Exception
     */
    public static function getUserAssets()
    {
        return self::$spotTradeModel::getUserAssets();
    }

    /**
     * 获取交易对信息
     * @return array|mixed
     * @throws Exception
     */
    public static function symbolList()
    {
        $attr = [];
        $attr['symbol_id'] = self::$symbol_id;
        return self::$spotTradeModel::symbolList($attr);
    }

    /**
     * 获取币种信息
     * @return array|mixed
     * @throws Exception
     */
    public static function itemList()
    {
        $attr = [];
        $attr['symbol_id'] = self::$symbol_id;
        return self::$spotTradeModel::itemList($attr);
    }

    /**
     * 获取深度
     * @return array|mixed
     * @throws Exception
     */
    public static function depth()
    {
        $attr = [];
        $attr['symbol_id'] = self::$symbol_id;
        $attr['size'] = 100;
        return self::$spotTradeModel::depth($attr);
    }

    /**
     * 获取成交单
     * @return array|mixed
     * @throws Exception
     */
    public static function order()
    {
        $attr = [];
        $attr['symbol_id'] = self::$symbol_id;
        return self::$spotTradeModel::order($attr);
    }

    /**
     * 获取kline
     * @return array|mixed
     * @throws Exception
     */
    public static function kLine()
    {
        $attr = [];
        $attr['symbol_id'] = self::$symbol_id;
        $attr['begin'] = 1720020260;
        $attr['end'] = 1749020260;
        $attr['type'] = 1;
        return self::$spotTradeModel::kLine($attr);
    }

    /**
     * 获取币币行情
     * @return array|mixed
     * @throws Exception
     */
    public static function tickerInfo()
    {
        $attr = [];
        $attr['symbol_id'] = self::$symbol_id;
        return self::$spotTradeModel::tickerInfo($attr);
    }

    /**
     * 下市价单
     * @return array|mixed
     * @throws Exception
     */
    public static function dealMarketOrder()
    {
        $attr = [];
        $symbol = self::$symbol;
        $side = 'sell';
        // 出售时必传，购买时传0即可
        $number = 1001;
        // 购买时必传，出售时传0即可
        $total_money = 0;
        $client_user_id = 999;
        return self::$spotTradeModel::dealMarketOrder($symbol, $side, $number, $total_money, $client_user_id, $attr);
    }


    /**
     * 下限价单
     * @return array|mixed
     * @throws Exception
     */
    public static function dealLimitOrder()
    {
        $attr = [];
        $symbol = self::$symbol;
        $side = 'buy';
        $number = 100;
        $price = 1;
        $client_user_id = 999;
        return self::$spotTradeModel::dealLimitOrder($symbol, $side, $number, $price, $client_user_id, $attr );
    }


    /**
     * 委托详情
     * @return array|mixed
     * @throws Exception
     */
    public static function dealDetail()
    {
        $attr = [];
        $attr['id'] = 27138337;
        return self::$spotTradeModel::dealDetail($attr);
    }

    /**
     * 委托列表
     * @return array|mixed
     * @throws Exception
     */
    public static function getDealList()
    {
        $attr = [];
        $attr['status'] = '0,1,2,3';
        return self::$spotTradeModel::getDealList($attr);
    }


    /**
     * 获取历史委托列表
     * @return array|mixed
     * @throws Exception
     */
    public static function getHistoryDealList()
    {
        $attr = [];
        $attr['status'] = '0,1,2,3';
        $attr['symbol'] = 'ppt_usdt';
        return self::$spotTradeModel::getHistoryDealList($attr);
    }
    /**
     * 撤销委托
     * @return array|mixed
     */
    public static function cancelDeal()
    {
        $attr = [];
        $attr['id'] = '27138337';
        return self::$spotTradeModel::cancelDeal($attr);
    }

    /**
     * 批量撤销委托
     * @return array|mixed
     */
    public static function batchCancelDeal()
    {
        $attr = [];
        $attr['ids'] = '27146972,27146973,27146974';
        return self::$spotTradeModel::batchCancelDeal($attr);
    }


    /**
     * 充提币记录
     * @return array|mixed
     */
    public static function getCoinExchangeList()
    {
        $attr = [];
        return self::$spotTradeModel::getCoinExchangeList($attr);
    }
}