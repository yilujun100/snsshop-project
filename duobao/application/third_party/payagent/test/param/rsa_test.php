#!/usr/local/php/bin/php
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

require_once dirname(__FILE__) . '/../util/RSAParameterHandler.class.php';

$SSLCERT_PATH = dirname(__FILE__).'/../sslcert/';
$priKey = 'file://'.$SSLCERT_PATH.'/rsa_private_key.pem';
$pubKey = 'file://'.$SSLCERT_PATH.'/rsa_public_key.pem';

$rsaParam = new RSAParameterHandler();

$rsaParam->setKey($priKey, $pubKey);

$rsaParam->set('x', 'y');

var_dump($rsaParam->buildUrlQuery());
var_dump($rsaParam->getDebugInfo());

var_dump($rsaParam->verifySign());
var_dump($rsaParam->getDebugInfo());

