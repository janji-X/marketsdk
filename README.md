使用方法：
一.如果项目中使用composer管理包，则运行 composer require market/marketsdk
二.如果项目中没有使用composer管理包，则访问https://github.com/janji-X/marketsdk
   1.将代码下载到本地
   2.参照DemoController调用各个方法，需要将DemoController中如下5个变量替换为自己的。  
        // TODO 替换接口地址
        $url = "https://testapi.oihqfjapi.online";
        // TODO 替换api_key
        $api_key = "bNDe0dspSXubd2BvJCj9U4uM19dSDS";
        // TODO 替换api_secret
        $api_secret = "wMRoKgpGuSVK6mNIJHpEWGMACC1avXwB872iFeE7EB68INUTPo";
        // TODO 替换为做市交易对的id
        self::$symbol_id = 1213;
        // TODO 替换为做市交易对的名称
        self::$symbol = 'ppt_usdt';
