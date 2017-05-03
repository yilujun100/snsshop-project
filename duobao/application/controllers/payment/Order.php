<?php
require_once(APPPATH.'controllers/duogebao/Base.php');
require_once(APPPATH.'controllers/duogebao/Common.php');

class Order extends Duogebao_Base
{
    const PAY_ARRACH_KEY = '29os@^k(k~-2*jd';
    const PAY_STATUS_TIME_OUT = 86400;

    protected $need_login_methods = array('ajax_create_order', 'ajax_get_order');
    protected $disable_layout = true;

    protected $order_type;
    protected $pay_agent;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('pay');
        $this->load->service('order_service');
    }

    /**
     * 下单
     */
    public function ajax_create_order()
    {
        $log_label = 'payment order | ajax_create_order';

        $this->order_type = $this->post('order_type', 0);
        $this->pay_agent = $this->post('pay_agent', 0);
        if (! $this->order_type || ! in_array($this->order_type, array_keys(Lib_Constants::$order_type))) {
            $this->log->error($log_label, 'order type error', array('order_type'=>$this->order_type, 'user'=>$this->user));
            $this->output_json(Lib_Errors::ORDER_TYPE_NOT_FOUND);
        }
        if (! $this->pay_agent || ! in_array($this->pay_agent, array_keys(Lib_Constants::$order_pay_type))) {
            $this->log->error($log_label, 'pay agent error', array('pay_agent'=>$this->pay_agent, 'user'=>$this->user));
            $this->output_json(Lib_Errors::PAY_AGENT_ERROR);
        }
        $real_cashier =  'create_order_' . $this->order_type;
        if (! is_callable(array($this, $real_cashier))) {
            $this->log->error($log_label, 'cashier undefined', array('order_type'=>$this->order_type,'pay_agent'=>$this->pay_agent,'user'=>$this->user));
            $this->output_json(Lib_Errors::ORDER_CASHIER_UNDEFINED);
        }
        list($order_id, $data) = $this->{$real_cashier}();
        if ($order_id < Lib_Errors::SUCC) {
            $this->output_json($order_id);
        }
        $this->wx_unified_order($order_id, $data);
    }

    /**
     * 拼团订单
     */
    protected function create_order_6()
    {
        $log_label = 'payment order | create_order_6';

        $buy_type = $this->post('buy_type', 0);
        $address_id = $this->post('address_id', 0);
        $order_id = $this->post('order_id', 0);

        if ($order_id) {
            $params = array(
                'order_id' => $order_id,
                'address_id' => $address_id,
            );
            $ret = $this->get_api('pay_later_data', $params);
            if (Lib_Errors::SUCC != $ret['retCode']) {
                $this->log->error($log_label, 'get_order_groupon error', array('params'=>$params,'ret'=>$ret,'user'=>$this->user));
                return $ret['retCode'];
            }
            return array($order_id, array('goodsName'=>$ret['retData']['groupon']['sGoodsName']));
        } else {
            $groupon_id = $this->post('groupon_id', 0);
            $spec_id = $this->post('spec_id', 0);
            $diy_id = $this->post('diy_id', 0);

            if (! $buy_type ||
                ! in_array($buy_type, array_keys(Lib_Constants::$groupon_order)) ||
                ! $address_id) {
                return Lib_Errors::PARAMETER_ERR;
            }

            $params = array(
                'pay_agent' => $this->pay_agent,
                'buy_type' => $buy_type,
                'address_id' => $address_id,
                'groupon_id' => $groupon_id,
                'spec_id' => $spec_id,
                'diy_id' => $diy_id
            );
            $ret = $this->uin_api('create_groupon_order', $params);
            if (Lib_Errors::SUCC != $ret['retCode']) {
                $this->log->error($log_label, 'create order failed', array('order_type'=>$this->order_type,'pay_agent'=>$this->pay_agent,'params'=>$params, 'ret'=>$ret, 'user'=>$this->user));
                return $ret['retCode'];
            }
            return array($ret['retData']['order_id'], array('goodsName'=>$ret['retData']['groupon']['sGoodsName']));
        }
    }

    /**
     * 查询订单状态
     * 如果未支付，在条件满足的情况下会通过接口查询第三方的支付状态
     */
    public function ajax_get_order()
    {
        $log_label = 'payment order | ajax_get_order';

        $order_id = $this->post('order_id');
        if(empty($order_id)){
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $order = $this->order_service->get_order_detail($this->user['uin'], $order_id);
        if (empty($order)) {
            $this->log->error($log_label, 'order is not find', array('order_id'=>$order_id, 'user'=>$this->user));
            $this->output_json(Lib_Errors::ORDER_NOT_FOUND);
        }
        //如果未支付并且创建订单未超过24小时，则再次查询支付订单状态
        if ($order['iPayStatus'] == Lib_Constants::PAY_STATUS_UNPAID && $order['iCreateTime'] > (time()-self::PAY_STATUS_TIME_OUT)){
            $this->load->service('pay_service');
            $arrTradeInfo = $this->pay_service->query_order_info($order['sOrderId'], $order['sTransId']);
            if(! empty($arrTradeInfo) && $this->pay_service->is_weixin_paid($arrTradeInfo)){
                $repeat = 1;
                do {
                    $result = true;
                    $return = $this->order_service->set_succ_order($this->user['uin'], $arrTradeInfo);
                    if(is_numeric($return)){
                        $result =  false;
                    } else {
                        $this->order = $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
                    }
                    $repeat++;
                } while($result == false || $repeat < 3);
            }
        }
        $data = array(
            'is_paid' => $order['iPayStatus'] == Lib_Constants::PAY_STATUS_PAID ? 'true' : 'false',
            'is_refunded' => $order['iRefundingAmount'] > 0 || $order['iRefundedAmount'] > 0 ? 'true' : 'false'
        );
        $this->output_json(Lib_Errors::SUCC, $data);
    }

    /**
     * 调用微信统一下单
     *
     * @param $order_id
     * @param $data
     */
    protected function wx_unified_order($order_id, $data = null)
    {
        $log_label = 'payment wx_unified_order';

        $this->load->service('pay_service');
        $params = array(
            'order_id' => $order_id,
            'pay_agent_type' => $this->order_type
        );
        if ($data && is_array($data)) {
            $params = array_merge($params, $data);
        }

        $this->load->service('pay_service');
        $result = $this->pay_service->wapUrlAction($this->user['uin'], $params);
        if (empty($result['redirectUrl'])) {
            $this->log->error($log_label, 'wapUrlAction failed', array('result'=>$result,'params'=>$params));
            $this->render_result(Lib_Errors::SVR_ERR, $result);
        }

        $package = $result['redirectUrl'];
        parse_str($package, $params);

        require_once APPPATH.'third_party/payagent/util/WxPayPubHelper.php';
        $jsApi = new JsApi_pub();
        $openid = $this->user['openid'];
        $unifiedOrder = new UnifiedOrder_pub();


        $wx_conf = config_item('weixinPay');

        if(!empty($wx_conf['SUB_APPID']) && !empty($wx_conf['SUB_MCHID'])) { //子账号收款
            $unifiedOrder->setParameter("sub_openid",$openid);//用户openid
        } else {
            $unifiedOrder->setParameter("openid",$openid);//用户openid
        }

        $unifiedOrder->setParameter("body", mb_convert_encoding($params['body'],"UTF-8","GBK"));
        $unifiedOrder->setParameter("out_trade_no", $order_id);
        $unifiedOrder->setParameter("total_fee", $params['total_fee']);
        $unifiedOrder->setParameter("notify_url", $params['notify_url']);
        $unifiedOrder->setParameter("trade_type", "JSAPI");
        $unifiedOrder->setParameter("attach", encrypt(array($order_id, $params['total_fee'], $openid), self::PAY_ARRACH_KEY));

        $prepay_id = $unifiedOrder->getPrepayId();
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = json_decode($jsApi->getParameters(), true);
        $this->log->notice($log_label, 'wxpay jssdk string', array('jsApiParameters'=>$jsApiParameters,'notify_url'=>$params['notify_url']));
        $this->render_result(Lib_Errors::SUCC, array(
            'debug' => 0,
            'order_id' => $order_id,
            'jsapicall' => $jsApiParameters,
            'ispaid' => Lib_Constants::PAY_STATUS_UNPAID
        ));
    }
}