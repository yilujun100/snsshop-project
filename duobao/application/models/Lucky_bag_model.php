<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 福袋模型-model
 */
class Lucky_bag_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;      //分组名
    protected $table_name = 't_lucky_bag';              //表名
    protected $table_primary = 'iBagId';                //主键
    protected $cache_row_key_column = 'iBagId';         //缓存key字段  可自定义
    protected $auto_update_time = false;                //添加或修改时自动更新createtime 或updatetime
    protected $can_real_delete = false;                 //允许真删除
    protected $table_num = 10;
    protected $db_map_column = 'iUin';
    protected $need_cache_row = false;

    protected $columns = array('iBagId','iUin','iType','sWish','iCoupon','iPayAmount','iPerson','iPerCoupon','iUsed','iUsedPerson','iIsPaid','iIsDone','iIsTimeOut','iStatus','iStartTime','iEndTime','iUpdateTime');

    /**
     * 取用户福袋
     * @param $uin
     * @param $order_id
     */
    public function get_bag_info($uin, $bag_id)
    {
        if (!$uin || !$bag_id) {
            return false;
        }

        $row = $this->get_row(array('iUin'=>$uin, 'iBagId'=>$bag_id));
       /* if ($row) {
            $row = $this->format_bag_info($row);
        }*/
        return $row;
    }

    /**
     * 取福袋列表
     * @param $params
     * @param $order_by
     * @param int $p_index
     * @param int $p_size
     */
    public function get_bag_list($params, $order_by, $p_index=1, $p_size=10)
    {
        return $this->row_list(implode(',', $this->columns), $params, $order_by, $p_index, $p_size);
    }

    /**
     * 格式化数据
     * @param $order
     */
    public function format_bag_info($order)
    {
        return array(
            'bag_id' => $order['iBagId'],
            'uin' => $order['iUin'],
            'type' => $order['iType'],
            'wish' => $order['sWish'],
            'status' => $order['iStatus'],
            'coupon' => $order['iCoupon'],
            'pau_amount' => $order['iPayAmount'],
            'person' => $order['iPerson'],
            'per_coupon' => $order['iPerCoupon'],
            'used' => $order['iUsed'],
            'used_person' => $order['iUsedPerson'],
            'is_paid' => $order['iIsPaid'],
            'is_timeout' => $order['iIsTimeOut'],
            'start_time' => $order['iStartTime'],
            'end_time' => $order['iEndTime'],
            'total_amount' => $order['iTotalAmount'],
            'pay_amount' => $order['iPayAmount'],
            'pay_coupon' => $order['iPayCoupon']
        );
    }
}