<?php

header("Content-type: text/html; charset=utf-8");
require_once('common/Common.php');
require_once('common/Base.php');
require_once('common/Log.php');


class deliver extends MY_Controller
{
    public function run()
    {
        $user = WTG_BModel_Base::getDataByMapi('http://dev.mapi.gaopeng.com/user/address/list',array('uin'=>'2141318264268524745','type'=>'dev'));
        if(is_array($user) && $user['retData']){
            $address = $user['retData'];
        }else{
            WTG_Lib_Log::debug('User', "用户收货地址没有找到");
            //echo '没有查询到用户信息';exit;
        }

        pr($address);die;

    }
}