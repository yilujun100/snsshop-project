<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户接口
 * Class User
 */
class User extends API_Base {
    /**
     * 微信用户添加或更新
     */
    public function update_wx_user()
    {
        $wx_user = $this->cdata;
        if ($wx_user && is_array($wx_user)) {
            $this->load->service('user_service');
            $ret = $this->user_service->add_or_update_wx_user($wx_user);
            if ($ret < 0) {
                $this->render_result(Lib_Errors::SVR_ERR);
            } else {
                $this->render_result(Lib_Errors::SUCC,$ret);
            }
            $this->user_service->add_or_update_wtg_user($wx_user);
        } else {
            $this->log->error('User',Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($wx_user).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
    }

    /**
     * 微信用户添加或更新
     */
    public function update_wtg_wx_user()
    {
        $wx_user = $this->cdata;
        if ($wx_user && is_array($wx_user)) {
            $this->load->service('user_service');
            $ret = $this->user_service->add_or_update_wtg_user($wx_user);
            if ($ret < 0) {
                $this->render_result(Lib_Errors::SVR_ERR);
            } else {
                $this->render_result(Lib_Errors::SUCC,$ret);
            }
            $this->user_service->add_or_update_wx_user($wx_user);
        } else {
            $this->log->error('User',Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($wx_user).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
    }

    /**
     * 取微团购用户信息
     */
    public function get_wtg_wx_user()
    {
        extract($this->cdata);
        if (empty($openid)) {
            $this->log->error('User', Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('weixin_user_model');
        $user_info = $this->weixin_user_model->get_wx_user_by_openid($openid);
        if (!$user_info) {
            $this->log->error('User', Lib_Errors::USER_NOT_EXISTS.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::USER_NOT_EXISTS);
        }
        $this->render_result(Lib_Errors::SUCC,$user_info);
    }

    /**
     * 判断是否为微信新用户 - 是则创建新用户
     */
    public function set_wx_new_user()
    {
        extract($this->cdata);
        if (empty($openid)) {
            $this->log->error('User', Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $act_id = empty($act_id) ? 0 : intval($act_id);

        $this->load->model('user_model');
        $user_info = $this->user_model->get_wx_user_by_openid($openid);
        if (empty($user_info)) {//新用户
            $this->load->service('user_service');
            $uin = $this->user_service->add_wx_user(array('openid'=>$openid));
            if ($uin) {
                $this->load->model('wx_new_user_model');
                if ($this->wx_new_user_model->add_row(array('sOpenId'=>$openid, 'iUin'=>$uin, 'iActId'=>$act_id))) {
                    $this->render_result(Lib_Errors::SUCC, $uin);
                } else {
                    $this->render_result(Lib_Errors::SUCC, $uin);
                }
            } else {
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        } else {
            if ($this->user_model->is_new_user($user_info)) {
                $this->load->model('wx_new_user_model');
                if ($this->wx_new_user_model->add_row(array('sOpenId'=>$openid, 'iUin'=>$user_info['iUin'], 'iActId'=>$act_id))) {
                    $this->render_result(Lib_Errors::SUCC, $user_info['iUin']);
                } else {
                    $this->render_result(Lib_Errors::SUCC, $user_info['iUin']);
                }
            } else {
                $this->render_result(Lib_Errors::SUCC, $user_info['iUin']);
            }
        }
    }

    /**
     * 判断是否为微信新用户 - 是则创建新用户
     */
    public function check_wx_new_user()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error('User', Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('wx_new_user_model');
        if ($this->wx_new_user_model->get_row(array('iUin'=>$uin,'iStatus'=>Lib_Constants::STATUS_0))) {
            $this->render_result(Lib_Errors::SUCC, 1);
        } else {
            $this->render_result(Lib_Errors::SUCC, 0);
        }
    }


    public function get_wx_user()
    {
        extract($this->cdata);
        if (empty($openid)) {
            $this->log->error('User', Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_wx_user_by_openid($openid);
        if (!$user_info) {
            $this->log->error('User', Lib_Errors::USER_NOT_EXISTS.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::USER_NOT_EXISTS);
        }
        $this->render_result(Lib_Errors::SUCC,$user_info);
    }

    /**
     * 取用户基本信息
     */
    public function base_info()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error('User', Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_base_info($uin, $this->client_id);
        if (!$user_info) {
            $this->log->error('User', Lib_Errors::USER_NOT_EXISTS.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::USER_NOT_EXISTS);
        }
        $this->render_result(Lib_Errors::SUCC,$user_info);
    }

    /**
     * 取用户基本信息
     */
    public function ext_info()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->log->error('User', Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('user_ext_model');
        $user_info = $this->user_ext_model->get_user_ext_info($uin);
        if (!$user_info) {
            $this->log->error('User', Lib_Errors::USER_NOT_EXISTS.' | params: '.json_encode($this->cdata)).' | '.__METHOD__;
            $this->render_result(Lib_Errors::USER_NOT_EXISTS);
        }
        $this->render_result(Lib_Errors::SUCC,$user_info);
    }
}