<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户晒单接口
 * Class Luckybag
 */
class Share extends API_Base {
    /**
     * 新增用户晒单
     */
    public function add()
    {
        extract($this->cdata);
        if (empty($uin) ||
            !array_key_exists($this->client_id, Lib_Constants::$platforms) ||
            empty($con) ||
            empty($imgs) ||
            ( !empty($con) && (mb_strlen($con)<10 ||  mb_strlen($con)> 500))
        ) {
            $this->log->error('AwardsActivity','Share | params error 1 | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->log->error('AwardsActivity','Share | params error 2 | params:'.json_encode($this->cdata).' | '.__METHOD__);
        //脏词处理
        //$con =

        if(empty($imgs)) {
            $this->log->error('AwardsActivity','Share | params error 3 | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        if(!empty($share_id)) {//修改
            $this->load->model('share_model');
            $row = $this->share_model->get_row(array('iShareId'=>$share_id,'iUin'=>$uin));
            if(empty($row)) {
                $this->log->error('AwardsActivity','Share | params error 4 | params:'.json_encode($this->cdata).' | '.__METHOD__);
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
            if($this->share_model->update_row(array('sContent'=>$con,'sImg'=>json_encode($imgs), 'iUpdateTime'=>time()), array('iShareId'=>$share_id,'iUin'=>$uin))){
                $this->render_result(Lib_Errors::SUCC);
            } else {
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        } else {//新增
            if (empty($act_id) || empty($period)) {
                $this->log->error('AwardsActivity','Share | params error 5 | params:'.json_encode($this->cdata).' | '.__METHOD__);
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
//            $ip = empty($ip) ? ip2long('127.0.0.1') : $ip;
            $ip = ip2long(empty($ip) ? '127.0.0.1' : $ip);
            $this->load->service('operation_service');
            $ret = $this->operation_service->add_action_share($uin, $this->client_id, array('act_id' => $act_id,'period' => $period, 'con'=>$con, 'imgs'=>$imgs, 'ip'=>$ip));
            if ($ret < 0) {
                $this->render_result($ret);
            } else {
                /*//晒单成功则推送消息,此活动还木上线，先注释
                $share_info = $this->operation_service->get_row(array("iShareId"=>intval($ret)));
                $this->load->model('user_model');
                $user = $this->user_model->get_row(array('iUin'=>$uin));
                $this->load->service('push_service');
                $rs = $this->push_service->add_task(
                    Lib_Constants::$msg_business_type[Lib_Constants::MSG_TEM_SHARE_ORDER_PUSH],
                    period_code_encode($act_id,$period).intval($ret).time(),
                    $uin,
                    array(
                        'url' => intval($ret) > 0 ? gen_uri('/share/detail',array('id'=>intval($ret))) : gen_uri('/share/index'),
                        'nick_name'=>$user['sNickName'],
                        'uin' => $uin,
                        'goods_name' => empty($share_info['sGoodsName']) ? '' : $share_info['sGoodsName'],
                        'act_id' => $act_id,
                        'period' => $period,
                        'period_str' => period_code_encode($act_id,$period),
                        'end_date' => date('Y-m-d',strtotime('-7 days'))
                    )
                );
                if($rs < 0){
                    $this->log->error('PushService','add push task fail | params['.json_encode($this->cdata).'] | rs['.$rs.'] | '.__METHOD__);
                }*/
                $this->render_result(Lib_Errors::SUCC, $ret);
            }
        }
    }

    /**
     * 取系统晒单列表
     */
    public function share_list()
    {
        extract($this->cdata);

        $params = array();
        $p_cur = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        $this->load->model('share_model');
        if (isset($uin)) {
            $params['iUin'] = $uin;
        }
        if (isset($act_id)) {
            $params['iActId'] = $act_id;
        }
        if (isset($period)) {
            $params['iPeriod'] = $period;
        }
        $ret = $this->share_model->get_share_list($this->client_id,$params, $p_cur, $p_size);
        if (!empty($ret['list']) && is_array($ret['list'])) {
            if (!empty($to_uin)) {
                $share_ids = array();
                foreach($ret['list'] as $key => $item) {
                    $share_ids[$key] = $item['share_id'];
                    $ret['list'][$key]['is_liked'] = 0;
                }
                if ($share_ids) {
                    $this->load->model('share_user_action_log_model');
                    $list = $this->share_user_action_log_model->get_user_liked_list($to_uin, $share_ids);
                    if ($list) {
                        foreach($share_ids as $k=>$v){
                            if(in_array($v, $list)) {
                                $ret['list'][$k]['is_liked'] = 1;
                            }
                        }
                    }
                }
            }
        } else {
            $ret = array();
        }

        $this->render_result(Lib_Errors::SUCC, $ret);
    }

    public function user_list()
    {
        extract($this->cdata);

        if (empty($uin)) {
            $this->log->error('AwardsActivity','Share | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $p_cur = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $this->load->model('share_model');
        $ret = $this->share_model->get_share_list($this->client_id, $uin, $p_cur, $p_size);
        if ($ret < 0) {
            $this->render_result($ret);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }

    /**
     * 晒单查看/点赞
     */
    public function operate()
    {
        extract($this->cdata);

        if (empty($uin) || empty($type) || empty($share_id) || !in_array($type, Lib_Constants::$share_opts)) {
            $this->log->error('AwardsActivity','Share | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->service('operation_service');
        $ret = $this->operation_service->add_share_operate($uin, $this->client_id, $share_id, $type);
        if ($ret < 0) {
            $this->render_result($ret);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }

    /**
     * 晒单点赞列表【晒单维度】
     */
    public function like_list()
    {
        extract($this->cdata);

        if (empty($uin) || empty($share_id)) {
            $this->log->error('AwardsActivity','Share |  Like_list | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $p_cur = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $this->load->model('share_action_log');
        $ret = $this->share_action_log->get_list($share_id, $this->client_id, Lib_Constants::SHARE_OPT_LIKE, $p_cur, $p_size);
        if (!$ret) {
            $this->log->error('AwardsActivity', 'Share | User_like_list | get user share list failed | params:'.json_encode($this->cdata).' | sql'.$this->share_action_log->db->last_query().' | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }

    /**
     * 晒单点赞列表【用户维度】
     */
    public function user_like_list()
    {
        extract($this->cdata);

        if (empty($uin) ) {
            $this->log->error('AwardsActivity','Share |  User_like_list | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $p_cur = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $this->load->model('share_user_action_log');
        $ret = $this->share_user_action_log->get_list($uin, $this->client_id, Lib_Constants::SHARE_OPT_LIKE, $p_cur, $p_size);
        if (!$ret) {
            $this->log->error('AwardsActivity', 'Share | User_like_list | get user share list failed | params:'.json_encode($this->cdata).' | sql'.$this->share_action_log_model->db->last_query().' | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }

    public function detail()
    {
        extract($this->cdata);

        if (empty($share_id) ) {
            $this->log->error('AwardsActivity','Share |  Detail | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('share_model');
        $ret = $this->share_model->detail($share_id);
        $this->render_result(Lib_Errors::SUCC, $ret);
    }

    /**
     * 是否已点赞
     */
    public function is_liked()
    {
        extract($this->cdata);

        if (empty($share_id) || empty($uin) ) {
            $this->log->error('AwardsActivity','Share |  ISLIKED | params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('share_user_action_log_model');
        $count = $this->share_user_action_log_model->row_count(array('iUin'=>$uin, 'iShareId'=>$share_id, 'iPlatForm'=>$this->client_id));
        if($count >= 0) {
            $this->render_result(Lib_Errors::SUCC, $count);
        } else {
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}