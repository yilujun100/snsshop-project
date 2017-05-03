<?php


class Free extends Duogebao_Base
{
    public $layout_name = null;
    protected $need_login_methods = array('index','result');

    public function __construct()
    {
        parent::__construct();
    }


    //0元购首页
    public function index(){
        $share_uin = $this->get_post('share_uid','');

        if(empty($act_id) || empty($peroid)){
            //show_error('夺宝活动参数错误！');
        }

        $this->load->model('user_ext_model');
        $api_ret = $this->get_api('user_ext_info', array('uin'=>$this->user['uin']));
        $user_ext = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        if($user_ext && is_numeric($user_ext['free_time']) && $user_ext['free_time'] < strtotime(date('Y-m-d'))){//每天用户访问，免费夺宝次数加1
            if($this->get_api('free_coupon',array('uin'=>$this->user['uin']))){
                $user_ext['free_coupon'] = $user_ext['free_coupon']+1;
            }
        }

        $detail = $this->get_api('active_free');
        $list = $detail = $detail['retData'];
        if(empty($detail)){
            show_error('暂没有0元夺宝商品！');
        }
        $detail['shareUrl'] = gen_uri('/free/index',array('share_uid'=>$this->user['uin']));
        $this->set_wx_share('free_active', array_merge($detail, array('user'=>$this->user)));

        if(!empty($share_uin)){//分享人
            set_cookie('share_u', $share_uin, 3600*24, $_SERVER['HTTP_HOST']);
        }

        $this->load->driver('cache');
        $peroid = array();
        foreach($list as &$val){
            $peroid_str = period_code_encode($val['iActId'],$val['iPeroid']);
            $cache_key = 'ajax_active_num_'.$peroid_str;
            $cache_data = $this->cache->memcached->get($cache_key);
            $val['cache_data'] = $cache_data;
            $peroid[] = $peroid_str;
        }

        $this->assign('peroid_arr',implode(',',$peroid));
        $this->assign('user_ext',$user_ext);
        $this->assign('detail',$detail);
        $this->assign('list',$list);
        $this->render();
    }


    public function result(){
        $peroid_str = $this->get_post('peroid_str');
        list($act_id,$peroid) = period_code_decode($peroid_str);
        if(empty($act_id) || empty($peroid)){
            show_error('夺宝活动参数错误！');
        }

        $this->load->model('user_ext_model');
        $user_ext = $this->user_ext_model->get_user_by_uin($this->user['uin']);
        $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        $detail = $detail['retData'];
        if(empty($detail)){
            show_error('夺宝活动不存在！');
        }
        $detail['shareUrl'] = gen_uri('/free/index',array('peroid_str'=>$peroid_str,'share_uid'=>$this->user['uin']));
        $this->set_wx_share('free_active', array_merge($detail, array('user'=>$this->user)));

        $this->assign('user_ext',$user_ext);
        $this->assign('peroid_str',$peroid_str);
        $this->assign('detail',$detail);
        $this->render();
    }


    public function ajax_active_num()
    {
        $peroid_str = $this->get_post('peroid_str');
        if(empty($peroid_str)){
            return false;
        }

        $peroid = explode(',',$peroid_str);
        $this->load->driver('cache');

        $cache_data = array();
        foreach($peroid as $str){
            $cache_key = 'ajax_active_num_'.$str;
            $cache_data[] = $this->cache->memcached->get($cache_key);
        }

        return $this->render_result(Lib_Errors::SUCC,json_encode($cache_data));
    }
}