<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 福袋订单模型-model
 */
class Bag_order_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;      //分组名
    protected $table_name = 't_bag_order';              //表名
    protected $table_primary = 'sOrderId';                //主键
    protected $cache_row_key_column = 'sOrderId';         //缓存key字段  可自定义
    protected $need_cache_row = FALSE;                  //缓存key字段  可自定义
    protected $auto_update_time = false;                //添加或修改时自动更新createtime 或updatetime
    protected $can_real_delete = false;                 //允许真删除
    protected $table_num = 10;
    protected $db_map_column =  array('iUin', 'sOrderId');


    /**
     * 取用户订单
     * @param $uin
     * @param $order_id
     */
    public function get_order_info($uin, $order_id)
    {
        if (!$order_id) {
            return false;
        }

        if($uin) {
            $row = $this->get_row(array('iUin'=>$uin, 'sOrderId'=>$order_id), true);
        } else {
            $row = $this->get_row(array('sOrderId'=>$order_id), true);
        }
        return $row;
    }


    public function get_bag_order_by_id($uin,$order_id){
        if (!$uin || !$order_id) {
            return false;
        }

        $row = $this->get_row(array('iUin'=>$uin,'sOrderId'=>$order_id),true);
        return array(
            'sOrderId' => $row['sOrderId'],
            'iUin' => $row['iUin'],
            'iBagId' => $row['iBagId'],
            'iAmount' => $row['iPayAmount'],
            'iCount' => $row['iTotalAmount']/Lib_Constants::COUPON_UNIT_PRICE,
            'iTotalPrice' => $row['iTotalAmount'],
            'iCreateTime' => $row['iCreateTime'],
            'iPayTime' => $row['iPayTime'],
            'iStatus' => $row['iStatus'],
            'iUpdateTime' => $row['iUpdateTime']
        );
    }

    /**
     * 格式化数据
     * @param $order
     */
    public function format_bag_order($order)
    {
        return array(
            'order_id' => $order['sOrderId'],
            'uin' => $order['iUin'],
            'bag_id' => $order['iBagId'],
            'status' => $order['iStatus'],
            'total_amount' => $order['iTotalAmount'],
            'pay_amount' => $order['iPayAmount'],
            'pay_coupon' => $order['iPayCoupon']
        );
    }

    /**
     * 支付成功回调
     * @param $uin
     * @param $order_id
     * @param $trans_id
     * @param $pay_time
     * @return bool
     */
    public function set_order_succ($uin,$order_id,$trans_id,$pay_time, $refund=false)
    {
        if(empty($uin) || empty($order_id)) return false;
        $status = $refund ? Lib_Constants::PAY_STATUS_UNPAID : Lib_Constants::PAY_STATUS_PAID;

        if(empty($trans_id)){
            $data = array('iStatus'=>$status,'iPayTime'=>$pay_time,'iUpdateTime'=>time());
        }else{
            $data = array('iStatus'=>$status,'iPayTime'=>$pay_time,'iUpdateTime'=>time(),'sTransId'=>$trans_id);
        }
        if ($result = $this->update_row($data,array('sOrderId'=>$order_id))){
            return true;
        }else{
            return false;
        }
    }
}