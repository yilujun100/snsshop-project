<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户签到接口
 * Class Luckybag
 */
class Sign extends API_Base {
    /**
     * 新增用户签到
     */
    public function add()
    {
        extract($this->cdata);

        if (empty($uin) || !array_key_exists($this->client_id, Lib_Constants::$platforms)) {
            $this->log->error('Sign','params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('operation_service');
        $ret = $this->operation_service->add_action_sign($uin, $this->client_id);
        if ($ret < 0) {
            $this->render_result($ret);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }
}