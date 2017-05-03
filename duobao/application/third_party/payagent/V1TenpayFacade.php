<?php

require_once dirname(__FILE__) . '/PayFacadeBase.class.php';

class V1TenpayFacade extends PayFacadeBase
{
	//财付通内网IP
	const mch_tenpay_ip = '10.137.151.209';// '10.163.2.31';
	//证书路径
	public $SSLCERT_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->SSLCERT_PATH = dirname(__FILE__).'/sslcert/';
	}

	protected $mchBatchTransferReqParams;

	protected function init($sGateUrl='')
	{
		parent::init();
		$this->reqParams->initCreateSign(array(), array('sign'), false);
		$this->httpClient->setGateURL($sGateUrl);
		$this->resParams->initCreateSign(array(), array('sign'), false);
	}

	/**
	 * @param int $iUin 用户id
	 * @return bool true:用户QQ号 false:内部用户id
	 */
	public static function isUinUserQQ($iUin)
	{
		return ($iUin > 10000 && $iUin < 0xFFFFFFFF);
	}

	/**
	 * QQ团购接入财付通支付中心1.0 特殊定制的签名规则,空值也参与签名
	 * sign=md5(cmdno=1&date=20051219&pay_type=2&bargainor_id=1000000301
	 * 		&purchaser_id=123456&transaction_id=1000000301200512190000012138
	 * 		&sp_billno=1111&fee_type=1&total_fee=1300&show_fee=&goods_tag=
	 * 		&return_url=http://www.xxx.com/tenpay1.aspx&attach=1&key=1000000301)
	 * @author ryanfan, nathanfan, sangechen
	 */
	protected static $arrPayGateSignParams = array(
			"cmdno",
			"date",
			"pay_type",
			"bargainor_id",
			"purchaser_id",
			"transaction_id",
			"sp_billno",
			"fee_type",
			"total_fee",
			"show_fee",
			"goods_tag",
			"return_url",
			"attach",
			"spbill_create_ip" //这个是保留旧代码逻辑加上的
	);

	/**
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param int      $iUin        买家QQ号
	 * @param string   $sKey        买家QQ登录skey
	 * @param string   $sOrderId    28位内部订单号
	 * @param string   $sTransactionId    28位财付通交易订单号
	 * @param int      $iTotalPrice 订单总价,分为单位
	 * @param string   $sDesc       商品描述,128个字符64汉字内,不包含特殊符号
	 * @param int      $iBankType   用户选择支付网银的银行编码
	 * @param string   $sReturnURL  支付成功回调URL
	 * @param string   $sAttach     自定义参数, 支付成功回调原样带回
	 * @param string   $sClientIP   用户下单IP
	 */
	public function redirectToB2CPayGate($arrPartner, $iUin, $sKey,
										 $sOrderId, $sTransactionId, $iTotalPrice, $sDesc,
										 $iBankType, $sReturnURL, $sAttach,
										 $sClientIP)
	{
		//初始化,CGI地址使用DNS
		$this->init("https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi");
		//按照指定参数列表生成签名,空值也参与签名
		$this->reqParams->initCreateSign(self::$arrPayGateSignParams, array('sign'), true);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");

		//初始化参数
		$this->reqParams->set("ver", "2");//特殊定制版本
		/** sign begin */
		$this->reqParams->set("cmdno", "1");
		$this->reqParams->set("date", date("Ymd"));
		$this->reqParams->set("pay_type", "2");//B2C支付(带QQ登录态)
		$this->reqParams->set("bargainor_id", $arrPartner['iPartnerId']);//商户号
		if (self::isUinUserQQ($iUin))
		{
			$this->reqParams->set("purchaser_id", $iUin);//买家qq号码
		}
		$this->reqParams->set("transaction_id", $sTransactionId);//财付通交易单号
		$this->reqParams->set("sp_billno", $sOrderId);//商家订单号(QQ团购侧2个订单号一样)
		$this->reqParams->set("fee_type", "1");//1（人民币）
		$this->reqParams->set("total_fee", $iTotalPrice);//分为单位
		$this->reqParams->set("show_fee", "");//展示金额(不知道作用)
		$this->reqParams->set("goods_tag", "qqtuangou");//商品标识(不知道作用,固定值)
		$this->reqParams->set("return_url", $sReturnURL);//支付成功后回调URL
		$this->reqParams->set("attach", $sAttach);//自定义参数,支付回调原样带回
		$this->reqParams->set("spbill_create_ip", $sClientIP);//买家IP,用于检测非法支付(详见文档)
		/** sign end */
		$this->reqParams->set("cs", "UTF-8");//字符集编码
		$this->reqParams->set("desc", $sDesc);//商品描述,128个字符64汉字内,不包含特殊符号
		$this->reqParams->set("bank_type", $iBankType);//银行编码
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		//$this->reqParams->set("skey", $sKey);//买家qq登录skey

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();

		$this->httpClient->setQueryString($sQueryString);
		$sRequestURL = $this->httpClient->getRequestURL();

		//重定向到财付通.
		if (self::isUinUserQQ($iUin) && !empty($sKey))
		{
			//联合登录跳转支付
			self::doSendWithCommunityLogin($iUin, $sKey, $sRequestURL);
		}
		else
		{
			//无登录态跳转
			header("Location:" . $sRequestURL);
		}

		return $sRequestURL;
	}

	/**
	 * 带登录态,浏览器重定向到财付通接口. 目前用于B2C支付跳转
	 * @param int $iUin	用户QQ号
	 * @param string $sKey	QQ登录sKey,从cookie取得
	 * @param string $sRequestURL	需要跳转到的财付通CGI
	 */
	protected static function doSendWithCommunityLogin($iUin, $sKey, $sRequestURL)
	{
		$sEncodedUrl = urlencode($sRequestURL);
		$url = "https://www.tenpay.com/cgi-bin/v1.0/communitylogin.cgi?win=self&p_uin=$iUin&skey=$sKey&u1=$sEncodedUrl";
		header("Location:" . $url);
		//exit(0);
	}

	/**
	 * 支付成功回调(协议5.1)签名规则,空值也签名
	 */
	protected static $arrV1PayNotifySignParams = array(
			"cmdno",
			"pay_result",
			"date",
			"transaction_id",
			"sp_billno",
			"total_fee",
			"fee_type",
			"attach"
	);

	/**
	 * B2C支付成功回调(协议5.2)签名规则,空值也签名
	 * @author sangechen, eattazhong, fadyzhuang
	 */
	protected static $arrB2CPayNotifySignParams = array(
			"cmdno",
			"pay_result",
			"date",
			"transaction_id",
			"sp_billno",
			"total_fee",
			"fee_type",
			"attach",
			"pay_time",
			"fee1",
			"fee2",
			"fee3",
			"vfee"
	);

	/**
	 * 默认规则:ksort参数数组,空值不参与签名
	 */
	protected static $arrDefaultPayNotifySignParams = array();

	/**
	 * @param string     $sKey               MD5签名私钥
	 * @param array      $arrNotifyParams    支付通知支付成功的参数数组
	 * @param array      $arrSignParams      签名key数组
	 *
	 * @return bool true:支付通知签名合法
	 */
	protected function verifyPayNotifySign($sKey, $arrNotifyParams, $arrSignParams)
	{
		//初始化, 不需要
		$this->init("");
		if (!empty($arrSignParams)) //使用指定的签名数组,空值也签名
		{
			$this->resParams->initCreateSign($arrSignParams, array('sign'), true); //true表示空值也参加签名
		}

		//设置签名key
		$this->resParams->setKey($sKey, "&key=");

		//设置参数
		$this->resParams->setAll($arrNotifyParams);

		if ($this->resParams->verifySign())
		{
			return true;
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = $this->resParams->getDebugInfo();
		}

		return false;
	}

	/**
	 * 验证支付成功通知, 解析参数并返回.
	 * @see TenpayFacade::redirectToPayGate()
	 * @param string     $sKey               MD5签名私钥
	 * @param array      $arrNotifyParams    支付宝通知支付成功的参数数组
	 *
	 * @return array     false: 验证支付通知失败或支付通知非法或支付状态非法
	 *                   array: 支付通知验证通过且订单为'已支付'状态
	 */
	public function processPayNotify($sKey, $arrNotifyParams)
	{
		if (isset($arrNotifyParams['cmdno']) && $arrNotifyParams['cmdno'] == 1) //如果cmdno==1, 表示用固定签名
		{
			if (isset($arrNotifyParams['ver']) && $arrNotifyParams['ver'] == 2) //ver==2表示5.2签名规则
			{
				$arrSignParams = self::$arrB2CPayNotifySignParams;
			}
			else
			{
				$arrSignParams = self::$arrV1PayNotifySignParams;
			}
		}
		else
		{
			$arrSignParams = self::$arrDefaultPayNotifySignParams;
		}

		if (!$this->verifyPayNotifySign($sKey, $arrNotifyParams, $arrSignParams)) //签名验证不通过
		{
			return false;
		}

		//支付结果
		$iPayResult = $this->resParams->get('pay_result', 'int', -1); //旧版本通知协议: pay_result==0表示支付成功
		//交易订单号
		$sTransactionId = $this->resParams->get('transaction_id', 'string', ''); //由财付通系统生成, 或者由v1.0支付接口传入
		//商户订单号
		$sOrderId = $this->resParams->get('sp_billno', 'string', ''); //旧版本通知协议: sp_billno为商户订单号
		//交易金额
		$iTotalPrice = $this->resParams->get('total_fee', 'int', 0); //单位分
		//附加数据
		$sAttach = $this->resParams->get('attach', 'string', '');

		if ($iPayResult == 0)
		{
			return array(
					'transactionid' => $sTransactionId,
					'orderid' => $sOrderId,
					'totalprice' => $iTotalPrice,
					'attach' => $sAttach,
					);
		}
		else
		{
			$this->iRetCode = -4;
			$this->sErrMsg = "[CFT]~processPayNotify($sOrderId-$sTransactionId)~trade_status: $iPayResult";
		}

		return false;
	}

	/**
	 * @param string   $sResultURL  用于展示支付成功通知处理结果的URL, 即成功页或错误信息页
	 * @return string  返回给财付通通知程序的响应数据
	 */
	public static function generatePayNotifyResponse($sResultURL)
	{
		$html = <<< EOF
<html>
<head>
<meta name="TENCENT_ONLINE_PAYMENT" content="China TENCENT">
<script language="javascript">
window.location.href='$sResultURL';
</script>
</head>
<body></body>
</html>
EOF;
		return $html;
		//exit();
	}

	/**
	 * B2C支付成功回调(协议5.2)签名规则,空值也签名
	 * @author sangechen, eattazhong, fadyzhuang
	 */
	protected static $arrQueryOrderV3SignParams = array(
			"attach",
			"bargainor_id",
			"cmdno",
			"date",
			"fee_type",
			"pay_info",
			"pay_result",
			"sp_billno",
			"total_fee",
			"transaction_id"
	);

	/**
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param string   $sOrderId    28位内部订单号
	 * @param string   $sTransactionId    28位财付通交易订单号
	 * @return boolean true:查询成功且订单支付成功
	 *                 使用_getResponseParameters()获得订单参数
	 */
	public function isOrderPaid($sHost, $arrPartner, $sOrderId, $sTransactionId)
	{
		//初始化CGI 地址
		$this->init("http://$sHost/cgi-bin/cfbi_query_order_v3.cgi");
		//按照指定参数列表验证签名,空值也参与签名
		$this->resParams->initCreateSign(self::$arrQueryOrderV3SignParams, array('sign'), true);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		//初始化参数
		$this->reqParams->set("cmdno", "2");
		$this->reqParams->set("date", substr($sOrderId, 10, 8)); //商户订单生成的日期
		$this->reqParams->set("bargainor_id", $arrPartner['iPartnerId']);
		$this->reqParams->set("transaction_id", $sTransactionId);
		$this->reqParams->set("sp_billno", $sOrderId);
		$this->reqParams->set("attach", "1");
		$this->reqParams->set("output_xml", "1");
		$this->reqParams->set("charset", "UTF-8"); //GB2312

		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(60);
		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);
			$this->iRetCode = $arrResponse['retcode'];

			if ($this->iRetCode == 0)
			{
				$this->resParams->setAll($arrResponse);
				if ($this->resParams->verifySign())
				{
					return ($this->resParams->get('pay_result') == 0);
				}
				else
				{
					$this->sErrMsg = "[CFT]~isOrderPaid~".$this->resParams->getDebugInfo();
				}
			}
			else
			{
				$this->sErrMsg = "[CFT]~isOrderPaid~retcode:".$this->iRetCode
								.", errmsg:".$arrResponse['retmsg'];
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~isOrderPaid~".$this->httpClient->getCurlErrMsg();
		}

		return false;
	}

	/**
	 * 下载对账文件接口使用 TenpayFacade
	 */

	/**
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param string   $sTransactionId    28位财付通交易订单号
	 * @param string   $sRefundId   退款流水单号
	 * @param int      $iRefundPrice 退款金额(分),可做部分退款,分账商户只能全额退款
	 * @param int      $iRefundType 1:商户号退款；2：现金帐号退款； 3:优先商户号退款，若商户号余额不足，再做现金帐号退款。
	 * @param string   $sClientIP   退款操作IP
	 * @param string   $sDesc       退款描述
	 * @return int 0 ~ refund success; <0 ~ refund error.
	 */
	public function refundOrder($sHost, $arrPartner, $sTransactionId, $sRefundId, $iRefundPrice,
								$iRefundType, $sClientIP, $sDesc)
	{
		//初始化CGI 地址
		$this->init("https://$sHost/cgi-bin/mchbatchtransfer.cgi");

		//业务参数
		$reqArray = array(
				"op_code" => "1003",
				"op_name" => "b2c_refund",
				"op_user" => $arrPartner['iPartnerId'],
				"op_passwd" => $arrPartner['sLoginPasswd'],
				"op_time" => date("YmjHis"),
				"sp_id" => $arrPartner['iPartnerId'],
				"trans_id" => $sTransactionId,
				"refund_id" => $sRefundId,
				"refund_type" => $iRefundType,
				"client_ip" => $sClientIP,
				"rec_acc" => "",
				"rec_acc_truename" => "",
				"cur_type" => "1", //（1：人民币）
				"pay_amt" => $iRefundPrice,
				"desc" => $sDesc,
		);
		$reqXml = XMLArray::KVArrayToXML('root', $reqArray, true, 'GB2312'); //var_dump($reqXml); //exit();

		$content = base64_encode($reqXml);

		$md5Res1 = strtolower(md5($content));
		$md5Src2 = $md5Res1 . $arrPartner['sKey'];
		$abstract = strtolower(md5($md5Src2));

		//var_dump($xml . "=>" . $content . "=>" . $md5Src2 . " => sign:" . $abstract);  exit();

		$this->httpClient->setSslCaInfo($this->SSLCERT_PATH."cacert.pem");
		$this->httpClient->setSslCert($this->SSLCERT_PATH.$arrPartner['iPartnerId'].".pem", $arrPartner['sCertPasswd']);

		$sQueryString = "content=" . urlencode($content) . "&abstract=" . urlencode($abstract);
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(60);
		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);
			$this->iRetCode = $arrResponse['retcode'];

			//无需验证签名

			if ($this->iRetCode != 0)
			{
				$this->sErrMsg = "[CFT]~refundOrder~retcode:".$this->iRetCode.", errmsg:"
						 		.$arrResponse['retmsg'];
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~refundOrder~".$this->httpClient->getCurlErrMsg();
		}

		return $this->iRetCode;
	}

	/**
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param int      $iTransTime  指定需要什么时间的对账文件
	 * @param string   $sClientIP   客户端IP
	 *
	 * @return string content of trans file <$spid-$trans_ts.csv>, false on failure.
	 */
	public function getCAccountTransList($sHost, $arrPartner, $iTransTime, $sClientIP)
	{
		//初始化CGI 地址
		$this->init("https://$sHost/cgi-bin/mchbatchtransfer.cgi");

		//业务参数
		$reqArray = array(
				"op_code" => "1010",
				"op_name" => "down_client_trans",
				"op_user" => $arrPartner['iPartnerId'],
				"op_passwd" => $arrPartner['sLoginPasswd'],
				"op_time" => date("YmdHis"),
				"sp_id" => $arrPartner['iPartnerId'],
				"trans_time" => date("Y-m-d", $iTransTime),
				"client_ip" => $sClientIP,
		);
		$reqXml = XMLArray::KVArrayToXML('root', $reqArray, true, 'GB2312'); //var_dump($reqXml); //exit();

		$content = base64_encode($reqXml);

		$md5Res1 = strtolower(md5($content));
		$md5Src2 = $md5Res1 . $arrPartner['sKey'];
		$abstract = strtolower(md5($md5Src2));

		//var_dump($xml . "=>" . $content . "=>" . $md5Src2 . " => sign:" . $abstract);  exit();

		$this->httpClient->setSslCaInfo($this->SSLCERT_PATH."cacert.pem");
		$this->httpClient->setSslCert($this->SSLCERT_PATH.$arrPartner['iPartnerId'].".pem", $arrPartner['sCertPasswd']);

		$sQueryString = "content=" . urlencode($content) . "&abstract=" . urlencode($abstract);
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(600);
		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);
			$this->iRetCode = $arrResponse['retcode'];

			//无需验证签名

			if ($this->iRetCode != 0)
			{
				$this->sErrMsg = "[CFT]~getCAccountTransList~retcode:".$this->iRetCode.", errmsg:"
						 		.$arrResponse['retmsg'];
			}
			else
			{
				$result = $arrResponse['result'];
				if (strpos($result, '`')!==false)//有订单数据
				{
					return $result;
				}
				else
				{
					$this->sErrMsg = "[CFT]~getCAccountTransList~no data: result{"
							.strtr($result, "\r\n", "  ")."}";//去除换行符
				}
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~getCAccountTransList~".$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

	/**
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param int      $iQueryItem  1:可用余额 2:结算费率（或分账费率）3：证书有效期
	 * @param string   $sClientIP   客户端IP
	 *
	 * @return string item_value, false on failure.
	 */
	public function queryPartnerInfo($sHost, $arrPartner, $iQueryItem, $sClientIP)
	{
		//初始化CGI 地址
		$this->init("https://$sHost/cgi-bin/mchQueryBInfo.cgi");

		//业务参数
		$reqArray = array(
				"sp_id" => $arrPartner['iPartnerId'],
				"op_user" => $arrPartner['iPartnerId'],
				"op_passwd" => $arrPartner['sLoginPasswd'],
				"op_time" => date("YmdHis"),
				"client_ip" => $sClientIP,
				"query_item" => $iQueryItem,
		);
		$reqXml = XMLArray::KVArrayToXML('root', $reqArray, true, 'GB2312'); //var_dump($reqXml); //exit();

		$content = base64_encode($reqXml);

		$md5Res1 = strtolower(md5($content));
		$md5Src2 = $md5Res1 . $arrPartner['sKey'];
		$abstract = strtolower(md5($md5Src2));

		//var_dump($xml . "=>" . $content . "=>" . $md5Src2 . " => sign:" . $abstract);  exit();

		$this->httpClient->setSslCaInfo($this->SSLCERT_PATH."cacert.pem");
		$this->httpClient->setSslCert($this->SSLCERT_PATH.$arrPartner['iPartnerId'].".pem", $arrPartner['sCertPasswd']);

		$sQueryString = "content=" . urlencode($content) . "&abstract=" . urlencode($abstract);
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(60);
		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);
			$this->iRetCode = $arrResponse['retcode'];

			//无需验证签名

			if ($this->iRetCode != 0)
			{
				$this->sErrMsg = "[CFT]~queryPartnerInfo~retcode:".$this->iRetCode.", errmsg:"
						 		.$arrResponse['retmsg'];
			}
			else
			{
				return $arrResponse["item_value"];
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~queryPartnerInfo~".$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

}