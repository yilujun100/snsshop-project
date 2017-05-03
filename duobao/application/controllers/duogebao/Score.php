<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 积分商城
 * Class Score
 */
class Score extends Duogebao_Base
{
    protected  $need_login_methods = array('mall','exchange','ajax_mall');

    /***
     * 积分商城
     */
    public function mall()
    {
        $api_ret = $this->get_api('user_ext_info', array('uin'=>$this->user['uin']));
        $user_ext = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        $this->assign('user_ext', $user_ext);

        $api_ret = $this->get_api('score_activity_list', array('p_index'=>1, 'p_size'=>4));
        $activity_list = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        $this->assign('activity_list', $activity_list);
        $this->render();
    }

    /**
     * ajax - 积分商城分页
     */
    public function ajax_mall()
    {
        $page_index = $this->get('p_index', 1);
        $api_ret = $this->get_api('score_activity_list', array('p_index'=>$page_index, 'p_size'=>4));
        $activity_list = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        if(!empty($activity_list['list'])) {
            $this->load->model('score_activity_model');
            $activity_list['list'] = $this->score_activity_model->foramt_list($activity_list['list']);
        }
        $this->render_result(Lib_Errors::SUCC, $activity_list);
    }

    /**
     * ajax- 积分兑换
     */
    public function exchange()
    {
        if ($this->input->is_ajax_request()) {
            $act_id = $this->post('act_id', 0);
            $uin = $this->post('uin', 0);
            $score = $this->post('score', 0);
            if (!$uin || $score<=0 || !$act_id || $this->user['uin'] != $uin) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $api_ret = $this->get_api('user_ext_info', array('uin'=>$uin));
            if(empty($api_ret['retData'])) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
            $user_ext = $api_ret['retData'];
            if ($user_ext['score'] <= 0 || $user_ext['score']<$score) {
                $this->render_result(Lib_Errors::SCORE_NOT_ENOUGH);
            }

            $api_ret = $this->get_api('score_exchange', array('uin'=>$uin, 'act_id'=>$act_id));
            if($api_ret['retCode'] == Lib_Errors::SUCC && $api_ret['retData']) {
                $this->render_result(Lib_Errors::SUCC, $api_ret['retData']);
            } else {
                $this->render_result($api_ret['retCode']);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }
}