#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../AlipayFacade.php';

$arrPartner = array(
	'iPartnerId' => '2088301487863565',
	'sKey' => 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh',
	'sEmail' => 'fpayment@ftuan.com',
);

//$iUin = 1036634591;
//$sKey = '';
$sOrderId = '1210338701201308011402560291';
$iTotalPrice = 1;
$sShortName = 'QQ团购';
$sDesc = 'QQ团购支付宝';
//$iBankType = 0;
$sReturnURL = 'http://dev.gaopeng.qq.com/deal/alipay/return';
$sNotifyURL = 'http://dev.gaopeng.qq.com/deal/alipay/notify';
$sAttach = 'xx%3Dyy';
$sClientIP = '127.0.0.1';
$iBeginTime = 0;
$iEndTime = time()+80;

$payFacade = new AlipayFacade();
$ret = $payFacade->getPayGateURL($arrPartner,
								 $sOrderId, $iTotalPrice, $sShortName, $sDesc,
								 $sReturnURL, $sNotifyURL, $sAttach,
								 $sClientIP, $iBeginTime, $iEndTime);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
