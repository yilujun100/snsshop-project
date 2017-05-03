<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weixin extends API_Base {
    /**
     * 微信用户添加或更新
     */
    public function user()
    {
        $this->log->notice('weixin_user',json_encode($this->cdata));
        $wx_user = $this->cdata;
        if ($wx_user && is_array($wx_user)) {
            $this->load->service('user_service');
            if ($uin = $this->user_service->add_or_update_wx_user($wx_user)) {
                return $this->render_result(0, array('uin'=>$uin));
            }
        }
        return $this->render_result(100001);
    }
}