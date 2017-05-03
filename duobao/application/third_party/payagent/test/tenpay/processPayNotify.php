#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../TenpayFacade.php';

$arrPartner = array(
		'iPartnerId' => '1206604901',
		'sKey' => '03cb965837d36236ffa3976d1b9b6484',
);

if($argc < 2)
    exit("Usage: ".$argv[0]." sQueryString \r\n");

$sQueryString = strval($argv[1]);
//$sQueryString = 'sign=NhGagARvjQszvAqzOLK8b%2FjrTQL7uOdYJnM8P97qtB1OT4EqtL33WScyqkdaRkm8aSb6FEtgVfFKajkmOJwt%2FVBctdxMe%2BrsGhkKkhrNaAuwF9kFPU724yez2yfXrOq7WqNne0%2B0vREG%2BDS4CuRKtVg2LzVnnxceyZ3%2F%2BQexjSU%3D&sign_type=RSA&notify_data=%3Cnotify%3E%3Cpartner%3E2088301487863565%3C%2Fpartner%3E%3Cdiscount%3E0.00%3C%2Fdiscount%3E%3Cpayment_type%3E8%3C%2Fpayment_type%3E%3Csubject%3E%E3%80%90%E4%BA%BA%E6%B0%91%E5%B9%BF%E5%9C%BA%E3%80%91U%26ME%E5%A4%8F%E6%97%A5%E6%B8%85%E7%88%BD%E5%A5%97%E9%A4%90%EF%BC%8C%E6%97%A0%E9%9C%80%E9%A2%84%E7%BA%A6%EF%BC%8C%E8%8A%82%E5%81%87%E6%97%A5%E9%80%9A%E7%94%A8%3C%2Fsubject%3E%3Ctrade_no%3E2013080500280819%3C%2Ftrade_no%3E%3Cbuyer_email%3Eghost_prayer%40126.com%3C%2Fbuyer_email%3E%3Cgmt_create%3E2013-08-05+14%3A21%3A21%3C%2Fgmt_create%3E%3Cquantity%3E1%3C%2Fquantity%3E%3Cout_trade_no%3E1362464412201308051420570122%3C%2Fout_trade_no%3E%3Cseller_id%3E2088301487863565%3C%2Fseller_id%3E%3Ctrade_status%3ETRADE_SUCCESS%3C%2Ftrade_status%3E%3Cis_total_fee_adjust%3EN%3C%2Fis_total_fee_adjust%3E%3Ctotal_fee%3E13.80%3C%2Ftotal_fee%3E%3Cgmt_payment%3E2013-08-05+14%3A21%3A22%3C%2Fgmt_payment%3E%3Cseller_email%3Efpayment%40ftuan.com%3C%2Fseller_email%3E%3Cprice%3E13.80%3C%2Fprice%3E%3Cbuyer_id%3E2088302128780195%3C%2Fbuyer_id%3E%3Cuse_coupon%3EN%3C%2Fuse_coupon%3E%3C%2Fnotify%3E';
parse_str($sQueryString, $arrNotifyParams);

$payFacade = new TenpayFacade();
$ret = $payFacade->processPayNotify($arrPartner['sKey'], $arrNotifyParams);
var_export($ret); echo "\n";

require_once dirname(__FILE__) . '/../exec_after.inc';
