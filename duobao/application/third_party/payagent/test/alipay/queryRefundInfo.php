#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../AlipayFacade.php';

$arrPartner = array(
	'iPartnerId' => '2088301487863565',
	'sKey' => 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh',
);

if($argc < 2)
    exit("Usage: ".$argv[0]." sRefundId [ sTransactionId ] \r\n");

$sRefundId = strval(@$argv[1]);
$sTransactionId = strval(@$argv[2]);

$payFacade = new AlipayFacade();
$ret = $payFacade->queryRefundInfo(HttpClient::HTTP_PROXY1, $arrPartner, $sRefundId, $sTransactionId);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
