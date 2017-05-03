<?php
/**
 * 用户相关
 * Class User
 */

class User extends Duogebao_Base
{
    protected $need_login = true;
    private static $host_list = array(
        'wtgdev.vikduo.com'
    );

    public function weixin()
    {
        if ($this->input->is_ajax_request()){
            show_error('链接异常!');
        }
        $ref = $this->get('ref', gen_uri('/home/index'));
        if(empty($ref)){
            show_error('ref参数必填!');
        }
        $ref_arr = parse_url($ref);
        if(isset($ref_arr['host']) && !in_array($ref_arr['host'], self::$host_list)) {
            show_error('ref参数解析错误或不合法!');
        }

        $return_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        //校验登陆
        $this->load->service('user_service');
        if($uin = $this->user_service->valid_user_login()) {
            $api_ret = $this->get_api('user_base_info', array('uin' => $uin));
            if ($api_ret['retCode'] == Lib_Errors::SUCC && !empty($api_ret['retData'])) {
                $this->user = $api_ret['retData'];
            }
        }

        if (empty($this->user)) {
            //微信授权取用户信息
            $code = $this->get_post('code',ISMOBILE ? false : 1);
            $state = $this->get_post('state',ISMOBILE ? false : 1);
            if ($code && $state) { //微信oauth2.0授权回跳
                if(!ISMOBILE && in_array(get_ip(),config_item('ip_white_list'))){
                    $user= json_decode('{"subscribe":1,"openid":"oCNCMs1IHZNC1Fyj_LZ9aPnp7xYc","nickname":"ivan","sex":1,"language":"zh_CN","city":"\u6df1\u5733","province":"\u5e7f\u4e1c","country":"\u4e2d\u56fd","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/ReOB2BSUPv3eW4A5CEsoSjCPUZawHfklicm1fmUynuyHiaiaFvwRwlIHCO0C3Q6Lbic4VuWon85zubtFlgpNDJraGFEYOgSJF6zic\/0","subscribe_time":1453961169,"remark":"","groupid":0}',true);
                }else{
                    $user = Lib_WeixinUserOauth2::getWxUserOauth2($code, $state);
                }
                if ($user) {
                    $this->load->model('user_model');
                    $this->user = $this->user_model->format_wx_oauth2_user($user);
                    $api_ret = $this->get_api('get_wx_user', array('openid'=>$user['openid']));
                    if (empty($api_ret['retData'])) {//取不到用户信息
                        $api_ret = $this->get_api('update_wx_user', $user);//更新或添加用户
                        if (!empty($api_ret['retData'])) {
                            $uin = $api_ret['retData'];
                            $this->user['uin'] = $uin;
                        }
                    } else {
                        $user_info = $api_ret['retData'];
                        $this->user['uin'] = $user_info['iUin'];
                    }
                    if ($this->user['uin']) {
                        $this->user_service->fresh_user_login($this->user['uin']);
                    }
                }
            } else { //跳转微信oauth2.0授权登陆
                Lib_WeixinUserOauth2::redirectAuth($return_url);
            }
        }

        if (empty($this->user) || empty($this->user['uin'])) {
            show_error('登陆失败');
        } else {
            redirect($ref);
        }
    }
}