<?php

require_once dirname(__FILE__) . '/PayFacadeBase.class.php';

class AlipayFacade extends PayFacadeBase
{
	const RESULT_TRUE = 'T';
	const RESULT_FALSE = 'F';
	const RESULT_PROC = 'P'; //处理中或银行卡充退中

	const TRADE_STATUS_WAIT_BUYER_PAY = 'WAIT_BUYER_PAY';  //交易创建，等待买家付款
	const TRADE_STATUS_CLOSED = 'TRADE_CLOSED';            //在指定时间段内未支付时关闭的交易;在交易完成全额退款成功时关闭的交易
	const TRADE_STATUS_SUCCESS = 'TRADE_SUCCESS';          //交易成功，且可对该交易做操作，如：多级分润、退款等
	const TRADE_STATUS_PENDING = 'TRADE_PENDING';          //等待卖家收款（买家付款后，如果卖家账号被冻结）
	const TRADE_STATUS_FINISHED = 'TRADE_FINISHED';        //交易成功且结束，即不可再做任何操作

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

	const REFUND_STATUS_SUCCESS = 'REFUND_SUCCESS';        //全额退款情况：trade_status= TRADE_CLOSED，而refund_status=REFUND_SUCCESS
                                                           //非全额退款情况：trade_status= TRADE_SUCCESS，而refund_status=REFUND_SUCCESS
	const REFUND_STATUS_CLOSED = 'REFUND_CLOSED';          //退款关闭

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string     $sProxy             `ip:port`
	 */
	protected function init($sProxy=null)
	{
		parent::init();

		$this->reqParams->initCreateSign(array(), array('sign', 'sign_type'), false);
		$this->httpClient->setGateURL("https://mapi.alipay.com/gateway.do");
		$this->resParams->initCreateSign(array(), array('sign', 'sign_type'), false);

		if (!empty($sProxy))
		{
			$this->httpClient->setProxy($sProxy);
		}
	}

	/**
	 * 拼接参数,设置跳转到支付宝,支付成功后回调.
	 * @param array    $arrPartner   array('iPartnerId'=>,'sKey'=>,'sEmail'=>,)
	 * @param string   $sOrderId    28位内部订单号
	 * @param int      $iTotalPrice 订单总价,分为单位
	 * @param string   $sShortName  商品简述(char[256])
	 * @param string   $sDesc   商品描述(char[1000])
	 * @param string   $sReturnURL  支付成功后浏览器跳转URL
	 * @param string   $sNotifyURL  支付成功后后台通知URL
	 * @param string   $sAttach     自定义参数, 支付成功回调原样带回(char[100])
	 * @param string   $sClientIP   用户下单IP
	 * @param int      $iBeginTime  交易开始时间
	 * @param int      $iEndTime    交易关闭时间
	 *
	 * @return string 支付网关URL
	 */
	public function getPayGateURL($arrPartner,
								  $sOrderId, $iTotalPrice, $sShortName, $sDesc,
								  $sReturnURL, $sNotifyURL, $sAttach,
								  $sClientIP, $iBeginTime, $iEndTime)
	{
		//初始化
		$this->init();

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);

		//设置参数
		$this->reqParams->set("service", "create_direct_pay_by_user");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		$this->reqParams->set("notify_url", $sNotifyURL);
		$this->reqParams->set("return_url", $sReturnURL);
		//$this->reqParams->set("error_notify_url", "");
		$this->reqParams->set("out_trade_no", $sOrderId);
		$this->reqParams->set("subject", $sShortName);
		$this->reqParams->set("payment_type", "1");
		$this->reqParams->set("seller_email", $arrPartner['sEmail']);
		$this->reqParams->set("total_fee", round($iTotalPrice/100, 2));
		$this->reqParams->set("body", $sDesc);
		//$this->reqParams->set("show_url", $sShowURL);
		//$this->reqParams->set("anti_phishing_key", ""); //需要alipay服务端配置
		//$this->reqParams->set("exter_invoke_ip", $sClientIP); //需要alipay服务端配置
		$this->reqParams->set("extra_common_param", $sAttach);
		$diffSec = $iEndTime - time();
		if ($diffSec >= 60) //至少有1分钟
		{
			$this->reqParams->set("it_b_pay", intval($diffSec/60).'m');
		}

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();

		$this->httpClient->setQueryString($sQueryString);
		$sRequestURL = $this->httpClient->getRequestURL();

		return $sRequestURL;
	}

	/**
	 * @param string     $sKey               MD5签名私钥
	 * @param array      $arrNotifyParams    支付宝通知支付成功的参数数组
	 *
	 * @return bool true:支付通知签名合法
	 */
	protected function verifyPayNotifySign($sKey, $arrNotifyParams)
	{
		//初始化
		$this->init();

		//设置签名key
		$this->resParams->setKey($sKey);

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
	 * @param int        $iPartnerId         签约的支付宝账号对应的支付宝唯一用户号,以2088开头的16位纯数字组成
	 * @param string     $sNotifyId          支付成功通知id
	 *
	 * @return bool true:支付通知id合法
	 */
	protected function verifyPayNotifyId($iPartnerId, $sNotifyId)
	{
		//初始化
		$this->init(HttpClient::HTTP_PROXY1);

		//设置参数
		$this->reqParams->set("service", "notify_verify");
		$this->reqParams->set("partner", $iPartnerId);
		$this->reqParams->set("notify_id", $sNotifyId);

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());

		if ($this->httpClient->execute())
		{
			$sRawResponse = $this->httpClient->getResponseBody();
			if (preg_match('/true$/i', $sRawResponse) == 1)
			{
				return true;
			}
			else
			{
				$this->iRetCode = -3;
				$this->sErrMsg = "[ZFB]~verifyPayNotifyId($sNotifyId)~ret: $sRawResponse";
			}
		}
		else
		{
			$this->iRetCode = -2;
			$this->sErrMsg = "[ZFB]~verifyPayNotifyId($sNotifyId)~".$this->httpClient->getCurlErrMsg();
		}

		return false;
	}

	/**
	 * 验证支付成功通知, 解析参数并返回.
	 * @see AlipayFacade::getPayGateURL
	 * @param int        $iPartnerId         签约的支付宝账号对应的支付宝唯一用户号,以2088开头的16位纯数字组成
	 * @param string     $sKey               MD5签名私钥
	 * @param array      $arrNotifyParams    支付宝通知支付成功的参数数组
	 *
	 * @return array     false: 验证支付通知失败或支付通知非法或支付状态非法
	 *                   array: 支付通知验证通过且订单为'已支付'状态
	 */
	public function processPayNotify($iPartnerId, $sKey, $arrNotifyParams)
	{
		if (!$this->verifyPayNotifySign($sKey, $arrNotifyParams) //签名验证不通过
		 )//FIXME || !$this->verifyPayNotifyId($iPartnerId, $arrNotifyParams['notify_id'])) //或notify_id不合法
		{
			return false;
		}

		$sTradeStatus = $arrNotifyParams['trade_status'];
		$sTransactionId = $arrNotifyParams['trade_no']; //由支付宝系统生成
		$sOrderId = $arrNotifyParams['out_trade_no'];
		$iTotalPrice = intval($arrNotifyParams['total_fee'] * 100); //单位由元转换为分
		$sAttach = $arrNotifyParams['extra_common_param'];

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
			$this->sErrMsg = "[ZFB]~processPayNotify($sOrderId-$sTransactionId)~trade_status: $sTradeStatus";
		}

		return false;
	}

	/**
	 * @param string     $sProxy             代理地址: `ip:port`
	 * @param array      $arrPartner         array('iPartnerId'=>,'sKey'=>,)
	 * @param string     $sOrderId           28位内部订单号
	 * @param string     $sTransactionId     支付宝交易单号<推荐使用该参数>
	 *
	 * @return array 查询成功且订单支付成功返回相应数组, 否则返回空
	 */
	public function queryTradeInfo($sProxy, $arrPartner, $sOrderId, $sTransactionId)
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);
		$this->resParams->setKey($arrPartner['sKey']);

		//设置参数
		$this->reqParams->set("service", "single_trade_query");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		$this->reqParams->set("trade_no", $sTransactionId);
		$this->reqParams->set("out_trade_no", $sOrderId);

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$this->httpClient->setTimeout(60);

		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);

			if ($arrResponse['is_success'] == self::RESULT_TRUE)
			{
				$this->resParams->setAll($arrResponse['response']['trade']);
				$this->resParams->set('sign', $arrResponse['sign']);
				$this->resParams->set('sign_type', $arrResponse['sign_type']);

				if ($this->resParams->verifySign())
				{
					return $this->resParams->getAll();
				}
			}

			$this->iRetCode = -2;
			$this->sErrMsg = "[ZFB]~queryTradeInfo($sTransactionId,$sOrderId)~"
							."is_success(F) or verify sign failed!";
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~queryTradeInfo($sTransactionId,$sOrderId)~"
							.$this->httpClient->getCurlErrMsg();
		}

		return array();
	}

	const MAX_TRADE_COUNT = 1000; //单次查询返回的最大条数

	/**
	 * @param string     $sProxy             代理地址: `ip:port`
	 * @param array      $arrPartner         array('iPartnerId'=>,'sKey'=>,)
	 * @param int        $iTransBeginTime
	 * @param int        $iTransEndTime
	 * @param string     $sTradeStatus
	 *
	 * @return array 返回结果按交易创建时间排序, 最多MAX_TRADE_COUNT条记录,即需要分时间多次段查询
	 *               返回结果按`gmt_create`字段排序
	 */
	public function getTransList($sProxy, $arrPartner,
								 $iTransBeginTime, $iTransEndTime,
								 $sTradeStatus=self::TRADE_STATUS_SUCCESS)
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);
		$this->resParams->setKey($arrPartner['sKey']);

		//设置参数
		$this->reqParams->set("service", "query_trade_list_partner");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		$this->reqParams->set("gmt_begin", date("Y-m-d H:i:s", $iTransBeginTime));
		$this->reqParams->set("gmt_end", date("Y-m-d H:i:s", $iTransEndTime));
		//$this->reqParams->set("seller_email", $sSellerEmail);
		$this->reqParams->set("trade_status", $sTradeStatus);

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$this->httpClient->setTimeout(600);

		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::XMLToKVArray($sXmlContent); //var_dump($arrResponse);

			if ($arrResponse['is_success'] == self::RESULT_TRUE)
			{
				//foreach ($arrResponse['response']['trade'] as $trade)
				//{
				//	echo $trade['gmt_create'].'~'.$trade['gmt_last_modified_dt'].'~'
				//			.$trade['out_trade_no'].'~'.$trade['trade_status']."\n";
				//}
				//var_dump($arrResponse['response']['trade'][0]);
				//return $arrResponse['response']['trade_count'];
				return $arrResponse['response']['trade'];
			}

			$this->iRetCode = -2;
			$this->sErrMsg = "[ZFB]~getTransList($iTransBeginTime,$iTransEndTime)~is_success(${arrResponse['is_success']}), error(${arrResponse['error']})!";
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~getTransList($iTransBeginTime,$iTransEndTime)~".$this->httpClient->getCurlErrMsg();
		}

		return false;
	}

	/**
	 * 调用支付宝接口退款, 每次只处理一笔退款
	 * @param string     $sProxy             代理地址: `ip:port`
	 * @param array      $arrPartner         array('iPartnerId'=>,'sKey'=>,)
	 * @param string     $sNotifyURL         退款执行成功后通知URL
	 * @param string     $sTransactionId     支付宝交易订单号, 对应trade_no字段
	 * @param string     $sRefundId          退款流水单号, `yyyyMMdd+流水号`
	 * @param int        $iRefundPrice       退款金额(分), 累计退款金额不能超过支付总金额
	 * @param string     $sRefundReason      退款原因
	 *
	 * @return int 0: 退款请求提交支付宝系统成功, 退款到账后会回调$sNotifyURL; <0 ~ refund error.
	 */
	public function refundOrder($sProxy, $arrPartner,
								$sNotifyURL, $sTransactionId,
								$sRefundId, $iRefundPrice, $sRefundReason)
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);
		$this->resParams->setKey($arrPartner['sKey']);

		//退款日期（8位当天日期）+流水号（3～24位，不能接受“000”，但是可以接受英文字符）, 201211210001
		$batch_no = $sRefundId; //substr($sRefundId, -17); //截取$sRefundId中`yyyyMMdd+流水号`部分
		$refund_date = date("Y-m-d H:i:s");
		$batch_num = 1; //每次只处理1笔退款, 多笔的话用#分隔
		//交易退款数据集[$收费退款数据集][|分润退款数据集][$$退子交易]
		//交易退款数据集=原付款支付宝交易号^退款总金额^退款理由, 2011011001034366^20.00^协商退款
		$trade_no = $sTransactionId;
		$refund_fee = round($iRefundPrice/100, 2);
		$refund_reason = strtr($sRefundReason, '^|$#', '    '); //退款理由中不能有[^|$#]等特殊字符
		$detail_data = "$trade_no^$refund_fee^$refund_reason";

		//设置参数
		$this->reqParams->set("service", "refund_fastpay_by_platform_nopwd");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		$this->reqParams->set("notify_url", $sNotifyURL);
		//$this->reqParams->set("dback_notify_url", '');
		$this->reqParams->set("batch_no", $batch_no);
		$this->reqParams->set("refund_date", $refund_date);
		$this->reqParams->set("batch_num", $batch_num);
		$this->reqParams->set("detail_data", $detail_data);
		//$this->reqParams->set("use_freeze_amount", 'N');
		//$this->reqParams->set("return_type", 'xml');

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$this->httpClient->setTimeout(60);

		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::SimpleXMLToKVArray($sXmlContent); //var_dump($arrResponse);

			if ($arrResponse['is_success'] != self::RESULT_TRUE)
			{
				$this->iRetCode = -2;
				$this->sErrMsg = "[ZFB]~refundOrder($batch_no,$detail_data)~is_success(${arrResponse['is_success']}), error(${arrResponse['error']})!";
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~refundOrder($batch_no,$detail_data)~".$this->httpClient->getCurlErrMsg();
		}

		return $this->iRetCode;
	}

	public function processRefundNotify()
	{
		;
	}

	/**
	 * 查询退款结果
	 * @param string     $sProxy             代理地址: `ip:port`
	 * @param array      $arrPartner         array('iPartnerId'=>,'sKey'=>,)
	 * @param string     $sRefundId          退款流水单号, `yyyyMMdd+流水号`
	 * @param string     $sTransactionId     支付宝交易订单号, 对应trade_no字段
	 *
	 * @return string `批次号^原付款交易号^退交易金额^处理结果码`
	 */
	public function queryRefundInfo($sProxy, $arrPartner,
									$sRefundId, $sTransactionId)
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);
		$this->resParams->setKey($arrPartner['sKey']);

		//设置参数
		$this->reqParams->set("service", "refund_fastpay_query");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		$this->reqParams->set("batch_no", $sRefundId);
		$this->reqParams->set("trade_no", $sTransactionId);

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$this->httpClient->setTimeout(60);

		if ($this->httpClient->execute())
		{
			//is_success=F&error_code=REFUND_NOT_EXIST
			//is_success=T&result_details=20121204001^2012111620984834^0.01^TRADE_HAS_CLOSED
			//#20121122001^2012111620984834^0.01^SUCCESS
			$sKVContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			parse_str($sKVContent, $arrResponse); //var_dump($arrResponse);

			if ($arrResponse['is_success'] == self::RESULT_TRUE)
			{
				//每笔退款之间用#分隔
				//退交易结果[$退收费结果][|退分润结果][$$退子交易结果]
				//退交易结果=批次号^原付款交易号^退交易金额^处理结果码^是否充退^充退处理结果,
				//         20121122001^2012111620984834^0.01^SUCCESS
				return $arrResponse['result_details'];
			}

			$this->iRetCode = -2;
			$this->sErrMsg = "[ZFB]~queryRefundInfo($sRefundId,$sTransactionId)~"
							."is_success(F) or verify sign failed!";
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~queryRefundInfo($sRefundId,$sTransactionId)~"
							.$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

	const TC_ALL = 0; //返回全部记录
	const TC_TRANSFER = 3011; //转账 (退款流水数据)
	const TC_CHARGE = 3012; //收费 (支付宝手续费[0.1%])
	const TC_TOP_UP = 4003; //充值
	const TC_WITHDRAW = 5004; //提现
	const TC_BOUNCE = 5103; //退票
	const TC_ONLINE_PAY = 6001; //在线支付

	/**
	 * 对于红包支付, 两条流水都是`6001~在线支付`类型
	 * 对于通用积分支付, 实际付款是`6001~在线支付`类型, 优惠款是`3011~转账`类型
	 * 为了得到最终的结算数据: no_coupon=Y && trans_code不指定, 然后再判断csv结果的`$13`区分类型
	 * @param string     $sProxy
	 * @param array      $arrPartner         array('iPartnerId'=>,'sKey'=>,)
	 * @param int        $iStartTime
	 * @param int        $iEndTime
	 * @param bool       $isMergeCoupon
	 * @param int        $iTransCode
	 *
	 * @return array('count'=>int, 'csv_data'=>string)|boolean
	 */
	public function getBillList($sProxy, $arrPartner,
								$iStartTime, $iEndTime,
								$isMergeCoupon = true,
								$iTransCode = 0)
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);
		$this->resParams->setKey($arrPartner['sKey']);

		//设置参数
		$this->reqParams->set("service", "export_trade_account_report");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		$this->reqParams->set("gmt_create_start", date("Y-m-d H:i:s", $iStartTime));
		$this->reqParams->set("gmt_create_end", date("Y-m-d H:i:s", $iEndTime));
		$this->reqParams->set("no_coupon", (($isMergeCoupon)? "Y" : "N"));
		//交易类型代码: 3011~转账;3012~收费;4003~充值;5004~提现;5103~退票;6001~在线支付
		if ($iTransCode != self::TC_ALL)
		{
			$this->reqParams->set("trans_code", $iTransCode);
		}

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$this->httpClient->setTimeout(600);

		if ($this->httpClient->execute())
		{
			$sXmlContent = $this->httpClient->getResponseBody(); //var_dump($sXmlContent);
			$arrResponse = XMLArray::XMLToKVArray($sXmlContent); //var_dump($arrResponse);

			if ($arrResponse['is_success'] != self::RESULT_TRUE)
			{
				$this->iRetCode = -2;
				$this->sErrMsg = "[ZFB]~getBillList($iStartTime,$iEndTime,$iTransCode)~is_success(${arrResponse['is_success']}), error(${arrResponse['error']})!";
			}
			else
			{
				$this->resParams->setAll($arrResponse['response']['csv_result']);
				$this->resParams->set('sign', $arrResponse['sign']);
				$this->resParams->set('sign_type', $arrResponse['sign_type']);

				if ($this->resParams->verifySign())
				{
					return $this->resParams->getAll();
				}
			}
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~getBillList($iStartTime,$iEndTime,$iTransCode)~".$this->httpClient->getCurlErrMsg();
		}

		return false;
	}

	/**
	 * @param string $sProxy
	 * @param array      $arrPartner         array('iPartnerId'=>,'sKey'=>,)
	 *
	 * @return string
	 */
	public function getMobilePayChannel($sProxy, $arrPartner)
	{
		//初始化
		$this->init($sProxy);

		//设置签名key
		$this->reqParams->setKey($arrPartner['sKey']);
		$this->resParams->setKey($arrPartner['sKey']);

		//设置参数
		$this->reqParams->set("service", "mobile.merchant.paychannel");
		$this->reqParams->set("partner", $arrPartner['iPartnerId']);
		$this->reqParams->set("_input_charset", "utf-8");
		$this->reqParams->set("sign_type", "MD5");
		//$this->reqParams->set("sign", "");//签名字段,获取URL时会自动生成
		//$this->reqParams->set("out_user", "");

		//获取请求串(生成签名'sign'), 然后设置重定向到支付宝.
		$sQueryString = $this->reqParams->buildUrlQuery();
		$this->httpClient->setQueryString($sQueryString);
		//var_dump($this->httpClient->getRequestURL());
		$this->httpClient->setTimeout(60);

		if ($this->httpClient->execute())
		{
			$sKVContent = $this->httpClient->getResponseBody(); //var_dump($sKVContent);

			$this->iRetCode = -2;
			$this->sErrMsg = "[ZFB]~getMobilePayChannel()~is_success(F) or verify sign failed!";
		}
		else
		{
			$this->iRetCode = -1;
			$this->sErrMsg = "[ZFB]~getMobilePayChannel()~".$this->httpClient->getCurlErrMsg();
		}

		return '';
	}

}

//TODO 退款回调, 退款结果查询, 退款交易列表查询对账
//     交易列表状态转变确认, trade_closed触发取消订单操作
//email的用处, 多网站接入同一账号是否可以用不同email
//交易创建后 不设置关闭时间 多久后交易关闭

