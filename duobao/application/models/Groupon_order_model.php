<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团订单 model
 *
 * Class Groupon_order_model
 */
class Groupon_order_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB_USER; // 分组名
    protected $table_name  = 't_groupon_order'; // 表名
    protected $table_primary = 'sOrderId'; // 主键
    protected $cache_row_key_column  = 'sOrderId'; // 缓存key字段
    protected $table_num = 10;
    protected $db_map_column = array('iUin', 'sOrderId'); //分库分表字段
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime

    protected $min_timeout = 172800; // 订单最小超时时间，2天

    /**
     * 验证订单
     *
     * @param $order_id
     * @param $paid
     *
     * @return int|array
     */
    public function valid($order_id, $paid = true)
    {
        // 订单不存在
        if (! ($order = $this->get_row($order_id, true, false))) {
            return Lib_Errors::GROUPON_ORDER_NOT_EXISTS;
        }
        // 订单未支付
        if ($paid && Lib_Constants::PAY_STATUS_PAID != $order['iPayStatus']) {
            return Lib_Errors::GROUPON_ORDER_UNPAID;
        }
        if ($this->is_invalid($order)) {
            return Lib_Errors::ORDER_INVALID;
        }
        // 订单已使用
        $this->load->model('groupon_join_user_model');
        if ($this->groupon_join_user_model->get_row($order_id, true, false)) {
            return Lib_Errors::GROUPON_ORDER_USED;
        }
        return $order;
    }

    /**
     * 判断是否已支付
     * @param $order
     * @return boolp
     */
    public function is_paid($order)
    {
        if (!is_array($order)) {
            $order = $this->get_row(trim($order), true, false);
        }

        if (empty($order)) {
            return false;
        }

        if (Lib_Constants::PAY_STATUS_PAID == $order['iPayStatus'] && !empty($order['sTransId'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将订单标记为已支付状态
     *
     * @param $order_id
     * @param $trans_id
     * @param $pay_time
     *
     * @return bool
     */
    public function set_paid($order_id, $trans_id, $pay_time)
    {
        if (empty($order_id)) {
            return false;
        }
        $data = array(
            'iPayStatus' => Lib_Constants::PAY_STATUS_PAID,
            'iPayTime' => $pay_time,
        );
        if ($trans_id) {
            $data['sTransId'] = $trans_id;
        }
        return $this->update_row($data, $order_id);
    }

    /**
     * 判断订单是否能取消
     *
     * @param $uin
     * @param $order_id
     *
     * @return int|array
     */
    public function can_cancel($uin, $order_id)
    {
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id
        );
        if (! ($order = $this->get_row($where, true, false))) {
            return Lib_Errors::ORDER_NOT_FOUND;
        }
        if (Lib_Constants::PAY_STATUS_UNPAID == $order['iPayStatus']) {
            return $order;
        } else {
            return Lib_Errors::PAYED;
        }
    }

    /**
     * 判断订单是否能删除
     *
     * @param $uin
     * @param $order_id
     *
     * @return int|array
     */
    public function can_delete($uin, $order_id)
    {
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
        );
        if (! ($order = $this->get_row($where, true, false))) {
            return Lib_Errors::ORDER_NOT_FOUND;
        }
        if ($this->is_invalid($order)) {
            return $order;
        } else {
            return Lib_Errors::ORDER_VALID;
        }
    }

    /**
     * 判断订单是否能确认收货
     *
     * @param $uin
     * @param $order_id
     *
     * @return int|array
     */
    public function can_receipt($uin, $order_id)
    {
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
        );
        if (! ($order=$this->get_row($where, true, false))) {
            return Lib_Errors::ORDER_NOT_FOUND;
        }
        if ($this->is_invalid($order)) {
            return Lib_Errors::ORDER_INVALID;
        }
        if (Lib_Constants::PAY_STATUS_UNPAID == $order['iPayStatus']) {
            return Lib_Errors::GROUPON_ORDER_UNPAID;
        }
        if (Lib_Constants::ORDER_DELIVER_ING != $order['iDeliverStatus']) {
            return Lib_Errors::ORDER_UNDELIVERED;
        }
        return $order;
    }

    /**
     * 获取订单状态
     *
     * @param $order
     *
     * @return int
     */
    public function get_state($order)
    {
        if ($this->is_invalid($order)) {
            return Lib_Constants::ORDER_STATE_INVALID;
        }

        if (Lib_Constants::PAY_STATUS_PAID != $order['iPayStatus']) {
            return Lib_Constants::ORDER_STATE_UNPAID;
        }

        if (Lib_Constants::ORDER_DELIVER_DONE == $order['iDeliverStatus']) {
            return Lib_Constants::ORDER_STATE_DONE;
        }

        if (Lib_Constants::ORDER_DELIVER_ING == $order['iDeliverStatus']) {
            return Lib_Constants::ORDER_STATE_DELIVERING;
        }

        if ($order['iRefundedAmount'] > 0) {
            return Lib_Constants::ORDER_STATE_REFUNDED;
        }

        if ($order['iRefundingAmount'] > 0) {
            return Lib_Constants::ORDER_STATE_REFUNDING;
        }

        if ($order['iRebatingAmount'] > 0) {
            return Lib_Constants::ORDER_STATE_REBATING;
        }

        if ($order['iRebatedAmount'] > 0) {
            return Lib_Constants::ORDER_STATE_REBATED;
        }

        return Lib_Constants::ORDER_STATE_UNDELIVERED;
    }

    /**
     * 判断订单是否失效
     *
     * @param $order
     *
     * @return bool
     */
    public function is_invalid($order)
    {
        if (Lib_Constants::PAY_STATUS_PAID == $order['iPayStatus']) {
            return false;
        }
        if (Lib_Constants::ORDER_STATUS_INVALID == $order['iStatus'] ||
            Lib_Constants::ORDER_STATUS_CANCEL == $order['iStatus']) {
            return true;
        }
        $order_timeout = $this->get_timeout();
        return time() - $order['iCreateTime'] > $order_timeout;
    }

    /**
     * 获取订单超时时间
     *
     * @return int
     */
    protected function get_timeout()
    {
        $order_timeout = get_variable('order_timeout');
        if (! $order_timeout || $order_timeout < $this->min_timeout) {
            $order_timeout = $this->min_timeout;
        }
        return (int) $order_timeout;
    }

    /**
     * @param      $params
     * @param bool $from_write
     * @param bool $from_cache
     *
     * @return bool|mixed
     */
    public function get_row($params, $from_write = false, $from_cache=true)
    {
        if (!is_array($params)) {
            $params = array($this->table_primary => $params);
        }
        $params['iStatus !='] = Lib_Constants::ORDER_STATUS_DELETED;
        return parent::get_row($params, $from_write = false, $from_cache=true);
    }
}