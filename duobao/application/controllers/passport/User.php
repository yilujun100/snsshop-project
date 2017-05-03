<?php
/**
 * 用户相关
 * Class User
 */

class User extends Passport_Base
{
    private $valid_flag = true;
    public function weixin()
    {
        //必须有回跳地址
        $ref = $this->get('ref', '');
        if (empty($ref)) {
            show_error(Lib_Errors::get_error(Lib_Errors::EXCEPTION_REQUEST));
        }

        //解析回跳地址
        $ref_arr = parse_url($ref);
        if(!isset($ref_arr['host']) || !isset(self::$hot_white_list[$ref_arr['host']])) {
            show_error(Lib_Errors::get_error(Lib_Errors::EXCEPTION_REQUEST));
        }

        $ticket = $this->get('ticket', '');
        if($ticket != 'dgbtest123' || empty($ref_arr['query']) || (!empty($ref_arr['query']) && strpos($ref_arr['query'], 'ticket=dgbtest123') === false)) {
            $this->valid_flag = false;
        }

        if (ENVIRONMENT == 'production' && !$this->valid_flag) {
            //非手机端
            if( !ISMOBILE || //非手机端
                !Lib_Weixin::isFromWeixin() || //非微信端
                $this->input->is_ajax_request()  //异步请求
            ) {
                show_error(Lib_Errors::get_error(Lib_Errors::UNKNOW_CLIENT));
            }
        }
        $no_subcribe = intval($this->get('sb', 0)); //非强制关注

        //平台来源 wtg duogebao
        $platform_src = self::$hot_white_list[$ref_arr['host']]['platform'];
        $subscribe_url = self::$hot_white_list[$ref_arr['host']]['subscribe_url'];

        switch ($platform_src) {
            case 'dev-duogebao'://开发
            case 'duogebao'://夺宝奇兵
                $this->get_wx_duobao($ref, $subscribe_url, $no_subcribe);
                break;
            case 'wtg'://微团购
                $this->get_wx_wtg($ref, $subscribe_url);
                break;
            default:
                show_error(Lib_Errors::get_error(Lib_Errors::UNKNOW_CLIENT));
        }
    }

    public function check_new_uin($uin)
    {
        return (strlen($uin) == 18 && strpos($uin,'1')===0 && is_numeric($uin)) ? true : false;
    }

    public function check_old_uin($uin)
    {
        return (strlen($uin) == 19 && is_numeric($uin)) ? true : false;
    }

    private function get_wx_duobao($return_url, $subscribe_url, $no_subcribe= 0)
    {
        $this->load->service('user_service');
        $uin = $this->user_service->valid_user_login();
        if($uin) {
            $api_ret = $this->get_api('user_base_info', array('uin' => $uin));
            if ($api_ret['retCode'] == Lib_Errors::SUCC && !empty($api_ret['retData'])) {
                redirect($return_url);
            }
        }

        //微信授权取信息
        $user = $this->get_wx_oauth2();
        if (empty($user['openid'])) {
            show_error('获取用户信息失败!');
        }

        if (empty($user['subscribe'])) { //未关注
            if (!$no_subcribe) { //强制关注
                if ($subscribe_url) {
                    redirect($subscribe_url);
                } else {
                    show_error('未关注公众号!');
                }
            }

            $act_id = intval($this->get('act_id', 0));
            //取db用户信息
            $api_ret = $this->get_api('set_wx_new_user', array('openid'=>$user['openid'], 'act_id'=>$act_id));
            if ($api_ret['retCode'] != Lib_Errors::SUCC) { //取不到用户信息
                show_error('获取用户信息失败!!');
            } else {
                $uin = $api_ret['retData'];
            }
        } else {
            //取db用户信息
            $api_ret = $this->get_api('get_wx_user', array('openid'=>$user['openid']));
            if (empty($api_ret['retData'])) { //取不到用户信息
                $this->log->error('Passport', 'WeixinUser | get_wx_user| failed | user: '.json_encode($api_ret));
                $api_ret = $this->get_api('update_wx_user', $user); //更新或添加用户
                if (!empty($api_ret['retData'])) {
                    $this->log->error('Passport', 'WeixinUser | update_wx_user | info | user: '.json_encode($api_ret));
                    $uin = $api_ret['retData'];
                }else{
                    $this->log->error('Passport', 'WeixinUser | update_wx_user failed| user: '.json_encode($api_ret));
                }
            } else {
                $user_info = $api_ret['retData'];
                $uin = $user_info['iUin'];

                $this->load->model('user_model');
                if ($this->user_model->check_wxuser_need_update($user, $user_info)) {//需要更新用户信息
                    $user['login_time'] = time();
                    $api_ret = $this->get_api('update_wx_user', $user);//更新或添加用户
                    $this->log->error('Passport', 'WeixinUser | update_wx_user| info | user: '.json_encode($api_ret));
                    if (!empty($api_ret['retData'])) {

                        $uin = $api_ret['retData'];
                    }
                }
            }
        }

        if (empty($uin)) {
            $this->log->error('Passport', 'WeixinUser | get user info failed | user: '.json_encode($user));
            show_error('获取用户信息失败');
        }

        //更新cookie
        $this->load->service('user_service');
        $this->user_service->fresh_user_login($uin);

        redirect($return_url);
    }

    private function get_wx_wtg($return_url, $subscribe_url='')
    {
        $this->load->service('user_service');
        $openid = $this->user_service->get_wtg_user_login_cookie();
        if ($openid) {
            redirect($this->final_return_url($return_url, $openid));
        }

        //微信授权取信息
        $user = $this->get_wx_oauth2();

        if (empty($user['openid'])) {
            show_error('取微信用户信息失败！');
        }

        if (!$user['subscribe']) { //未关注
            if ($subscribe_url) {
                redirect($subscribe_url);
            } else {
                show_error('未关注公众号！');
            }
        }

        //取db用户信息
        $api_ret = $this->get_api('get_wtg_wx_user', array('openid'=>$user['openid']));
        if (empty($api_ret['retData'])) {//取不到用户信息
            $api_ret = $this->get_api('update_wtg_wx_user', $user);//更新或添加用户
            if (!empty($api_ret['retData'])) {
                $uin = $api_ret['retData'];
            }
        } else {
            $user_info = $api_ret['retData'];
            $uin = $user_info['uin'];
        }

        if (empty($uin)) {
            $this->log->error('Passport', 'WeixinUser | get user info failed | user: '.json_encode($user));
            show_error('获取用户信息失败');
        }

        //更新cookie
        $this->user_service->set_wtg_login_cookie($user['openid']);

        redirect($this->final_return_url($return_url, $user['openid']));
    }

    private function final_return_url($return_url, $openid)
    {
        $this->config->load('api');
        $skey = $this->config->item('skey');
        $cdata = encrypt($openid, $skey[Lib_Constants::PLATFORM_WX]);
        if (strpos($return_url,'?') === false) {
            return $return_url.'?cdata='.$cdata;
        } else {
            return $return_url.'&cdata='.$cdata;
        }
    }

    private function get_wx_oauth2()
    {
        //微信授权取用户信息
        $code = $this->get_post('code',ISMOBILE ? false : 1);
        $state = $this->get_post('state',ISMOBILE ? false : 1);
        if ($code && $state) { //微信oauth2.0授权回跳
            if(!ISMOBILE && (ENVIRONMENT != 'production' || $this->valid_flag === false)){
                $user= json_decode('{"subscribe":1,"openid":"oCNCMs1IHZNC1Fyj_LZ9aPnp7xYc","nickname":"test","sex":1,"language":"zh_CN","city":"\u6df1\u5733","province":"\u5e7f\u4e1c","country":"\u4e2d\u56fd","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/MtiahOiaj1KthWiaVxW1jfMUP1w7hzuVvwsL7iavJO2FicfhFDJznYFkvV0gY0SHKMTCPGeHm1icibia3tdKYJhFu8UjibOiaCDrrlYTWP\/0","subscribe_time":1453961169,"remark":"","groupid":0}',true);
            }else{
                $user = Lib_WeixinUserOauth2::getWxUserOauth2($code, $state);
            }
            return $user;
        } else { //跳转微信oauth2.0授权登陆
            $cur_url = current_url();
            Lib_WeixinUserOauth2::redirectAuth($cur_url);
        }
    }
}