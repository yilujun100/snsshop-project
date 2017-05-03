<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 百分好礼分享有礼活动
 *
 * Class Activity_share
 */
class Activity_share extends Duogebao_Base
{
    public $layout_name = 'common';



    /**
     * 是否需要验证登陆
     *
     * @var array
     */
    protected $need_login_methods = array('index');

    public function __construct()
    {
        parent::__construct();
        $this->assign('cdn_project_url',$this->config->item('resource_url').'activity/share/');
        $this->add_css(array('share_gift'));
        $this->assign('menus_show', 0);
        $this->set_wx_share('share_invite', array());
    }

    /**
     * 活动首页
     */
    public function index()
    {
        $this->log->notice('Share_invite', 'activity_share::index | uin:'.$this->user['uin'].' | time:'.date('Y-m-d H:i:s').' | url:'.current_url());
        //分享链接
        $sign = gen_sign($this->user['uin'], Lib_Constants::ACTIVITY_ID);
        $this->set_wx_share('share_invite', array('shareUrl'=>gen_uri('/activity_share/detail', array('sign'=>$sign, 'uin'=>$this->user['uin']))));

        //弹幕
        $this->get_invite_succ_list();

        //已领券数
        $invite_coupon_count = $this->get_api('get_invite_coupon_count', array('uin'=>$this->user['uin'], 'act_id'=>Lib_Constants::ACTIVITY_ID));
        $invite_coupon_count = empty($invite_coupon_count['retData']) ? 0 : intval($invite_coupon_count['retData']);
        $this->assign('invite_coupon_count', $invite_coupon_count);

        $this->render();
    }

    /**
     * 点击分享详情页
     */
    public function detail()
    {
        $sign = $this->get('sign', '');
        $uin = $this->get('uin', 0);

        if (empty($sign) || empty($uin) || gen_sign($uin, Lib_Constants::ACTIVITY_ID) != $sign) {
            $this->log->error('Activity_Share', 'params error | '.__METHOD__);
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }

        //校验登录
        $this->get_wx_user(array('sb'=>1, 'act_id'=>Lib_Constants::ACTIVITY_ID));

        if (empty($this->user['uin'])) {
            $this->log->error('Activity_Share', 'user error | '.__METHOD__);
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }
        $this->log->notice('Share_invite', 'activity_share::detail | uin:'.$this->user['uin'].' | time:'.date('Y-m-d H:i:s'));

        $api_ret = $this->get_api('check_wx_new_user', array('uin'=>$this->user['uin']));
        if ($api_ret['retCode'] != Lib_Errors::SUCC) {
            $this->log->error('Activity_Share', 'check wx new user error | '.__METHOD__);
            show_error(Lib_Errors::get_error(Lib_Errors::REQUEST_ERROR));
        }
        $is_new_user  = intval($api_ret['retData']);

        if($is_new_user) {
            if(!empty($this->user['uin'])){
                $ret =  $this->get_api('get_share_invite_succ', array('to_uin'=>$this->user['uin']));
            }
            if(!empty($ret['retData']) && $ret['retCode'] == Lib_Errors::SUCC) {
                $this->assign('is_awards', 1);
            }
        }

        //弹幕
        $this->get_invite_succ_list();


        $this->assign('is_new', $is_new_user);
        $this->assign('uin', $uin);
        $this->assign('sign', $sign);
        $this->render();
    }

    /**
     * ajax - 领券
     */
    public function ajax_get_coupon()
    {
        if(!$this->input->is_ajax_request()){
            $this->render_result(Lib_Errors::REQUEST_ERROR);
        }

        $sign = $this->post('sign', '');
        $uin = $this->post('uin', 0);

        if (empty($sign) || empty($uin) || gen_sign($uin, Lib_Constants::ACTIVITY_ID) != $sign) {
            $this->log->error('Activity_Share', 'params error | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        //校验登录
        $this->get_wx_user(array('sb'=>1));
        $this->log->notice('Share_invite', 'activity_share::ajax_get_coupon | uin:'.$this->user['uin'].' | time:'.date('Y-m-d H:i:s'));

        if ($uin == $this->user['uin']) {
            $this->log->error('Activity_Share', 'is same user | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        if(!empty($this->user['uin'])){
            $api_ret = $this->get_api('add_share_invite_succ', array('uin'=>$uin, 'to_uin'=>$this->user['uin'], 'act_id'=>Lib_Constants::ACTIVITY_ID));
            if ($api_ret['retCode'] == Lib_Errors::SUCC) {
                $this->render_result(Lib_Errors::SUCC);
            } else {
                $this->render_result($api_ret['retCode']);
            }
        }
        $this->render_result(Lib_Errors::PARAMETER_ERR);
    }

    /**
     * 取邀请成功列表
     */
    private function get_invite_succ_list()
    {
        $api_ret = $this->get_api('share_invite_succ_list', array('act_id'=>Lib_Constants::ACTIVITY_ID));
        $list = (empty($api_ret['retData']) || empty($api_ret['retData']['list'])) ? array() : $api_ret['retData']['list'];
        $default = get_variable(Lib_Constants::VARIABLE_SHARE_INVITE_SUCC_DEFAULT,array());
        $list = $list+$default;
        $this->assign('invite_succ_list', $list);
        return true;
    }
}