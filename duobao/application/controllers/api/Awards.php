<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户奖励接口
 * Class Luckybag
 */
class Awards extends API_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 积分兑换
     */
    public function grant()
    {
        extract($this->cdata);
        if (empty($uin) || empty($act)) {
            $this->log->error('AwardsActivity','params error |  params: '.json_encode($this->cdatac).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->service('awards_service');
        if ($this->awards_service->grant_user_awards($uin, $act, $this->client_id)) {
            $this->render_result(Lib_Errors::SUCC);
        } else {
            $this->log->error('AwardsActivity','grant awards failed |  params: '.json_encode($this->cdatac).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}