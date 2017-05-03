<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 相关活动配置
 * Class Address
 */
class Recharge extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    public function get_activity_conf()
    {
        $this->load->model('recharge_activity_model');
        $conf = $this->recharge_activity_model->get_activity_conf();
        if(!empty($conf)){
            $conf['sConf'] = json_decode($conf['sConf'],true);
        }else{
            $conf = array();
            $conf['sConf'] = Lib_Constants::$recharge_activity_config;
        }

        $this->render_result(Lib_Errors::SUCC,$conf);
    }
}