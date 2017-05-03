<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 订单统计接口
 * Class Order
 */
class Order extends API_Base
{
    private $pay_service = null;

    public function __construct()
    {
        parent::__construct(true);
        //$this->pay_service = $this->load->service('pay_service');
    }


    //创建订单
    public function create_active_order()
    {
        extract($this->cdata);

        $share_uin = empty($share_uin) ? '' : $share_uin;
        $this->log->error('Order','params err | params: '.json_encode($this->cdata).' | '.__METHOD__);
        if(!isset($multi)){ //立即购买
            if(empty($uin) || empty($qty) || empty($act_id) || empty($peroid) || empty($act_type) || empty($buy_type) || empty($goods_id)){
                $this->log->error('Order','params err | params: '.json_encode($this->cdata).' | '.__METHOD__);
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            //由于下单不指定券及支付方式，由支付这边指定,这里先用默认
            $coupon = 0;
            $amount = empty($total) ? 0 : $total;
            $actives = array(
                array(
                    'act_id' => $act_id,
                    'goods_id' => $goods_id,
                    'peroid' => $peroid,
                    'count' => $qty
                ),
            );

        }else{ //合并下单
            if(empty($uin) || empty($actives) || empty($act_type) || empty($buy_type) || empty($total)){
                $this->log->error('Order','multi params err | params: '.json_encode($this->cdata).' | '.__METHOD__);
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $coupon = 0;
            $amount = $total;
            $actives = empty($actives) ? array() : $actives;
        }

        $this->load->service('order_service');
        $this->order_service->ip = empty($ip) ? ip2long('127.0.0.1') : $ip;
        $result  = $this->order_service->create_active_order($uin,$act_type,$buy_type,$coupon,$amount,$actives,$plat_from = Lib_Constants::PLATFORM_WX,$pay_agent_type = Lib_Constants::ORDER_PAY_TYPE_WX,$share_uin);
        if(!is_array($result) || $result < 0){
            $this->render_result($result,$result);
        }else{
            $this->render_result(Lib_Errors::SUCC,$result);
        }
    }


    /**
     * 创建夺宝券订单
     */
    public function create_coupon_order()
    {
        extract($this->cdata);

        if(empty($uin)){
            $this->log->error('Order','params err | params: '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $number = empty($number) ? 0 : $number;


        //判断是否为活动
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
        $result  = $this->order_service->create_coupon_order($uin,$total,$count);

        if(!is_numeric($result) || $result < 0){
            $this->render_result($result,$result);
        }else{
            $this->render_result(Lib_Errors::SUCC,$result);
        }
    }


    /*public function get_order_list(){
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->row_list('*');

        $this->render_result(Lib_Errors::SUCC,$list);
    }*/

    //获以兑换订单
    public function get_exchange_list()
    {
        extract($this->cdata);
        if(empty($uin)){
            $this->log->error('Order','params err | params: '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $page_index = empty($page_index) ? 1 : $page_index;
        $page_size = empty($page_size) ? 5 : $page_size;

        $this->load->model('active_order_model');
        $list = $this->active_order_model->row_list('sOrderId,sMergeOrderId,iUin,iGoodsId,iActId,iPeroid,iPayStatus,sTransId',$where=array('iUin'=>$uin,'iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iBuyType'=>Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE), $order_by=array(), $page_index, $page_size);

        $this->render_result(Lib_Errors::SUCC,$list);
    }


    /**
     * 获取订单详情
     */
    public function get_order_detail(){
        extract($this->cdata);
        if(empty($uin) || empty($order_id)){
            $this->log->error('Order','params err | params: '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('order_service');
        $list = $this->order_service->get_order_detail($uin,$order_id);

        if(is_numeric($list) || $list < 0){
            $this->render_result($list,$list);
        }else{
            $this->render_result(Lib_Errors::SUCC,$list);
        }
    }
}