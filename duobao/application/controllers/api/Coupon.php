<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 抵用券接口
 * Class Coupon
 */
class Coupon extends API_Base
{
    /**
     * 取抵用券操作日志列表
     */
    public function log_list()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error('Coupon', 'ActionLog | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $params = array('iUin' => $uin);
        if(!empty($action)) {
            $params['iAction'] = $action;
        }
        if (!empty($bag_id)){
            $params['sExt'] = $bag_id;
        }
        $params['iNum>'] = 0;

        $this->load->model('coupon_action_log_model');
        $row_list = $this->coupon_action_log_model->get_action_log_list($params, array('iAddTime'=> 'desc'), $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $row_list);
    }


    //免费领取券
    public function get_free_coupon()
    {
        extract($this->cdata);
        if(empty($active_type) || empty($uin) || empty($key)){
            $this->log->error('Coupon', 'get coupon | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $active_data = get_variable(Lib_Constants::VARIABLE_FREE_COUPON_ACTIVE_KEY,array());
        if(!in_array($active_type,array_keys($active_data))){
            $this->log->error('Coupon', 'active not found | params error | '.json_encode($this->cdata).' | active_data['.json_encode($active_data).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
        }
        $active = $active_data[$active_type];

        //检查活动配置参数
        if(empty($active) || strtotime($active['start_date']) > time() || strtotime($active['end_date']) < time()){
            $this->log->error('Coupon', 'active date error | params error | '.json_encode($this->cdata).' | active_data['.json_encode($active_data).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::ACTIVE_OUTLINE);
        }

        //不同活动不同的检查
        $ext = array();
        switch($active_type){
            case 'share_audit': //晒单审核送夺宝券
                $this->load->model('share_model');
                list($act_id,$peroid) = period_code_decode($key);
                if(empty($act_id) || empty($peroid)){
                    $this->log->error('Coupon', 'act_id and peroid can not empty | params error | '.json_encode($this->cdata).' | '.__METHOD__);
                    $this->render_result(Lib_Errors::PARAMETER_ERR);
                }
                $share_info = $this->share_model->get_row(array('iUin'=>$uin,'iActId'=>$act_id,'iPeriod'=>$peroid));
                if(empty($share_info)){
                    $this->log->error('Coupon', 'share order is not found | params error| act_id['.$act_id.'] | peroid['.$peroid.'] | '.json_encode($this->cdata).' | '.__METHOD__);
                    $this->render_result(Lib_Errors::PARAMETER_ERR);
                }

                $key = $active_type.'_'.$key;
                $this->load->model('coupon_action_log_model');
                $log = $this->coupon_action_log_model->get_row(array('sKey'=>$key,'iUin'=>$uin));
                if(!empty($log)){
                    $this->render_result(Lib_Errors::BAG_HAVE_GET);
                }
                $ext = array('act_id'=>$act_id,'peroid'=>$peroid,'uin'=>$uin);
                break;

            case 'share_user': //用户分享新增用户送夺宝券
                break;
        }

        //统一调添加券service
        $this->load->service('awards_service');
        $data = array(
            'uin' => $uin,
            'action' => Lib_Constants::AWARDS_TYPE_SHARE,
            'prize_count' => $active['count'], //领取数量
            'platform' => $this->client_id,
            'awards_name' => $active_type,
            'ext' => $ext,
            'key' => $key
        );
        $rs = $this->awards_service->grant_awards_coupon($data);
        if(is_array($rs)){
            $this->log->notice('Coupon', 'add user free coupon succ | '.json_encode($this->cdata).' | data['.json_encode($data).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SUCC,$rs);
        }else{
            $this->log->error('Coupon', 'add user free coupon fail | params error | '.json_encode($this->cdata).' | active_data['.json_encode($active).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}