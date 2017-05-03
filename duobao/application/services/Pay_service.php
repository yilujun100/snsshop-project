<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 支付接口
 * Class Pay_service
 */

require_once APPPATH.'third_party/payagent/TenpayFacade.php';
require_once APPPATH.'third_party/payagent/AlipayFacade.php';
require_once APPPATH.'third_party/payagent/WeiXinFacade.php';
class Pay_service extends  MY_Service
{

    /**
     * 获取wap支付跳转url
     */
    public function wapUrlAction($uin, $params)
    {
        /**
         * 活动相关订单，由将没有的参数补齐
         */
        $this->load->service('order_service');
        $order_type = $this->order_service->check_order_type($params['order_id']);
        $order = $this->order_service->get_order_detail($uin,$params['order_id']);
        switch($order_type){
            case Lib_Constants::ORDER_TYPE_ACTIVE:
            case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                $order = $order['merage_order'];
                $order['iPayAmount'] = $order['iAmount'];
                $order['sOrderId'] = $order['sMergeOrderId'];
                break;

            case Lib_Constants::ORDER_TYPE_COUPON:
                $order['iPayAmount'] = $order['iTotalPrice'];
                break;

            case Lib_Constants::ORDER_TYPE_BAG:
                $order['iPayAmount'] = $order['iAmount'];
                break;

            case Lib_Constants::ORDER_TYPE_GROUPON:
                break;

            default:
                return Lib_Errors::ORDER_TYPE_NOT_FOUND;
        }

        $this->log->notice('PayService','wapUrl trace log | params['.json_encode($params).'] | '.__METHOD__);
//        $order['iPayAmount'] = 1; //测试的时候支付一分钱
        $order['iPayAgentType'] = $params['pay_agent_type'];

        $groupon = new stdClass();
        if (! empty($params['goodsName'])) {
            $groupon->grouponNameTip = $params['goodsName'];
        } else {
            $groupon->grouponNameTip = '微团购夺宝券';
        }
        $groupon->beginTime = strtotime('2016-2-1');
        $groupon->endTime = strtotime('2026-2-1');

        $redirectUrl = $this->get_pay_package($order, $groupon, array(), array());

        $retData = array(
            'redirectUrl' => $redirectUrl,
            'payAgentType' => $params['pay_agent_type'],
        );
        return $retData;
    }




    /**
     * 生成支付请求URL, 设定用户浏览器跳转到支付页面
     * @param       $order    用户订单对象
     * @param     $groupon  团购对象
     * @param array      $attachArr          自定义参数数组(请求来源跟踪,统计等)
     * @param array      $extParamArr        扩展参数数组(目前未使用)
     * @return string 跳转支付url
     * @see MAPI_Controller_Payment::tenpayNotifyAction()
     * @see MAPI_Controller_Payment::tenpayCallBackAction()
     */
    public function get_pay_package($order, $groupon, $attachArr, $extParamArr)
    {
        $pay_partner = config_item('pay_partner');
        $arrPartner = isset($pay_partner[Lib_Constants::ORDER_PAY_TYPE_WX]) ? $pay_partner[Lib_Constants::ORDER_PAY_TYPE_WX] : array();
        if (empty($arrPartner))
        {
            $this->log->error('PayService','wapUrl trace log | getPartnerInfoArr(weixinpay) failed | '.__METHOD__);
            return false;
        }

        //不同的客户端id，传给支付后同步回跳通知页面
        $attachArr['clientId'] = 1;

        //设置回调CGI, $notifyUrl处理异步回调, $callbackUrl处理页面跳转回调
        $notifyUrl = config_item('pay_notify');

        //标题和简述, 如果出现因非法字符校验失败, 套一层urlencode()
        $subject = cn_substr($groupon->grouponNameTip, 25);

        //自定义参数
        $attachStr = urlencode(http_build_query($attachArr));
        $userIP = get_ip();

        /*file_put_contents('/tmp/weixinpay.log', "\npay:\n" . var_export(array($arrPartner['iPartnerId'], $arrPartner['sKey'],
                $order->orderId, $order->totalPrice, $subject,
                'WX', '', $notifyUrl, $attachStr,
                $userIP, $groupon->beginTime, $groupon->endTime), true)."\n", FILE_APPEND);*/
        //拼接跳转WAP支付的url
        $tenpayFacade = new TenpayFacade();
        $url = $tenpayFacade->getPayGateURL($arrPartner, 0, '',
            $order['sOrderId'], $order['iPayAmount'], $subject,
            'WX', '', $notifyUrl, $attachStr,
            $userIP, $groupon->beginTime, $groupon->endTime);
        $package = (string) parse_url($url, PHP_URL_QUERY);

        $this->log->notice('PayService',sprintf("weixinpay_pay %s %d %d %s", $order['sOrderId'], $order['iPayAgentType'], $order['iUin'], $package).' | '.__METHOD__);
        return $package;
    }


    /**
     * 查询订单状态
     * @param $order_id
     * @param $trans_id
     * @return array
     */
    public function query_order_info($order_id,$trans_id)
    {
        $weixin_facade = new WeiXinFacade();

        return $result = $weixin_facade->queryOrderPayInfo($order_id,$trans_id);
    }


    //判断WX是否支付
    public function is_weixin_paid($arr_info)
    {
        $weixin_facade = new WeiXinFacade();

        return $result = $weixin_facade->isTradePaid($arr_info);
    }

    public function skip_order_pay($order_info)
    {
        //判断参数是否已经都设置
        if(!isset($order_info['order_id']) || !isset($order_info['callback_url']) || !isset($order_info['pay_agent']))
        {
            return array('type'=>'output','code'=>Lib_Errors::PARAMETER_ERR,'error_msg'=>'');
        }
        $order_id = (string)$order_info['order_id'];
        $pay_redirect = empty($order_info['callback_url'])?$this->config->item('pay_redirect'):$order_info['callback_url'];

        if(empty($order_id) || empty($this->user['uin'])){
            $this->log->error('Payment','params error | order_id['.$order_id.'] | user['.json_encode($this->user).']');
            return array('type'=>'output','code'=>Lib_Errors::PARAMETER_ERR,'error_msg'=>'');
        }

        $this->load->service('order_service');
        $pay_agent = $this->order_service->check_order_type($order_id);
        if(empty($pay_agent)){
            $this->log->error('Payment','order type not find | order_id['.$order_id.'] | '.__METHOD__);
            return array('type'=>'output','code'=>Lib_Errors::ORDER_TYPE_NOT_FOUND,'error_msg'=>'');
        }

        $order = $order_detail = $this->order_service->get_order_detail($this->user['uin'],$order_id);
        if(empty($order_detail) || !is_array($order_detail)){
            $this->log->error('Payment','order not find | order_id['.$order_id.'] | '.__METHOD__);
            return array('type'=>'output','code'=>Lib_Errors::ORDER_NOT_FOUND,'error_msg'=>'');
        }

        $pay_state = $order_detail['iPayStatus'];
        //是否已经支付
        if($pay_state == Lib_Constants::PAY_STATUS_PAID){
            return array('type'=>'output','code'=>Lib_Errors::PAYED,'error_msg'=>'');
        }


        $order_type = $this->order_service->check_order_type($order_id);
        $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
        if(empty($order)){
            $this->log->error('Payment','order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
            return array('type'=>'output','code'=>Lib_Errors::ORDER_NOT_FOUND,'error_msg'=>'');
        }

        $goods = array();
        $pay_disabled = 0;
        $pay_coupon = 0;
        switch($order_type){
            case Lib_Constants::ORDER_TYPE_ACTIVE:
            case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                if(empty($order['merage_order']) || empty($order['active_order'])){
                    $this->log->error('Payment','active order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
                    return array('type'=>'output','code'=>Lib_Errors::ORDER_NOT_FOUND,'error_msg'=>'');
                }
                $this->load->model('active_peroid_model');
                foreach($order['active_order'] as $o){
                    $detail = $this->active_peroid_model->get_active_row($o['iActId'],$o['iPeroid']);
                    $goods[] = array(
                        'goods_name' => $detail['sGoodsName'],
                        'goods_count' => $o['iCount'],
                        'goods_price' => $o['iUnitPrice']
                    );
                }
                $order = $order['merage_order'];
                $order['sOrderId'] = $order['sMergeOrderId'];

                $this->load->model('user_ext_model');
                $user_ext = $this->user_ext_model->get_user_ext_info($this->user['uin']);
                $pay_coupon = $user_ext['coupon']-ceil($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE) >= 0 ? ceil($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE) : ceil($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE);
                $user_ext = empty($user_ext) ? array() : $user_ext;
                $disabled = empty($user_ext) || $pay_coupon < 0 ? true : false;
                $pay_disabled = true;
                break;

            case Lib_Constants::ORDER_TYPE_COUPON:
                $pay_disabled = false;
                $disabled = false;
                $goods = array(
                    array(
                        'goods_name' => '[夺宝券]'.$order['iCount'].'张',
                        'goods_count' => $order['iCount'],
                        'goods_price' => $order['iTotalPrice']
                    )
                );
                break;

            case Lib_Constants::ORDER_TYPE_BAG:
                $this->load->model('user_ext_model');
                $user_ext = $this->user_ext_model->get_user_ext_info($this->user['uin']);
                $pay_coupon = $user_ext['coupon']-ceil($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE) >= 0 ? ceil($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE) : $user_ext['coupon'];
                $user_ext = empty($user_ext) ? array() : $user_ext;
                $disabled = false;
                $pay_disabled = false;
                $goods = array(
                    array(
                        'goods_name' => '[福袋]'.$order['iCount'].'张夺宝券',
                        'goods_count' => $order['iCount'],
                        'goods_price' => $order['iTotalPrice']
                    )
                );
                break;
        }

        //获取充值夺宝券配制
        $this->load->model('recharge_activity_model');
        $conf = $this->recharge_activity_model->get_activity_conf();
        if(!empty($conf)){
            $conf['sConf'] = json_decode($conf['sConf'],true);
            $activity = 1;
        }else{
            $conf = array();
            $conf['sConf'] = Lib_Constants::$recharge_activity_config;
            $activity = 0;
        }

        $pay_url = $this->config->item('pay_url');


        $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
        $pay_agent = $order_info['pay_agent'];
        if(empty($order)){
            $this->log->error('Payment','order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
            return array('type'=>'render','code'=>Lib_Errors::ORDER_NOT_FOUND,'error_msg'=>'pay_result');
        }elseif($order['iPayStatus'] == Lib_Constants::PAY_STATUS_PAID){
            return array('type'=>'render_result','code'=>Lib_Errors::PAYED,'error_msg'=>'');
        }

        $order_type = $this->order_service->check_order_type($order_id);
        if($pay_disabled){ //禁用了支付方式，则全是用券支付
            $this->load->model('user_ext_model');
            $user_ext = $this->user_ext_model->get_user_ext_info($this->user['uin']);
            if(empty($pay_coupon)){
                return array('type'=>'render_result','code'=>Lib_Errors::PARAMETER_ERR,'error_msg'=>'');
            }elseif($user_ext['coupon']<$pay_coupon){
                return array('type'=>'render_result','code'=>Lib_Errors::ACTIVE_COUPON_NOT_STOCK,'error_msg'=>'');
            }

            //检查是否为全部支付
            if($order_type == Lib_Constants::ORDER_TYPE_ACTIVE || $order_type == Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE){
                $order = $order['merage_order'];
            }
            if($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE != $pay_coupon){
                return array('type'=>'render_result','code'=>Lib_Errors::PARAMETER_ERR,'error_msg'=>'');
            }

            //减用户券数
            $this->load->service('awards_service');
            $reduct_result = $this->awards_service->reduce_coupon_action(
                $this->user['uin'],
                $order_type == Lib_Constants::ORDER_TYPE_ACTIVE ? Lib_Constants::BUY_DUOBAO_ACTIVE : Lib_Constants::EXCHANGE_DUOBAO_ACTIVE,
                $pay_coupon,
                Lib_Constants::PLATFORM_WX,
                array('order_id'=>$order_id)
            );
            if(!is_array($reduct_result)){
                return array('type'=>'render_result','code'=>Lib_Errors::SVR_ERR,'error_msg'=>'');
            }

            //更新订单
            $this->load->service('order_service');
            $return  = false;
            switch($order_type){
                case Lib_Constants::ORDER_TYPE_ACTIVE:
                case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                    $this->load->model('active_merage_order_model');
                    if(!$this->active_merage_order_model->update_row(array('iCoupon'=>$pay_coupon),array('iUin'=>$this->user['uin'],'sMergeOrderId' => $order_id))){
                        return array('type'=>'render_result','code'=>Lib_Errors::SVR_ERR,'error_msg'=>'');
                    }
                    $return = $this->order_service->set_succ_active_order($this->user['uin'],$order_id,$order_id);
                    break;

                case Lib_Constants::ORDER_TYPE_BAG:
                    $return = $this->order_service->set_succ_bag_order($this->user['uin'],$order_id,array('trans_id'=>$order_id));
                    break;
            }

            if(!is_numeric($return) && $return){
                $pay_state = Lib_Constants::PAY_STATUS_PAID;
            }
            return array('type'=>'render_result','code'=>Lib_Errors::SUCC,'error_msg'=>array(
                'order_id' => $order_id,
                'ispaid' => $pay_state,
                'is_skip'   =>  1,
                'pay_redirect'  =>  $pay_redirect
            ));
        }else{ //选择了支付方式
            //通过参数发起service请求，返回对应的getPayURL及相关的参数package
            $this->load->service('pay_service');
            $wx_conf = config_item('weixinPay');
            $params = array(
                'order_id' => $order_id,
                'pay_agent_type' => $order_type,
            );
            $result = $this->pay_service->wapUrlAction($this->user['uin'],$params);

            //生成WX支付的JSSDK所需要的参数
            $package = $result['redirectUrl'];
            parse_str($package, $params);


            require_once APPPATH.'third_party/payagent/util/WxPayPubHelper.php';
            //使用jsapi接口
            $jsApi = new JsApi_pub();

            $openid = $this->user['openid'];

            //=========步骤2：使用统一支付接口，获取prepay_id============
            //使用统一支付接口
            $unifiedOrder = new UnifiedOrder_pub();

            //设置统一支付接口参数
            //设置必填参数
            //appid已填,商户无需重复填写
            //mch_id已填,商户无需重复填写
            //noncestr已填,商户无需重复填写
            //spbill_create_ip已填,商户无需重复填写
            //sign已填,商户无需重复填写
            if(!empty($wx_conf['SUB_APPID']) && !empty($wx_conf['SUB_MCHID'])) { //子账号收款
                $unifiedOrder->setParameter("sub_openid",$openid);//用户openid
            } else {
                $unifiedOrder->setParameter("openid",$openid);//用户openid
            }

            $unifiedOrder->setParameter("body",iconv('GBK', 'UTF-8', $params['body']));//商品描述
            //自定义订单号，此处仅作举例
            $timeStamp = time();
            $PAY_ARRACH_KEY = '29os@^k(k~-2*jd';
            //$out_trade_no = $wx_conf['APPID']."$timeStamp";
            $unifiedOrder->setParameter("out_trade_no",$this->order_id);//商户订单号
            $unifiedOrder->setParameter("total_fee",$params['total_fee']);//总金额
            $unifiedOrder->setParameter("notify_url",$params['notify_url']);//通知地址
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
            $unifiedOrder->setParameter("attach",encrypt(array($order_id,$params['total_fee'],$openid),$PAY_ARRACH_KEY));//用于验证非正常通知回调
            //非必填参数，商户可根据实际情况选填
            //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
            //$unifiedOrder->setParameter("device_info","XXXX");//设备号
            //$unifiedOrder->setParameter("attach","XXXX");//附加数据
            //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
            //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
            //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
            //$unifiedOrder->setParameter("openid","XXXX");//用户标识
            //$unifiedOrder->setParameter("product_id","XXXX");//商品ID

            $prepay_id = $unifiedOrder->getPrepayId();
            //=========步骤3：使用jsapi调起支付============
            $jsApi->setPrepayId($prepay_id);

            $jsApiParameters = $jsApi->getParameters();

            //-----------------------------------------------
            $this->log->notice('Payment','wxpay jssdk string | params['.$jsApiParameters.'] | '.__METHOD__);
            return array('type'=>'render_result','code'=>Lib_Errors::SUCC,'error_msg'=>array(
                'debug' => 1,
                'order_id' => $this->order_id,
                'jsapicall' => $jsApiParameters,
                'ispaid' => $this->pay_state,
                'is_skip'   =>  1,
                'pay_redirect'  =>  $pay_redirect
            ));
        }
    }
}