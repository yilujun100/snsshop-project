#!/usr/local/php/bin/php
<?php
require_once dirname(__FILE__) . '/../exec_before.inc';

require_once dirname(__FILE__) . '/../../AlipayWapFacade.php';

if($argc < 2)
    exit("Usage: ".$argv[0]." sQueryString \r\n");

$sQueryString = strval($argv[1]);
parse_str($sQueryString, $arrCallBackParams);

$payFacade = new AlipayWapFacade();
$ret = $payFacade->processWapPayNotify($arrCallBackParams);
var_export($ret); echo "\n";

//标题中有xml特殊字符["&'<>], 返回的xml会解析失败
//财付通接口普遍没有这个问题(attach未使用), 支付宝中subject,body会回传, 支付宝wap中subject
//var_dump(XMLArray::XMLToKVArray('<notify><partner>2088301487863565</partner><discount>0.00</discount><payment_type>8</payment_type><subject>【坂田】正点量贩&KTV日场欢唱套餐，免费停车，免费wifi</subject><trade_no>2013081026543197</trade_no><buyer_email>shenfei1145@yahoo.cn</buyer_email><gmt_create>2013-08-10 11:54:32</gmt_create><quantity>1</quantity><out_trade_no>1362464412201308101154230115</out_trade_no><seller_id>2088301487863565</seller_id><trade_status>TRADE_SUCCESS</trade_status><is_total_fee_adjust>N</is_total_fee_adjust><total_fee>12.00</total_fee><gmt_payment>2013-08-10 11:54:32</gmt_payment><seller_email>fpayment@ftuan.com</seller_email><price>12.00</price><buyer_id>2088102946717975</buyer_id><use_coupon>N</use_coupon></notify>'));

require_once dirname(__FILE__) . '/../exec_after.inc';
