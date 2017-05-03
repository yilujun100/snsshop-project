#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../TenpayFacade.php';

$arrPartner = array(
		'iPartnerId' => '1206604901',
		'sKey' => '03cb965837d36236ffa3976d1b9b6484',
);

$iUin = 1036634591;
$sKey = '';
$sOrderId = '1210338701201308011402560091';
$iTotalPrice = 1;
$sDesc = 'QQ团购';
$iBankType = 0;
$sReturnURL = 'http://dev.gaopeng.qq.com/deal/tenpay/return';
$sNotifyURL = 'http://dev.gaopeng.qq.com/deal/tenpay/notify';
$sAttach = 'xx%3Dyy';
$sClientIP = '127.0.0.1';
$iBeginTime = 0;
$iEndTime = 0;

$payFacade = new TenpayFacade();
$ret = $payFacade->getPayGateURL($arrPartner, $iUin, $sKey,
								 $sOrderId, $iTotalPrice, $sDesc,
								 $iBankType, $sReturnURL, $sNotifyURL, $sAttach,
								 $sClientIP, $iBeginTime, $iEndTime);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
