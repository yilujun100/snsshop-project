#!/usr/local/php/bin/php
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

require_once dirname(__FILE__) . '/../TenpayWapFacade.php';

$tenpayFacade = new TenpayWapFacade();

$arrPartner = array(
		'iPartnerId' => '1206604901',
		'sKey' => '03cb965837d36236ffa3976d1b9b6484',
		'sCertPasswd' => '1206604901',
		'sLoginPasswd' => 'ch38425',
		'iRefundMode' => 1,
);
// var_dump($tenpayFacade->getPayShortNo(TenpayWapFacade::mch_tenpay_ip, $arrPartner, '1212044701',
// 									  1036634591, '1210227201201305301121130091', '1206604901201305301121130091', 990,
// 									  'QQ团购', 'http://tuan.qq.com/deal/notify', '1036634591-100', '10.24.66.64'));
// //2013053003226007

// var_dump($tenpayFacade->getPayShortNo(TenpayWapFacade::mch_tenpay_ip, $arrPartner, '1212044701',
// 									  1036634591, '1362464412201306141521240391', '1206604901201306141521240391', 90,
// 									  'leshua~464787', 'http://mapi.tuan.qq.com/public/pay_notify.php', '1036634591~10010', '10.129.137.19'));
// //2013053003226007



var_dump($tenpayFacade->getWapPayUrl(HttpClient::HTTP_PROXY1, $arrPartner,
									 '【东海西路】韩式汗蒸超值女性套餐', '1210227201201306171604500089', '1206604901201306171604500089', 990,
									 'http://dev.mapi.gaopeng.com/tenpay/wap_callback?clientId=11', 'http://dev.mapi.gaopeng.com/tenpay/wap_notify', 'clientId%3D11', 0,
									 2041589654292995689, 0, 0));

//var_dump($tenpayFacade->isOrderPaid(HttpClient::HTTP_PROXY1, $arrPartner, '1210227201201306171604500089', '1206604901201306171604500089'));
//var_dump($tenpayFacade->isOrderPaid(HttpClient::HTTP_PROXY1, $arrPartner, '1362464412201305302000150142', '1206604901201305302000150142'));



echo "retcode=".$tenpayFacade->_getRetCode()."\n";
echo "errmsg=".$tenpayFacade->_getErrMsg()."\n";
echo "requrl=".$tenpayFacade->_getRequestURL()."\n";
echo "curlerrno=".$tenpayFacade->_getCurlErrno()."\n";
echo "curlerrmsg=".$tenpayFacade->_getCurlErrMsg()."\n";
echo "httpcode=".$tenpayFacade->_getCurlHttpCode()."\n";
echo "res_content=".$tenpayFacade->_getResponseContent()."\n";
echo "res_params:\n";
var_dump($tenpayFacade->_getResponseParameters());

