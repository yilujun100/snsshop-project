<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 大订单MODEL
 */
class Coupon_order_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_coupon_order';                //表名
    protected $table_primary = 'sOrderId';                   //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num= 10;
    protected $db_map_column = array('iUin', 'sOrderId');  //分表字段

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 新增购买夺宝券订单
     * @param $uin
     * @param $data
     * @return bool
     */
    public function add_coupon_order($uin,$data)
    {
        if(empty($uin) || empty($data) || !isset($data['order_id'])) return false;

        $arr = array(
            'sOrderId' => $data['order_id'],
            'iUin' => $data['uin'],
            'iCount' => $data['count'],
            'iPresentCount' => $data['present_count'],
            'iUnitPrice' => $data['unit_price'],
            'iTotalPrice' => $data['total_price'],
            'iSrc' => $data['src'],
            'iPlatformId' => $data['plat_from'],
            'iIP' => $data['ip'],
            'iLocation' => $data['location'],
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );
        if($result = $this->map($uin)->add_row($arr)){
            return true;
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
    public function set_succ_coupon_order($uin,$order_id,$trans_id,$pay_time)
    {
        if(empty($uin) || empty($order_id)) return false;

        if(empty($trans_id)){
            $data = array('iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iPayTime'=>$pay_time,'iLastModTime'=>time());
        }else{
            $data = array('iPayStatus'=>Lib_Constants::PAY_STATUS_PAID,'iPayTime'=>$pay_time,'iLastModTime'=>time(),'sTransId'=>$trans_id);
        }
        if($result = $this->map($uin)->update_row($data,array('sOrderId'=>$order_id,'iUin'=>$uin))){
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
    public function get_coupon_order_by_id($uin,$order_id){
        if(empty($order_id)) return false;

        if($info = $this->get_row(array('sOrderId'=>$order_id,'iUin'=>$uin),true)){
            return $info;
        }else{
            return false;
        }
    }
}