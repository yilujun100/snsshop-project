<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 订单相关service
 * Class Order_service
 */
class Order_service extends  MY_Service
{
    const PAY_ARRACH_KEY = '29os@^k(k~-2*jd';

    public $ip;

    /**
     * @param $uin 用户
     * @param $act_type 夺宝活动类型：1系统活动,2用户自定义
     * @param $buy_type 购买类型：1夺宝，2夺宝券兑换商品
     * @param $coupon 购买所使用的夺宝券数量
     * @param $amount 购买所支付的金额
     * @param array $actives 活动数组，基中包含：act_id,peroid,goods_id,count
     * @return array|string
     */
    public function create_active_order($uin,$act_type,$buy_type,$coupon,$amount,$actives = array(),
                                        $plat_from = Lib_Constants::PLATFORM_WX,$pay_agent_type = Lib_Constants::ORDER_PAY_TYPE_WX,$share_uin = '')
    {
        if(empty($uin) || empty($actives) || empty($act_type) || empty($buy_type)){
            $this->log->error('OrderService','param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //判断订单类型
        if($buy_type != Lib_Constants::ORDER_TYPE_ACTIVE && $buy_type != Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE){
            $this->log->error('OrderService','order type error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }


        if($buy_type == Lib_Constants::ORDER_TYPE_ACTIVE){ //参与夺宝
            //详细参数检查
            $active_ids = array();
            foreach($actives as $active){
                if(!isset($active['act_id']) || !isset($active['peroid']) || !isset($active['goods_id']) || !isset($active['count'])){
                    $this->log->error('OrderService','active params error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
                    return Lib_Errors::PARAMETER_ERR;
                }
                $active_ids[] = $active['act_id'];
            }
            //查询对应的活动信息
            $this->load->model('active_peroid_model');
            if(!$active_list = $this->active_peroid_model->get_active_peroids('*',array('where_in'=>array('iActId',$active_ids),'iIsCurrent'=>1))){
                $this->log->error('OrderService','get active list fail | sql['.$this->active_cart_model->db->last_query().')] | params['.json_encode(func_get_args()).'] | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }

            $temp = array();
            foreach($active_list['list'] as $list){
                $temp[$list['iActId']] = $list;
            }
            $active_list = $temp;
            unset($temp);


            //活动参数检查
            $total_price = 0;
            foreach($actives as $active){
                $item = isset($active_list[$active['act_id']]) ? $active_list[$active['act_id']] : array();
                if(empty($item)){
                    $this->log->error('OrderService','active not exist | params['.json_encode($active).'] | '.__METHOD__);
                    return Lib_Errors::ACTIVE_NOT_FOUND;
                }elseif($item['iPeroid'] != $active['peroid']){ //检查期数是否一致
                    $this->log->error('OrderService','active not this current peroid | params['.json_encode($active).'] | params['.json_encode($item).'] | '.__METHOD__);
                    return Lib_Errors::ACTIVE_OVER_TIME;
                }elseif($item['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){ //判断当期状态
                    $this->log->error('OrderService','active lot_state is not right | params['.json_encode($active).'] | params['.json_encode($item).'] | '.__METHOD__);
                    return Lib_Errors::ACTIVE_IS_NOT_LOTTEY;
                }elseif($item['iLotCount'] - $item['iSoldCount'] < $active['count']){ //检查当期库存
                    $this->log->error('OrderService','current active stock out | params['.json_encode($active).'] | params['.json_encode($item).'] | '.__METHOD__);
                    return Lib_Errors::ACTIVE_NOT_STOCK;
                }elseif($item['iBuyCount'] < $active['count']){ //检查单次购买上限
                    $this->log->error('OrderService','users to buy more than upper limit | params['.json_encode($active).'] | params['.json_encode($item).'] | '.__METHOD__);
                    return Lib_Errors::ACTIVE_USER_TIME_UPPER;
                }
                $total_price = $total_price + $item['iCodePrice']*$active['count'];
            }

            if($total_price != ($coupon*Lib_Constants::COUPON_UNIT_PRICE+$amount)){
                $this->log->error('OrderService','active order clearing amount error | total_price['.$total_price.'] | params['.json_encode(func_get_args()).'] | '.__METHOD__);
                return Lib_Errors::ORDER_CLEARING_AMOUNT;
            }
        }else{ //夺宝兑换
            $active_ids = array();
            foreach($actives as $active){
                if(!isset($active['act_id']) || !isset($active['peroid']) || !isset($active['goods_id']) || !isset($active['count'])){
                    $this->log->error('OrderService','active params error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
                    return Lib_Errors::PARAMETER_ERR;
                }
                $active_ids[] = $active['act_id'];
            }
            //查询对应的活动信息
            $this->load->model('active_peroid_model');
            $active_list = $this->active_peroid_model->get_active_peroids('*',array('where_in'=>array('iActId',$active_ids)));

            $temp = array();
            foreach($active_list['list'] as $list){
                $temp[$list['iActId']] = $list;
            }
            $active_list = $temp;
            unset($temp);

            $total_price = 0;
            $this->load->model('active_config_model');
            foreach($actives as $active){
                $item = $this->active_config_model->get_row(array('iActId'=> $active['act_id'],'iState'=>Lib_Constants::PUBLISH_STATE_ONLINE));
                if(empty($item)){
                    $this->log->error('OrderService','active not exist | params['.json_encode($active).'] | '.__METHOD__);
                    return Lib_Errors::ACTIVE_NOT_FOUND;
                }

                $total_price = $total_price + ($item['iCodePrice']*$item['iLotCount'])*$active['count'];
            }

            if($total_price != ($coupon*Lib_Constants::COUPON_UNIT_PRICE+$amount)){
                $this->log->error('OrderService','active order clearing amount error | total_price['.$total_price.'] | params['.json_encode(func_get_args()).'] | '.__METHOD__);
                return Lib_Errors::ORDER_CLEARING_AMOUNT;
            }
        }


        //订单创建将使用事务处理
        //如果创建失败，将会尝试3次创建
        $repeat = 1;
        $this->load->model('active_merage_order_model');
        $this->load->model('active_order_model');
        do{
            //**************事务开始**************************
            $return = true;
            //$this->active_peroid_model->db->trans_start();

            //创建大订单
            $merage_order_id = $this->setOrderId($plat_from,$buy_type,$uin);
            $data = array(
                'order_id' => $merage_order_id,
                'uin' => $uin,
                'total_price' => $total_price,
                'coupon' => $coupon,
                'amount' => $amount,
                'pay_type' => $pay_agent_type,
                'src' => '',
                'plat_form' => $plat_from,
                'ip' => empty($this->ip) ? ip2long('127.0.0.1') : $this->ip
            );
            if(!$merage_order = $this->active_merage_order_model->add_merage_order($uin,$data)){
                $return = false;
                $this->log->error('OrderService','add merage order fail | repeat['.$repeat.'] | params['.json_encode($data).'] | '.__METHOD__);
            }else{
                //创建小订单
                foreach($actives as $active){
                    $item = isset($active_list[$active['act_id']]) ? $active_list[$active['act_id']] : array();

                    $active_order = $this->setOrderId($plat_from,$buy_type,$uin);
                    $data = array(
                        'order_id' => $active_order,
                        'merge_order_id' => $merage_order_id,
                        'uin' => $uin,
                        'act_id' => $item['iActId'],
                        'goods_id' => $item['iGoodsId'],
                        'peroid'  => $item['iPeroid'],
                        'act_type' => $act_type,
                        'buy_type' => $buy_type,
                        'count' => $active['count'],
                        'unit_price' => $buy_type == Lib_Constants::ORDER_TYPE_ACTIVE ? $item['iCodePrice'] : $item['iCodePrice']*$item['iLotCount'],
                        'total_price' => $item['iCodePrice']*$active['count'],
                        'amount' => $item['iCodePrice']*$active['count'],
                        'pay_type' => $pay_agent_type,
                    );
                    if(!$active_order = $this->active_order_model->add_active_order($uin,$data)){
                        $return = false;
                        $this->log->error('OrderService','add active order fail | repeat['.$repeat.'] | sql['.$this->active_order_model->db->last_query().'] | '.__METHOD__);
                    }
                }
            }

            //**************事务结束****************************
            /*$this->active_peroid_model->db->trans_complete();
            if($this->active_peroid_model->db->trans_status() === FALSE){
                $this->log->error('OrderService','transaction status return false | repeat['.$repeat.'] | '.__METHOD__);
                $return = false;
            }*/
            $repeat++;
        }while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && $return == false);
        if(!$return || !$merage_order){
            $this->log->error('OrderService','add order fail | repeat['.$repeat.'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }


        //0元购
        if($total_price == 0){
            $this->log->error('OrderService','share free coupon | rs['.$share_uin.'] | uin['.$share_uin.'] | '.__METHOD__);
            //需要扣除免费券数量
            $free_coupon = 0;
            foreach($actives as $active){
                $free_coupon = $free_coupon + $active['count'];
            }
            if(empty($free_coupon)){
                $this->log->error('OrderService','free coupon quantity total 0 | rs['.json_encode($actives).'] | '.__METHOD__);
                return Lib_Errors::PARAMETER_ERR;
            }

            //减去用户免费夺宝机会
            $this->load->model('user_ext_model');
            $user_ext = $this->user_ext_model->get_user_by_uin($uin);
            if(!$user_ext || empty($user_ext['iFreeCoupon']) || $user_ext['iFreeCoupon'] < $free_coupon){
                $this->log->error('OrderService','user free coupon quantity not sufficient | rs['.json_encode($user_ext).'] | '.__METHOD__);
                return Lib_Errors::REDUCE_FREE_COUPON_FAILED;
            }
            if(!$rs = $this->user_ext_model->update_count(array('iFreeCoupon'=>'-'.$free_coupon),$uin)){
                $this->log->error('OrderService','subtract free coupon fail | rs['.$rs.'] | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }

            //设置成功订单
            if(!$return = $this->set_succ_active_order($uin,$merage_order_id,$merage_order_id)){
                $this->log->error('OrderService','set active order succ fail | rs['.$rs.'] | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }

            //邀请好友首次参加
            if(!empty($share_uin)){
                $user_order = $this->active_merage_order_model->row_count(array('iUin'=>$uin,'iTotalPrice'=>0,'iPayStatus'=>Lib_Constants::PAY_STATUS_PAID),array(),true);
                $this->log->error('OrderService','share free coupon | rs['.$user_order.'] | sql['.$this->active_merage_order_model->db->last_query().'] | '.__METHOD__);
                if($user_order && $user_order == 1){
                    $this->log->error('OrderService','share free coupon | rs['.$user_order.'] | uin['.$share_uin.'] | '.__METHOD__);
                    $this->user_ext_model->update_count(array('iFreeCoupon'=>'+1'),$share_uin);
                }
            }
            $is_paid = 1;
            $is_zero = 'true';
        }

        return array('order_id'=>$merage_order_id,'is_paid'=>isset($is_paid) ? $is_paid : 0,'is_zero'=>isset($is_zero) ? $is_zero : '');
    }


    /**
     * 创建夺宝券订单
     * @param $uin
     * @param $total 总价
     * @param $count 购买数量
     * @param int $plat_from 购买平台
     * @param int $pay_agent_type
     */
    public function create_coupon_order($uin,$total,$count,$plat_from = Lib_Constants::PLATFORM_WX,$pay_agent_type = Lib_Constants::ORDER_PAY_TYPE_WX)
    {
        if(empty($uin) || empty($total) || empty($count)){
            $this->log->error('OrderService','param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        if($count*Lib_Constants::COUPON_UNIT_PRICE != $total){
            $this->log->error('OrderService','total param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //检查用户信息
        /*$this->load->service('user_service');
        if(!$this->user_service->check_user_uin){
            $this->log->error('OrderService','user not exist | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::USER_NOT_EXISTS;
        }*/

        //判断是否需要赠送
        $this->load->model('recharge_activity_model');
        $conf = $this->recharge_activity_model->get_activity_conf();
        if(!empty($conf)){
            $conf['sConf'] = json_decode($conf['sConf'],true);
        }else{
            $conf = array();
            $conf['sConf'] = Lib_Constants::$recharge_activity_config;
        }
        $present = 0;
        foreach($conf['sConf'] as $val){
            if($val['c'] <= $count){
                $present = $val['s'];
            }
        }
        if(!empty($present)){
            $this->log->notice('OrderService','present count['.$present.'] | params['.json_encode($conf).'] | '.__METHOD__);
        }


        $this->load->model('coupon_order_model');
        $order_id = $this->setOrderId($plat_from,Lib_Constants::ORDER_TYPE_COUPON,$uin);
        $data = array(
            'order_id' => $order_id,
            'uin' => $uin,
            'count' => $count,
            'present_count' => $present,
            'unit_price' => Lib_Constants::COUPON_UNIT_PRICE,
            'total_price' => Lib_Constants::COUPON_UNIT_PRICE * $count,
            'src' => '',
            'plat_from' => $plat_from,
            'ip' => empty($this->ip) ? ip2long('127.0.0.1') : $this->ip,
            'location' => ''
        );

        $repeat = 1;
        do{
            $return = true;
            $order = $this->coupon_order_model->add_coupon_order($uin, $data);
            if(!$order){
                $this->log->error('OrderService','add active order fail | repeat['.$repeat.'] | sql['.$this->active_order_model->db->last_query().'] | '.__METHOD__);
                $return = false;
            }
        }while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && $return == false);

        if(!$return){
            return Lib_Errors::SVR_ERR;
        }

        return $order_id;
    }


    /**
     * 夺宝活动订单成功回调
     * @param $uin
     * @param $order_id
     * @param $data | trans_id
     * @return int
     */
    public function set_succ_active_order($uin,$order_id,$trans_id)
    {
        if(empty($uin) || empty($order_id) || empty($trans_id)){
            $this->log->error('OrderService','param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        $this->load->model('active_merage_order_model');
        if(!$order_info = $this->active_merage_order_model->get_merage_order_by_id($uin,$order_id)){
            $this->log->error('OrderService','merage order not fond | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::ORDER_NOT_FOUND;
        }

        if($order_info['iPayStatus'] == Lib_Constants::PAY_STATUS_PAID){
            return true;
        }

        $repeat = 1;
        $pay_time = time();
        do{
            $return = true;
            if(!$this->active_merage_order_model->set_succ_merage_order($uin,$order_id,$trans_id,$pay_time)){
                $this->log->error('OrderService','set succ merage order fail | repeat['.$repeat.'] | sql['.$this->active_merage_model->db->last_query().'] | '.__METHOD__);
                $return = false;
            }
            $repeat++;
        }while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && $return == false);
        if(!$return){
            return Lib_Errors::SVR_ERR;
        }

        $repeat = 1;
        $this->load->model('active_order_model');
        do{
            $return = true;
            if(!$this->active_order_model->set_succ_active_order($uin,$order_id,$trans_id,$pay_time)){
                $this->log->error('OrderService','set succ active order fail | repeat['.$repeat.'] | sql['.$this->active_order_model->db->last_query().'] | '.__METHOD__);
                $return = false;
            }
            $repeat++;
        }while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && $return == false);
        if(!$return){
            return Lib_Errors::SVR_ERR;
        }

        //如果是兑换，则添加发货数据
        //$this->log->error('OrderService','111111 | '.__METHOD__);
        if($this->check_order_type($order_id) == Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE){
            $order = $this->active_order_model->get_row(array('sMergeOrderId'=>$order_id,'iUin'=>$uin));
            //$this->log->error('OrderService','2222222 | josn['.json_encode($order).'] | '.__METHOD__);
            $this->load->model('order_deliver_model');
            if(!$this->order_deliver_model->add_deliver_row($order_info['iUin'],$order['iGoodsId'],$order['sOrderId'],Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE,'兑换活动')){
                $this->log->error('OrderService','add deliver order fail | sql['.$this->order_deliver_model->db->last_query().')]');
            }
        }

        return true;
    }


    /**
     * 充值夺宝券成功回调
     * @param $uin
     * @param $order_id
     * @param $trans_id
     * @return bool|int
     */
    public function set_succ_coupon_order($uin,$order_id,$trans_id)
    {
        if(empty($uin) || empty($order_id) || empty($trans_id)){
            $this->log->error('OrderService','param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        $this->load->model('coupon_order_model');
        if(!$order_info = $this->coupon_order_model->get_coupon_order_by_id($uin,$order_id)){
            $this->log->error('OrderService','coupon order not fond | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::ORDER_NOT_FOUND;
        }

        if($order_info['iPayStatus'] == Lib_Constants::PAY_STATUS_PAID){
            return true;
        }

        $repeat = 1;
        $pay_time = time();
        do{
            $return = true;
            if(!$this->coupon_order_model->set_succ_coupon_order($uin,$order_id,$trans_id,$pay_time)){
                $this->log->error('OrderService','set succ coupon order fail | repeat['.$repeat.'] | sql['.$this->coupon_order_model->db->last_query().'] | '.__METHOD__);
                $return = false;
            }
            $repeat++;
        }while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && $return == false);


        if(!$return){
            return Lib_Errors::SVR_ERR;
        }else{
            //添加券流水及赠送流水
            $this->load->service('awards_service');
            $add_coupon = $this->awards_service->add_coupon_action(
                $uin,
                Lib_Constants::BUY_COUPON,
                $order_info['iCount'],
                $order_info['iPresentCount'],
                Lib_Constants::PLATFORM_WX,
                array('order_id'=>$order_info['sOrderId'])
            );

            //券充值结果
            if(!$add_coupon == Lib_Errors::SUCC){
                $this->log->error('OrderService','add user coupon fail | params['.json_encode(array($uin,
                        Lib_Constants::BUY_COUPON,
                        $order_info['iCount'],
                        $order_info['iPresentCount'],
                        Lib_Constants::PLATFORM_WX,
                        array('order_id'=>$order_info['sOrderId']))).'] | result['.$add_coupon.'] | '.__METHOD__);
            }else{
                //推送通知
                $this->load->service('push_service');
                $this->load->model('user_model');
                $user = $this->user_model->get_row(array('iUin'=>$uin));
                get_instance()->config->load('pay');
                $config = config_item('weixinNotify');
                $config = $config['buyCouponSucc'];
                $data = array(
                    'order_id' => $order_id,
                    'open_id' => $user['sOpenId'],
                    'price_string' => price_format($order_info['iTotalPrice']).'元',
                    'count' => $order_info['iCount'],
                    'present' => $order_info['iPresentCount'],
                    'url' => $config['url']
                );
                $rs = $this->push_service->add_task(Lib_Constants::$msg_business_type[Lib_Constants::MSG_TEM_COUPON_SUCC],$order_id,$uin,$data);
                if($rs < 0){
                    $this->log->error('PushService','add push task fail | params['.json_encode($data).'] | rs['.$rs.'] | '.__METHOD__);
                }
            }
            return true;
        }
    }

    /**
     * 创建福袋订单
     * @param $uin
     * @param $total
     * @param $count
     * @param $present_count
     * @param int $plat_from
     * @param int $pay_agent_type
     */
    public function create_luckybag_order($uin,$bag_id, $pay_amout, $pay_coupon, $plat_from = Lib_Constants::PLATFORM_WX,$pay_agent_type = Lib_Constants::ORDER_PAY_TYPE_WX)
    {
        if (empty($uin) || empty($bag_id) || (empty($pay_amout) && empty($pay_coupon))) {
            $this->log->error('OrderService','CreateBagOrder | param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }
        $order_id = $this->setOrderId($plat_from,Lib_Constants::ORDER_TYPE_BAG,$uin);
        $total_amout = ($pay_amout + $pay_coupon)*Lib_Constants::COUPON_UNIT_PRICE;
        $data = array(
            'sOrderId' => $order_id,
            'iUin' => $uin,
            'iBagId' => $bag_id,
            'iPayAmount' => ($pay_amout*Lib_Constants::COUPON_UNIT_PRICE),
            'iPayCoupon' => $pay_coupon,
            'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
            'iTotalAmount' => $total_amout,
            'iCreateTime' => time(),
        );
        $this->load->model('bag_order_model');
        $repeat = 1;
        $return = false;
        while ($repeat <= Lib_Constants::ORDER_OPERATE_NUM && !$return) {
            $data = $this->bag_order_model->add_row($data);
            if (!$data) {
                $this->log->error('OrderService','CreateBagOrder | create bag order failed  | repeat['.$repeat.'] | params['.json_encode(func_get_args()).'] | sql['.$this->bag_order_model->db->last_query().'] | '.__METHOD__);
            }
            $return = true;
            $repeat++;
        };

        if(!$return){
            return Lib_Errors::SVR_ERR;
        }

        return $order_id;
    }

    /**
     * 福袋订单支付成功回调
     * @param $uin
     * @param $order_id
     * @param $data
     * @return bool|int
     */
    public function set_succ_bag_order($uin,$order_id,$data)
    {
        if(empty($uin) || empty($order_id) || empty($data)){
            $this->log->error('OrderService','param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        if(empty($data['user_ext'])) {
            $this->load->model('user_ext_model');
            $user_ext = $this->user_ext_model->get_user_ext_info($uin, true);
            if (empty($user_ext)) {
                $this->log->error('OrderService','param error | params['.json_encode(func_get_args()).'] | '.__METHOD__);
                return Lib_Errors::PARAMETER_ERR;
            }
        } else {
            $user_ext = $data['user_ext'];
        }

        $this->load->model('bag_order_model');
        if (!$order_info = $this->bag_order_model->get_order_info($uin,$order_id)){
            $this->log->error('OrderService','bag order not fond | params['.json_encode(func_get_args()).'] | sql : '.$this->bag_order_model->db->last_query().'| '.__METHOD__);
            return Lib_Errors::ORDER_NOT_FOUND;
        }

        if($order_info['iStatus'] == Lib_Constants::PAY_STATUS_PAID){
            return true;
        }

        $refund =  $user_ext['coupon'] < $order_info['iPayCoupon'] ? true : false;
        $pay_time = time();
        $is_paid = false;
        $repeat = 1;
        $trans_id = isset($data['trans_id']) ? $data['trans_id'] : '';
        $return = false;
        while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && !$return){
            if(!$this->bag_order_model->set_order_succ($uin, $order_id, $trans_id, $pay_time, $refund)){
                $this->log->error('OrderService','update bag order pay state failed | repeat['.$repeat.'] | sql['.$this->bag_order_model->db->last_query().'] | '.__METHOD__);
            }
            $return = true;
            $repeat++;
        };

        if(!$return){
            return Lib_Errors::SVR_ERR;
        }

        if($refund) {//插入退款表
            $this->load->model('order_refund_model');
            $return = false;
            $data = array(
                'sOrderId' =>  $order_info['sOrderId'],
                'iUin' => $order_info['iUin'],
                'iBuyType' => Lib_Constants::ORDER_TYPE_BAG,
                'iPayAgentType' => $order_info['iPayAgentType'] ? $order_info['iPayAgentType'] : Lib_Constants::ORDER_PAY_TYPE_WX,
                'iRefundPrice' => $order_info['iPayAmount'],
                'iRefundCoupon' => 0,
                'sTransId' => $trans_id,
                'sRemark' => $order_id,
                'iCreateTime' => time(),
            );
            while($repeat <= Lib_Constants::ORDER_OPERATE_NUM && !$return){
                if(!$this->order_refund_model->create_refund_order($uin, $data)){
                    $this->log->error('OrderService','add bag order refund failed | repeat['.$repeat.'] | sql['.$this->order_refund_model->db->last_query().'] | '.__METHOD__);
                }
                $return = true;
                $repeat++;
            };
        } else {
            //更新用户夺宝券数量 夺宝券数量为0不需要更新
            if ( $order_info['iPayCoupon']) {
                $this->load->model('user_ext_model');
                if (!$this->user_ext_model->update_count(array('iCoupon'=>-$order_info['iPayCoupon'], 'iUpdateTime' => time()), $uin)) {
                    $this->log->error('LuckyBagService', 'PullBag | update user_ext coupon num failed | '.json_encode(func_get_args()).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
                    return Lib_Errors::REDUCE_COUPON_FAILED;
                }
            }

            //更新福袋支付状态
            $this->load->model('lucky_bag_model');
            if(!$this->lucky_bag_model->update_row(array('iIsPaid'=>Lib_Constants::PAY_STATUS_PAID, 'iUpdateTime'=>time()), array('iUin'=>$uin, 'iBagId' => $order_info['iBagId']))){
                $this->log->error('OrderService','set succ bag order failed | repeat['.$repeat.'] | sql['.$this->bag_order_model->db->last_query().'] | '.__METHOD__);
            } else {
                $is_paid = true;
            }

            //更新用户福袋信息
            if (!$this->user_ext_model->update_count(array('iLuckyBag'=> 1, 'iHisLuckyBag' => 1,'iUpdateTime' => time()), $uin)) {
                $this->log->error('OrderService', 'PullBag | update user_ext luckybag num failed | '.json_encode(func_get_args()).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }

            //添加抵用券积分消费日志
            $this->load->model('coupon_action_log_model');
            $params = array(
                'iUin' => $uin,
                'iAction' => Lib_Constants::ACTION_USE_BAG,
                'iNum' => $order_info['iPayCoupon'],
                'sExt' => json_encode(array('bag_id'=>$data['bag_id'], 'order_id'=>$order_info['sOrderId'])),
                'iAddTime' => time(),
                'iType' => Lib_Constants::ACTION_OUTCOME //支出
            );
            if (!$this->coupon_action_log_model->add_row($params)) {
                $this->log->error('LuckyBagService','PullBag | add user pull bag action log failed  | '.json_encode($params).' | '.$this->coupon_action_log_model->db->last_query());
            }
        }
        return $is_paid;
    }

    /**
     * 创建积分订单
     * @param $uin
     * @param $act_id
     * @param $goods_id
     * @param $score_price 原价积分 冗余字段
     * @param $score_unit_price
     * @param int $plat_from
     * @param int $pay_agent_type
     * @return int|string
     */
    public function create_score_order($uin,$act_id, $goods_id, $score_price, $score_unit_price, $count, $plat_from = Lib_Constants::PLATFORM_WX)
    {
        $this->load->model('score_order_model');
        $order_id = $this->setOrderId($plat_from, Lib_Constants::ORDER_TYPE_POINT_EXCHANGE);
        //生成订单
        $data = array(
            'sOrderId' => $order_id,
            'iUin' => $uin,
            'iActivityId' => $act_id,
            'iCount' => 1,
            'iUnitPrice' => $score_unit_price,
            'iOriPrice' => $score_price,
            'iTotalPrice' => $score_unit_price*$count,
            'iCreateTime' => time(),
            'iPayTime' => 0,
            'iStatus' => Lib_Constants::PAY_STATUS_UNPAID,
        );

        if (!$this->score_order_model->add_row($data)) {
            $this->log->error('OrderService','Score | Exchange | create score exchange order | params:'.json_encode($data).' | sql: '.$this->score_order_model->db->last_query().' |  '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        } else {
            $this->log->notice('OrderService','Score | Exchange | create score exchange order | params:'.json_encode($data).' | '.__METHOD__);
        }
        return $order_id;
    }

    /**
     * 积分订单成功回调
     * @param $uin
     * @param $act_id
     * @param $order_id
     * @param $goods_id
     * @param $score_price
     * @param $score_unit_price
     * @param $count
     * @param int $plat_from
     * @return int
     */
    public function set_succ_score_order($uin, $act_id, $order_id, $goods_id, $goods_num, $pre_price, $count, $goods_type, $plat_from = Lib_Constants::PLATFORM_WX)
    {
        //更新状态
        $this->load->model('user_ext_model');
        $total_score = $pre_price*$count;
        //更新用户积分信息
        if (!$this->user_ext_model->update_count(array('iScore'=> -$total_score, 'iHisUsedScore' =>$total_score,'iUpdateTime' => time()), $uin)) {
            $this->log->error('OrderService', 'Score | Exchange | update user_ext score num failed | '.json_encode(func_get_args()).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        //添加积分日志
        $this->load->model('score_action_log_model');
        $params = array(
            'iUin' => $uin,
            'iAction' => Lib_Constants::SCORE_ACTION_EXCHANGE,
            'iScoreCount' => $total_score,
            'sExt' => json_encode(array('act_id'=>$act_id, 'order_id'=>$order_id)),
            'iExchangeTime' => time(),
            'sAwardsName' => '积分兑换', //支出
            'iType' => Lib_Constants::ACTION_OUTCOME, //支出
            'iPlatForm' => $plat_from //支出
        );
        if (!$this->score_action_log_model->add_row($params)) {
            $this->log->error('OrderService','Score | Exchange | add user pull bag action log failed  | '.json_encode($params).' | '.$this->score_action_log_model->db->last_query());
        }

        //更新活动库存
        $this->load->model('score_activity_model');
        if (!$this->score_activity_model->update_count(array('iUsed'=>$count, 'iUpdateTime'=>time()), $act_id)) {
            $this->log->error('OrderService', 'Score | Exchange | update score activity used num failed | '.json_encode(func_get_args()).' | sql:'.$this->score_activity_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
        //更新订单状态
        if(!$this->score_order_model->update_row(array('iStatus'=> Lib_Constants::PAY_STATUS_PAID, 'iPayTime'=>time()), $order_id)){
            $this->log->error('OrderService', 'Score | Exchange | update score order status failed | '.json_encode(func_get_args()).' | sql:'.$this->score_order_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        if ($goods_type == Lib_Constants::GOODS_TYPE_TICKET) {//兑券 自动发券
            //更新用户抵用券数量
            if (!$this->user_ext_model->update_count(array('iCoupon'=> $goods_num, 'iHisGiftCoupon' => $goods_num, 'iHisCoupon' => $goods_num,'iUpdateTime' => time()), $uin)) {
                $this->log->error('OrderService', 'Score | Exchange | update user_ext coupon num failed | '.json_encode(func_get_args()).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }

            //添加抵用券兑换日志
            $this->load->model('coupon_action_log_model');
            $params = array(
                'iUin' => $uin,
                'iAction' => Lib_Constants::SCORE_EXCHANGE,
                'iNum' => $goods_num,
                'sExt' => json_encode(array('act_id'=>$act_id, 'order_id'=>$order_id)),
                'iAddTime' => time(),
                'iType' => Lib_Constants::ACTION_INCOME //支出
            );
            if (!$this->coupon_action_log_model->add_row($params)) {
                $this->log->error('LuckyBagService','Score | Exchange | add user coupon action log failed  | '.json_encode($params).' | '.$this->coupon_action_log_model->db->last_query());
            }
        } else {
            //添加发货列表
            $this->load->model('order_deliver_model');
            $data = array(
                'iGoodsId' => $goods_id,
                'iType' => Lib_Constants::DELIVER_TYPE_SCORE,
                'sOrderId' => $order_id,
                'iCreateTime' => time(),
            );
            if(!$this->order_deliver_model->add_row($data)) {
                $this->log->error('OrderService', 'Score | inset into deliver failed | '.json_encode(func_get_args()).' | sql:'.$this->score_order_model->db->last_query().' | '.__METHOD__);
            }
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 创建拼团订单
     *
     * @param     $buyType
     * @param     $uin
     * @param     $addressId
     * @param     $grouponId
     * @param     $specId
     * @param     $diyId
     * @param     $pay_agent
     * @param     $count
     * @param     $fee
     * @param int $plat_from
     *
     * @return int|string
     */
    public function create_groupon_order($buyType, $uin, $addressId, $grouponId=null, $specId=null, $diyId=null, $pay_agent=null, $count=1, $fee=0, $plat_from=Lib_Constants::PLATFORM_WX)
    {
        $log_label = 'order service | create_groupon_order';

        $this->load->model('address_model');
        if (! ($address = $this->address_model->get_address_info_by_id($addressId, $uin))) { // 收货地址错误
            $this->log->error($log_label, 'deliver address not exist', array('args'=>func_get_args()));
            return Lib_Errors::ADDRESS_NOT_FOUND;
        }

        $this->load->service('groupon_service');
        if (($check = $this->groupon_service->create_order_check($buyType, $uin, $grouponId, $specId, $diyId, $count)) < Lib_Errors::SUCC) {
            $this->log->error($log_label, 'create_order_check error', array('args'=>func_get_args()));
            return $check;
        }
        list($groupon, $spec) = $check;
        // 创建订单
        $order_id = $this->setOrderId($plat_from, Lib_Constants::ORDER_TYPE_GROUPON, $uin);

        if (Lib_Constants::GROUPON_ORDER_DIRECT == $buyType) {
            $unitPrice = $groupon['iPrice'];
            $specId = 0;
            $diyId = 0;
        } else {
            $unitPrice = $spec['iDiscountPrice'];
            $specId = $spec['iSpecId'];
            if (Lib_Constants::GROUPON_ORDER_DIY == $buyType) {
                $diyId = 0;
            }
        }
        $total_price = $unitPrice * $count;
        $data = array(
            'sOrderId' => $order_id,
            'iUin' => $uin,
            'iGoodsId' => $groupon['iGoodsId'],
            'iGrouponType' => $groupon['iGrouponType'],
            'iGrouponId' => $groupon['iGrouponId'],
            'iSpecId' => $specId,
            'iDiyId' => $diyId,
            'iUnitPrice' => $unitPrice,
            'iCount' => $count,
            'iTotalPrice' => $total_price,
            'iFee' => $fee,
            'iPayAmount' => $total_price + $fee,
            'iRealPrice' => $total_price,
            'iPayStatus' => Lib_Constants::PAY_STATUS_UNPAID,
            'iBuyType' => $buyType,
            'sName' => $address['sName'],
            'sMobile' => $address['sMobile'],
            'sAddress' => $address['sProvince'].' '.$address['sCity'].' '.$address['sDistrict'].' '.$address['sAddress'],
            'iPlatForm' => $plat_from,
        );
        if ($pay_agent && in_array($pay_agent, array_keys(Lib_Constants::$order_pay_type))) {
            $data['iPayAgentType'] = $pay_agent;
        }
        $this->load->model('groupon_order_model');
        if (! $this->groupon_order_model->add_row($data)) { // 添加订单记录失败
            $this->log->error($log_label, 'create groupon order failed', array('sql'=>$this->groupon_order_model->db->last_query(),'data'=>$data,'args'=>func_get_args()));
            return Lib_Errors::GROUPON_ORDER_CREATE_FAILED;
        } else {  // 添加订单记录成功
            $this->log->notice($log_label, 'create groupon order success', array('sql'=>$this->groupon_order_model->db->last_query(),'data'=>$data,'args'=>func_get_args()));
        }
        return array($order_id, $groupon);
    }

    /**
     * 标记订单为 已支付
     *
     * @param $uin
     * @param $order_id
     * @param $trans_id
     *
     * @return int
     */
    public function set_succ_groupon_order($uin, $order_id, $trans_id)
    {
        $log_label = 'order service | set_succ_groupon_order';

        $this->load->model('user_model');
        if (! ($user = $this->user_model->get_user_by_uin($uin))) { // 用户 $uin 错误
            $this->log->error($log_label, 'user does not exist', array('args'=>func_get_args()));
            return Lib_Errors::GROUPON_ORDER_USER_ERROR;
        }

        $this->load->model('groupon_order_model');
        if (! ($order = $this->groupon_order_model->get_row($order_id, true, false))) { // 订单不存在
            $this->log->error($log_label, 'order not exist', array('args'=>func_get_args()));
            return Lib_Errors::ORDER_NOT_FOUND;
        }

        if ($order['iUin'] != $uin) { // 用户 uin 与订单数据不匹配
            $this->log->error($log_label, 'order not match uin', array('args'=>func_get_args()));
            return Lib_Errors::PARAMETER_ERR;
        }

        if ($this->is_paid($order)) { // 已支付
//            $this->log->error($log_label, 'order has paid', array('args'=>func_get_args(),'order'=>$order));
            return Lib_Errors::SUCC;
        }

        // 将订单标记为 已支付 状态
        $repeat = 0;
        $pay_time = time();
        do {
            $return = true;
            if (! $this->groupon_order_model->set_paid($order_id, $trans_id, $pay_time)) {
                $this->log->error($log_label, 'set order paid failed', array('repeat'=>$repeat,'sql'=>$this->groupon_order_model->db->last_query(),'args'=>func_get_args()));
                $return = false;
            } else {
                $order['iPayStatus'] = Lib_Constants::PAY_STATUS_PAID;
            }
            $repeat ++;
        } while ($repeat < Lib_Constants::ORDER_OPERATE_NUM && $return == false);
        if (! $return) {
            return Lib_Errors::GROUPON_ORDER_PAID_ERROR;
        }

        $this->load->service('groupon_service');
        return $this->groupon_service->order_paid_process($order);
    }

    /**
     * 成功回调
     * @param $uin
     * @param $arrNotify
     * @return bool
     */
    public function set_succ_order($uin, $arrNotify)
    {
        $log_label = 'order service | set_succ_order | notify';

        $type = $this->check_order_type($arrNotify['out_trade_no']);
        $return  = false;
        switch ($type) {
            case Lib_Constants::ORDER_TYPE_ACTIVE:
            case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                $order = $this->get_order_detail($uin, $arrNotify['out_trade_no']);
                if(!is_array($order) || !isset($order['merage_order'])){
                    $this->log->notice($log_label, 'active order not find', array('uin'=>$uin,'arrNotify'=>$arrNotify));
                    return false;
                }

                if($this->check_order($order['merage_order'], $arrNotify)){
                    $return = $this->set_succ_active_order($uin,$arrNotify['out_trade_no'],$arrNotify['transaction_id']);
                }
                break;

            case Lib_Constants::ORDER_TYPE_COUPON:
                $order = $this->get_order_detail($uin, $arrNotify['out_trade_no']);
                if(!is_array($order)){
                    $this->log->notice($log_label, 'coupon order not find', array('uin'=>$uin,'arrNotify'=>$arrNotify));
                    return false;
                }

                $order['iAmount'] = $order['iTotalPrice'];
                if($this->check_order($order,$arrNotify)){
                    $return = $this->set_succ_coupon_order($uin,$arrNotify['out_trade_no'],$arrNotify['transaction_id']);
                }
                break;

            case Lib_Constants::ORDER_TYPE_BAG:
                $order = $this->get_order_detail($uin,$arrNotify['out_trade_no']);
                if(!is_array($order)){
                    $this->log->notice($log_label, 'bag order not find', array('uin'=>$uin,'arrNotify'=>$arrNotify));
                    return false;
                }
                if($this->check_order($order,$arrNotify)){
                    $return = $this->set_succ_bag_order($uin,$arrNotify['out_trade_no'],array('trans_id'=>$arrNotify['transaction_id']));
                }
                break;

            case Lib_Constants::ORDER_TYPE_GROUPON:
                $order = $this->get_order_detail($uin, $arrNotify['out_trade_no']);
                if( ! is_array($order)) {
                    $this->log->notice($log_label, 'groupon order not exists', array('code'=>$order,'uin'=>$uin,'notify'=>$arrNotify));
                    return false;
                }
                if (! $this->check_order($order, $arrNotify)) {
                    $this->log->notice($log_label, 'groupon order check error', array('uin'=>$uin,'notify'=>$arrNotify,'order'=>$order));
                } else {
                    $return = $this->set_succ_groupon_order($uin, $arrNotify['out_trade_no'], $arrNotify['transaction_id']);
                }
                break;
            default:
                return false;
        }

        if((!is_numeric($return) && $return) || Lib_Errors::SUCC == $return) {
            $this->log->notice($log_label, 'set order succ', array('uin'=>$uin,'notify'=>$arrNotify,'order'=>$order));
        } else {
            $this->log->notice($log_label, 'set order failed', array('return'=>$return,'order_type'=>$type,'uin'=>$uin,'notify'=>$arrNotify,'order'=>$order));
        }

        return $return;
    }

    /**
     * 确认发货
     *
     * @param $uin
     * @param $order_id
     *
     * @return int
     */
    public function confirm_deliver($uin, $order_id)
    {
        $log_label = 'order service | confirm_deliver';
        $this->load->model('user_model');
        if (! ($user = $this->user_model->get_user_by_uin($uin))) { // 用户 $uin 错误
            $this->log->error($log_label, 'user does not exist', array('args'=>func_get_args()));
            return Lib_Errors::GROUPON_ORDER_USER_ERROR;
        }

        $order_type = $this->check_order_type($order_id);
        if (Lib_Constants::ORDER_TYPE_GROUPON == $order_type) {
            $this->load->service('groupon_service');
            return $this->groupon_service->confirm_deliver($uin, $order_id);
        }
        return Lib_Errors::SUCC;
    }

    /**
     * @return int
     */
    public function order_address($uin, $order_id, $address_id)
    {
        $log_label = 'order service | confirm_deliver';
        $this->load->model('user_model');
        if (! ($user = $this->user_model->get_user_by_uin($uin))) { // 用户 $uin 错误
            $this->log->error($log_label, 'user does not exist', array('args'=>func_get_args()));
            return Lib_Errors::GROUPON_ORDER_USER_ERROR;
        }

        $order_type = $this->check_order_type($order_id);
        if (Lib_Constants::ORDER_TYPE_GROUPON == $order_type) {
            $this->load->service('groupon_service');
            return $this->groupon_service->order_address($uin, $order_id, $address_id);
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 检查订单是否合法
     * @param $order
     * @param $arrNotify
     * @return bool
     */
    protected function check_order($order, $arrNotify)
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
     * 返回订单详情
     * @param $order_id
     * @return array|int
     */
    public function get_order_detail($uin,$order_id){
        $type = $this->check_order_type($order_id);

        switch($type){
            case Lib_Constants::ORDER_TYPE_ACTIVE:
            case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                $this->load->model('active_merage_order_model');
                $this->load->model('active_order_model');
                $merage_order = $this->active_merage_order_model->get_merage_order_by_id($uin,$order_id);
                $active_order = $this->active_order_model->get_merage_order_by_id($uin,$order_id);
                if(empty($merage_order) || empty($active_order)){
                    $this->log->error('OrderService','active order not find | order_id['.$order_id.'] | '.__METHOD__);
                    $this->log->error($this->active_merage_order_model->db->last_query());
                    $this->log->error($this->active_order_model->db->last_query());
                    return Lib_Errors::ORDER_NOT_FOUND;
                }
                $order = array(
                    'merage_order' => $merage_order,
                    'active_order' => $active_order,
                    'iPayStatus' => $merage_order['iPayStatus'],
                    'sOrderId' => $merage_order['sMergeOrderId'],
                    'sTransId' => $merage_order['sTransId']
                );
                break;

            case Lib_Constants::ORDER_TYPE_COUPON:
                $this->load->model('coupon_order_model');
                if(!$order = $this->coupon_order_model->get_coupon_order_by_id($uin,$order_id)){
                    $this->log->error('OrderService','coupon order not find | order_id['.$order_id.'] | '.__METHOD__);
                    return Lib_Errors::ORDER_NOT_FOUND;
                }
                break;

            case Lib_Constants::ORDER_TYPE_BAG:
                $this->load->model('bag_order_model');
                $order = $this->bag_order_model->get_bag_order_by_id($uin,$order_id);
                $order['iPayStatus'] = isset($order['iStatus']) ? $order['iStatus'] : Lib_Constants::PAY_STATUS_UNPAID;
                break;

            case Lib_Constants::ORDER_TYPE_GROUPON:
                $log_label = 'order service | get_order_detail';
                $this->load->model('groupon_order_model');
                if(! ($order = $this->groupon_order_model->get_row($order_id, true, false))) {
                    $this->log->error($log_label, 'groupon order not exist', array('order_id'=>$order_id,'uin'=>$uin));
                    return Lib_Errors::ORDER_NOT_FOUND;
                }
                break;

            default:
                $this->log->error('OrderService | get_order_detail','order type fail', array('order_id'=>$order_id,'type'=>$type));
                return Lib_Errors::ORDER_TYPE_NOT_FOUND;
        }

        return $order;
    }



    //判断支付状态
    public function is_paid($order)
    {
        return empty($order) || !isset($order['iPayStatus']) || $order['iPayStatus'] != Lib_Constants::PAY_STATUS_PAID ?  false : true;
    }


    /**
     * 返回订单类型
     * @param $order_id
     * @return string
     */
    public function check_order_type($order_id)
    {
        return substr($order_id,1,1);
    }


    /**
     * 根据订单返回平台id
     * @param $order_id
     * @return string
     */
    public function check_order_platfrom($order_id)
    {
        return substr($order_id,0,1);
    }


    /**

     * 统一返回指定格式的订单号
     * 格式：平台类型+订单类型+年月日时分秒+微秒+随机数+uin后两位
     * @param $plat_from
     * @param $uin
     * @param $type
     * @return string
     */
    public function setOrderId($plat_from,$type,$uin = null)
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr($usec,2,4);
        if($uin == null){
            return $plat_from.$type.date('YmdHis').$usec.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 4);
        }else{
            $suffix = substr($uin,-2);
            return $plat_from.$type.date('YmdHis').$usec.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 2).$suffix;
        }
    }
}