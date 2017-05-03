#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../TenpayWapFacade.php';

$arrPartner = array(
		'iPartnerId' => '1206604901',
		'sKey' => '03cb965837d36236ffa3976d1b9b6484',
);

$iUin = 1036634591;
//$sKey = '';
$sOrderId = '1210338701201308011402560091';
$sTransactionId = '';
$iTotalPrice = 1;
$sDesc = 'QQ团购';
$iBankType = 0;
$sCallBackURL = 'http://dev.mapi.gaopeng.com/tenpay/wap_callback';
$sNotifyURL = 'http://dev.mapi.gaopeng.com/tenpay/wap_notify';
$sAttach = 'xx%3Dyy';
//$sClientIP = '127.0.0.1';
$iBeginTime = 0;
$iEndTime = 0;

$payFacade = new TenpayWapFacade();
$ret = $payFacade->getWapPayUrl(HttpClient::HTTP_PROXY1, $arrPartner,
								$sDesc, $sOrderId, $sTransactionId, $iTotalPrice,
								$sCallBackURL, $sNotifyURL, $sAttach, $iBankType,
								$iUin, $iBeginTime, $iEndTime);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
