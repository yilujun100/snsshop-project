<?php


defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 小订单MODEL
 * @TODO Active_merage_order_model
 */
class Active_order_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_active_order';                //表名
    protected $table_primary = 'sOrderId';                   //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num= 10;
    protected $db_map_column = array('iUin', 'sOrderId');  //分表字段

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 创建夺宝订单，即小订单
     * @param $uin
     * @param $data
     * @return bool
     */
    public function add_active_order($uin,$data)
    {
        if(empty($uin) || empty($data) || !isset($data['merge_order_id'])) return false;

        $arr = array(
            'sOrderId' => $data['order_id'],
            'sMergeOrderId' => $data['merge_order_id'],
            'iUin' => $data['uin'],
            'iActId' => $data['act_id'],
            'iGoodsId' => $data['goods_id'],
            'iPeroid'  => $data['peroid'],
            'iActType' => $data['act_type'],
            'iBuyType' => $data['buy_type'],
            'iCount' => $data['count'],
            'iUnitPrice' => $data['unit_price'],
            'iTotalPrice' => $data['total_price'],
            'iAmount' => $data['amount'],
            'iPayAgentType' => $data['pay_type'],
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
     * 通过大订单号查询对应的小订单
     * @param $uin
     * @param $order_id
     * @return array|bool|mixed
     */
    public function get_merage_order_by_id($uin,$order_id)
    {
        if(empty($order_id)) return false;

        if($info = $this->get_rows(array('sMergeOrderId'=>$order_id,'iUin'=>$uin),true)){
            return $info;
        }else{
            return false;
        }
    }

    /**
     * 查询小订单
     * @param $uin
     * @param $order_id
     * @return array|bool|mixed
     */
    public function get_order_by_id($uin,$order_id)
    {
        if(empty($order_id)) return false;

        if($info = $this->get_rows(array('sOrderId'=>$order_id,'iUin'=>$uin))){
            return $info;
        }else{
            return false;
        }
    }


    /**
     * 订单成功支付回调
     * @param $uin  用户ID
     * @param $order_id     小订单号
     * @param $trans_id  支付流水
     * @param $pay_time  支付时间，要与大订单支付时间一致
     * @return bool
     */
    public function set_succ_active_order($uin,$order_id,$trans_id,$pay_time)
    {
        if(empty($uin) || empty($order_id)) return false;

        if(empty($trans_id)){
            $data = array('iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iPayTime'=>$pay_time,'iLastModTime'=>time());
        }else{
            $data = array('iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iPayTime'=>$pay_time,'iLastModTime'=>time(),'sTransId'=>$trans_id);
        }
        if($result = $this->update_rows($data,array('sMergeOrderId'=>$order_id,'iUin'=>$uin))){
            return true;
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
    public function refund_active_order($uin,$order_id,$refund_amount)
    {
        if(empty($uin) || empty($order_id)) return false;

        $arr = array(
            'iLastModTime'=>time(),
            'iRefundingAmount' => 'iRefundingAmount'+$refund_amount,
            'iRefundStatus' => 1
        );
        if($result = $this->update_row($arr,array('sOrderId'=>$order_id,'iUin'=>$uin))){
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
    public function refuned_active_order($uin,$order_id,$refund_amount)
    {
        if(empty($uin) || empty($order_id)) return false;

        $status = Lib_Constants::REFUND_STATUS_GOING;
        $order_info = $this->get_row(array('iUin'=>$uin,'sOrderId'=>$order_id));
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
        if($result = $this->update_row($arr,array('sOrderId'=>$order_id,'iUin'=>$uin))){
            return true;
        }else{
            return false;
        }
    }

}