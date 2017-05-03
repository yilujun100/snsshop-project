#!/usr/local/php/bin/php
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

require_once dirname(__FILE__) . '/../AlipayWapFacade.php';

$alipayFacade = new AlipayWapFacade();

var_dump($alipayFacade->getWapPayUrl(HttpClient::HTTP_PROXY1, '2088301487863565', 'fpayment@ftuan.com',
									  'http://dev.mapi.gaopeng.com/alipay/wap_callback',
									  'http://dev.mapi.gaopeng.com/alipay/wap_notify',
									  'http://sange.gaopeng.qq.com/beijing/deal/buy/1310704115',
									  'test', '1210227201201303151522470191', 1, '1036634591', AlipayWapFacade::BANKTYPE_CREDITCARD));

// var_dump($alipayFacade->getWapPayUrl(HttpClient::HTTP_PROXY1, '2088301487863565', 'fpayment@ftuan.com',
// 									 'http://dev.mapi.gaopeng.com/alipay/wap_callback',
// 									 'http://dev.mapi.gaopeng.com/alipay/wap_notify',
// 									 'http://dev.mapi.gaopeng.com/',
// 									 '测试产品_FOR_图片比例022', '1210227201201303161938140091', 1, '1036634591'));


// $arrNotifyParams = array();
// $arrFixSignKey = array("service","v","sec_id","notify_data");

//parse_str('out_trade_no=1210227201201303161938140091&request_token=requestToken&result=success&trade_no=2013031658616034&sign=lUPsS129QUhFNGgFMTwBuwEm0yCOYY5xzCIjMPHMW5DKFFkuA8zGhyaaFiIJsszV8P08DqBIh6NY1emx6mp%2B6Wtii48AaAOAoWkSroavQuvIKeRHhl16CYiUxVKa3%2B5HoXDbUODI5VGjzMLWSKYK5W%2BMd34yzJ8Um6yOVuD0k7c%3D&sign_type=0001', $arrNotifyParams);
//var_dump($alipayFacade->processWapPayCallBack($arrNotifyParams));

// parse_str('service=alipay.wap.trade.create.direct&sign=JqHDI8XPF6mh41KvEhkV7Cs55qdVgQetK%2B3VrhjLJZOPIEAkcaxtaTeyWwyJ97N3uoCH2uzos8i0jGZmrBJbNA5iBUKEYXxu%2FhLDjJ1GbRufQaf6iEnszZ9u1Xk9slEWO5UZYsN7QTR4hVjEY9FFDZNWBqxpFFACeDEiIlUzdLg%3D&sec_id=0001&v=1.0&notify_data=EqOJXlG1CaBmdzm5xDKJseiLCZXXqToXcyeq156eEFEkYzTV6X9qo1F4dnX3RZo7806RXUquxDNumXble%2Bzorj3pD0zH4Y8Y%2FCuEZqvb0KHM988eusmgAO%2BV2oS5pyxZAxiaJKFSNjho2cBjbBsGlJu%2B1lQDHRgu1uelwlubTgW5MRa2foGk%2Fh1qWAMRmpVmhRIz03SqNwV%2BenhSz%2Fml6322QDMzFCmW2APzizoeCZ2evnBxgiKXgN0uQrZyB%2BCydH8Ei%2FU93GthOcDa2PfBFeVSN7EUTBEhep8xIk4Glq3vvvzsr8UPKjKwVz%2FP%2F1UPZZlzJG0huJXTCIvVPmKFyaQRc9r9GQnP0Mz3ecXwRV3ZnIZUKJxJqEX0khSkQ6oaDpSKZZm5%2B1RAmGZoHIiC6U5P1N2UXJQEvA8n5Id6kjkeirBjQh0YxLI0KdUNfVt52GCG6A57HWJczuvT66kLva7CcZ57mQBZX2jlelUcacETbQ990tcOOaPej3lWmHFykcAGgszzSWPYUyyRJzaG8G8tks9X1cCuUdH4Y6iHRHTil%2BeScZ3PqK%2FMwyDHSx6y9r5tVkOW%2BEloM0RyF7lJyb6E6AwH%2FGQaE0hX6tGdmnmbd3xyGyCI1EVAmtLJ8uCsFX7%2FlHCcg6SKQyVzMPsXlSaTFhgcXl8COtcZ5drNBf47SNdnqAx2ymj4J709J4Q8W%2Fq5l1LXRgd2SVozLyBMfLHieJw682XEd8k7Ss23YhHuW%2BizEImuWwE4j3xF7DEztpPBZ4TapAp1prYsm%2B92FH%2BbHmZSe3xR5jODmunM6ltTkjtmM6f92ePOvRdy984dWzcVe1e3qfKxR%2FTFDdrEbaqOxtB2hmUO9IL9thJMfF2fT18rSWY9vNh2c9ybA6FFaC9pTxqI%2Bd%2FWa3NJ5p8abim6MpiKyGYgdwLJiEtmctFP1djXC81WVSL17cS1Xby7vpEu7EZlP9uS76XwlzcCiZu50rHSRYMyVIMfQUQqQV7i90b6nn2Hb%2FNJLARm%2BO8DYTgI0nxKtva8foSBIzdnnX2%2FKVsf0eQ%2BWFVVVmtLep%2FRMmq9VecU8iYz9BELCJGKg7gvJo86CXsi5tLrQc9pgPC7LRu1V%2BQ19OX%2B5YE5AcdcBIO74I5eXDKLdtjFGXfExpF8J%2FxmYAVsdW8Hk2g%2FC8U1ZtW%2B6Vs1EJ66qbD2ixMtM%2F%2BIjw%2BStatZ8fc7dkA4J6wWRbwSR82Qe7ekv9LgAa2wmWjKMzzykYuf%2FEM5sAVT0WGXX1M5QcIWj5vv37QBLDMoJMbYE7M0iEzWXmyx2VVWeKU6BivLecmoiOFawj1jUNwQRBiPAm2bApmf8%2B%2FmfrnwVovsB%2BI%2Fzk3IXPyWZBmVv7cCOLgBXFV0E7EWUnVzopdVPRUB2VJmsiXdwaYd6BklAo1YSg6IUzGDaiIIPQypgE7LWBoxNN%2BW1GlYyH0rwzHN1L9fuV9ISS%2FoR2zyYnNL0frPDu%2B1%2FtAY%2FsoL%2BB7hOIVgUxZo4AmBWQuNF7maMRBWF4CjBsoYnIgTthOt', $arrNotifyParams);
// var_dump($arrNotifyParams);
// var_dump($alipayFacade->processWapPayNotify($arrNotifyParams));

//TODO secure pay 异步通知调试
// parse_str('notify_data=<notify><payment_type>1</payment_type><subject>测试产品_FOR_图片比例022</subject><trade_no>2013031658616034</trade_no><buyer_email>18616814352</buyer_email><gmt_create>2013-03-16 19:48:24</gmt_create><notify_type>trade_status_sync</notify_type><quantity>1</quantity><out_trade_no>1210227201201303161938140091</out_trade_no><notify_time>2013-03-16 19:48:34</notify_time><seller_id>2088301487863565</seller_id><trade_status>TRADE_FINISHED</trade_status><is_total_fee_adjust>N</is_total_fee_adjust><total_fee>0.01</total_fee><gmt_payment>2013-03-16 19:48:34</gmt_payment><seller_email>fpayment@ftuan.com</seller_email><gmt_close>2013-03-16 19:48:34</gmt_close><price>0.01</price><buyer_id>2088202892122347</buyer_id><notify_id>9af6b5b0a81dac88d86723a45b82bdf303</notify_id><use_coupon>N</use_coupon></notify>&sign=C9Z%2FLHPS8pDwCP28v8%2FdOD%2FmH0V11SIO7zcpzO7nRw5%2BERFoW0aMtzm3kiB5lyTSI%2Bj24P3QxSOdeIP%2BRjuXIgUtOlFrPz3f2T%2BBDT1M235xsMrhX5r4fkumbaEHSf24imPa%2FnzqGzASxrU3kynU5T2t%2BDkRhXI9oWXErmjxL6I%3D', $arrNotifyParams);
// var_dump($arrNotifyParams);
// var_dump($alipayFacade->processSecurePayNotify($arrNotifyParams));

echo "retcode=".$alipayFacade->_getRetCode()."\n";
echo "errmsg=".$alipayFacade->_getErrMsg()."\n";
echo "requrl=".$alipayFacade->_getRequestURL()."\n";
echo "curlerrno=".$alipayFacade->_getCurlErrno()."\n";
echo "curlerrmsg=".$alipayFacade->_getCurlErrMsg()."\n";
echo "httpcode=".$alipayFacade->_getCurlHttpCode()."\n";
echo "res_content=".$alipayFacade->_getResponseContent()."\n";
echo "res_params:\n";var_dump($alipayFacade->_getResponseParameters());

