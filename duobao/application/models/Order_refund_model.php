<?php


defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 退款MODEL
 *
 */
class Order_refund_model extends MY_Model {

    protected $db_group_name = DATABASE_YYDB;                 //分组名
    protected $table_name = 't_order_refund';                //表名
    protected $table_primary = 'iAutoId';                     //主键
    protected $cache_row_key_column = 'iAutoId';                 //缓存key字段  可自定义
    protected $table_num= 1;
    protected $need_cache_row = false;                  //缓存key字段  可自定义

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 申请退款
     * @param $uin
     * @param $data
     * @return bool
     */
    public function create_refund_order($uin,$data)
    {
        if(empty($uin) || empty($data)) return false;

        $arr = array(
            'sOrderId' => $data['sOrderId'],
            'iUin' => $uin,
            'sToken' => $data['sToken'],
            'sRefundKey' => $this->serial(), //内部退款号
            'iBuyType' => $data['iBuyType'],
            'iPayAgentType' => $data['iPayAgentType'],
            'iRefundPrice' => $data['iRefundPrice'],
            'iRefundCoupon' => $data['iRefundCoupon'],
            'sTransId' => $data['sTransId'],
            'sRemark' => $data['sRemark'],
            'iRefundType' => empty($data['iRefundType']) ? Lib_Constants::REFUND_TYPE_BUY : $data['iRefundType'],
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );
        if($result = $this->add_row($arr)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 退款回调
     * @param $auto_id
     * @param $data
     * @return bool
     */
    public function update_refund_order($auto_id,$data)
    {
        if(empty($data) || empty($auto_id)) return false;

        $arr = array(
            'iLastModTime'=>time(),
            'iTryTimes' => $data['iTryTimes'],
            'iLocked' => $data['iLocked'],
            'sRefundId' => $data['sRefundId'],
            'iRetStatus' => $data['iRetStatus'],
            'iRetCode' => $data['iRetCode'],
            'sRetDetail' => $data['sRetDetail']
        );
        if($result = $this->update_row($arr,$auto_id)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 生成退款refund key
     * @return string
     */
    public function serial()
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr($usec,2,6);

        return date('YmdHis').$usec.rand(10,99);
    }
}