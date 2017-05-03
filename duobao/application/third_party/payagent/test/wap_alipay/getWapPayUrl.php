#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../AlipayWapFacade.php';

$arrPartner = array(
	'iPartnerId' => '2088301487863565',
	'sKey' => 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh',
	'sEmail' => 'fpayment@ftuan.com',
);

$iUin = 1036634591;
//$sKey = '';
$sOrderId = '1210338701201308011402560291';
$iTotalPrice = 1;
$sShortName = 'QQ团购';
//$sDesc = 'QQ团购支付宝';
$sCashierCode = '';
$sCallBackURL = 'http://dev.mapi.gaopeng.com/alipay/wap_callback';
$sNotifyURL = 'http://dev.mapi.gaopeng.com/alipay/wap_notify';
$sMerchantURL = '';
//$sAttach = 'xx%3Dyy';
//$sClientIP = '127.0.0.1';
//$iBeginTime = 0;   默认15天
//$iEndTime = time()+80;

$payFacade = new AlipayWapFacade();
$ret = $payFacade->getWapPayUrl(HttpClient::HTTP_PROXY1, $arrPartner['iPartnerId'], $arrPartner['sEmail'],
								$sCallBackURL, $sNotifyURL, $sMerchantURL,
								$sShortName, $sOrderId, $iTotalPrice, $iUin, $sCashierCode = '');
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
