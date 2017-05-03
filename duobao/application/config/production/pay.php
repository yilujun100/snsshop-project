<?php

//支付网关，以便后期独立
$config['pay_url'] = 'http://dgbpayment.gaopeng.com/payment/pay/index';
$config['pay_redirect'] = 'http://dgbpayment.gaopeng.com/payment/pay/result';
$config['pay_notify'] = 'http://dgbpayment.gaopeng.com/payment/pay/notify';

/*
$config['pay_partner'] = array(
    1 => array(
        'iPartnerId' => '1216432801',
        'sEmail' => '',
        'sKey' => '6abf4c7f95553d90247c32d09da5e76a',
        'sCertPasswd' => '1216432801',
        'sLoginPasswd' => 'gp388628',
        'iRefundMode' => 1
    ),
);
*/
$config['pay_partner'] = array(
    1 => array(
        'iPartnerId' => '1349254401',
        'sEmail' => '',
        'sKey' => 'GAOPENGbaifenhaoLI20160531185130',
        'sCertPasswd' => '1349254401',
        'sLoginPasswd' => '977844',
        'iRefundMode' => 1
    ),
);

//微信配置
$config['weixinConfig'] = array(
    'appId'     => 'wx8e4c7364a259befb',
    'appSecret' => '70eec0964d1cad3dcc698272003c78a1',
    'token'     => '458ErfdlvfAeof7rR2oe',
    'nonceStr'	=> 'adssdasssd13d',
    'signType'	=> 'SHA1',
    'appKey'	=> 'SegFQwgCuzi43z6o7auvHsy8Zr1JZNWhrCkFy1HsXpUpopdSX9L51KzpB1y1bUF1Vp6TNcKb151ORC6fagJyqJnq8jQGBU7WHzgTbouzQSO8UhMsXUqM2bCyUQEU99ge', // 用于微信支付
);

//微信消息配置
$config['weixinNotify'] = array(
    'batchSendReadyInfo' => array( //即奖开始
        'url' => 'http://duogebao.gaopeng.com/duogebao/my/active?cls=going',
        'TEMP_ID' => 6003,
        'template_id' => 'BycGjbZsqePQtfFBrMOAPW25eUbLshgKGwc_aVd5hH8',//模板KEY
    ),
    'batchSendResultInfo' => array(//开奖结果
        'url' => 'http://duogebao.gaopeng.com/duogebao/my/active?cls=opened',
        'TEMP_ID' => 6003,
        'template_id' => 'BycGjbZsqePQtfFBrMOAPW25eUbLshgKGwc_aVd5hH8',//模板KEY
    ),
    'batchSendNotifyInfo' => array(//发码通知
        'url' => 'http://duogebao.gaopeng.com/duogebao/home/index',
        'TEMP_ID' => 6002,
        'template_id' => 'pCe85kcaCvv_fM0TK1h9LOf3UY27PgLTl9TUPxQfIM4',//模板KEY
    ),
    'buyCouponSucc' => array(//充值夺宝券
        'url' => 'http://duogebao.gaopeng.com/duogebao/home/index',
        'TEMP_ID' => 6002,
        'template_id' => 'pCe85kcaCvv_fM0TK1h9LOf3UY27PgLTl9TUPxQfIM4',//模板KEY
    ),
    'dailyDeliverNotify' => array( // 延迟发货
        'TEMP_ID' => 6003,
        'template_id' => 'h5tMj7C45k1yEF6meJ0Mc0-MDwSgUxc2oy2VOoAIFpQ', //模板KEY
    ),
    'cancelOrderNotify' => array( // 取消订单
        'TEMP_ID' => 6004,
        'template_id' => '3mPQ49PZwGYdBI2oLrAMQXO89Dd-3O_xW4XMLBY2kUk', //模板KEY
    ),
    'deliverNotify' => array( // 发货
        'TEMP_ID' => 6005,
        'template_id' => 'OFVv6yhwCrD6C5CtipLGtDojRTr2t7K_wbMUJoDlxn0',//模板KEY
    ),
);

//微信支付及退款配置
$config['weixinPay'] = array(
    'APPID' => 'wx488f73947945e425',
    'MCHID' => '1260789701',
    'KEY' => 'bhghhnthtnhhnythnynhynh67u67u7fh',//6abf4c7f95553d90247c32d09da5e76a
    'APPSECRET' => '70eec0964d1cad3dcc698272003c78a1',//70eec0964d1cad3dcc698272003c78a1
    'SUB_APPID' => 'wx8e4c7364a259befb',
    'SUB_MCHID' => '1349254401',
    'JS_API_CALL_URL' => 'http://www.xxxxxx.com/demo/js_api_call.php',
    'SSLCERT_PATH' => APPPATH.'third_party/payagent/sslcert/weixin_cert.pem',
    'SSLKEY_PATH' => APPPATH.'third_party/payagent/sslcert/weixin_key.pem',
    'NOTIFY_URL' => $config['pay_notify'],
    'CURL_TIMEOUT' => 30
);