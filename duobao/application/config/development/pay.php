<?php

//支付网关，以便后期独立
$config['pay_url'] = 'http://duogebao.vikduo.com/payment/pay/index';
$config['pay_redirect'] = 'http://duogebao.vikduo.com/payment/pay/result'; //默认订单成功提示页
$config['pay_notify'] = 'http://duogebao.vikduo.com/payment/pay/notify';

$config['pay_partner'] = array(
    1 => array(
        'iPartnerId' => '1234615202',
        'sEmail' => '',
        'sKey' => 'w8w4qnxacbrsoa6dvm8l1clfjp85su1q',
        'sCertPasswd' => '1234615202',
        'sLoginPasswd' => 'gp388628',
        'iRefundMode' => 1
    ),
);


//微信配置
$config['weixinConfig'] = array(
    'appId'     => 'wx863f8ec546ec960a',
    'appSecret' => '5874360b0b89cc715950c69a54ec9439',
    'token'     => 'test_token_wtg',
    'nonceStr'  => 'adssdasssd13d',
    'signType'  => 'SHA1',
    'appKey'    => 'SegFQwgCuzi43z6o7auvHsy8Zr1JZNWhrCkFy1HsXpUpopdSX9L51KzpB1y1bUF1Vp6TNcKb151ORC6fagJyqJnq8jQGBU7WHzgTbouzQSO8UhMsXUqM2bCyUQEU99ge', // 用于微信支付
);

//微信消息配置
$config['weixinNotify'] = array(
    'batchSendReadyInfo' => array( //即奖开始
        'url' => 'http://duogebao.vikduo.com/duogebao/my/active?cls=going',
        'TEMP_ID' => 2002,
        'template_id' => 'fQTfecrkG54KoG2KSfpa2kNoCwRTGQ0Id6fFQriRuD4',//模板KEY
    ),
    'batchSendResultInfo' => array(//开奖结果
        'url' => 'http://duogebao.vikduo.com/duogebao/my/active?cls=opened',
        'TEMP_ID' => 2002,
        'template_id' => 'fQTfecrkG54KoG2KSfpa2kNoCwRTGQ0Id6fFQriRuD4',//模板KEY
    ),
    'batchSendNotifyInfo' => array(//发码通知
        'url' => 'http://duogebao.vikduo.com/duogebao/home/index',
        'TEMP_ID' => 2001,
        'template_id' => '52kqrTxdTxPFUIasU2nVjVt6pV7TQvJ0WQUT-ojaXzc',//模板KEY
    ),
    'dailyDeliverNotify' => array( // 延迟发货
        'TEMP_ID' => 2003,
        'template_id' => 'oTupKpSuetmFdi6xFr70naduCv1fgjwQHNc_9u_I6i4',//模板KEY
    ),
    'cancelOrderNotify' => array( // 取消订单
        'TEMP_ID' => 2004,
        'template_id' => 'Zg4NEvqblpAZcoIhvbmg68Ykd2Lw5rvNsPNJcn_hKeU',//模板KEY
    ),
    'deliverNotify' => array( // 发货
        'TEMP_ID' => 2005,
        'template_id' => 'CbRtKKHyqBQkX8mgbzB0xhpeln6KflK4uc87tEesiEA',//模板KEY
    ),
);

//微信支付及退款配置
$config['weixinPay'] = array(
    'APPID' => 'wx863f8ec546ec960a',
    'MCHID' => '1234615202',
    'KEY' => '2W3282ipIs9ks0b8sl4cjj20x8c8dq30',
    'APPSECRET' => '5874360b0b89cc715950c69a54ec9439',
    'JS_API_CALL_URL' => 'http://www.xxxxxx.com/demo/js_api_call.php',
    'SSLCERT_PATH' => APPPATH.'third_party/payagent/testcert/weixin_cert.pem',
    'SSLKEY_PATH' => APPPATH.'third_party/payagent/testcert/weixin_key.pem',
    'NOTIFY_URL' => $config['pay_notify'],
    'CURL_TIMEOUT' => 30
);