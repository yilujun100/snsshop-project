<?php

require_once dirname(__FILE__) . '/PayFacadeBase.class.php';
require_once dirname(__FILE__) .'/util/WxPayPubHelper.php';

class WeiXinFacade extends PayFacadeBase
{
    public $merchant = '';//商户号

	//证书路径
	public $SSLCERT_PATH;

	public function __construct()
	{
		parent::__construct();
        get_instance()->config->load('pay');
        $config = config_item('weixinPay');
        $this->merchant = $config['MCHID'];
		//$this->SSLCERT_PATH = $config['SSLCERT_PATH'];
	}

	protected function init($sGateUrl='')
	{
		parent::init();
		//$this->reqParams->initCreateSign(array(), array('sign'), false);
		//$this->httpClient->setGateURL($sGateUrl);
		//$this->resParams->initCreateSign(array(), array('sign'), false);
	}

    /**
     * 判断是否支付
     * @param $arrTradeInfo
     * @return bool
     */
    public static function isTradePaid($arrTradeInfo)
    {
        if (isset($arrTradeInfo['result_code']) && isset($arrTradeInfo['transaction_id']) && $arrTradeInfo['result_code'] == 'SUCCESS'){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 订单查询
     * @param $sOrderId 商户订单号
     * @param $sTransactionId   支付流水号
     * @return array    订单详情
     */
    public function queryOrderPayInfo($sOrderId, $sTransactionId)
    {
        $OrderQuery = new OrderQuery_pub();
        if($sTransactionId) {
            $OrderQuery->setParameter('transaction_id',$sTransactionId);
        }

        if ($sOrderId) {
            $OrderQuery->setParameter('out_trade_no',$sOrderId);
        }

        $xml = $OrderQuery->createXml();
        $result = $OrderQuery->postXmlCurl($xml,$OrderQuery->url);
        $result = empty($result) ? array() : $OrderQuery->xmlToArray($result);

        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS'){
            $this->iRetCode = 0;
        }else{
            $this->iRetCode = -1;
            $this->sErrMsg = '[CFT]~refundOrder~retcode:'.$result['err_code'].', errmsg:'.$result['err_code_des'];
        }

        return $result;
    }

    /**
     * 订单查询
     * @param $sOrderId 商户订单号
     * @param $sTransactionId   支付流水号
     * @return array    订单详情
     */
    public function queryRefund($sRefundKey, $sTransactionId)
    {
        $RefundQuery = new RefundQuery_pub();
        $RefundQuery->setParameter('transaction_id',$sTransactionId);
        $RefundQuery->setParameter('out_refund_no',$sRefundKey);
        $xml = $RefundQuery->createXml();
        $result = $RefundQuery->postXmlCurl($xml,$RefundQuery->url);
        $result = empty($result) ? array() : $RefundQuery->xmlToArray($result);

        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS'){
            $this->iRetCode = 0;
        }else{
            $this->iRetCode = -1;
            $this->sErrMsg = '[CFT]~refundQuery~retcode:'.$result['err_code'].', errmsg:'.$result['err_code_des'];
        }

        return $result;
    }


    /**
     * 退款
     * @param $sOrderId 商户订单号
     * @param $sTransactionId   支付流水号
     * @param $sRefundKey   退款单号
     * @param $iTotalPrice  订单总额
     * @param $iRefundPrice  退款总额
     */
    public function refundOrder($sOrderId, $sTransactionId,$sRefundKey, $iTotalPrice, $iRefundPrice)
    {
        $refund = new Refund_pub();
        $refund->setParameter('transaction_id',$sTransactionId);
        $refund->setParameter('out_trade_no',$sOrderId);
        $refund->setParameter('out_refund_no',$sRefundKey);
        $refund->setParameter('total_fee',$iTotalPrice);
        $refund->setParameter('refund_fee',$iRefundPrice);
        $refund->setParameter('op_user_id',$this->merchant);

        $xml = $refund->createXml();//pr($xml);pr($refund->url);
        $result = $refund->postXmlSSLCurl($xml,$refund->url);
        $result = empty($result) ? array() : $refund->xmlToArray($result);

        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS'){
            $this->iRetCode = 0;
        }else{
            $this->iRetCode = -1;
            if(isset($result['err_code_des']) && isset($result['err_code'])){
                $this->sErrMsg = '[CFT]~refundOrder~retcode:'.$result['err_code'].', errmsg:'.$result['err_code_des'];
            }else{
                $this->sErrMsg = '[CFT]~refundOrder~retcode:'.$result['return_code'].', errmsg:'.$result['return_msg'];
            }
        }

        return $result;
    }


    /**
     * 退款查询
     * @param $sRefundId 退款单号
     * @param $sRefundKey 商户退款号
     * @param $sOrderId 商户订单号
     * @param $sTransactionId   支付流水号
     * @return array|mixed
     */
    public function queryOrderRefundInfo( $sRefundId, $sRefundKey,$sOrderId, $sTransactionId)
    {
        $queryRefund = new RefundQuery_pub();
        $queryRefund->setParameter('out_refund_no',$sRefundKey);
        $queryRefund->setParameter('out_trade_no',$sOrderId);
        $queryRefund->setParameter('transaction_id',$sTransactionId);
        $queryRefund->setParameter('refund_id',$sRefundId);

        $xml = $queryRefund->createXml();
        $result = $queryRefund->postXmlCurl($xml,$queryRefund->url);
        $result = empty($result) ? array() : $queryRefund->xmlToArray($result);

        if(isset($result['result_code']) && $result['result_code'] == 'SUCCESS'){
            $this->iRetCode = 0;
        }else{
            $this->iRetCode = -1;
            $this->sErrMsg = '[CFT]~queryOrderRefundInfo~retcode:'.$result['err_code'].', errmsg:'.$result['err_code_des'];
        }

        return $result;
    }

}