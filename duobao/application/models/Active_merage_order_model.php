<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 大订单MODEL
 * @TODO Active_order_model
 */
class Active_merage_order_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_active_merage_order';                //表名
    protected $table_primary = 'iAutoId';                   //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num= 10;
    protected $db_map_column = 'iUin';  //分表字段

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 增加合并订单，即大订单
     * 与小订单的关系是一对多的或者一对一
     * @param $uin
     * @param $data
     * @return bool
     */
    public function add_merage_order($uin,$data)
    {
        if(empty($uin) || empty($data) || !isset($data['order_id'])) return false;

        $arr = array(
            'sMergeOrderId' => $data['order_id'],
            'iUin' => $data['uin'],
            'iTotalPrice' => $data['total_price'],
            'iCoupon' => 0,
            'iAmount' => 0,
            'iPayAgentType' => $data['pay_type'],
            'iSrc' => $data['src'],
            'iPlatformId' => $data['plat_form'],
            'iIP' => $data['ip'],
            'iLocation' => '',
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );
        if($result = $this->add_row($arr)){
            return $result;
        }else{
            return false;
        }
    }

    /**
     * 支付成功回调
     * @param $uin
     * @param $order_id
     * @param $trans_id
     * @param $pay_time
     * @return bool
     */
    public function set_succ_merage_order($uin,$order_id,$trans_id,$pay_time)
    {
        if(empty($uin) || empty($order_id)) return false;

        if(empty($trans_id)){
            $data = array('iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iPayTime'=>$pay_time,'iLastModTime'=>time());
        }else{
            $data = array('iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iPayTime'=>$pay_time,'iLastModTime'=>time(),'sTransId'=>$trans_id);
        }
        if($result = $this->update_row($data,array('sMergeOrderId'=>$order_id,'iUin'=>$uin))){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除订单,这里是只作订单软删除
     * @param $uin
     * @param $order_id
     * @return bool
     */
    public function del_merage_order($uin,$order_id)
    {
        if(empty($uin) || empty($order_id)) return false;

        $arr = array(
            'iLastModTime'=>time(),
            'iStatus' => Lib_Constants::STATUS_DELETED
        );
        if($result = $this->update_row($arr,$order_id)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 获取订单详细
     * @param $uin
     * @param $order_id
     */
    public function get_merage_order_by_id($uin,$order_id){
        if(empty($order_id)) return false;

        if($info = $this->get_row(array('sMergeOrderId'=>$order_id,'iUin'=>$uin),true)){
            return $info;
        }else{
            return false;
        }
    }


    /**
     * 申请退款
     * @param $uin
     * @param $order_id
     * @param $refund_amount
     * @return bool
     */
    public function refund_merage_order($uin,$order_id,$refund_amount)
    {
        if(empty($uin) || empty($order_id)) return false;

        $arr = array(
            'iLastModTime'=>time(),
            'iRefundingAmount' => 'iRefundingAmount'+$refund_amount,
            'iRefundStatus' => 1
        );
        if($result = $this->update_row($arr,$order_id)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 退款成功回调
     * @param $uin
     * @param $order_id
     * @param $refund_amount
     * @return bool
     */
    public function refuned_merage_order($uin,$order_id,$refund_amount)
    {
        if(empty($uin) || empty($order_id)) return false;

        $status = Lib_Constants::REFUND_STATUS_GOING;
        $order_info = $this->get_row(array('iUin'=>$uin,'sMergeOrderId'=>$order_id));
        if($order_info){
            //判断是否已经全额退款
            if($order_info['iRefundedAmount']+$refund_amount == $order_info['iAmount']){
                $status = Lib_Constants::REFUND_STATUS_END;
            }
        }
        $arr = array(
            'iLastModTime'=>time(),
            'iRefundingAmount' => 'iRefundingAmount'- $refund_amount,
            'iRefundedAmount' => 'iRefundedAmount'+ $refund_amount,
            'iRefundStatus' => $status
        );
        if($result = $this->update_row($arr,$order_id)){
            return true;
        }else{
            return false;
        }
    }
}