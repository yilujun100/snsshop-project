<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Sign extends Duogebao_Base
{
    protected $need_login_methods = array('index','add');

    public function index()
    {
        echo 'index-index';
    }

    /**
     * 签到
     */
    public function add()
    {
        $api_ret = $this->get_api('action_sign', array('uin'=>$this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $this->render_result(Lib_Errors::SUCC, $api_ret['retData']);
        } elseif ($api_ret['retCode'] == Lib_Errors::USER_SIGNED) {
            $this->render_result(Lib_Errors::SUCC,array(), Lib_Errors::get_error(Lib_Errors::USER_SIGNED));
        } else {
            $this->render_result($api_ret['retCode']);
        }
    }
}