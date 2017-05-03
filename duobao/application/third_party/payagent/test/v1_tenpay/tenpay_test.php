#!/usr/local/php/bin/php
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

require_once dirname(__FILE__) . '/../TenpayFacade.php';

$tenpayFacade = new TenpayFacade();

$arrPartner = array(
		'iPartnerId' => '1206604901',
		'sKey' => '03cb965837d36236ffa3976d1b9b6484',
		'sCertPasswd' => '1206604901',
		'sLoginPasswd' => 'ch38425',
		'iRefundMode' => 1,
);
// var_dump($tenpayFacade->getPayShortNo(TenpayFacade::mch_tenpay_ip, $arrPartner, '1212044701',
// 									  1036634591, '1210227201201305301121130091', '1206604901201305301121130091', 990,
// 									  'QQ团购', 'http://tuan.qq.com/deal/notify', '1036634591-100', '10.24.66.64'));
// //2013053003226007

// var_dump($tenpayFacade->getPayShortNo(TenpayFacade::mch_tenpay_ip, $arrPartner, '1212044701',
// 									  1036634591, '1362464412201306141521240291', '1206604901201306141521240291', 90,
// 									  'leshua~464787', 'http://mapi.tuan.qq.com/public/pay_notify.php', '1036634591~10010', '10.129.137.19'));
// //2013053003226007


// $arrPartner = array(
// 		'iPartnerId' => '1900000109',
// 		'sKey' => '8934e7d15453e97507ef794cf7b0519d',
// );
// // var_dump($tenpayFacade->redirectToPayGate($arrPartner, 0, '', '1210227201'.'2013053017564204', 1, 'test_weixin_pay~1',
// // 										  'WX', '', 'http://dev.mapi.gaopeng.com/alipay/wap_notify', '1036634591-10086',
// // 										  '10.24.66.64', strtotime('2013-05-30'), strtotime('2013-06-30')));
// // var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '12102272012013053017564204', ''));
// // var_dump($tenpayFacade->redirectToPayGate($arrPartner, 0, '', '1210227201'.'2013060316312400', 1, 'test_weixin_pay(微团购)~2',
// // 										  'WX', '', 'http://dev.mapi.gaopeng.com/alipay/wap_notify', '1036634591-10086',
// // 										  '10.24.66.64', strtotime('2013-06-03'), strtotime('2013-07-03')));
// // var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '12102272012013060316312400', ''));
// // $arrPartner = array(
// // 		'iPartnerId' => '1216432801',
// // 		'sKey' => '6abf4c7f95553d90247c32d09da5e76a',
// // );
// var_dump($tenpayFacade->getPayGateURL($arrPartner, '1210227201'.'20130605151527011', 1, 'test_weixin_pay(微团购)~3',
// 									  'WX', 'http://dev.mapi.gaopeng.com/tenpay/wap_callback', 'http://dev.mapi.gaopeng.com/wenxinpay/notify', '1036634591-10086',
// 									  '10.24.66.64', strtotime('2013-06-05'), strtotime('2013-07-05')));
// //var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '121022720120130605151527011', ''));


// $arrPartner = array(
// 		'iPartnerId' => '1216432801',
// 		'sKey' => '6abf4c7f95553d90247c32d09da5e76a',
// );
// var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '1362464412201307171700430085', ''));



// var_dump($tenpayFacade->redirectToB2CPayGate($arrPartner, 1036634591, '',
// 											 '1210227201201305301121130091', '1206604901201305301121130091', 990, 'QQ团购哈哈哈哈 ',
// 											 '', 'http://tuan.qq.com/deal/notify', '1036634591-100', '10.24.66.64'));

//parse_str('attach=1036634591~10010&bargainor_id=1206604901&cmdno=1&date=20130619&fee_type=1&pay_info=OK&pay_result=0&pay_time=1371636940&sign=F2F35C8CDE62479C113D4EAEE28D5748&sp_billno=1210227201201306191813580291&total_fee=8800&transaction_id=1206604901201306191813580291&ver=1', $arrNotifyParams);
//parse_str('attach=u%253D2041935214270943171%2526s%253D133%2526c%253D0.-1%2526i%253Dnull&bargainor_id=1206604901&cmdno=1&date=20130625&fee1=12800&fee2=0&fee3=0&fee_type=1&pay_info=OK&pay_result=0&pay_time=1372131776&sign=38AD843493F53192AD45DCD2FFAD02C9&sp_billno=1362464412201306251137090271&total_fee=12800&transaction_id=1206604901201306251137090271&ver=2&vfee=0', $arrNotifyParams);
//parse_str('attach=1988927~10010&bank_billno=&bank_type=0&bargainor_id=1206604901&charset=1&fee_type=1&pay_info=OK&pay_result=0&purchase_alias=117276278&sign=3BF36298D50F91417A7C7E51D969BD55&sp_billno=1210227201201306250809380027&time_end=20130625080954&total_fee=15600&transaction_id=1206604901201306250809380027&ver=2.0', $arrNotifyParams);
// parse_str('attach=1036634591-10086&bank_billno=206054083578&bank_type=3006&discount=0&fee_type=1&input_charset=GBK&notify_id=WE37gwCoFBcAKdkH34Y1nZp4ZERnhm2QlSOCky-Bb9moAzyy78KqRjWU7LU1NTGspBnIcvoiwHAtvgNDZzzz1XEsgYlmsMwf&out_trade_no=1210227201201306201222390089&partner=1900000109&product_fee=1&sign_type=MD5&time_end=20130605152603&total_fee=980&trade_mode=1&trade_state=0&transaction_id=1900000109201306201222390089&transport_fee=0&sign=f758add999ada294023ce60703136d2d', $arrNotifyParams);
// //Config
// $arrSignKeyConfig = array(
// 		'1206604901' => '03cb965837d36236ffa3976d1b9b6484', //新统一收款账户
// 		'1000009301' => '26b0056ce1adc41af80467def33ad810', //老QQ团购收款账户, 老mapi都用这个签名. `sign_sp_id`
// 		'1900000109' => '8934e7d15453e97507ef794cf7b0519d',
// );
// //如果特殊指定则用sign_sp_id, 否则用支付商户号
// if (isset($arrNotifyParams['sign_sp_id']))
// {
// 	$sKey = $arrSignKeyConfig[$arrNotifyParams['sign_sp_id']];
// }
// else if (isset($arrNotifyParams['bargainor_id']))
// {
// 	$sKey = $arrSignKeyConfig[$arrNotifyParams['bargainor_id']];
// }
// else if (isset($arrNotifyParams['partner']))
// {
// 	$sKey = $arrSignKeyConfig[$arrNotifyParams['partner']];
// }
// else
// {
// 	exit('ERROR!!!');
// }
// var_dump($tenpayFacade->processPayNotify($sKey, $arrNotifyParams));

//var_dump($tenpayFacade->isOrderPaid(TenpayFacade::mch_tenpay_ip, $arrPartner, '1210227201201305301121130091', '1206604901201305301121130091'));
//var_dump($tenpayFacade->isOrderPaid(TenpayFacade::mch_tenpay_ip, $arrPartner, '1362464412201306071713540160', '1206604901201306071713540160'));

//var_dump($tenpayFacade->getTransList(TenpayFacade::mch_tenpay_ip, $arrPartner, strtotime('2013-05-29'), 2));

//var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '1210227201201305301121130091', '1206604901201305301121130091'));
//var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '1362464412201305302000150142', '1206604901201305302000150142'));

//var_dump($tenpayFacade->gwQueryOrderRefundInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '1210227201201302152310040173', '1206604901201302152310040173', '109120660490120130413025530000'));

//var_dump(XMLArray::SimpleXMLToKVArray('<x><a /> <b></b> <c> </c></x>'));
//var_dump(simplexml_load_string('<x><a /> <b></b> <c> </c></x>', "SimpleXMLElement", (($hasCDATA)? LIBXML_NOCDATA : 0)));

//var_dump($tenpayFacade->refundOrder(TenpayFacade::mch_tenpay_ip, $arrPartner, '1206604901201304050529150091', '109120660490120130405054520000', 1880, 1, '10.24.66.64', '测试退款'));
// var_dump($tenpayFacade->refundOrder(TenpayFacade::mch_tenpay_ip, $arrPartner, '1206604901201301202211340037', '109120660490120130424122937000', 9900, 1, '10.24.66.64', '测试退款'));
// //[CFT]~refundOrder~retcode:03020137, errmsg:收款财付通账户已经注销，请联系财付通人员进行处理!

//var_dump($tenpayFacade->getCAccountTransList(TenpayFacade::mch_tenpay_ip, $arrPartner, strtotime('2013-06-01'), '10.24.66.64'));

//var_dump($tenpayFacade->queryPartnerInfo(TenpayFacade::mch_tenpay_ip, $arrPartner, 1, '10.24.66.64'));


$arrPartner = array(
		'iPartnerId' => '1216432801',
		'sKey' => '6abf4c7f95553d90247c32d09da5e76a',
		'sCertPasswd' => '1216432801',
		'sLoginPasswd' => '111111',
		'iRefundMode' => 1,
);

$xx = 'attach=clientId%253D13&bank_type=WX&body=%A1%BE7%B3%C792%B5%EA%CD%A8%D3%C3%A1%BFCOSTA%D6%D0%B1%AD%D2%FB%C6%B71%B1%AD+%A3%A810%B1%AD%C6%F0%A3%A9%A3%AC%B3%AC%D6%B5...&fee_type=1&input_charset=GBK&notify_url=http%3A%2F%2Fmapi.gaopeng.com%2Fweixinpay%2Fnotify&out_trade_no=1362464412201308051232280165&partner=1216432801&spbill_create_ip=211.99.197.228&time_expire=20140326235959&time_start=20130427000000&total_fee=23900&sign=F4D19A7EF6919584171DEB69C70CB68E';
parse_str($xx, $arr); var_dump($arr);
foreach ($arr as $key => $value)
{
	echo "$key=$value&";
}
echo "\n";

var_dump(urldecode($xx));

$arr['body'] = '【7城92店通用】COSTA中杯饮品1杯 （10杯起），超值...';
var_dump(http_build_query(array('xx'=>'y y'), null, null, PHP_QUERY_RFC3986));
var_dump(http_build_query($arr));

exit();

//var_dump($tenpayFacade->gwQueryOrderPayInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '1210227201201307101444240180', '1216432801201307100312925154'));
//var_dump($tenpayFacade->gwRefundOrder(TenpayFacade::mch_tenpay_ip, $arrPartner, '1210227201201307101444240180', '1216432801201307100312925154', '20130716104033000', 1, 1));
//var_dump($tenpayFacade->gwQueryOrderRefundInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, '1210227201201307101444240180', '1216432801201307100312925154', ''));



echo "retcode=".$tenpayFacade->_getRetCode()."\n";
echo "errmsg=".$tenpayFacade->_getErrMsg()."\n";
echo "requrl=".$tenpayFacade->_getRequestURL()."\n";
echo "curlerrno=".$tenpayFacade->_getCurlErrno()."\n";
echo "curlerrmsg=".$tenpayFacade->_getCurlErrMsg()."\n";
echo "httpcode=".$tenpayFacade->_getCurlHttpCode()."\n";
echo "res_content=".$tenpayFacade->_getResponseContent()."\n";
echo "res_params:\n";
var_dump($tenpayFacade->_getResponseParameters());

