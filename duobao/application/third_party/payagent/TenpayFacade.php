<?php

require_once dirname(__FILE__) . '/PayFacadeBase.class.php';

class TenpayFacade extends PayFacadeBase
{
	//财付通内网IP
	const mch_tenpay_ip = '10.137.151.209';// '10.163.2.31';
	const gw_tenpay_ip = '10.128.69.34';//'172.16.82.134';
	const open_tenpay_ip = '10.128.69.144';//'10.128.69.145';'10.133.42.138';
	//证书路径
	public $SSLCERT_PATH;

	public function __construct()
	{
		parent::__construct();
		$this->SSLCERT_PATH = dirname(__FILE__).'/sslcert/';
	}

	protected function init($sGateUrl='')
	{
		parent::init();
		$this->reqParams->initCreateSign(array(), array('sign'), false);
		$this->httpClient->setGateURL($sGateUrl);
		$this->resParams->initCreateSign(array(), array('sign'), false);
	}

	/**
	 * @param int $iUin 用户id
	 *
	 * @return bool true:用户QQ号 false:内部用户id
	 */
	public static function isUinUserQQ($iUin)
	{
		return ($iUin > 10000 && $iUin < 0xFFFFFFFF);
	}

	/**
	 * @param string $transactionId 28位财付通交易订单号
	 *
	 * @return int 提取出订单号前10位的商户号
	 */
	public static function transId2partnerId($transactionId)
	{
		return intval(substr($transactionId, 0, 10));	//交易号前10位为商户号
	}

	/**
	 * @param array    $arrTradeInfo   array('trade_state'=>,'pay_result'=>,)
	 *
	 * @return boolean true:订单已支付
	 */
	public static function isTradePaid($arrTradeInfo)
	{
		if (isset($arrTradeInfo['trade_state']))
		{
			return (strval($arrTradeInfo['trade_state']) === '0');
		}
		else if (isset($arrTradeInfo['pay_result']))
		{
			return (strval($arrTradeInfo['pay_result']) === '0');
		}
		else
		{
			return false;
		}
	}

	/**
	 * 返回支付网关URL, 如果iUin和sKey有效可以直接联合登录
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,)
	 * @param int      $iUin        买家QQ号
	 * @param string   $sKey        买家QQ登录skey
	 * @param string   $sOrderId    28位内部订单号
	 * @param int      $iTotalPrice 订单总价,分为单位
	 * @param string   $sDesc       商品描述(32)
	 * @param int      $iBankType   用户选择支付网银的银行编码
	 * @param string   $sReturnURL  支付成功后浏览器跳转URL
	 * @param string   $sNotifyURL  支付成功后后台通知URL
	 * @param string   $sAttach     自定义参数, 支付成功回调原样带回(127)
	 * @param string   $sClientIP   用户下单IP
	 * @param int      $iBeginTime  交易开始时间
	 * @param int      $iEndTime    交易关闭时间
	 *
	 * @return string 支付网关URL
	 */
	public function getPayGateURL($arrPartner, $iUin, $sKey,
								  $sOrderId, $iTotalPrice, $sDesc,
								  $iBankType, $sReturnURL, $sNotifyURL, $sAttach,
								  $sClientIP, $iBeginTime, $iEndTime)
	{
		//初始化,CGI地址使用DNS
		$this->init("https://gw.tenpay.com/gateway/pay.htm");

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");

		//初始化参数
		$this->reqParams->set("input_charset", 'GBK'); //FIXME 微信支付微团购目前用的是写死GBK
		$this->reqParams->set("bank_type", $iBankType);//银行编码
//		$this->reqParams->set("body", iconv("UTF-8", "GBK//ignore", $sDesc));
		$this->reqParams->set("body", mb_convert_encoding($sDesc,"GBK","UTF-8"));
		$this->reqParams->set("attach", $sAttach); //自定义参数,支付回调原样带回
		if (!empty($sReturnURL))
		{
		$this->reqParams->set("return_url", $sReturnURL); //支付成功后回调URL
		}
		$this->reqParams->set("notify_url", $sNotifyURL); //支付成功后异步通知URL
		if (self::isUinUserQQ($iUin))
		{
			$this->reqParams->set("buyer_id", $iUin);
		}
		$this->reqParams->set("partner", $arrPartner['iPartnerId']); //商户号
		$this->reqParams->set("out_trade_no", $sOrderId); //商家订单号
		$this->reqParams->set("total_fee", $iTotalPrice); //分为单位
		$this->reqParams->set("fee_type", "1"); //1（人民币）
		$this->reqParams->set("spbill_create_ip", $sClientIP);//买家IP,用于检测非法支付(详见文档)
		if ($iBeginTime > 0 && $iEndTime > 0 && $iEndTime > $iBeginTime)
		{
		$this->reqParams->set("time_start", date("YmdHis", $iBeginTime));
		$this->reqParams->set("time_expire", date("YmdHis", $iEndTime));
		}
		//$this->reqParams->set("transport_fee", "");
		//$this->reqParams->set("product_fee", "");
		//$this->reqParams->set("goods_tag", "");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery(true, true); //var_dump($this->reqParams->getDebugInfo());

		$this->httpClient->setQueryString($sQueryString);
		$sRequestURL = $this->httpClient->getRequestURL();

		if (self::isUinUserQQ($iUin) && !empty($sKey))
		{
			return self::getCommunityLoginURL($iUin, $sKey, $sRequestURL);
		}
		else
		{
			return $sRequestURL;
		}
	}

	/**
	 * 带登录态,浏览器重定向到财付通接口. 目前用于B2C支付跳转
	 * @param int $iUin	用户QQ号
	 * @param string $sKey	QQ登录sKey,从cookie取得
	 * @param string $sRequestURL	需要跳转到的财付通CGI
	 *
	 * @return string 联合登录URL
	 */
	protected static function getCommunityLoginURL($iUin, $sKey, $sRequestURL)
	{
		$sEncodedUrl = urlencode($sRequestURL);
		$url = "https://www.tenpay.com/cgi-bin/v1.0/communitylogin.cgi?win=self&p_uin=$iUin&skey=$sKey&u1=$sEncodedUrl";
		return $url;
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
	 * WAP支付成功回调签名规则 {默认规则:ksort参数数组,空值不参与签名}
	 * @author anakinli, weiminlin, fadyzhuang
	 * @ref mapi.tuan.qq.com, m.tuan.qq.com
	 */
	protected static $arrWAPPayNotifySignParams = array();

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
	 * 验证支付成功通知, 解析参数并返回. {兼容各个财付通通知协议}
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

		if (!$this->verifyPayNotifySign($sKey, $arrNotifyParams, $arrSignParams) //签名验证不通过
		 )//FIXME || !$this->verifyPayNotifyId($iPartnerId, $arrNotifyParams['notify_id'])) //或notify_id不合法
		{
			return false;
		}

		//支付结果
		$iPayResult = $this->resParams->get('pay_result', 'int', -1); //旧版本通知协议: pay_result==0表示支付成功
		if ($iPayResult == -1)
		{
			$iPayResult = $this->resParams->get('trade_state', 'int', -1); //新版本: trade_state==0表示支付成功
		}
		//交易订单号
		$sTransactionId = $this->resParams->get('transaction_id', 'string', ''); //由财付通系统生成, 或者由v1.0支付接口传入
		//商户订单号
		$sOrderId = $this->resParams->get('sp_billno', 'string', ''); //旧版本通知协议: sp_billno为商户订单号
		if ($sOrderId == '')
		{
			$sOrderId = $this->resParams->get('out_trade_no', 'string', ''); //新版本: out_trade_no为商户订单号
		}
		//交易金额
		$iTotalPrice = $this->resParams->get('total_fee', 'int', 0); //单位分
		//附加数据
		$sAttach = $this->resParams->get('attach', 'string', '');

		if (self::isTradePaid($arrNotifyParams))
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
	 * 下载对账文件接口签名特殊处理,空值不参与签名
	 */
	protected static $arrGetTransListSignParams = array(
			"spid",
			"trans_time",
			"stamp",
			"cft_signtype",
			"mchtype"
		);

	/**
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,)
	 * @param int      $iTransTime  指定需要什么时间的对账文件
	 * @param int      $iMchType    0:返回当日所有订单； 1:返回当日成功支付的订单 2:返回当日退款的订单
	 *
	 * @return string content of trans file <$spid-$trans_ts.csv>, false on failure.
	 */
	public function getTransList($sHost, $arrPartner, $iTransTime, $iMchType)
	{
		//初始化CGI 地址
		$this->init("http://$sHost/cgi-bin/mchdown_real_new.cgi"); //对账文件下载CGI
		//按照指定参数列表生成签名,空值不参与签名
		$this->reqParams->initCreateSign(self::$arrGetTransListSignParams, array('sign'), false);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");

		$this->reqParams->set("spid", $arrPartner['iPartnerId']);//商户号
		$this->reqParams->set("trans_time", date("Y-m-d", $iTransTime));
		$this->reqParams->set("stamp", time());
		$this->reqParams->set("cft_signtype", "0");//0:默认值，不需要财付通签名，效率最高
		$this->reqParams->set("mchtype", $iMchType);

		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$sRequestURL = $this->httpClient->getRequestURL();

		$this->httpClient->setTimeOut(600);
		if ($this->httpClient->execute())
		{
			$sContent = $this->httpClient->getResponseBody(); //var_dump($sContent);
			if (strpos($sContent, '`') !== false)//有订单数据
			{
				return $sContent;
			}
			else
			{
				$this->iRetCode = -2;
				$this->sErrMsg = "[CFT]~getTransList($sRequestURL)~[${arrPartner['iPartnerId']}-$iTransTime-$iMchType]"
								." return{".iconv("GBK", "UTF-8//ignore", strtr($sContent, "\r\n", "  "))."}";//去除换行符,转为UTF-8
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~getTransList($sRequestURL)~".$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

	/**
	 * @param string   $sHost       'gw.tenpay.com' or ip get by L5{XXX,XXX} default:'10.128.69.34'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,)
	 * @param string   $sOrderId    28位内部订单号
	 * @param string   $sTransactionId    28位财付通交易订单号<优先>
	 *
	 * @return array response parameters(...), false on failure.
	 */
	public function queryOrderPayInfo($sHost, $arrPartner, $sOrderId, $sTransactionId)
	{
		//初始化CGI 地址
		$this->init("https://$sHost/gateway/normalorderquery.xml");

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		$this->reqParams->set("input_charset", 'UTF-8');
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		if (!empty($sTransactionId))
		{
			$this->reqParams->set("transaction_id", $sTransactionId);
		}
		$this->reqParams->set("out_trade_no", $sOrderId);

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
					return ($this->resParams->getAll());
				}
				else
				{
					$this->sErrMsg = "[CFT]~queryOrderPayInfo~".$this->resParams->getDebugInfo();
				}
			}
			else
			{
				$this->sErrMsg = "[CFT]~queryOrderPayInfo~retcode:".$this->iRetCode.", errmsg:"
						 		.$arrResponse['retmsg'];
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~queryOrderPayInfo~".$this->httpClient->getCurlErrMsg();
		}

		return array();
	}

	/**
	 * @param string   $sHost       'gw.tenpay.com' or ip get by L5{XXX,XXX} default:'10.128.69.34'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,)
	 * @param string   $sTransactionId    28位财付通交易订单号<优先>
	 * @param string   $sRefundKey  内部退款流水号
	 * @param int      $iTotalPrice 订单总金额(分)
	 * @param int      $iRefundPrice 退款金额(分),可做部分退款
	 *
	 * @return int 0 ~ refund success; <0 ~ refund error.
	 */
	public function refundOrder($sHost, $arrPartner, $sTransactionId,
	                            $sRefundKey, $iTotalPrice, $iRefundPrice)
	{
		//初始化CGI 地址
		$this->init("https://$sHost/refundapi/gateway/refund.xml");

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		$this->reqParams->set("input_charset", 'UTF-8');
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("transaction_id", $sTransactionId);
		//$this->reqParams->set("out_trade_no", $sOrderId);
		$this->reqParams->set("out_refund_no", $sRefundKey);
		$this->reqParams->set("total_fee", $iTotalPrice);
		$this->reqParams->set("refund_fee", $iRefundPrice);
		if ($arrPartner['iPartnerId'] == '1216899801') //特殊处理腾讯收款账号退款权限
		{
			$op_user_id = $arrPartner['iPartnerId']."001";
		}
		else
		{
			$op_user_id = $arrPartner['iPartnerId'];
		}
		$this->reqParams->set("op_user_id", $op_user_id);
		$this->reqParams->set("op_user_passwd", $arrPartner['sLoginPasswd']);

		$this->httpClient->setSslCaInfo($this->SSLCERT_PATH."cacert.pem");
		$this->httpClient->setSslCert($this->SSLCERT_PATH.$arrPartner['iPartnerId'].".pem", $arrPartner['sCertPasswd']);

		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(60); //var_dump($this->httpClient);
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
					return ($this->resParams->getAll());
				}
				else
				{
					$this->sErrMsg = "[CFT]~refundOrder~".$this->resParams->getDebugInfo();
				}
			}
			else
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

		return array();
	}

	/**
	 * @param string   $sHost       'gw.tenpay.com' or ip get by L5{XXX,XXX} default:'10.128.69.34'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,)
	 * @param string   $sRefundId   退款流水单号,优先级: $sRefundId > $sRefundKey > $sTransactionId. 不能都为空
	 * @param string   $sRefundKey  内部退款流水号
	 * @param string   $sTransactionId    28位财付通交易订单号
	 *
	 * @return array response parameters(...), false on failure.
	 */
	public function queryOrderRefundInfo($sHost, $arrPartner, $sRefundId, $sRefundKey, $sTransactionId)
	{
		//初始化CGI 地址
		$this->init("https://$sHost/gateway/normalrefundquery.xml");

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		$this->reqParams->set("input_charset", 'UTF-8');
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		//同时存在时以优先级高为准，优先级为：refund_id>out_refund_no>transaction_id>out_trade_no
		if (!empty($sRefundId))
		{
			$this->reqParams->set("refund_id", $sRefundId);
		}
		$this->reqParams->set("out_refund_no", $sRefundKey);

		$this->reqParams->set("transaction_id", $sTransactionId);

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
					return ($this->resParams->getAll());
				}
				else
				{
					$this->sErrMsg = "[CFT]~queryOrderRefundInfo~".$this->resParams->getDebugInfo();
				}
			}
			else
			{
				$this->sErrMsg = "[CFT]~queryOrderRefundInfo~retcode:".$this->iRetCode.", errmsg:"
						 		.$arrResponse['retmsg'];
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~queryOrderRefundInfo~".$this->httpClient->getCurlErrMsg();
		}

		return array();
	}

}