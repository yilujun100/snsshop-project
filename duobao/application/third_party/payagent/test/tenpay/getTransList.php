#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../TenpayFacade.php';

$arrPartner = array(
		'iPartnerId' => '1206604901',
		'sKey' => '03cb965837d36236ffa3976d1b9b6484',
);

$iTransTime = strtotime('yesterday');
$iMchType = 0;

$payFacade = new TenpayFacade();
$ret = $payFacade->getTransList(TenpayFacade::mch_tenpay_ip, $arrPartner, $iTransTime, $iMchType);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
