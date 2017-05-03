<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 积分接口接口
 * Class Luckybag
 */
class Score extends API_Base {
    /**
     * 用户积分收支明细列表
     */
    public function log_list()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error('Score','params error | params1:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        if ($p_index<=0 || $p_size <= 0) {
            $this->log->error('Score','params error | params2:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('score_action_log_model');
        if ($score_list = $this->score_action_log_model->get_score_action_list($uin, $this->client_id, $p_index, $p_size, array('iExchangeTime'=> 'desc'))) {
            $this->render_result(Lib_Errors::SUCC, $score_list);
        } else {
            $this->log->error('Score','get score action failed | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }

    /**
     * 积分活动列表
     */
    public function activity_list()
    {
        extract($this->cdata);

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        $now = time();
        $this->load->model('score_activity_model');
        $params = array(
            'iState' => Lib_Constants::ACTIVE_STATE_ONLINE,
            'iStartTime<=' => $now,
            'iEndTime>=' => $now,
        );
        $ret = $this->score_activity_model->get_score_activity_list($params, array(), $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $ret);
    }

    /**
     * 积分兑换
     */
    public function exchange()
    {
        extract($this->cdata);
        if (empty($uin) || empty($act_id)) {
            $this->log->error('Socre', 'Exchange | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('score_service');
        $ret = $this->score_service->exchange($uin, $act_id, 1, $this->client_id);
        if (is_numeric($ret) && $ret<0) {
            $this->render_result($ret);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }
}