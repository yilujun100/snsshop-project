#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../TenpayFacade.php';

$arrPartner = array(
		'iPartnerId' => '1216834901',
		'sKey' => 'a632e2f76410f92cd493127b505c4797',
);

$iUin = 0;
$sKey = '';
$sOrderId = '1210338701201308011402560091';
$iTotalPrice = 1;
$sBody = 'QQ团购';
$iBankType = 'WX';
$sReturnURL = 'http://dev.gaopeng.qq.com/deal/tenpay/return?payAgentType=5';
$sNotifyURL = 'http://dev.gaopeng.qq.com/deal/tenpay/notify?payAgentType=5';
$sAttach = 'xx%3Dyy';
$sClientIP = '127.0.0.1';
$iBeginTime = 0;
$iEndTime = 0;

$payFacade = new TenpayFacade();
$ret = $payFacade->getPayGateURL($arrPartner, $iUin, $sKey,
								 $sOrderId, $iTotalPrice, $sBody,
								 $iBankType, $sReturnURL, $sNotifyURL, $sAttach,
								 $sClientIP, $iBeginTime, $iEndTime);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
