<?php

require_once dirname(__FILE__) . '/PayFacadeBase.class.php';

class TenpayWapFacade extends PayFacadeBase
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

	protected function init($sGateUrl='', $sProxy = null)
	{
		parent::init();
		$this->reqParams->initCreateSign(array(), array('sign'), false);
		$this->httpClient->setGateURL($sGateUrl);
		$this->resParams->initCreateSign(array(), array('sign'), false);

		if (!empty($sProxy))
		{
			$this->httpClient->setProxy($sProxy);
		}
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
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * "https://mch.tenpay.com/cgi-bin/fpay_createOrder.cgi"//DNS解析,需要外网IP/eth0
	 * (($supplierInfo->refundMode==0)?"http://":"https://")
	 *     .self::$mch_tenpay_ip."/cgi-bin/fpay_createOrder.cgi"//内网正式环境
	 * "http://172.25.38.239/cgi-bin/fpay_createOrder.cgi"//DEV网络测试
	 * "http://112.90.139.15:18097/cgi-bin/fpay_createOrder.cgi"//IDC网络测试
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param string   $sCardSPID   乐刷的商户号
	 * @param int      $iUin        用户QQ
	 * @param string   $sOrderId    28位内部订单号
	 * @param string   $sTransactionId    28位财付通交易订单号
	 * @param int      $iTotalPrice 交易金额,分为单位
	 * @param string   $sDesc       交易描述
	 * @param string   $sReturnURL  支付成功回调URL
	 * @param string   $sAttach     自定义参数, 支付成功回调原样带回
	 * @param string   $sClientIP   用户下单IP
	 *
	 * @return string $short_no on success, '' on failure.
	 */
	public function getPayShortNo($sHost, $arrPartner, $sCardSPID,
								  $iUin, $sOrderId, $sTransactionId, $iTotalPrice,
								  $sDesc, $sReturnURL, $sAttach,
								  $sClientIP)
	{
		//初始化CGI 地址
		$sScheme = ($arrPartner['iRefundMode'] === 0)? 'http' : 'https';
		$this->init("$sScheme://$sHost/cgi-bin/fpay_createOrder.cgi");

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		//2.0 对应的支付成功协议是5.2版本，和带登录态B2C支付通知一致
		//WAP版本使用1.0, 对应支付通知5.1版本
		$this->reqParams->set("service_version", '1.0');
		$this->reqParams->set("input_charset", 'UTF-8');
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);

		$this->reqParams->set("card_spid", $sCardSPID);//刷卡支付商户号
		$this->reqParams->set("date", date("Ymd"));
		$this->reqParams->set("body", $sDesc);
		if (self::isUinUserQQ($iUin))
		{
			$this->reqParams->set("buyer_id", $iUin);
		}
		$this->reqParams->set("transaction_id", $sTransactionId);
		$this->reqParams->set("out_trade_no", $sOrderId);
		$this->reqParams->set("total_fee", $iTotalPrice);
		$this->reqParams->set("fee_type", 1);//1（人民币）
		$this->reqParams->set("return_url", $sReturnURL);//支付成功后回调URL
		$this->reqParams->set("attach", $sAttach);
		$this->reqParams->set("spbill_create_ip", $sClientIP);

		$this->httpClient->setSslCaInfo($this->SSLCERT_PATH."cacert.pem");
		$this->httpClient->setSslCert($this->SSLCERT_PATH.$arrPartner['iPartnerId'].".pem", $arrPartner['sCertPasswd']);

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
					return strval($this->resParams->get('short_no'));
				}
				else
				{
					$this->sErrMsg = "[CFT]~getPayShortNo~".$this->resParams->getDebugInfo();
				}
			}
			else
			{
				$this->sErrMsg = "[CFT]~getPayShortNo~retcode:".$this->iRetCode
								.", errmsg:".$arrResponse['retmsg'];
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~getPayShortNo~".$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

	public function getWapPayToken( $sProxy, $arrPartner, $iSalePlat,
									$sDesc, $sOrderId, $sTransactionId, $iTotalPrice,
									$sCallBackURL, $sNotifyURL, $sAttach, $iBankType = 0,
									$iUin = 0, $iBeginTime = 0, $iEndTime = 0)
	{
		//初始化CGI 地址
		$this->init("https://".(($iSalePlat)?'cl':'wap').".tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi", $sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		$this->reqParams->set("ver", '2.0'); //ver默认值是1.0，目前版本ver取值应为2.0
		if ($iSalePlat)
		{
			$this->reqParams->set("sale_plat", $iSalePlat); //'sale_plat' => 211,
		}
		$this->reqParams->set("charset", '1'); //1 UTF-8, 2 GB2312
		$this->reqParams->set("bank_type", $iBankType);
		$this->reqParams->set("desc", $sDesc); //商品描述，32个字符以内
		if (self::isUinUserQQ($iUin))
		{
			$this->reqParams->set("purchaser_id", $iUin);
		}
		$this->reqParams->set("bargainor_id", $arrPartner['iPartnerId']);
		//'sign_sp_id' => TUAN_CFT_ID,
		$this->reqParams->set("sp_billno", $sOrderId);
		if (!empty($sTransactionId))
		{
			$this->reqParams->set("transaction_id", $sTransactionId);
		}
		$this->reqParams->set("total_fee", $iTotalPrice);
		$this->reqParams->set("fee_type", '1');
		$this->reqParams->set("notify_url", $sNotifyURL);
		$this->reqParams->set("callback_url", $sCallBackURL);
		$this->reqParams->set("attach", $sAttach);
		if ($iBeginTime > 0 && $iEndTime > 0 && $iEndTime > $iBeginTime)
		{
			$this->reqParams->set("time_start", date("YmdHis", $iBeginTime));
			$this->reqParams->set("time_expire", date("YmdHis", $iEndTime));
		}

		$sQueryString = $this->reqParams->buildUrlQuery(true, true);
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(60);
		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);

			if (isset($arrResponse['token_id']) && !empty($arrResponse['token_id']))
			{
				return $arrResponse['token_id'];
			}
			else
			{
				$this->iRetCode = -2;
				$this->sErrMsg = "[CFT]~getWapPayToken~err_info[${arrResponse['err_info']}]";
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~getWapPayToken~".$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

	public function getWapPayUrl($sProxy, $arrPartner,
								 $sDesc, $sOrderId, $sTransactionId, $iTotalPrice,
								 $sCallBackURL, $sNotifyURL, $sAttach, $iBankType = 0,
								 $iUin = 0, $iBeginTime = 0, $iEndTime = 0)
	{
		$token_id = $this->getWapPayToken($sProxy, $arrPartner, 0, $sDesc, $sOrderId, $sTransactionId, $iTotalPrice, $sCallBackURL, $sNotifyURL, $sAttach, $iBankType, $iUin, $iBeginTime, $iEndTime);
		if (empty($token_id))
		{
			return '';
		}

		//初始化,CGI地址使用DNS
		$this->init("https://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_gate.cgi");

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");

		//初始化参数
		$this->reqParams->set("token_id", $token_id);

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();

		$this->httpClient->setQueryString($sQueryString);
		$sRequestURL = $this->httpClient->getRequestURL();

		return $sRequestURL;
	}

	/**
	 * WAP支付成功回调签名规则 {默认规则:ksort参数数组,空值不参与签名}
	 * @author anakinli, weiminlin, fadyzhuang
	 * @ref mapi.tuan.qq.com, m.tuan.qq.com
	 */
	protected static $arrWAPPayNotifySignParams = array();

	/**
	 * @param string     $sKey               MD5签名私钥
	 * @param array      $arrNotifyParams    支付宝通知支付成功的参数数组
	 *
	 * @return bool true:支付通知签名合法
	 */
	protected function verifyPayNotifySign($sKey, $arrNotifyParams)
	{
		//初始化, 不需要
		$this->init("");

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
	 * @see TenpayWapFacade::getWapPayUrl()
	 * @param string     $sKey               MD5签名私钥
	 * @param array      $arrNotifyParams    支付宝通知支付成功的参数数组
	 *
	 * @return array     false: 验证支付通知失败或支付通知非法或支付状态非法
	 *                   array: 支付通知验证通过且订单为'已支付'状态
	 */
	public function processPayNotify($sKey, $arrNotifyParams)
	{
		if (!$this->verifyPayNotifySign($sKey, $arrNotifyParams) //签名验证不通过
		 )//FIXME || !$this->verifyPayNotifyId($iPartnerId, $arrNotifyParams['notify_id'])) //或notify_id不合法
		{
			return false;
		}

		$iPayResult = $arrNotifyParams['pay_result'];
		$sTransactionId = $arrNotifyParams['transaction_id']; //由财付通系统生成, 或者由v1.0支付接口传入
		$sOrderId = $arrNotifyParams['sp_billno'];
		$iTotalPrice = $arrNotifyParams['total_fee']; //单位分
		$sAttach = $arrNotifyParams['attach'];

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
	 * @param string   $sHost       'mch.tenpay.com' or ip get by L5{17480,131072} default:'10.137.151.209'
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sCertPasswd'=>,'sLoginPasswd'=>,'iRefundMode'=>,);
	 * @param string   $sOrderId    28位内部订单号
	 * @param string   $sTransactionId    28位财付通交易订单号
	 * @return boolean true:查询成功且订单支付成功
	 *                 使用_getResponseParameters()获得订单参数
	 */
	public function isOrderPaid($sProxy, $arrPartner, $sOrderId, $sTransactionId)
	{
		//初始化CGI 地址
		$this->init("http://wap.tenpay.com/cgi-bin/wapmainv2.0/wm_query_order.cgi", $sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey'], "&key=");
		$this->resParams->setKey($arrPartner['sKey'], "&key=");

		$this->reqParams->set("ver", '2.0');
		$this->reqParams->set("bargainor_id", $arrPartner['iPartnerId']);
		if (!empty($sTransactionId))
		{
			$this->reqParams->set("transaction_id", $sTransactionId);
		}
		$this->reqParams->set("sp_billno", $sOrderId);
		$this->reqParams->set("attach", "1");
		$this->reqParams->set("charset", "UTF-8"); //GB2312

		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		$this->httpClient->setTimeOut(60);
		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);

			$this->resParams->setAll($arrResponse);
			if ($this->resParams->verifySign())
			{
				return self::isTradePaid($arrResponse);
			}
			else
			{
				$this->sErrMsg = "[CFT]~isOrderPaid~".$this->resParams->getDebugInfo();
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[CFT]~isOrderPaid~".$this->httpClient->getCurlErrMsg();
		}

		return false;
	}

}
