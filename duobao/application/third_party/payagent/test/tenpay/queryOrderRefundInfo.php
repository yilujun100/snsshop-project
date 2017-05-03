#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../TenpayFacade.php';

$arrAllPartner = array(
	2 => array(
			'iPartnerId' => '1206604901',
			'sKey' => '03cb965837d36236ffa3976d1b9b6484',
		),
	4 => array(
			'iPartnerId' => '1216432801',
			'sKey' => '6abf4c7f95553d90247c32d09da5e76a',
		),
	5 => array(
			'iPartnerId' => '1216834901',
			'sKey' => 'a632e2f76410f92cd493127b505c4797',
		),
	6 => array(
			'iPartnerId' => '1216899801',
			'sKey' => '3945b26ba8a2122306f6482918ba8bb0',
		),
);

if($argc < 2)
    exit("Usage: ".$argv[0]." sRefundId [ sRefundKey sTransactionId iPayAgentType ] \r\n");

$sRefundId = strval(@$argv[1]);
$sRefundKey = strval(@$argv[2]);
$sTransactionId = strval(@$argv[3]);
$iPayAgentType = intval(@$argv[4]);

if (array_key_exists($iPayAgentType, $arrAllPartner))
{
	$arrPartner = $arrAllPartner[$iPayAgentType];
}
else
{
	$arrPartner = $arrAllPartner[2];
}

$payFacade = new TenpayFacade();
$ret = $payFacade->queryOrderRefundInfo(TenpayFacade::gw_tenpay_ip, $arrPartner, $sRefundId, $sRefundKey, $sTransactionId);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
