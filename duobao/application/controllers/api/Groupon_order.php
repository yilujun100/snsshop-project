<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团
 * Class User
 */
class Groupon_order extends API_Base
{
    /**
     * 可见字段
     *
     * @var array
     */
    protected $fields = array(
        'sOrderId,iUin,iGoodsId,iGrouponType,iGrouponId,
            iSpecId,iDiyId,iUnitPrice,iCount,iTotalPrice,iFee,iPayAmount,iRealPrice,
            iPayAgentType,iPayStatus,iPayTime,sTransId,iBuyType,
            iRefundedAmount,iRefundingAmount,iRefundStatus,iRebatedAmount,iRebatingAmount,iRebateStatus,
            sName,sMobile,sAddress,iStatus,iDeliverStatus,iCreateTime'
    );

    /**
     * 创建订单检查
     */
    public function create_order_check()
    {
        extract($this->cdata);
        if (empty($uin) || ! $this->check_uin($uin)) {
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        if (empty($buy_type) ||
            ! in_array($buy_type, array_keys(Lib_Constants::$groupon_order))) {
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $groupon_id = empty($groupon_id) ? 0 : $groupon_id;
        $spec_id = empty($spec_id) ? 0 : $spec_id;
        $diy_id = empty($diy_id) ? 0 : $diy_id;
        $this->load->service('groupon_service');
        if (($check = $this->groupon_service->create_order_check($buy_type, $uin, $groupon_id, $spec_id, $diy_id)) < Lib_Errors::SUCC) {
            $this->output_json($check);
        }
        $this->output_json(Lib_Errors::SUCC, array('groupon'=>$check[0],'spec'=>$check[1],'diy'=>$check[2]));
    }

    /**
     * 创建订单
     *
     * @return int
     */
    public function create_order()
    {
        extract($this->cdata);
        if (empty($pay_agent) || ! in_array($pay_agent, array_keys(Lib_Constants::$order_pay_type))) {
            return Lib_Errors::PARAMETER_ERR;
        }
        if (empty($buy_type) || ! in_array($buy_type, array_keys(Lib_Constants::$groupon_order))) {
            return Lib_Errors::PARAMETER_ERR;
        }
        if (empty($address_id)) {
            return Lib_Errors::PARAMETER_ERR;
        }
        if (empty($uin) || ! $this->check_uin($uin)) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $groupon_id = empty($groupon_id) ? 0 : $groupon_id;
        $spec_id = empty($spec_id) ? 0 : $spec_id;
        $diy_id = empty($diy_id) ? 0 : $diy_id;

        $this->load->service('order_service');
        list($order_id, $groupon) = $this->order_service->create_groupon_order($buy_type, $uin, $address_id, $groupon_id, $spec_id, $diy_id, $pay_agent);
        if ($order_id < Lib_Errors::SUCC) {
            $this->output_json($order_id);
        }
        $this->output_json(Lib_Errors::SUCC, array('order_id'=>$order_id,'groupon'=>$groupon));
    }

    /**
     * 获取订单团购信息
     *
     * 主要用于订单支付时
     *
     * @return int
     */
    public function pay_later_data()
    {
        $log_label = "api | groupon order | get_order_groupon";

        extract($this->cdata);
        if (empty($order_id)) {
            $this->log->error($log_label, 'parameter error | order_id empty');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('groupon_order_model');
        if (($order = $this->groupon_order_model->valid($order_id, false)) < Lib_Errors::SUCC) { // 订单无效
            $this->log->error($log_label, 'order invalid', array('code'=>$order,'order_id'=>$order_id));
            $this->output_json($order);
        }
        if (! empty($address_id)) {
            $this->load->model('address_model');
            if (! ($address = $this->address_model->get_address_info_by_id($address_id, $order['iUin']))) { // 收货地址错误
                $this->log->error($log_label, 'deliver address not exist', array('order'=>$order));
                $this->output_json(Lib_Errors::ADDRESS_NOT_FOUND);
            }
            $data = array(
                'sName' => $address['sName'],
                'sMobile' => $address['sMobile'],
                'sAddress' => $address['sAddress']
            );
            if (! $this->address_model->update_row($data, $order_id)) {
                $this->log->error($log_label, 'update address failed', array('order'=>$order));
                $this->output_json(Lib_Errors::UPDATE_ORDER_ADDRESS);
            }
        }

        $this->load->model('groupon_active_model');
        if (($groupon = $this->groupon_active_model->valid($order['iGrouponId'], 1)) < Lib_Errors::SUCC) { // 团购无效
            $this->log->error($log_label, 'groupon invalid', array('code' => $groupon, 'order'=>$order));
            $this->output_json($groupon);
        }

        $this->load->service('groupon_service');
        if (($check = $this->groupon_service->create_order_check($order['iBuyType'],$order['iUin'],$order['iGrouponId'],$order['iSpecId'],$order['iDiyId'])) < Lib_Errors::SUCC) {
            $this->log->error($log_label, 'create order check error', array('code' => $check, 'order'=>$order));
            $this->output_json($check);
        }
        $data = array(
            'order' => $order,
            'groupon' => $groupon,
            'spec' => $check[1],
            'diy' => $check[2],
        );

        $this->output_json(Lib_Errors::SUCC, $data);
    }

    /**
     * 我的订单
     */
    public function my_order_list()
    {
        $log_label = "api | groupon order | my_order";

        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error($log_label, 'uin error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $where = array(
            'iUin' => $uin,
            'iStatus !=' => Lib_Constants::ORDER_STATUS_DELETED
        );
        if (! empty($order_state)) {
            switch ($order_state) {
                case 'unpaid':
                    $where['iPayStatus'] = Lib_Constants::PAY_STATUS_UNPAID;
                    break;
                case 'undelivered':
                    $where['iPayStatus'] = Lib_Constants::PAY_STATUS_PAID;
                    $where['iDeliverStatus'] = Lib_Constants::ORDER_DELIVER_NOT;
                    break;
                case 'delivered':
                    $where['iPayStatus'] = Lib_Constants::PAY_STATUS_PAID;
                    $where['iDeliverStatus'] = Lib_Constants::ORDER_DELIVER_ING;
                    break;
            }
        }
        $order_by = array('iCreateTime'=>'DESC');

        $page_index = ! empty($page_index) ? intval($page_index) : 1;
        $page_size = ! empty($page_size) ? intval($page_size) : self::$PAGE_SIZE;

        $this->load->model('groupon_order_model');

        if($list = $this->groupon_order_model->row_list($this->fields, $where, $order_by, $page_index, $page_size)) {
            $list['sql'] = $this->groupon_order_model->db->last_query();
            $list['where'] = $where;
            if ($list['count'] > 0) {
                $this->load->model('groupon_active_model');
                foreach ($list['list'] as & $v) {
                    $groupon = $this->groupon_active_model->get_row($v['iGrouponId']);
                    $v['sGoodsName'] = $groupon['sGoodsName'];
                    $v['sImg'] = $groupon['sImg'];
                    $v['iState'] = $this->groupon_order_model->get_state($v);
                    $v['sBuyTypeDesc'] = Lib_Constants::$groupon_order[$v['iBuyType']];
                }
            }
            $this->output_json(Lib_Errors::SUCC, $list);
        }
        $this->output_json(Lib_Errors::REQUEST_ERROR);
    }

    /**
     * 订单详情
     */
    public function my_order_detail()
    {
        $log_label = "api | groupon order | order_detail";

        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error($log_label, 'uin error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        if (empty($order_id)) {
            $this->log->error($log_label, 'order_id error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('groupon_order_model');
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
        );
        $order = $this->groupon_order_model->get_row($where);
        if ($order) {
            $this->load->model('groupon_active_model');
            $order['groupon'] = $this->groupon_active_model->get_row($order['iGrouponId']);
            $order['iState'] = $this->groupon_order_model->get_state($order);
            $order['sBuyTypeDesc'] = Lib_Constants::$groupon_order[$order['iBuyType']];
            $this->output_json(Lib_Errors::SUCC, $order);
        } else {
            $this->log->error($log_label, 'order not exist');
            $this->output_json(Lib_Errors::ORDER_NOT_FOUND);
        }
    }

    /**
     * 取消订单
     */
    public function my_order_cancel()
    {
        $log_label = "api | groupon order | my_order_cancel";

        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error($log_label, 'uin error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        if (empty($order_id)) {
            $this->log->error($log_label, 'order_id error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('groupon_order_model');
        if (($order = $this->groupon_order_model->can_cancel($uin, $order_id)) < Lib_Errors::SUCC) {
            $this->output_json($order);
        }
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
        );
        $data = array(
            'iStatus'=>Lib_Constants::ORDER_STATUS_CANCEL
        );
        if ($this->groupon_order_model->update_row($data, $where)) {
            $this->output_json(Lib_Errors::SUCC);
        }
        $this->log->error($log_label, 'cancel order failed', array('uin'=>$uin,'order'=>$order));
        $this->output_json(Lib_Errors::SVR_ERR);

    }

    /**
     * 删除订单
     */
    public function my_order_delete()
    {
        $log_label = "api | groupon order | my_order_delete";

        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error($log_label, 'uin error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        if (empty($order_id)) {
            $this->log->error($log_label, 'order_id error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('groupon_order_model');
        if (($order = $this->groupon_order_model->can_delete($uin, $order_id)) < Lib_Errors::SUCC) {
            $this->output_json($order);
        }
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
        );
        $data = array(
            'iStatus'=>Lib_Constants::ORDER_STATUS_DELETED
        );
        if ($this->groupon_order_model->update_row($data, $where)) {
            $this->output_json(Lib_Errors::SUCC);
        }
        $this->log->error($log_label, 'delete order failed', array('uin'=>$uin,'order'=>$order));
        $this->output_json(Lib_Errors::SVR_ERR);
    }

    /**
     * 确认收货
     */
    public function my_order_receipt()
    {
        $log_label = "api | groupon order | my_order_receipt";

        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error($log_label, 'uin error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        if (empty($order_id)) {
            $this->log->error($log_label, 'order_id error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('groupon_order_model');
        if (($order = $this->groupon_order_model->can_receipt($uin, $order_id)) < Lib_Errors::SUCC) {
            $this->output_json($order);
        }
        $this->load->model('order_deliver_model');
        $where = array('sOrderId'=>$order['sOrderId']);
        $deliver = $this->order_deliver_model->get_row($where, true, false);
        if (! is_array($deliver) || ! isset($deliver['iDeliverStatus'])) {
            $this->log->error($log_label, 'order deliver data exception', array('order'=>$order));
            $this->output_json(Lib_Errors::SVR_ERR);
        }
        if (empty($deliver['sExtField']) || ! ($ext = json_decode($deliver['sExtField'], true))) {
            $this->log->error($log_label, 'deliver trace exception', array('order'=>$order,'deliver'=>$deliver));
            $ext = array();
        }
        $ext[5] = time();
        $deliver_data = array(
            'iConfirmStatus' => 1,
            'sExtField' => json_encode($ext),
        );
        if (! $this->order_deliver_model->update_row($deliver_data, $deliver['iAutoId'])) {
            $this->log->error($log_label, 'update deliver error', array('order'=>$order,'deliver'=>$deliver));
            $this->output_json(Lib_Errors::SVR_ERR);
        }
        $order_data = array(
            'iDeliverStatus'=>Lib_Constants::ORDER_DELIVER_DONE
        );
        if (! $this->groupon_order_model->update_row($order_data, $order_id)) {
            $this->log->error($log_label, 'update groupon order error', array('order'=>$order,'deliver'=>$deliver));
            $this->output_json(Lib_Errors::SVR_ERR);
        }
        $this->output_json(Lib_Errors::SUCC);
    }

    /**
     * 返回订单发货信息
     */
    public function my_order_express()
    {
        $log_label = "api | groupon order | my_order_express";

        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error($log_label, 'uin error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        if (empty($order_id)) {
            $this->log->error($log_label, 'order_id error');
            $this->output_json(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('order_deliver_model');
        $where = array(
            'sOrderId'=>$order_id,
            'iUin'=>$uin
        );
        if (! ($deliver = $this->order_deliver_model->get_row($where))) {
            $this->log->error($log_label, 'order deliver data exception', array('order_id'=>$order_id,'uin'=>$uin));
            $this->output_json(Lib_Errors::SVR_ERR);
        }
        $this->load->model('express_company_model');
        if (! ($express = $this->express_company_model->get_row($deliver['iExpId']))) {
            $this->log->error($log_label, 'deliver express data exception', array('order_id'=>$order_id,'uin'=>$uin,'deliver'=>$deliver));
            $this->output_json(Lib_Errors::SVR_ERR);
        }
        $this->load->model('goods_item_model');
        if (! ($goods = $this->goods_item_model->get_row($deliver['iGoodsId']))) {
            $this->log->error($log_label, 'deliver goods data exception', array('order_id'=>$order_id,'uin'=>$uin,'deliver'=>$deliver));
            $this->output_json(Lib_Errors::SVR_ERR);
        }
        $deliver['sExpExt'] = $express['sExt'];
        $deliver['sGoodsImg'] = $goods['sImg'];
        $deliver['sGoodsName'] = $goods['sName'];
        $this->output_json(Lib_Errors::SUCC, $deliver);
    }
}