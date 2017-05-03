<?php
require_once(APPPATH.'controllers/duogebao/Base.php');
require_once(APPPATH.'controllers/duogebao/Common.php');

class Pay extends Duogebao_Base
{
    const ORDER_RESULT_AD_POSITION = 3;
    const PAY_STATUS_TIME_OUT = 86400;//支付时间未超过24小时，则会主动查询订单
    const PAY_ARRACH_KEY = '29os@^k(k~-2*jd';

    protected $order_id = null;
    protected $order_type = null;
    protected $pay_agent = null;
    protected $pay_redirect = null;
    protected $pay_state = Lib_Constants::PAY_STATUS_UNPAID;
    protected $order;
    protected $return_code = Lib_Errors::SUCC;
    protected $need_login_methods = array('cart_buy','cashier','index','ajax_pay','ajax_get_order','ajax_buy_now','result','buy_coupon','active_buy','pull');

    protected $disable_layout = true;

    protected $result_url;

    public function __construct()
    {
        parent::__construct();
        $this->config->load('pay');
        $this->load->service('order_service');
    }

    //购买购物车物品
    public function cart_buy()
    {
        $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        //$user_ext = array('uin','coupon','his_gift_coupon','his_coupon','lucky_bag','his_lucky_bag','score','free_coupon','free_time','his_used_score','sign_time');
        //$this->user = array('uin','nick_name','head_img','contact_state','openid','province','city','country');
        $this->assign('user_ext', $user_ext);
        $this->assign('user', $this->user);

        $active_arr = $this->get_post('peroid_str');
        if(empty($active_arr)){
            show_error('参数错误！');
        }
        $active = $data = $qty_arr = array();
        foreach($active_arr as $key => $peroid_str){
            list($act_id,$peroid) = period_code_decode($peroid_str);
            $active[$act_id] = $peroid;
            $qty_arr[$act_id] = $this->get_post($peroid_str.'_num');
        }

        //查询所对应的夺宝单
        $total = 0;
        $in_str = implode(',',array_keys($active));
        $active_list = $this->get_api('active_currect_list',array('in_str'=>$in_str));
        $active_list = $active_list['retData'];
        $check = true;
        foreach($active_list as $val){
            if(!isset($active[$val['iActId']]) || $val['iPeroid'] != $active[$val['iActId']]){
                $check = false;
            }
            $total += $qty_arr[$val['iActId']]*$val['iCodePrice'];
        }
        if(count($active) != count($active_list) || !$check){
            $this->log->error('Active',Lib_Errors::ACTIVE_OVER_TIME.' | active['.json_encode($active).'] | active_list['.json_encode($active_list).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
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
        $this->assign('conf',$conf);
        $url = $this->get_post('callback_url',$this->config->item('pay_result_url'));
        $this->assign('result_url',$url);
        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('total',$total);
        $this->assign('qty_arr',$qty_arr);
        $this->assign('order_callback',$this->config->item('pay_result_url'));
        $this->render(array('active'=>$active_list));
    }

    /**
     * 发福袋
     */
    public function pull()
    {
        $this->set_wx_share('luckybag');
        $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        $this->assign('user_ext', $user_ext);
        $this->assign('user', $this->user);
        $this->render();
    }

    /**
     * 购买夺宝券
     */
    public function buy_coupon()
    {
        $this->set_wx_share('buy_coupon');
        $val = $this->get_post('val');

        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('default',$val);
        $this->assign('callback_url',gen_uri('/coupon/pay_result'));

        $conf = $this->get_api('activity_conf');
        $conf = $conf['retCode'] == 0 ? $conf['retData'] : array();
        $this->render(array('conf'=>$conf));
    }

    //购买夺宝活动
    public function active_buy()
    {
        $this->set_wx_share('active_buy');
        $this->assign('user', $this->user);
        $peroid_str = $this->get_post('peroid_str');
        $back_url = $this->get_post('back_url',$this->config->item('pay_result_url'));
        if(empty($peroid_str)){
            show_error('夺宝活动参数错误！');
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);
        $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        if($detail['retCode'] != 0){
            $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
            show_error('查询夺宝活动失败！');
        }
        $detail = $detail['retData'];

        //判断是否可购买
        if($detail['iIsCurrent'] != Lib_Constants::ACTIVE_CURRENT_PEROID || $detail['iLotState'] != Lib_Constants::ACTIVE_STATE_DEFAULT || $detail['iProcess'] == 100){
            $this->log->notice('Active','active under stock | peroid['.$peroid_str.'] | '.__METHOD__);
            $this->assign('stock_out',1);
        }else{
            $this->assign('stock_out',0);
        }
        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));

        $this->assign('peroid_str',$peroid_str);
        $this->assign('stock',$detail['iLotCount']-$detail['iSoldCount']);
        $this->assign('detail',$detail);

        //用户夺宝券
        $coupon = $this->get_api('user_ext_info',array('uin'=>$this->user['uin']));
        $this->assign('coupon',$coupon['retCode'] == 0 ? $coupon['retData']['coupon'] : 0);

        $this->assign('order_callback',$back_url);
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
        $this->assign('conf',$conf);
        $this->render(array(),'pay/active_buy');
    }

    /**
     * 收银员
     */
    public function cashier()
    {
        $log_label = 'payment pay | cashier';

        $this->order_type = $this->get('order_type', 0);

        $this->result_url = $this->get('result_url', gen_uri('/result','','payment'));

        if (!in_array($this->order_type, array_keys(Lib_Constants::$order_type))) {
            $this->log->error($log_label, 'order type error', array('order_type'=>$this->order_type, 'user'=>$this->user));
            show_error(Lib_Errors::get_error(Lib_Errors::ORDER_TYPE_NOT_FOUND));
        }
        $real_cashier =  'cashier_' . $this->order_type;
        if (! is_callable(array($this, $real_cashier))) {
            $this->log->error($log_label, 'cashier undefined', array('order_type'=>$this->order_type, 'user'=>$this->user));
            show_error(Lib_Errors::get_error(Lib_Errors::ORDER_CASHIER_UNDEFINED));
        }

        $this->assign('order_type', $this->order_type);
        $this->assign('result_url', $this->result_url);

        $this->router->set_method($real_cashier);
        $this->{$real_cashier}();
    }

    /**
     * 拼团订单收银员
     */
    protected function cashier_6()
    {
        $log_label = 'payment pay | cashier_6';

        $buy_type = $this->get('buy_type', 0);
        if (! $buy_type ||
            ! in_array($buy_type, array_keys(Lib_Constants::$groupon_order))) {
            $this->log->error($log_label, 'error buy_type', array('buy_type'=>$buy_type, 'user'=>$this->user));
            show_error(Lib_Errors::get_error(Lib_Errors::GROUPON_ORDER_TYPE_ERROR));
        }
        $this->assign('buy_type', $buy_type);
        $this->assign('buy_type_desc', Lib_Constants::$groupon_order[$buy_type]);

        $order_id = $this->get('order_id', 0);
        if ($order_id) {
            $this->assign('order_id', $order_id);
            $params = array(
                'order_id' => $order_id
            );
            $ret = $this->get_api('pay_later_data', $params);
            if (Lib_Errors::SUCC != $ret['retCode']) {
                $this->log->error($log_label, 'pay_later_data error', array('params'=>$params,'ret'=>$ret,'user'=>$this->user));
                show_error($ret['retMsg']);
            }
            $this->assign('groupon', $ret['retData']['groupon']);
            $this->assign('spec', $ret['retData']['spec']);
            $this->assign('diy', $ret['retData']['diy']);
            $order = $ret['retData']['order'];
            $params = array('uin'=>$order['iUin']);
            $ret = $this->get_api('addr_list', $params);
            if (Lib_Errors::SUCC == $ret['retCode']) {
                $this->assign('address', $ret['retData']);
            }
        } else {
            $groupon_id = $this->get('groupon_id', 0);
            $spec_id = $this->get('spec_id', 0);
            $diy_id = $this->get('diy_id', 0);
            $params = array(
                'buy_type' => $buy_type,
                'groupon_id' => $groupon_id,
                'spec_id' => $spec_id,
                'diy_id' => $diy_id,
            );
            $ret = $this->uin_api('create_groupon_order_check', $params);
            if (Lib_Errors::SUCC != $ret['retCode']) {
                $this->log->error($log_label, 'create_groupon_order_check error', array('params'=>$params, 'user'=>$this->user));
                show_error($ret['retMsg']);
            }

            $this->assign('groupon', $ret['retData']['groupon']);
            $this->assign('spec', $ret['retData']['spec']);
            $this->assign('diy', $ret['retData']['diy']);

            $ret = $this->uin_api('addr_list');
            if (Lib_Errors::SUCC == $ret['retCode']) {
                $this->assign('address', $ret['retData']);
            }
        }

        $this->render();
    }

    /**
     * 跳转至支付结果处理页
     *
     * @param int $code
     */
    protected function redirect_result($code = Lib_Errors::SUCC)
    {
        $this->return_code = $code;
        $query_arr = array(
            'return_code' => $this->return_code,
            'pay_state' => $this->return_code == Lib_Errors::SUCC ? 'SUCCESS' : 'FAILURE',
            'order_id' => $this->order_id
        );
        $separator = false === strpos($this->result_url, '?') ? '?' : '&';
        redirect($this->result_url . $separator . http_build_query($query_arr));
    }

    /**
     * 收银台首页
     * 统一支付，优点：集中管理支付方式，支付业务的独立，支付授权ETC
     */
    public function index()
    {
        $this->order_id = $this->get_post('order_id','');
        $this->pay_redirect = $this->get_post('callback_url',$this->config->item('pay_redirect'));

        if(empty($this->order_id) || empty($this->user)){
            $this->log->error('Payment','params error | order_id['.$this->order_id.'] | user['.json_encode($this->user).']');
            $this->output(Lib_Errors::PARAMETER_ERR);
        }

        $this->pay_agent = $this->order_service->check_order_type($this->order_id);
        if(empty($this->pay_agent)){
            $this->log->error('Payment','order type not find | order_id['.$this->order_id.'] | '.__METHOD__);
            $this->output(Lib_Errors::ORDER_TYPE_NOT_FOUND);
        }

        $this->order = $order_detail = $this->order_service->get_order_detail($this->user['uin'],$this->order_id);
        if(empty($order_detail) || !is_array($order_detail)){
            $this->log->error('Payment','order not find | order_id['.$this->order_id.'] | '.__METHOD__);
            $this->output(Lib_Errors::ORDER_NOT_FOUND);
        }

        $this->pay_state = $order_detail['iPayStatus'];
        //是否已经支付
        if($this->pay_state == Lib_Constants::PAY_STATUS_PAID){
            $this->output(Lib_Errors::PAYED);
        }


        $order_id = $this->order_id;
        $this->order_type = $this->order_service->check_order_type($order_id);
        $this->order = $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
        if(empty($order)){
            $this->log->error('Payment','order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
            $this->output(Lib_Errors::ORDER_NOT_FOUND);
        }

        $goods = array();
        switch($this->order_type){
            case Lib_Constants::ORDER_TYPE_ACTIVE:
            case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                if(empty($order['merage_order']) || empty($order['active_order'])){
                    $this->log->error('Payment','active order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
                    $this->output(Lib_Errors::ORDER_NOT_FOUND);
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
                $this->assign('user_ext',empty($user_ext) ? array() : $user_ext);
                $this->assign('disabled',empty($user_ext) || $pay_coupon < 0 ? true : false);
                $this->assign('pay_coupon',$pay_coupon);
                $this->assign('pay_disabled',true);
                break;

            case Lib_Constants::ORDER_TYPE_COUPON:
                $this->assign('pay_disabled',false);
                $this->assign('disabled',false);
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
                $this->assign('user_ext',empty($user_ext) ? array() : $user_ext);
                $this->assign('disabled',false);
                $this->assign('pay_coupon',$pay_coupon);
                $this->assign('pay_disabled',false);
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
        $this->assign('conf',$conf);
        $this->assign('is_activity',$activity);

        $this->assign('goods',$goods);
        $this->assign('order_type',$this->order_type);
        $this->assign('pay_redirect',$this->pay_redirect);
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->render(array('order'=>$order),'index');
    }

    /**
     * 提交支付，并且验证相关参数,
     * @return  成功则返回wx jsapi接口返所需参数
     */
    public function ajax_pay()
    {
        $this->order_id = $order_id = $this->get_post('order_id');
        $pay_disabled = $this->get_post('pay_disabled',0);//是否禁用了支付方式
        $paycoupon = $this->get_post('paycoupon',0);//需要支付的券数
        $this->order = $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
        $this->pay_agent = $this->get_post('payagent');
        if(empty($order)){
            $this->log->error('Payment','order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
            $this->render(Lib_Errors::ORDER_NOT_FOUND,'pay_result');
        }elseif($order['iPayStatus'] == Lib_Constants::PAY_STATUS_PAID){
            $this->render_result(Lib_Errors::PAYED);
        }

        $this->order_type = $this->order_service->check_order_type($order_id);
        if($pay_disabled){ //禁用了支付方式，则全是用券支付
            $this->load->model('user_ext_model');
            $user_ext = $this->user_ext_model->get_user_ext_info($this->user['uin']);
            if(empty($paycoupon)){
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }elseif($user_ext['coupon']<$paycoupon){
                $this->render_result(Lib_Errors::ACTIVE_COUPON_NOT_STOCK);
            }

            //检查是否为全部支付
            if($this->order_type == Lib_Constants::ORDER_TYPE_ACTIVE || $this->order_type == Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE){
                $order = $order['merage_order'];
            }
            if($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE != $paycoupon){
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            //减用户券数
            $this->load->service('awards_service');
            $reduct_result = $this->awards_service->reduce_coupon_action(
                $this->user['uin'],
                $this->order_type == Lib_Constants::ORDER_TYPE_ACTIVE ? Lib_Constants::BUY_DUOBAO_ACTIVE : Lib_Constants::EXCHANGE_DUOBAO_ACTIVE,
                $paycoupon,
                Lib_Constants::PLATFORM_WX,
                array('order_id'=>$order_id)
            );
            if(!is_array($reduct_result)){
                $this->render_result(Lib_Errors::SVR_ERR);
            }

            //更新订单
            $this->load->service('order_service');
            $return  = false;
            switch($this->order_type){
                case Lib_Constants::ORDER_TYPE_ACTIVE:
                case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                    $this->load->model('active_merage_order_model');
                    if(!$this->active_merage_order_model->update_row(array('iCoupon'=>$paycoupon),array('iUin'=>$this->user['uin'],'sMergeOrderId' => $order_id))){
                        $this->render_result(Lib_Errors::SVR_ERR);
                    }
                    $return = $this->order_service->set_succ_active_order($this->user['uin'],$order_id,$order_id);
                    break;

                case Lib_Constants::ORDER_TYPE_BAG:
                    $return = $this->order_service->set_succ_bag_order($this->user['uin'],$order_id,array('trans_id'=>$order_id));
                    break;
            }
            if(!is_numeric($return) && $return){
                $this->pay_state = Lib_Constants::PAY_STATUS_PAID;
            }
            $this->render_result(Lib_Errors::SUCC,array(
                'order_id' => $this->order_id,
                'ispaid' => $this->pay_state
            ));
        }else{ //选择了支付方式
            //通过参数发起service请求，返回对应的getPayURL及相关的参数package
            $this->load->service('pay_service');
            $wx_conf = config_item('weixinPay');
            $params = array(
                'order_id' => $this->order_id,
                'pay_agent_type' => $this->order_type,
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
            //$out_trade_no = $wx_conf['APPID']."$timeStamp";
            $unifiedOrder->setParameter("out_trade_no",$this->order_id);//商户订单号
            $unifiedOrder->setParameter("total_fee",$params['total_fee']);//总金额
            $unifiedOrder->setParameter("notify_url",$params['notify_url']);//通知地址
            $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
            $unifiedOrder->setParameter("attach",encrypt(array($this->order_id,$params['total_fee'],$openid),self::PAY_ARRACH_KEY));//用于验证非正常通知回调
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
            $this->render_result(Lib_Errors::SUCC,array(
                'debug' => 1,
                'order_id' => $this->order_id,
                'jsapicall' => $jsApiParameters,
                'ispaid' => $this->pay_state
            ));
        }
    }


    /**
     * 查询订单状态
     * 如果未支付，在条件满足的情况下会通过接口查询第三方的支付状态
     */
    public function ajax_get_order()
    {
        $order_id = $this->get_post('order_id');
        if(empty($order_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->order = $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);

        if(empty($order)){
            $this->log->error('Payment','order is not find | order_id[ '.$order_id.'] | uin['.json_encode($this->user).'] | '.__METHOD__);
            $this->render(Lib_Errors::ORDER_NOT_FOUND,'pay_result');
        }

        //如果未支付并且创建订单未超过24小时，则再次查询支付订单状态
        if($order['iPayStatus'] == Lib_Constants::PAY_STATUS_UNPAID && $order['iCreateTime'] > (time() - self::PAY_STATUS_TIME_OUT)){
            $this->load->service('pay_service');
            $arrTradeInfo = $this->pay_service->query_order_info($order['sOrderId'],$order['sTransId']);
            if(!empty($arrTradeInfo) && $this->pay_service->is_weixin_paid($arrTradeInfo)){
                $repeat = 1;
                do{
                    $result = true;
                    $return = $this->set_succ_order($this->user['uin'],$arrTradeInfo);
                    if(is_numeric($return)){
                        $result =  false;
                    }else{
                        $this->order = $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
                    }
                    $repeat++;
                }while($result == false || $repeat < 3);
            }
        }

        $this->render_result(Lib_Errors::SUCC,array('total_money'=>price_format($order['iTotalPrice']),'present_count'=>$order['iPresentCount'],'coupon_count'=>$order['iCount'],'is_paid'=>$order['iPayStatus'] == Lib_Constants::PAY_STATUS_PAID ? 'true' : 'false'));
    }


    //立即购买夺宝券
    public function ajax_buy_now()
    {
        //生成订单
        $other = $this->get_post('numberOther',0);
        $activity_id = $this->get_post('is_activity',0);
        $stamps_number = $this->get_post('stampsNumber');

        //判断是否为活动
        $number = empty($stamps_number) ? 0 : $stamps_number;
        $id = $activity_id;
        if(empty($id)){
            $conf =  isset(Lib_Constants::$recharge_activity_config[$number]) ? Lib_Constants::$recharge_activity_config[$number] : array();
            if(empty($conf)) $this->render_result(Lib_Errors::SVR_ERR);
            $count = empty($other) ? $conf['c'] : $other;
        }else{
            $this->load->model('recharge_activity_model');
            $conf = $this->recharge_activity_model->get_activity_conf();
            if(empty($conf)) $this->render_result(Lib_Errors::SVR_ERR);

            $conf = json_decode($conf['sConf'],true);
            $count = empty($other) ? $conf[$number]['c'] : $other;
        }

        $total = $count * Lib_Constants::COUPON_UNIT_PRICE;
        $this->load->service('order_service');
        $result  = $this->order_service->create_coupon_order($this->user['uin'],$total,$count);
        if(!is_numeric($result) || $result < 0){
            $this->render_result($result,$result);
        }
        $this->order_id = $order_id = $result;
        $this->order_type = empty($result['iPayAgentType']) ? Lib_Constants::ORDER_PAY_TYPE_WX : $result['iPayAgentType'];

        //请求支付参数
        //通过参数发起service请求，返回对应的getPayURL及相关的参数package
        $this->load->service('pay_service');
        $wx_conf = config_item('weixinPay');
        $params = array(
            'order_id' => $this->order_id,
            'pay_agent_type' => $this->order_type,
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
        //$out_trade_no = $wx_conf['APPID']."$timeStamp";
        $unifiedOrder->setParameter("out_trade_no",$this->order_id);//商户订单号
        $unifiedOrder->setParameter("total_fee",$params['total_fee']);//总金额
        $unifiedOrder->setParameter("notify_url",$params['notify_url']);//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
        $unifiedOrder->setParameter("attach",encrypt(array($this->order_id,$params['total_fee'],$openid),self::PAY_ARRACH_KEY));//用于验证非正常通知回调
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
        $this->render_result(Lib_Errors::SUCC,array(
            'debug' => 1,
            'order_id' => $this->order_id,
            'jsapicall' => $jsApiParameters,
            'ispaid' => $this->pay_state
        ));
    }


    /**
     * 默认支付结果页
     */
    public function result()
    {
        $order_id = $this->get_post('order_id');
        $return_code = $this->get_post('return_code', Lib_Errors::SUCC);
        $msg = '支付成功';
        $order = array();
        if (empty($order_id)) {
            $msg = Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR);
        } else {
            $order = $this->order_service->get_order_detail($this->user['uin'],$order_id);
        }
        $order['order_id'] = $order_id;

        $banner_advert = $this->get_api('ad_list', array('position_id'=>self::ORDER_RESULT_AD_POSITION));
        $this->assign('banner_advert',empty($banner_advert['retData']) ? array() : $banner_advert['retData']);

        $where['orderby'] = 'hot';
        $where['ordertype'] = 'asc';
        $where['p_index'] = 1;
        $where['p_size'] = 3;
        $list = $this->get_api('active_search',$where);
        $list = $list['retCode'] == 0 ? $list['retData'] : array();

        $this->assign('active_list',$list['list']);

        $data = array(
            'retCode' => $return_code,
            'retMsg' => !empty($return_code) ? Lib_Errors::get_error($return_code) : $msg,
            'retData' => $order,
        );

        $this->render(array('data'=>$data), 'pay_result');
    }

    /**
     * wx支付通知
     * @return bool
     */
    public function notify()
    {
        $this->log->notice('Payment','nofity | '.__METHOD__);
        require_once APPPATH.'third_party/payagent/util/WxPayPubHelper.php';
        //微信支付v3
        //使用通用通知接口
        $notify = new Notify_pub();

        //存储微信的回调
        $xml = file_get_contents('php://input');

        if(!$xml) {
            $this->log->error('Payment','nofity | wx callback xml content is empty | '.__METHOD__);
            return $this->errors();
        }
        $this->log->notice('Payment','nofity | xml['.$xml.'] | '.__METHOD__);
        $notify->saveData($xml);
        if (empty($notify->data) || !is_array($notify->data)) {
            $this->log->error('Payment','nofity | wx callback xml data error | '.__METHOD__);
            return $this->errors();
        }

        $arrNotify = $notify->data;
        //file_put_contents("/tmp/weixinpay.debug.log", json_encode($arrNotify), FILE_APPEND);
        $this->log->notice('Payment','nofity | arrNotify['.json_encode($arrNotify).'] | '.__METHOD__);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            //file_put_contents("/tmp/weixinpay.debug.log", 'checkSing fail:'.$arrNotify['out_trade_no'], FILE_APPEND);
            $this->log->error('Payment','nofity | checkSing fail：'.$arrNotify['out_trade_no'].' | '.__METHOD__);
            return $this->errors();
        }

        if ($notify->data["return_code"] == "FAIL") {
            return $this->errors();
        }
        elseif($notify->data["result_code"] == "FAIL"){
            return $this->errors();
        }
        //file_put_contents("/tmp/weixinpay.debug.log", 'update db ready:'.$arrNotify['out_trade_no'], FILE_APPEND);
        $this->log->notice('Payment','nofity | update db ready['.$arrNotify['out_trade_no'].'] | '.__METHOD__);

        //商户自行增加处理流程,
        //例如：更新订单状态
        //例如：数据库操作
        //例如：推送支付完成信息
        $sTransactionId =  $arrNotify['transaction_id'];
        //$sParterId = substr($sTransactionId, 0,10);
        //$sTransactionId = str_replace($sParterId,WxPayConf_pub::MCHID,$sTransactionId);
        /*$ret = $this->setOrderPaid(self::RAW_RESPONSE, MAPI_Model_UserOrder::PAY_AGENT_WEIXIN,
            $sTransactionId, $arrNotify['total_fee'], $arrNotify['out_trade_no']);*/
        //根据不同类型操作
        $this->load->model('user_model');
        $wx_conf = config_item('weixinPay');
        if(!empty($wx_conf['SUB_APPID']) && !empty($wx_conf['SUB_MCHID'])) { //子账号收款
            $userInfo = $this->user_model->get_wx_user_by_openid($arrNotify['sub_openid']);
        } else {
            $userInfo = $this->user_model->get_wx_user_by_openid($arrNotify['openid']);
        }
        if(empty($userInfo)){
            $this->log->error('Payment','nofity | not find user | openid['.$arrNotify['openid'].'] | '.__METHOD__);
            return $this->errors();
        }

        return $return = $this->set_succ_order($userInfo['iUin'],$arrNotify);
    }


    /**
     * 成功回调
     * @param $uin
     * @param $arrNotify
     * @return bool
     */
    private function set_succ_order($uin,$arrNotify)
    {
        $this->load->service('order_service');
        $type = $this->order_service->check_order_type($arrNotify['out_trade_no']);
        $return  = false;
        switch($type){
            case Lib_Constants::ORDER_TYPE_ACTIVE:
            case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                $order = $this->order_service->get_order_detail($uin,$arrNotify['out_trade_no']);
                if(!is_array($order) || !isset($order['merage_order'])){
                    $this->log->notice('Payment','nofity | active order not find | data['.json_encode($arrNotify).'] | '.__METHOD__);
                    return $this->errors();
                }

                if($this->check_order($order['merage_order'],$arrNotify)){
                    $return = $this->order_service->set_succ_active_order($uin,$arrNotify['out_trade_no'],$arrNotify['transaction_id']);
                }
                break;

            case Lib_Constants::ORDER_TYPE_COUPON:
                $order = $this->order_service->get_order_detail($uin,$arrNotify['out_trade_no']);
                if(!is_array($order)){
                    $this->log->notice('Payment','nofity | coupon order not find | data['.json_encode($arrNotify).'] | '.__METHOD__);
                    return $this->errors();
                }

                $order['iAmount'] = $order['iTotalPrice'];
                if($this->check_order($order,$arrNotify)){
                    $return = $this->order_service->set_succ_coupon_order($uin,$arrNotify['out_trade_no'],$arrNotify['transaction_id']);
                }
                break;

            case Lib_Constants::ORDER_TYPE_BAG:
                $order = $this->order_service->get_order_detail($uin,$arrNotify['out_trade_no']);
                if(!is_array($order)){
                    $this->log->notice('Payment','nofity | bag order not find | data['.json_encode($arrNotify).'] | '.__METHOD__);
                    return $this->errors();
                }

                if($this->check_order($order,$arrNotify)){
                    $return = $this->order_service->set_succ_bag_order($uin,$arrNotify['out_trade_no'],array('trans_id'=>$arrNotify['transaction_id']));
                }
                break;

            case Lib_Constants::ORDER_TYPE_GROUPON:
                $log_label = 'payment pay | set_succ_order';
                $order = $this->order_service->get_order_detail($uin, $arrNotify['out_trade_no']);
                if( ! is_array($order)) {
                    $this->log->notice($log_label, 'groupon order not exists', array('code'=>$order,'uin'=>$uin,'notify'=>$arrNotify));
                    return $this->errors();
                }
                if (! $this->check_order($order, $arrNotify)) {
                    $this->log->notice($log_label, 'groupon order check error', array('uin'=>$uin,'notify'=>$arrNotify,'order'=>$order));
                }
                $return = $this->order_service->set_succ_groupon_order($uin, $arrNotify['out_trade_no'], $arrNotify['transaction_id']);
                break;

            default:
                return $this->errors();
        }

        if((!is_numeric($return) && $return) || Lib_Errors::SUCC == $return){
            $this->log->notice('Payment','nofity | set order succ | data['.json_encode($arrNotify).'] | '.__METHOD__);
        }else{
            $this->log->notice('Payment','nofity | set order failed：ordertype['.$type.'];data['.json_encode($arrNotify).'] | return['.$return.'] | '.__METHOD__);
        }

        return $return;
    }

    /**
     * 检查订单是否合法
     * @param $order
     * @param $arrNotify
     * @return bool
     */
    protected function check_order($order,$arrNotify)
    {
        //如果不是开发或者正式环境，则需要做金额等参数检验
        if(ENVIRONMENT != 'production') return true;

        if(!isset($arrNotify['attach']) || !$attach = decrypt($arrNotify['attach'],self::PAY_ARRACH_KEY)){
            $this->log->notice('Payment','check_order | attach params abnormal | order['.json_encode($order).'] | data['.json_encode($arrNotify).'] | '.__METHOD__);
            return false;
        }
        if($order['iAmount'] != $arrNotify['total_fee'] || $arrNotify['result_code'] != "SUCCESS" || $arrNotify['return_code'] != "SUCCESS"){
            $this->log->notice('Payment','check_order | order exception | order['.json_encode($order).'] | data['.json_encode($arrNotify).'] | '.__METHOD__);
            return false;
        }else{
            return true;
        }
    }


    /**
     * 统一跳到结果页
     */
    protected function output($code = Lib_Errors::SUCC)
    {
        $this->return_code = $code;
        $arr = array(
            'return_code' => $this->return_code,
            'pay_stata' => $this->return_code == Lib_Errors::SUCC ? 'SUCCESS' : 'FAILURE',
            'order_id' => $this->order_id
        );

        $url = strstr('?',$this->pay_redirect) === false ? $this->pay_redirect."?".http_build_query($arr) : $this->pay_redirect.'&'.http_build_query($arr);
        redirect($url);
        exit;
    }


    //错误返回
    protected function errors()
    {
        return false;
    }
}