<?php


class Coupon extends Duogebao_Base
{
    const ORDER_RESULT_AD_POSITION = 3;
    protected $need_login_methods = array('buy_coupon','ajax_coupon_order','get_free_coupon','ajax_free_coupon');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 购买夺宝券
     */
    public function buy_coupon()
    {
        $val = $this->get_post('val');

        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('default',$val);
        $this->assign('callback_url',gen_uri('/coupon/pay_result'));

        $conf = $this->get_api('activity_conf');
        $conf = $conf['retCode'] == 0 ? $conf['retData'] : array();
        $this->render(array('conf'=>$conf));
    }


    //支付后结果页
    public function pay_result()
    {
        $order_id = $this->get_post('order_id');
        $return_code = $this->get_post('return_code');
        $pay_state = $this->get_post('pay_state');

        $banner_advert = $this->get_api('ad_list', array('position_id'=>self::ORDER_RESULT_AD_POSITION));
        $this->assign('banner_advert',empty($banner_advert['retData']) ? array() : $banner_advert['retData']);

        $where['orderby'] = 'hot';
        $where['ordertype'] = 'asc';
        $where['p_index'] = 1;
        $where['p_size'] = 3;
        $list = $this->get_api('active_search',$where);
        $list = $list['retCode'] == 0 ? $list['retData'] : array();

        $this->assign('active_list',$list['list']);

        $this->assign('data',array('order_id'=>$order_id,'return_code'=>$return_code,'pay_state'=>$pay_state));
        $this->render();
    }

    //提交订单
    public function ajax_coupon_order()
    {
        $other = $this->get_post('numberOther',0);
        $activity_id = $this->get_post('activityId',0);
        $stamps_number = $this->get_post('stampsNumber');
        $arr = array('other'=>$other,'id'=>$activity_id,'number'=>$stamps_number,'uin'=>$this->user['uin']);
        $order = $this->get_api('create_coupon_order',$arr);
        if($order['retCode'] != 0){
            $this->log->error('Coupon','create coupon order fail | params['.json_encode($arr).'] | return['.json_encode($order).'] | '.__METHOD__);
            $this->render_result($order['retCode']);
        }

        $this->render_result(Lib_Errors::SUCC,$order['retData']);
    }


    //免费领取券
    public function get_free_coupon()
    {
        $peroid_str = $this->get_post('peroid_str');
        $share_id = $this->get_post('share_id');

        if(empty($peroid_str) || empty($share_id)){
            $this->log->error('Coupon','params error | peroid_str['.$peroid_str.'] | share_id['.$share_id.'] | '.__METHOD__);
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);
        if(empty($act_id) || empty($peroid)){
            $this->log->error('Coupon','params error | peroid_str['.$peroid_str.'] | share_id['.$share_id.'] | '.__METHOD__);
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }

        $share_info = $this->get_api('share_detail',array('share_id'=>$share_id));
        if(empty($share_info) || $share_info['retCode'] != Lib_Errors::SUCC){
            show_error(Lib_Errors::get_error('晒单ID不存在！'));
        }

        $this->assign('share_id',$share_id);
        $this->assign('peroid_str',$peroid_str);
        $this->assign('active_type','share_audit');
        $this->assign('menus_show',false);
        $this->render();
    }

    //请求获取活动免费夺宝券
    public function ajax_free_coupon()
    {
        $peroid_str = $this->get_post('peroid_str');
        $share_id = $this->get_post('share_id');
        $active_type = $this->get_post('active_type');

        if(empty($peroid_str) || empty($share_id) || empty($active_type)){
            $this->log->error('Coupon','params error | peroid_str['.$peroid_str.'] | share_id['.$share_id.'] | active_type['.$active_type.'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);
        if(empty($act_id) || empty($peroid)){
            $this->log->error('Coupon','params error | peroid_str['.$peroid_str.'] | share_id['.$share_id.'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $share_info = $this->get_api('share_detail',array('share_id'=>$share_id));
        if(empty($share_info) || $share_info['retCode'] != Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $rs = $this->get_api('get_free_coupon',array('uin'=>$this->user['uin'],'key'=>$peroid_str,'active_type'=>$active_type));
        if($rs && $rs['retCode'] == Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result(isset($rs['retCode']) ? $rs['retCode'] : Lib_Errors::SVR_ERR);
        }
    }
}