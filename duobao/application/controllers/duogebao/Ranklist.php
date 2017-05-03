<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 充值排序榜
 * Class Ranklist
 * @author leo.zou
 * @date 2016/06/21
 */
class Ranklist extends Duogebao_Base
{
    public $layout_name = null;
    protected $need_login_methods = array('index');
    protected $activity_start_time = 0;
    protected $activity_end_time = 0;

    public function __construct()
    {
        parent::__construct();

        if(empty($this->user)){
            $this->user = $this->get_user();//获取缓存用户信息
        }

        //检查活动时间是否正常
        $date = get_variable('activity_ranklist_config',array());
        if(empty($date)  || !is_array($date) || strtotime($date['start_date']) > time()){
            show_error('亲，活动还未开始哦~~');
        }
        $this->activity_start_time = $date['start_date'];
        $this->activity_end_time = $date['end_date'];
    }


    //排行活动首页
    public function index()
    {
        $list = $this->get_api('get_rank_list');
        if($list['retCode'] != Lib_Errors::SUCC){
            show_error('亲，服务器压力山大哦~~请稍侯刷新再试哦~~');
        }
        $list = $list['retData'];

        //获取用户充值数据
        if(!empty($this->user)){
            $user_pay = $this->get_api('get_my_pay',array('uin'=>$this->user['uin']));
            $user_pay = $user_pay && $user_pay['retCode'] == Lib_Errors::SUCC ? $user_pay['retData'] : array();
        }else{
            $user_pay = array();
        }

        //用户弹幕
        $bullet_screen = $this->get_api('get_order_bullet_screen');
        $bullet_screen = $bullet_screen && Lib_Errors::SUCC == $bullet_screen['retCode'] ? $bullet_screen['retData'] : array();
        foreach($list as $li){
            if(is_robot($li['uin'])){
                $return = $this->get_simulate_recharge($li['total_price']/100);
                $bullet_screen[] = array(
                    'uin' => $li['uin'],
                    'total_price' => $return[0],
                    'present_count' => $return[1],
                    'nick_name' => $li['nick_name'],
                    'head_img' => $li['head_img']
                );
            }
        }
        shuffle($bullet_screen);

        //算出当前排行
        $current_rank = 0;
        foreach($list as $k => $val){
            if($this->user['uin'] == $val['uin']){
                $current_rank = $k+1;
                break;
            }
        }
        $prize_list = get_variable('activity_ranklist_prize',array());
        $this->assign('prize_list',$prize_list);
        $this->assign('bullet_screen',$bullet_screen);
        $this->assign('user_pay',$user_pay);
        $this->assign('user_info',$this->user);
        $this->assign('user_rank',$current_rank);
        $this->assign('list',$list);
        $this->assign('shareData', $this->get_share_info());
        $this->render();
    }


    protected function get_simulate_recharge($money)
    {
        $conf = array(array(2,0),array(5,0),array(10,1),array(30,5),array(50,8),array(100,18));
        $return = 0;
        foreach($conf as $con){
            if($money > $con[0]){
                $return = $con;
            }
        }

        return $return == 0 ? $conf[0] : $return;
    }

    protected function get_share_info()
    {
        $resource_url = $this->config->item('resource_url');
        return array( // 默认
            'shareTitle' => '充值上榜，大爷来！',
            'sendFriendTitle' => '充值上榜，大爷来！',
            'sendFriendDesc' => '【百分好礼】充值上榜就送iPhone6s，限时12h，手快戳！！',
            'shareUrl' => gen_uri('/ranklist/index'),
            'shareImg' => $resource_url.'/images/local_tyrants_v2/share.jpg'
        );
    }

}