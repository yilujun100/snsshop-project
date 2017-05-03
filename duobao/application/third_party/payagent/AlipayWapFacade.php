<?php

require_once dirname(__FILE__) . '/PayFacadeBase.class.php';
require_once dirname(__FILE__) . '/util/RSAParameterHandler.class.php';

class AlipayWapFacade extends PayFacadeBase
{
	const TRADE_STATUS_WAIT_BUYER_PAY = 'WAIT_BUYER_PAY';  //交易创建
	const TRADE_STATUS_CLOSED = 'TRADE_CLOSED';            //交易关闭
	const TRADE_STATUS_SUCCESS = 'TRADE_SUCCESS';          //支付成功, 触发通知
	const TRADE_STATUS_FINISHED = 'TRADE_FINISHED';        //交易成功, 触发通知

	public static function isTradePaid($arrTradeInfo)
	{
		if (isset($arrTradeInfo['trade_status']))
		{
			//TRADE_FINISHED（普通即时到账的交易成功状态）
			//TRADE_SUCCESS（开通了高级即时到账或机票分销产品后的交易成功状态）
			return ($arrTradeInfo['trade_status'] == self::TRADE_STATUS_FINISHED
				|| $arrTradeInfo['trade_status'] == self::TRADE_STATUS_SUCCESS);
		}
		else
		{
			return false;
		}
	}

	/**
	 * 证书路径
	 */
	protected $SSLCERT_PATH;

	/**
	 * 商户私钥
	 * @var mixed
	 * @see openssl_pkey_get_private()
	 */
	protected $partnerPrivateKey;

	/**
	 * 支付宝公钥
	 * @var mixed
	 * @see openssl_pkey_get_public()
	 */
	protected $alipayPublicKey;

	public function __construct()
	{
		parent::__construct();

		$this->SSLCERT_PATH = dirname(__FILE__).'/sslcert/';
		$this->partnerPrivateKey = 'file://'.$this->SSLCERT_PATH.'/rsa_private_key.pem';
		$this->alipayPublicKey = 'file://'.$this->SSLCERT_PATH.'/alipay_public_key.pem';
	}

	public function getRSAKeys()
	{
		return array(
				'privateKey' => file_get_contents($this->partnerPrivateKey),
				'publicKey' => file_get_contents($this->alipayPublicKey),
		);
	}

	/**
	 * WAP方式的网关与Web支付不同, 签名方式使用RSA
	 * @param string     $sProxy             `ip:port`
	 */
	protected function init($sProxy=null)
	{
		parent::init();

		//WAP都使用RSA签名方式
		if (!($this->reqParams instanceof RSAParameterHandler))
		{
			$this->reqParams = new RSAParameterHandler();
			$this->resParams = new RSAParameterHandler();
		}

		$this->reqParams->initCreateSign(array(), array('sign'), false);
		$this->httpClient->setGateURL("http://wappaygw.alipay.com/service/rest.htm");
		$this->resParams->initCreateSign(array(), array('sign'), false);

		if (!empty($sProxy))
		{
			$this->httpClient->setProxy($sProxy);
		}
	}

	const BANKTYPE_CREDITCARD = 'CREDITCARD'; //信用卡快捷支付 "CREDITCARD_CMB", //招行-信用卡快捷支付
	const BANKTYPE_DEBITCARD = 'DEBITCARD'; //储蓄卡快捷支付

	protected function getWapPayToken($sProxy, $iPartnerId, $sSellerEmail,
										$sCallBackURL, $sNotifyURL, $sMerchantURL,
										$sSubject, $sOrderId, $iTotalPrice, $iUin, $sCashierCode = '')
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($this->partnerPrivateKey, $this->alipayPublicKey);
		$this->resParams->setKey($this->partnerPrivateKey, $this->alipayPublicKey);

		//设置参数
		$this->reqParams->set("service", "alipay.wap.trade.create.direct");
		$this->reqParams->set("format", "xml");
		$this->reqParams->set("v", "2.0");
		$this->reqParams->set("partner", $iPartnerId);
		$this->reqParams->set("req_id", $sOrderId); //用于关联请求与响应，防止请求重播。支付宝限制来自同一个partner的请求号必须唯一。
		$this->reqParams->set("sec_id", "0001");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成

		$req_data = array(
				"subject" => $sSubject, //用户购买的商品名称。
				"out_trade_no" => $sOrderId, //支付宝合作商户网站唯一订单号。
				"total_fee" => round($iTotalPrice/100, 2), //[0.01，100000000.00]
				"seller_account_name" => $sSellerEmail, //卖家的支付宝账号
				"call_back_url" => $sCallBackURL, //支付成功后的跳转页面链接。支付成功才会跳转。
				"notify_url" => $sNotifyURL, //支付宝服务器主动通知商户网站里指定的页面http路径。
				"out_user" => $iUin, //买家在商户系统的唯一标识。
				"merchant_url" => $sMerchantURL, //收银台页面上，商品展示的超链接。
				"pay_expire" => "21600", //交易自动关闭时间，单位为分钟。默认值21600（即15天）。
			);
		if (!empty($sCashierCode))
		{
			$req_data["cashier_code"] = $sCashierCode; //银行支付前置
		}
		$this->reqParams->setArray2Xml("req_data", "direct_trade_create_req", $req_data); //请求业务参数

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);

		$this->httpClient->setTimeOut(60);
		if ($this->httpClient->execute())
		{
			$sKVContent = $this->httpClient->getResponseBody();
			parse_str($sKVContent, $arrResponse);

			$this->resParams->setAll($arrResponse);
			if (isset($arrResponse['res_data'], $arrResponse['sign'])) //响应内容包含这两个key, 说明请求处理成功
			{
				$this->resParams->decryptParam('res_data'); //解密res_data
				if ($this->resParams->verifySign())
				{
					$res_data = $this->resParams->getXml2Array('res_data');
					return $res_data['request_token'];
				}
				else
				{
					$this->iRetCode = -3;
					$this->sErrMsg = "[ZFB]~redirectToWAPPayGate()~verifySign failed!"
							.$this->resParams->getDebugInfo();
				}
			}
			else
			{
				$res_error = $this->resParams->getXml2Array('res_error');
				$this->iRetCode = -2;
				$this->sErrMsg = "[ZFB]~redirectToWAPPayGate()~error:".http_build_query($res_error);
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~redirectToWAPPayGate()~"
					.$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

	/**
	 * @param string     $sProxy             代理地址: `ip:port`
	 * @param int        $iPartnerId         签约的支付宝账号对应的支付宝唯一用户号,以2088开头的16位纯数字组成
	 * @param string     $sSellerEmail       卖家支付宝账号
	 * @param string     $sCallBackURL       支付成功前台跳转回调URL
	 * @param string     $sNotifyURL         支付成功后台调用回调URL
	 * @param string     $sMerchantURL       商品详情URL
	 * @param string     $sSubject           商品简述(char[256])
	 * @param string     $sOrderId           28位内部订单号
	 * @param int        $iTotalPrice        订单总价,分为单位
	 * @param int        $iUin               用户id
	 * @param string     $sCashierCode       银行编码
	 * @return string 支付网关URL
	 */
	public function getWapPayUrl($sProxy, $iPartnerId, $sSellerEmail,
								  $sCallBackURL, $sNotifyURL, $sMerchantURL,
								  $sSubject, $sOrderId, $iTotalPrice, $iUin, $sCashierCode = '')
	{
		$request_token = $this->getWapPayToken($sProxy, $iPartnerId, $sSellerEmail,
												$sCallBackURL, $sNotifyURL, $sMerchantURL,
												$sSubject, $sOrderId, $iTotalPrice, $iUin, $sCashierCode);
		if (empty($request_token))
		{
			return ''; //无法获得token
		}

		//重新初始化, 构造跳转支付url
		$this->reqParams->initCreateSign(array(), array('sign'), false);
		//设置签名key
		$this->reqParams->setKey($this->partnerPrivateKey, $this->alipayPublicKey);

		//设置参数
		$this->reqParams->set("service", "alipay.wap.auth.authAndExecute");
		$this->reqParams->set("format", "xml");
		$this->reqParams->set("v", "2.0");
		$this->reqParams->set("partner", $iPartnerId);
		$this->reqParams->set("sec_id", "0001");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成

		$req_data = array(
				"request_token" => $request_token, //授权令牌，调用“手机网页即时到账授权接口(alipay.wap.trade.create.direct)”成功后返回该值。
		);
		$this->reqParams->setArray2Xml("req_data", "auth_and_execute_req", $req_data); //请求业务参数

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);

		$sRequestURL = $this->httpClient->getRequestURL();
		return $sRequestURL;
	}

	/**
	 * WAP回调
	 * @param array $arrCallBackParams
	 * @return array
	 */
	public function processWapPayCallBack($arrCallBackParams)
	{
		//初始化
		$this->init();

		//重新初始化, sign_type也不参加签名
		$this->resParams->initCreateSign(array(), array('sign', 'sign_type'), false);
		//设置签名key
		$this->resParams->setKey($this->partnerPrivateKey, $this->alipayPublicKey);

		//设置参数
		$this->resParams->setAll($arrCallBackParams);

		$sTransactionId = $this->resParams->get('trade_no'); //由支付宝系统生成
		$sOrderId = $this->resParams->get('out_trade_no');
		$iTotalPrice = 0;
		$sAttach = ''; //WAP和SECURE方式不支持回传attach参数

		if ($this->resParams->verifySign()
		 && $this->resParams->get('result') == 'success')
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
			$this->iRetCode = -1;
			$this->sErrMsg = $this->resParams->getDebugInfo();
		}

		return false;
	}

	/**
	 * WAP通知
	 * @param array $arrNotifyParams
	 * @return array
	 */
	public function processWapPayNotify($arrNotifyParams)
	{
		//初始化
		$this->init();

		//重新初始化, 使用固定顺序key签名
		$this->resParams->initCreateSign(array('service','v','sec_id','notify_data'), array('sign'), false);
		//设置签名key
		$this->resParams->setKey($this->partnerPrivateKey, $this->alipayPublicKey);

		//设置参数
		$this->resParams->setAll($arrNotifyParams);
		$this->resParams->decryptParam('notify_data'); //解密notify_data

		return $this->processPayNotify();
	}

	/**
	 * SECURE通知
	 * @param array $arrNotifyParams
	 * @return array
	 */
	public function processSecurePayNotify($arrNotifyParams)
	{
		//初始化
		$this->init();

		//重新初始化, sign_type也不参加签名
		$this->resParams->initCreateSign(array(), array('sign', 'sign_type'), false);
		//设置签名key
		$this->resParams->setKey($this->partnerPrivateKey, $this->alipayPublicKey);

		//设置参数
		$this->resParams->setAll($arrNotifyParams);

		return $this->processPayNotify();
	}

	protected function processPayNotify()
	{
		if ($this->resParams->verifySign())
		{
			$notify_data_str = $this->resParams->get('notify_data');
			$notify_data_xmlstr = strtr($notify_data_str, '&', ' '); //避免xml解析出错, FIXME: 这样会破坏<subject>参数
			$notify_data = XMLArray::SimpleXMLToKVArray($notify_data_xmlstr);
			$sTradeStatus = $notify_data['trade_status'];
			$sTransactionId = $notify_data['trade_no']; //由支付宝系统生成
			$sOrderId = $notify_data['out_trade_no'];
			$iTotalPrice = intval($notify_data['total_fee'] * 100); //单位由元转换为分
			$sAttach = ''; //WAP和SECURE方式不支持回传attach参数

			if (self::isTradePaid($notify_data))
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
				$this->iRetCode = -2;
				$this->sErrMsg = "[ZFB]~processPayNotify($sOrderId-$sTransactionId)~trade_status: $sTradeStatus";
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = $this->resParams->getDebugInfo();
		}

		return false;
	}

}

