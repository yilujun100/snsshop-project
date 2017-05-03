<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 个人中心
 * Class My
 */
class My extends Duogebao_Base
{
    protected $need_login_methods = array('detail','operate', 'index','luckybag','coupon','score','collect','share','collect','active','ajax_active','address','ajax_score','active_win_order','ajax_addr_confirm','ajax_deliver_confirm','order_add_address');

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 个人中心首页
     */
    public function index()
    {
        $this->assign('menus_active_index', 5);
        $this->assign('user', $this->user);
        $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        $this->assign('user_ext', $user_ext);
        $this->assign('user', $this->user);


        // 未读消息数目
        $msg_count = 0;
        $api_res = $this->get_api('msg_count', array('uin'=>$this->user['uin']));
        if (isset($api_res['retCode']) && is_success($api_res['retCode'])) {
            $msg_count = $api_res['retData'];
        }
        $this->assign('msg_count', $msg_count);

        //可发放福袋总数
        $params = array(
            'uin'=>$this->user['uin'],
            'is_paid'=>Lib_Constants::PAY_STATUS_PAID,
            'is_done'=>Lib_Constants::BAG_NOT_DONE,
            'is_timeout'=>Lib_Constants::BAG_NOT_TIMEOUT,
            'no_list' => 1
        );
        $api_ret = $this->get_api('user_bag_list', $params);
        $act_bag_count = empty($api_ret['retData']) ? 0 : (is_array($api_ret['retData']) ? $api_ret['retData']['count'] : intval($api_ret['retData']));

				/*
        //分享有礼领券提示
        if(!empty($this->user['uin'])){
            $share_invite = $this->get_api('get_share_invite_succ', array('to_uin'=>$this->user['uin']));
        }
        if (!empty($share_invite['retData'])) {
            $share_invite_succ = $share_invite['retData'];
            if ($share_invite_succ['iToStatus'] == Lib_Constants::STATUS_0) {//领券
                $this->get_api('get_share_invite_awards', array('to_uin'=>$this->user['uin'],'act_id'=>Lib_Constants::ACTIVITY_ID, 'sign'=>gen_sign($share_invite_succ['iUin'], Lib_Constants::ACTIVITY_ID)));
            }
        }
				*/

        $this->assign('act_bag_count', $act_bag_count);
        $this->render();
    }

    /**
     * 个人中心-积分记录
     */
    public function score()
    {
        $api_ret = $this->get_api('user_ext_info', array('uin'=>$this->user['uin']));
        $user_ext = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        $this->assign('user_ext', $user_ext);

        $api_ret = $this->get_api('score_log_list', array('uin'=> $this->user['uin'], 'p_index'=>1, 'p_size'=>15));
        $log_list = empty($api_ret['retData']) ? array() : $api_ret['retData'];

        $this->assign('log_list', $log_list);
        $this->render();
    }
    /**
     * ajax - 个人中心-积分记录
     */
    public function ajax_score()
    {
        $api_ret = $this->get_api('user_ext_info', array('uin'=>$this->user['uin']));
        $user_ext = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        $this->assign('user_ext', $user_ext);

        $page_index = $this->get('p_index', 1);
        $api_ret = $this->get_api('score_log_list', array('uin'=> $this->user['uin'], 'p_index'=>$page_index, 'p_size'=>20));

        $log_list = empty($api_ret['retData']) ? array() : $api_ret['retData'];
        $this->load->model('score_action_log_model');

        if(!empty($log_list['list'])) {
            $log_list['list'] = $this->score_action_log_model->format_score_action_list($log_list['list']);
        }
        $this->render_result(Lib_Errors::SUCC, $log_list);
    }

    /**
     * 个人中心-我的晒单
     */
    public function share()
    {
        $p_size = 10;
        $p_index = $this->get('page', 1);
        $api_ret = $this->get_api('share_list', array('uin'=>$this->user['uin'], 'p_index'=>$p_index, 'p_size'=>$p_size));
        $share_list = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        $this->assign('share_list', $share_list);

        //检测是否还有中奖商品没有晒单
        $active_summary = $this->get_api('my_active_winner',array('uin'=>$this->user['uin']));
        $share_count = 0;
        $have_share_count = 0;

        if(!empty($share_list['list']) && !empty($active_summary['retData']['list']))
        {
            foreach($active_summary['retData']['list'] as $summary)
            {
                foreach($share_list['list'] as $share)
                {
                    if($summary['iActId'] == $share['act_id'] && $summary['iPeroid'] == $share['period'])
                    {
                        $share_count++;
                        break;
                    }
                }
            }
            $have_share_count = $active_summary['retData']['count'] - $share_count;
        }
        $this->assign('have_share_count', $have_share_count);

        $where['orderby'] = 'hot';
        $where['ordertype'] = 'asc';
        $where['p_index'] = 1;
        $where['p_size'] = 3;
        $list = $this->get_api('active_search',$where);
        $list = $list['retCode'] == 0 ? $list['retData'] : array();

        $this->assign('active_list',$list['list']);

        $recommend = array();
        if (empty($share_list)) {
            $recommend = array();
        }
        $this->assign('recommend', $recommend);

        $this->render();
    }

    /**
     * 个人中心-我的收藏
     */
    public function collect()
    {
        $list = $this->get_api('my_collect',array('uin'=>$this->user['uin']));
        $list = $list['retCode'] == Lib_Errors::SUCC ? $list['retData'] : array();

        //判断失效的夺宝活动是否有新一期,如有有则new_periods为新一期的期号,没有则为0
        if(!empty($list))
        {
            foreach($list['list'] as $key => $v)
            {
                $block_active_list = $this->get_api('active_currect_list',array('in_str'=>$v['iActId']));
                if($block_active_list['retCode'] == 0 && !empty($block_active_list['retData']))
                {
                    $list['list'][$key]['new_periods'] = $block_active_list['retData'][0]['iPeroid'];
                }
                else
                {
                    $list['list'][$key]['new_periods'] = 0;
                }
            }
        }

        $this->render(array('list'=>$list));
    }

    /**
     * 个人中心-我的收货地址
     */
    public function address()
    {

        $redirect_url = $this->get_post('redirect_url');
        $order_id = $this->get_post('order_id','');
        $business = $this->get_post('business','');
        $is_return = $this->get_post('is_return',0);
        $show_confirm = $this->get_post('show_confirm',2);
        $list = $this->get_api('addr_list',array('uin'=>$this->user['uin']));
        $list = $list['retCode'] == Lib_Errors::SUCC ? $list['retData'] : array();
        if (Lib_Constants::ORDER_TYPE_GROUPON == $business) {
            $this->assign('page_title', '拼团');
            $this->assign('menus_show', false);
        }
        $this->assign('is_return',$is_return);
        $this->assign('show_confirm',$show_confirm);
        $this->assign('order_id',$order_id);
        $this->assign('redirect_url',$redirect_url);
        $this->render(array('list'=>$list));
    }


    public function active()
    {
        $cls = $this->get_post('cls','all');
        $page_index  = $this->get_post('page_index ',1);

        $where = array('iUin'=>$this->user['uin']);
        switch($cls){
            case 'going':
                $where['iLotState'] = Lib_Constants::ACTIVE_LOT_STATE_DEFAULT;
                break;

            case 'opened':
                $where['iLotState'] = Lib_Constants::ACTIVE_LOT_STATE_OPENED;
                break;

            case 'winner':
                $active_summary = $this->get_api('my_active_winner',array('uin'=>$this->user['uin'],'page_size' => 6,'page_index'=>$page_index));
                $active_summary = $active_summary['retCode'] == Lib_Errors::SUCC ? $active_summary['retData'] : array();

                break;

            case 'exchange':
                $active_summary = $this->get_api('my_exchange',array('uin'=>$this->user['uin'],'page_size' => 6,'page_index'=>$page_index));
                $active_summary = $active_summary['retCode'] == Lib_Errors::SUCC ? $active_summary['retData'] : array();
                $active_detail = array();
                foreach($active_summary['list'] as &$item){
                    $peroid_code = period_code_encode($item['iActId'],$item['iPeroid']);
                    if(!isset($active_detail[$peroid_code])){
                        $detail = $this->get_api('active_detail',array('act_id'=>$item['iActId'],'peroid'=>$item['iPeroid']));
                        if($detail['retCode'] == Lib_Errors::SUCC){
                            $active_detail[$peroid_code] = $detail['retData'];
                        }
                    }
                    $item['detail'] = empty($active_detail[$peroid_code]) ? array() : $active_detail[$peroid_code];
                }
                unset($active_detail,$detail);
                break;

            case 'all':
            default:
                break;
        }


        if($cls != 'exchange' && $cls != 'winner'){
            $active_summary = $this->get_api('summary_order',array('where'=>$where,'page_size' => 5,'page_index'=>$page_index ));
            $active_summary = $active_summary['retCode'] == Lib_Errors::SUCC ? $active_summary['retData']: array('list'=>array());

            foreach($active_summary['list'] as $key => &$list){
                if($list['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){
                    $detail = $this->get_api('static_peroid_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }else{
                    $detail = $this->get_api('active_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }
            }
        }

        $addr = $this->get_api('addr_list',array('uin'=>$this->user['uin']));
        $address = $addr['retCode'] == Lib_Errors::SUCC ? $addr['retData'] : array();
        $this->assign('user_address',$address);

        /** 这一段代码是判断中奖用户的奖品状态 Begin */
        if($cls == 'all' || $cls == 'winner' || $cls == 'opened' || $cls == 'going')
        {
            foreach($active_summary['list'] as $key => &$list){

                if($list['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){
                    $detail = $this->get_api('static_peroid_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }else{
                    $detail = $this->get_api('active_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }
                $active_summary['list'][$key]['my_code'] = array();
                if(!empty($list['detail']) && ($cls == 'winner' || $list['iIsWin'] != 0))
                {
                    $deliver = $this->get_api('get_deliver',array('uin'=>$this->user['uin'],'order_id'=>$list['detail']['sWinnerOrder']));
                    if($deliver['retCode'] != Lib_Errors::SUCC){
                        $this->log->error('My','deliver order is not found | '.__METHOD__);
                        $deliver['retData'] = array();
                    }
                    $active_summary['list'][$key]['my_address'] = $addr;
                    $deliver = $deliver['retData'];
                    $deliver['sExtField'] = empty($deliver['sExtField']) ? array() : json_decode($deliver['sExtField'],true);

                    $deliver_status = count($deliver['sExtField'])+1;
                    $active_summary['list'][$key]['deliver_status'] = $deliver_status;
                    $is_active_order = substr($list['detail']['sWinnerOrder'],1,1) == Lib_Constants::ORDER_TYPE_ACTIVE ? true : false;
                    $active_summary['list'][$key]['is_active_order'] = $is_active_order;
                }

                    $order_summary = $this->get_api('get_summary_order',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid'],'uin'=>$this->user['uin']));
                    $order_summary = $order_summary['retCode'] == Lib_Errors::SUCC ? $order_summary['retData'] : array();
                    $my_code = array();
                    foreach($order_summary as $val){
                        if($val['iUin'] == $this->user['uin']){
                            $json_code = json_decode($val['sLuckyCodes']);
                            $my_code = array_merge($my_code,is_array($json_code) ? $json_code : array());
                        }
                    }
                    sort($my_code);
                    $active_summary['list'][$key]['my_code'] = $my_code;


            }
        }
        if($cls == 'exchange')
        {
            foreach($active_summary['list'] as $key => &$list){
                $active_summary['list'][$key]['my_code'] = array();
                $detail = $this->get_api('static_peroid_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();

                if(!empty($list['detail']))
                {
                    $deliver = $this->get_api('get_deliver',array('uin'=>$this->user['uin'],'order_id'=>$list['sOrderId']));
                    if($deliver['retCode'] != Lib_Errors::SUCC){
                        $this->log->error('My','deliver order is not found | '.__METHOD__);
                        show_error('发货订单查询失败!');
                    }

                    $active_summary['list'][$key]['my_address'] = $addr;
                    $deliver = $deliver['retData'];
                    $deliver['sExtField'] = empty($deliver['sExtField']) ? array() : json_decode($deliver['sExtField'],true);
                    $deliver_status = count($deliver['sExtField'])+1;
                    $active_summary['list'][$key]['deliver_status'] = $deliver_status;
                    $is_active_order = substr($list['detail']['sWinnerOrder'],1,1) == Lib_Constants::ORDER_TYPE_ACTIVE ? true : false;
                    $active_summary['list'][$key]['is_active_order'] = $is_active_order;
                }
            }
        }
        /** 这一段代码是判断中奖用户的奖品状态 End */

        $active_summary = empty($active_summary) ? array() : $active_summary;
        $this->assign('cls',$cls);
        $this->assign('user',$this->user);
        $this->assign('list',$active_summary);
        $this->render();
    }

    //中奖订单详情
    public function active_win_order()
    {
        $order_id = $this->get_post('order_id');
        $peroid_str = $this->get_post('peroid_str');
        if(empty($order_id) || empty($peroid_str)){
            $this->log->error('My','order_id is not empty | '.__METHOD__);
            show_error('订单不存在,或参数错误!');
        }

        $deliver = $this->get_api('get_deliver',array('uin'=>$this->user['uin'],'order_id'=>$order_id));
        if($deliver['retCode'] != Lib_Errors::SUCC){
            $this->log->error('My','deliver order is not found | '.__METHOD__);
            show_error('发货订单查询失败!');
        }
        $deliver = $deliver['retData'];
        $deliver['sExtField'] = empty($deliver['sExtField']) ? array() : json_decode($deliver['sExtField'],true);
        $deliver['status'] = count($deliver['sExtField'])+1;

        //查询夺宝详情
        list($act_id,$peroid) = period_code_decode($peroid_str);
        $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        $detail = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
        $this->assign('detail',$detail);
        $this->assign('peroid_str',$peroid_str);

        $addr = $this->get_api('addr_list',array('uin'=>$this->user['uin']));
        $addr = $addr['retCode'] == Lib_Errors::SUCC ? $addr['retData'] : array();
        $this->assign('addr',$addr);
        $this->assign('order_id',$order_id);

        $is_active_order = substr($order_id,1,1) == Lib_Constants::ORDER_TYPE_ACTIVE ? true : false;
        $this->assign('is_active_order',$is_active_order);

        $this->render(array('deliver'=>$deliver));
    }


    //发货填写地址
    public function order_add_address()
    {
        $order_id = $this->get_post('order_id');
        if(empty($order_id)){
            $this->log->error('My','order_id is not empty | '.__METHOD__);
            show_error('订单不存在,或参数错误!');
        }

        $deliver = $this->get_api('get_deliver',array('uin'=>$this->user['uin'],'order_id'=>$order_id));
        if($deliver['retCode'] != Lib_Errors::SUCC){
            $this->log->error('My','deliver order is not found | '.__METHOD__);
            show_error('发货订单查询失败!');
        }
        $deliver = $deliver['retData'];
        $deliver['sExtField'] = empty($deliver['sExtField']) ? array() : json_decode($deliver['sExtField'],true);
        $deliver['status'] = count($deliver['sExtField'])+1;

        //查询夺宝详情
        $detail = $this->get_api('goods_item',array('goods_id'=>$deliver['iGoodsId']));
        $detail = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
        $this->assign('detail',$detail);

        $addr = $this->get_api('addr_list',array('uin'=>$this->user['uin']));
        $addr = $addr['retCode'] == Lib_Errors::SUCC ? $addr['retData'] : array();
        $this->assign('addr',$addr);
        $this->assign('order_id',$order_id);

        $is_active_order = substr($order_id,1,1) == Lib_Constants::ORDER_TYPE_ACTIVE ? true : false;
        $this->assign('is_active_order',$is_active_order);

        $this->render(array('deliver'=>$deliver),'my/activity_order');
    }


    public function ajax_active()
    {
        $cls = $this->get_post('cls','all');
        $page_index  = $this->get_post('p_index',1);

        $where = array('iUin'=>$this->user['uin']);
        switch($cls){
            case 'going':
                $where['iLotState'] = Lib_Constants::ACTIVE_LOT_STATE_DEFAULT;
                break;

            case 'opened':
                $where['iLotState'] = Lib_Constants::ACTIVE_LOT_STATE_OPENED;
                break;

            case 'exchange':
                $active_summary = $this->get_api('my_exchange',array('uin'=>$this->user['uin'],'page_size' => 6,'page_index'=>$page_index));
                $active_summary = $active_summary['retCode'] == Lib_Errors::SUCC ? $active_summary['retData'] : array();
                $active_detail = array();
                foreach($active_summary['list'] as &$item){
                    $peroid_code = period_code_encode($item['iActId'],$item['iPeroid']);
                    if(!isset($active_detail[$peroid_code])){
                        $detail = $this->get_api('active_detail',array('act_id'=>$item['iActId'],'peroid'=>$item['iPeroid']));
                        if($detail['retCode'] == Lib_Errors::SUCC){
                            $active_detail[$peroid_code] = $detail['retData'];
                        }
                    }
                    $item['iLotState'] = 'exchange';
                    $item['sGoodsName'] = isset($active_detail[$peroid_code]) ? $active_detail[$peroid_code]['sGoodsName'] : '';
                    $item['iGoodsId'] = isset($active_detail[$peroid_code]) ? $active_detail[$peroid_code]['iGoodsId'] : '';
                    $item['detail'] = isset($active_detail[$peroid_code]) ? $active_detail[$peroid_code] : array();
                    $item['peroid_str'] = $peroid_code;
                    $item['url'] = gen_uri('/active/detail',array('id'=>$peroid_code));
                    $item['buy_url'] = gen_uri('/pay/active_buy',array('peroid_str'=>$peroid_code),'payment');
                    $item['share_url'] = gen_uri('/active/detail',array('id'=>$peroid_code,'share'=>1));
                    $item['order_detail_url'] = gen_uri('/my/active_win_order',array('order_id'=>$item['sOrderId'],'peroid_str'=>$peroid_code));
                }
                unset($active_detail,$detail);
                break;

            case 'winner':
                $active_summary = $this->get_api('my_active_winner',array('uin'=>$this->user['uin'],'page_size' => 6,'page_index'=>$page_index));
                $active_summary = $active_summary['retCode'] == Lib_Errors::SUCC ? $active_summary['retData'] : array();

                foreach($active_summary['list'] as &$list){
                    $detail = $list;
                    $list['detail'] = $detail;
                    $list['detail']['iLotTime'] = $list['iLotTime'];
                    $list['start_time'] = date('Y/m/d H:i:s');
                    $list['end_time'] =  date('Y/m/d H:i:s',$list['iLotTime']);
                    $list['iLotCount'] = $list['iWinnerCount'];
                    $list['iIsWin'] = 1;

                    $peroid_code = period_code_encode($list['iActId'],$list['iPeroid']);
                    $list['peroid_str'] = $peroid_code;
                    $list['url'] = gen_uri('/active/detail',array('id'=>$peroid_code));
                    $list['buy_url'] = gen_uri('/pay/active_buy',array('peroid_str'=>$peroid_code),'payment');
                    $list['share_url'] = gen_uri('/active/detail',array('id'=>$peroid_code,'share'=>1));
                    $list['order_detail_url'] = gen_uri('/my/active_win_order',array('order_id'=>$list['detail']['sWinnerOrder'],'peroid_str'=>$peroid_code));
                }
                break;

            case 'all':
            default:
                break;
        }

        if($cls != 'exchange' && $cls != 'winner'){
            $active_summary = $this->get_api('summary_order',array('where'=>$where,'page_size' => 5,'page_index'=>$page_index ));
            $active_summary = $active_summary['retCode'] == Lib_Errors::SUCC ? $active_summary['retData']: array('list'=>array());
            foreach($active_summary['list'] as $key => &$list){
                if($list['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){
                    $detail = $this->get_api('static_peroid_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                    $list['detail']['iLotTime'] = $detail['retCode'] == Lib_Errors::SUCC ? date('Y-m-d H:i:s',$detail['retData']['iLotTime']) : '';
                    $list['start_time'] = date('Y/m/d H:i:s');
                    $list['end_time'] =  $detail['retCode'] == Lib_Errors::SUCC ? date('Y/m/d H:i:s',$detail['retData']['iLotTime']) : '';
                }else{
                    $detail = $this->get_api('active_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }

                $peroid_code = period_code_encode($list['iActId'],$list['iPeroid']);
                $list['peroid_str'] = $peroid_code;
                $list['url'] = gen_uri('/active/detail',array('id'=>$peroid_code));
                $list['buy_url'] = gen_uri('/pay/active_buy',array('peroid_str'=>$peroid_code),'payment');
                $list['share_url'] = gen_uri('/active/detail',array('id'=>$peroid_code,'share'=>1));
                $list['order_detail_url'] = gen_uri('/my/active_win_order',array('order_id'=>$list['detail']['sWinnerOrder'],'peroid_str'=>$peroid_code));

            }
        }

        /** 这一段代码是判断中奖用户的奖品状态 Begin */
        if($cls == 'all' || $cls == 'winner' || $cls == 'opened' || $cls == 'going')
        {
            foreach($active_summary['list'] as $key => &$list){
                $active_summary['list'][$key]['my_code'] = array();
                if($list['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){
                    $detail = $this->get_api('static_peroid_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }else{
                    $detail = $this->get_api('active_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                    $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();
                }

                if(!empty($list['detail']) && ($cls == 'winner' || $list['iIsWin'] != 0))
                {
                    $deliver = $this->get_api('get_deliver',array('uin'=>$this->user['uin'],'order_id'=>$list['detail']['sWinnerOrder']));
                    if($deliver['retCode'] != Lib_Errors::SUCC){
                        $this->log->error('My','deliver order is not found | '.__METHOD__);
                        $deliver['retData'] = array();
                    }
                    $deliver = $deliver['retData'];
                    $deliver['sExtField'] = empty($deliver['sExtField']) ? array() : json_decode($deliver['sExtField'],true);
                    $deliver_status = count($deliver['sExtField'])+1;
                    $active_summary['list'][$key]['deliver_status'] = $deliver_status;
                    $is_active_order = substr($list['detail']['sWinnerOrder'],1,1) == Lib_Constants::ORDER_TYPE_ACTIVE ? true : false;
                    $active_summary['list'][$key]['is_active_order'] = $is_active_order;
                }

                $order_summary = $this->get_api('get_summary_order',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid'],'uin'=>$this->user['uin']));
                $order_summary = $order_summary['retCode'] == Lib_Errors::SUCC ? $order_summary['retData'] : array();
                $my_code = array();
                foreach($order_summary as $val){
                    if($val['iUin'] == $this->user['uin']){
                        $json_code = json_decode($val['sLuckyCodes']);
                        $my_code = array_merge($my_code,is_array($json_code) ? $json_code : array());
                    }
                }
                sort($my_code);
                $active_summary['list'][$key]['my_code'] = implode(',',$my_code);

                $peroid_code = period_code_encode($list['iActId'],$list['iPeroid']);

                $list['share_add_url'] = gen_uri('/share/add',array('period_code'=>$peroid_code));
                $list['address_redirect_url'] = gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$list['detail']['sWinnerOrder'],'peroid_str'=>$peroid_code)),'is_return'=>1,'show_confirm'=>1,'order_id'=>$list['detail']['sWinnerOrder']));
//                $list['address_redirect_url'] = gen_uri('/my/active_win_order',array('order_id'=>$list['detail']['sWinnerOrder'],'peroid_str'=>$peroid_code,'is_return'=>1));

            }
        }
        if($cls == 'exchange')
        {
            foreach($active_summary['list'] as $key => &$list){
                $active_summary['list'][$key]['my_code'] = array();
                $detail = $this->get_api('static_peroid_detail',array('act_id'=>$list['iActId'],'peroid'=>$list['iPeroid']));
                $list['detail'] = $detail['retCode'] == Lib_Errors::SUCC ? $detail['retData'] : array();

                if(!empty($list['detail']))
                {
                    $deliver = $this->get_api('get_deliver',array('uin'=>$this->user['uin'],'order_id'=>$list['sOrderId']));
                    if($deliver['retCode'] != Lib_Errors::SUCC){
                        $this->log->error('My','deliver order is not found | '.__METHOD__);
                        $deliver['retData'] = array();
                    }
                    $deliver = $deliver['retData'];
                    $deliver['sExtField'] = empty($deliver['sExtField']) ? array() : json_decode($deliver['sExtField'],true);
                    $deliver_status = count($deliver['sExtField'])+1;
                    $active_summary['list'][$key]['deliver_status'] = $deliver_status;
                    $is_active_order = substr($list['detail']['sWinnerOrder'],1,1) == Lib_Constants::ORDER_TYPE_ACTIVE ? true : false;
                    $active_summary['list'][$key]['is_active_order'] = $is_active_order;
                }
                $peroid_code = period_code_encode($list['iActId'],$list['iPeroid']);
                $list['address_redirect_url'] = gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$list['detail']['sWinnerOrder'],'peroid_str'=>$peroid_code)),'is_return'=>1,'show_confirm'=>1,'order_id'=>$list['detail']['sWinnerOrder']));
            }
        }
        /** 这一段代码是判断中奖用户的奖品状态 End */

        $active_summary = empty($active_summary) ? array() : $active_summary;
        $this->render_result(Lib_Errors::SUCC,$active_summary);
    }

    public function ajax_addr_confirm()
    {
        $order_id = $this->get_post('order_id');
        $addr = $this->get_api('addr_list',array('uin'=>$this->user['uin']));
        $addr = $addr['retCode'] == Lib_Errors::SUCC ? $addr['retData'] : array();

        if(empty($addr)){
            $this->render_result(Lib_Errors::ADDRESS_NOT_FOUND);
        }

        $data = array(
            'uin' => $this->user['uin'],
            'order_id' => $order_id,
            'name' => $addr['sName'],
            'address' => $addr['sProvince'].' '.$addr['sCity'].' '.$addr['sDistrict'].' '.$addr['sAddress'],
            'mobile' => $addr['sMobile']
        );
        if(!$this->get_api('confirm_addr',$data)){
            $this->render_result(Lib_Errors::SVR_ERR);
        }else{
            $this->render_result(Lib_Errors::SUCC);
        }
    }

    public function ajax_deliver_confirm()
    {
        $order_id = $this->get_post('order_id');
        if(empty($order_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        if(!$this->get_api('confirm_deliver',array('uin'=>$this->user['uin'],'order_id'=>$order_id))){
            $this->render_result(Lib_Errors::SVR_ERR);
        }else{
            $this->render_result(Lib_Errors::SUCC);
        }
    }


    public function ajax_exchange()
    {
        $page_index  = $this->get_post('page_index ',1);
    }
}
