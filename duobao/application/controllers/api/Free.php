<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户免费券领取接口
 * Class Free
 */
class Free extends API_Base {
    /**
     * 新增用户免费券/免费参与次数
     */
    public function add()
    {
        extract($this->cdata);

        if (empty($uin) || !array_key_exists($this->client_id, Lib_Constants::$platforms)) {
            $this->log->error('Free','params error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('operation_service');
        $ret = $this->operation_service->add_free_coupon($uin, $this->client_id);
        if ($ret < 0) {
            $this->render_result($ret);
        } else {
            $this->render_result(Lib_Errors::SUCC, $ret);
        }
    }
}