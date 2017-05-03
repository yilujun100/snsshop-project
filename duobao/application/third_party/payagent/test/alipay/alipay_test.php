#!/usr/local/php/bin/php
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

require_once dirname(__FILE__) . '/../AlipayFacade.php';

$alipayFacade = new AlipayFacade();

// $alipayFacade->redirectToPayGate(array(), 'http', 'http', '1206604901201211161507372791',
// 		 'xxx', 100, 'xxx', 'http', 'xxx', '127.0.0.1');

//var_dump($alipayFacade->verifyPayNotifyId('xxx'));

//var_dump($alipayFacade->queryTradeInfo('2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh', '1206604901201211161507372791', '2012111620984834'));
//var_dump($alipayFacade->queryTradeInfo(HttpClient::HTTP_PROXY1, '2088801813237016', 'asauvig9x1l1jqlqlvvqiqwiw0622lzs', '237847557288070', ''));
//var_dump($alipayFacade->queryTradeInfo(HttpClient::HTTP_PROXY1, '2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh', '1362464412201306071524210041', '2013060727832051'));

// $begtime = strtotime(@$argv[1]);
// $endtime = $begtime + 86400;
// if (!empty($argv[2]))
// 	$trade_status = $argv[2];
// else
// 	$trade_status = AlipayFacade::TRADE_STATUS_SUCCESS;
// var_dump($alipayFacade->getTransList(HttpClient::HTTP_PROXY1, '2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh', $begtime, $endtime, $trade_status));

//var_dump($alipayFacade->refundOrder('2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh', '', '2012111620984834', '20121204001', 1, 'sangechen^测试退款'));

//var_dump($alipayFacade->queryRefundInfo(HttpClient::HTTP_PROXY1, '2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh', '', '2012111620984834'));
//is_success=T&result_details=20121204001^2012111620984834^0.01^TRADE_HAS_CLOSED#20121122001^2012111620984834^0.01^SUCCESS

$t1 = strtotime(@$argv[1]);
var_dump($alipayFacade->getBillList(HttpClient::HTTP_PROXY1, '2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh', $t1, $t1+86400, true, AlipayFacade::TC_ONLINE_PAY));
//var_dump($alipayFacade->getMobilePayChannel(HttpClient::HTTP_PROXY1, '2088301487863565', 'xgjgz9o1r1b5czhuljjkdii3f1bncsbh'));


echo "retcode=".$alipayFacade->_getRetCode()."\n";
echo "errmsg=".$alipayFacade->_getErrMsg()."\n";
echo "requrl=".$alipayFacade->_getRequestURL()."\n";
echo "curlerrno=".$alipayFacade->_getCurlErrno()."\n";
echo "curlerrmsg=".$alipayFacade->_getCurlErrMsg()."\n";
echo "httpcode=".$alipayFacade->_getCurlHttpCode()."\n";
echo "res_content=".$alipayFacade->_getResponseContent()."\n";
echo "res_params:\n";
var_dump($alipayFacade->_getResponseParameters());

