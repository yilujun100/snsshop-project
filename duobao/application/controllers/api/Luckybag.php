<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 积分接口
 * Class Luckybag
 */
class Luckybag extends API_Base {

    /**
     * 添加福袋
     */
    public function add()
    {
        $this->cdata['platform'] = $this->client_id;
        $this->load->service('luckybag_service');
        $ret = $this->luckybag_service->pull_bag($this->cdata);
        if (is_numeric($ret) && $ret < 0) {
            $this->render_result($ret);
        }
        $this->render_result(Lib_Errors::SUCC, $ret);
    }

    /**
     * 用户福袋记录
     */
    public function bag_list()
    {
        extract($this->cdata);

        if (empty($uin)) {
            $this->log->error('LuckyBag', 'Log | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $params = array('iUin' => $uin);
        if (isset($is_timeout)) {
            $params['iIsTimeOut'] = intval($is_timeout);
        }
        if (isset($is_paid)) {
            $params['iIsPaid'] = intval($is_paid);
        }
        if (isset($is_done)) {
            $params['iIsDone'] = intval($is_done);
        }
        if (isset($is_done)) {
            $params['iIsDone'] = intval($is_done);
        }
        $no_list = isset($no_list) ? intval($no_list) : 0;

        $this->load->model('lucky_bag_model');
        if ($no_list) {
            $row_list = $this->lucky_bag_model->row_count($params);
        } else {
            $row_list = $this->lucky_bag_model->get_bag_list($params, array('iBagId' => 'desc'), $p_index, $p_size, $no_list);
        }

        $this->render_result(Lib_Errors::SUCC, $row_list);
    }

    /**
     * 用户收到福袋记录
     */
    public function active_list()
    {
        extract($this->cdata);

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $is_timeout = isset($is_timeout) ? intval($is_timeout) : Lib_Constants::BAG_NOT_TIMEOUT;
        $is_done = isset($is_done) ? intval($is_done) : Lib_Constants::BAG_NOT_DONE;
        $is_paid = isset($is_paid) ? intval($is_paid) : Lib_Constants::PAY_STATUS_PAID;
        if (empty($uin)) {
            $this->log->error('LuckyBag', 'Log | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $params = array(
            'iIsPaid' => $is_paid,
            'iIsTimeOut' => $is_timeout,
            'iIsDone' => $is_done,
            'iUin' => $uin
        );

        $this->load->model('lucky_bag_model');
        $row_list = $this->lucky_bag_model->row_list('*', $params, array('iBagId' => 'desc'), $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $row_list);
    }


    /**
     * 福袋操作日志记录
     */
    public function action_log_list()
    {
        extract($this->cdata);
        if (empty($uin) || empty($action)) {
            $this->log->error('LuckyBag', 'LogList | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $params = array(
            'iUin' => $uin,
            'iAction' => $action,
        );

        if (!empty($bag_id)){
            $params['iBagId'] = $bag_id;
        }
        if (!empty($to_uin)){
            $params['sExtend'] = $to_uin;
        }

        $this->load->model('bag_action_log_model');
        $row_list = $this->bag_action_log_model->get_action_log_list($params, array('iAddTime'=> 'desc'), $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $row_list);
    }

    public function is_user_got_bag()
    {
        extract($this->cdata);
        if (empty($uin) || empty($to_uin) || empty($bag_id)) {
            $this->log->error('LuckyBag', 'LogList | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('bag_action_log_model');
        $count = $this->bag_action_log_model->is_user_got_bag($uin, $bag_id, $to_uin);
        $this->render_result(Lib_Errors::SUCC, intval($count));

    }

    /**
     * 福袋订单详情
     */
    public function order_info()
    {
        extract($this->cdata);
        if (empty($order_id)) {
            $this->log->error('LuckyBag', 'BagOrderInfo | params error['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $uin = empty($uin) ? 0 : $uin;
        $this->load->model('bag_order_model');
        $order_info = $this->bag_order_model->get_order_info($uin, $order_id);
        if (!$order_info) {
            $this->log->error('LuckyBag', 'BagOrderInfo | order info not exist['.json_encode($this->cdata).'] | sql ['.$this->bag_order_model->db->last_query().'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->render_result(Lib_Errors::SUCC, $order_info);
    }

    /**
     * 福袋详情
     */
    public function bag_info()
    {
        extract($this->cdata);
        if (empty($uin) || empty($bag_id)) {
            $this->log->error('LuckyBag', 'BagInfo | params error['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('lucky_bag_model');
        $bag_info = $this->lucky_bag_model->get_bag_info($uin, $bag_id);
        if (!$bag_info) {
            $this->log->error('LuckyBag', 'BagInfo | bag info not exist['.json_encode($this->cdata).'] | sql ['.$this->lucky_bag_model->db->last_query().'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->render_result(Lib_Errors::SUCC, $bag_info);
    }

    /**
     * 福袋激活
     */
    public function active_bag()
    {
        extract($this->cdata);

        if (empty($uin) || empty($bag_id)) {
            $this->log->error('LuckyBag', 'ActiveBag | params error['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('luckybag_service');
        $ret = $this->luckybag_service->active_bag($uin, $bag_id);
        if (is_numeric($ret) && $ret<0) {
            $this->render_result($ret);
        } else {
            $this->render_result(Lib_Errors::SUCC);
        }
    }

    /**
     * 用户领取福袋
     */
    public function user_get_bag()
    {
        extract($this->cdata);

        if (empty($uin) || empty($bag_id) || empty($to_user)) {
            $this->log->error('LuckyBag', 'UserGetBag | params error['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('luckybag_service');
        $num = $this->luckybag_service->user_get_bag($uin, $bag_id, $to_user, $this->client_id);
        if ($num < 0 && is_numeric($num)) {
            $this->log->error('LuckyBag', 'ActiveBag | user_get_bag failed | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result($num);
        } else {
            $this->render_result(Lib_Errors::SUCC, $num);
        }
    }
}