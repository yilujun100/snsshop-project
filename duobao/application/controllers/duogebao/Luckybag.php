<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 福袋
 * Class My
 */
class Luckybag extends Duogebao_Base
{
    public $layout_name = 'luckybag';
    protected $wx_share_key = 'luckybag';

    public function __construct()
    {
        parent::__construct(array('luckybag_url','resource_url'));
    }

    protected $need_login_methods = array('records','operate', 'index', 'pull', 'pull_bag','info','open','coupon','ajax_coupon','my_bags','act_bags','pull_bags');
    /**
     * 发福袋首页
     */
    public function index()
    {
        $this->assign('user', $this->user);
        $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        $this->assign('user_ext', $user_ext);
        $this->assign('user', $this->user);
        $this->render();
    }

    /**
     * 发福袋
     */
    public function pull()
    {
        $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        $this->assign('user_ext', $user_ext);
        $this->assign('user', $this->user);
        $this->render();
    }

    /**
     * 个人中心-福袋记录
     */
    public function records()
    {
        $this->assign('user', $this->user);
        $this->assign('body_tab_init', true);
        //可发福袋
        $params = array(
            'uin'=>$this->user['uin'],
            'is_paid'=>Lib_Constants::PAY_STATUS_PAID,
            'is_done'=>Lib_Constants::BAG_NOT_DONE,
            'is_timeout'=>Lib_Constants::BAG_NOT_TIMEOUT,
            'p_index' => 1,
            'p_size' => 10,
        );
        $api_ret = $this->get_api('user_bag_list', $params);
        $act_bag = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        //收到的福袋
        $params = array(
            'uin'=>$this->user['uin'],
            'action'=>Lib_Constants::BAG_ACTION_GET,
            'p_index' => 1,
            'p_size' => 10,
        );
        $api_ret = $this->get_api('coupon_log_list', $params);
        $my_bag = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        //已发放福袋
        $params = array(
            'uin'=>$this->user['uin'],
            'is_paid'=>Lib_Constants::PAY_STATUS_PAID,
            'p_index' => 1,
            'p_size' => 10,
        );
        $api_ret = $this->get_api('user_bag_list', $params);
        $pull_bag = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        $this->assign('act_bag', $act_bag);//可发送
        $this->assign('pull_bag',$pull_bag);//已发送的
        $this->assign('my_bag', $my_bag); //我收到的
        $this->render();
    }


    /**
     * ajax - 已发送福袋列表
     */
    public function pull_bags()
    {
        if ($this->input->is_ajax_request()) {
            $p_index = $this->get('p_index', 1);

            $params = array(
                'uin'=>$this->user['uin'],
                'is_paid'=>Lib_Constants::PAY_STATUS_PAID,
                'p_index' => $p_index,
                'p_size' => 10,
            );
            $api_ret = $this->get_api('user_bag_list', $params);
            $act_bag = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];
            if (!empty($act_bag['list'])) {
                $act_bag['list'] = $this->format_act_bag_list($act_bag['list']);
            }
            $this->render_result(Lib_Errors::SUCC, $act_bag);
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * ajax - 可发送福袋列表
     */
    public function act_bags()
    {
        if ($this->input->is_ajax_request()) {
            $p_index = $this->get('p_index', 1);

            $params = array(
                'uin'=>$this->user['uin'],
                'is_paid'=>Lib_Constants::PAY_STATUS_PAID,
                'is_done'=>Lib_Constants::BAG_NOT_DONE,
                'is_timeout'=>Lib_Constants::BAG_NOT_TIMEOUT,
                'p_index' => $p_index,
                'p_size' => 10,
            );
            $api_ret = $this->get_api('user_bag_list', $params);
            $act_bag = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];
            if (!empty($act_bag['list'])) {
                $act_bag['list'] = $this->format_act_bag_list($act_bag['list']);
            }
            $this->render_result(Lib_Errors::SUCC, $act_bag);
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * ajax - 收到的福袋
     */
    public function my_bags()
    {
        if ($this->input->is_ajax_request()) {
            $p_index = $this->get('p_index', 1);
            //收到的福袋
            $params = array(
                'uin'=>$this->user['uin'],
                'action'=>Lib_Constants::BAG_ACTION_GET,
                'p_index' => $p_index,
                'p_size' => 10,
            );
            $api_ret = $this->get_api('coupon_log_list', $params);
            $my_bag = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];
            if (!empty($my_bag['list'])) {
                $my_bag['list'] = $this->format_my_bags($my_bag['list']);
            }
            $this->render_result(Lib_Errors::SUCC, $my_bag);
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * ajax - 发福袋
     */
//    public function pull_bag()
//    {
//        if ($this->input->is_ajax_request()) {
//            $type = $this->post('type', '');
//            $wish = $this->post('wish', Lib_Constants::LUCKY_BAG_WISH);
//            $wish || $wish = Lib_Constants::LUCKY_BAG_WISH;
//            $people = $this->post('people', '');
//            $coupon = $this->post('coupon', '');
//
//            $peopleNum = $this->post('peopleNum', '');
//            $perPeople = $this->post('perPeople', '');
//
//            $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
//            if ($api_ret['retCode'] == Lib_Errors::SUCC) {
//                $user_ext = $api_ret['retData'];
//            }
//
//            if (empty($user_ext)) {
//                $this->render_result(Lib_Errors::PARAMETER_ERR);
//            }
//            switch ($type){
//                case Lib_Constants::BAG_TYPE_NORMAL:
//                    if(empty($peopleNum) || empty($perPeople)){
//                        $this->render_result(Lib_Errors::PARAMETER_ERR);
//                    }
//                    $params['per_coupon'] = $perPeople;
//                    $params['person'] = $peopleNum;
//                    break;
//                case Lib_Constants::BAG_TYPE_RAND:
//                    if($people<= 0 || $coupon<= 0 || $people>$coupon){
//                        $this->render_result(Lib_Errors::PARAMETER_ERR);
//                    }
//                    $params['person'] = $people;
//                    $params['coupon'] = $coupon;
//                    break;
//                default:
//                    $this->render_result(Lib_Errors::PARAMETER_ERR);
//            }
//
//            $params = array_merge(array('uin'=>$this->user['uin'],'type'=>$type,'wish'=>$wish),$params);
//            $api_ret = $this->get_api('luckybag_add', $params);
//            if ($api_ret['retCode'] != Lib_Errors::SUCC) {
//                $this->render_result(Lib_Errors::SVR_ERR);
//            }
//
//            $order_info = $api_ret['retData'];
//            if ($order_info['is_paid']) {
//                $this->render_result(Lib_Errors::SUCC, $order_info);
//            } else {
//                $this->config->load('pay');
//                $pay_url = $this->config->item('pay_url');
//                $url = $pay_url.'?'.http_build_query(array('order_id'=>$order_info['order_id']));
//                $this->render_result(Lib_Errors::SUCC,array('url'=>$url));
//            }
//        } else {
//            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
//        }
//    }

    public function pull_bag()
    {
        if ($this->input->is_ajax_request()) {
            $type = $this->post('type', '');
            $wish = $this->post('wish', Lib_Constants::LUCKY_BAG_WISH);
            $wish || $wish = Lib_Constants::LUCKY_BAG_WISH;
            $people = $this->post('people', '');
            $coupon = $this->post('coupon', '');

            $peopleNum = $this->post('peopleNum', '');
            $perPeople = $this->post('perPeople', '');

            $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
            if ($api_ret['retCode'] == Lib_Errors::SUCC) {
                $user_ext = $api_ret['retData'];
            }

            if (empty($user_ext)) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
            $coupon_count = 0;
            switch ($type){
                case Lib_Constants::BAG_TYPE_NORMAL:
                    if(empty($peopleNum) || empty($perPeople)){
                        $this->render_result(Lib_Errors::PARAMETER_ERR);
                    }
                    $params['per_coupon'] = $perPeople;
                    $params['person'] = $peopleNum;
                    $coupon_count = $perPeople;
                    break;
                case Lib_Constants::BAG_TYPE_RAND:
                    if($people<= 0 || $coupon<= 0 || $people>$coupon){
                        $this->render_result(Lib_Errors::PARAMETER_ERR);
                    }
                    $params['person'] = $people;
                    $params['coupon'] = $coupon;
                    $coupon_count = $coupon;
                    break;
                default:
                    $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $params = array_merge(array('uin'=>$this->user['uin'],'type'=>$type,'wish'=>$wish),$params);
            $api_ret = $this->get_api('luckybag_add', $params);
            if ($api_ret['retCode'] != Lib_Errors::SUCC) {
                $this->render_result(Lib_Errors::SVR_ERR);
            }

            $order_info = $api_ret['retData'];
            if ($order_info['is_paid']) {
                $this->render_result(Lib_Errors::SUCC, $order_info);
            } else {
                $pay_redirect = $this->get_post('callback_url',$this->config->item('pay_redirect'));
                //$pay_coupon = abs($user_ext['coupon']-$coupon_count);
                $pay_coupon = $coupon_count;
                $disabled = false;
                $pay_disabled = false;
                $this->config->load('pay');
                $pay_url = $this->config->item('pay_url');
                $url = $pay_url.'?'.http_build_query(array('order_id'=>$order_info['order_id']));
                $this->render_result(Lib_Errors::SUCC,array('url'=>$url,'pay_redirect'=>$pay_redirect,'order_id'=>$order_info['order_id'],'pay_coupon'=>$pay_coupon,'disabled'=>0,'pay_disabled'=>0,'payagent'=>Lib_Constants::ORDER_PAY_TYPE_WX));
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * ajax - 打开福袋
     */
    public function open()
    {
        if ($this->input->is_ajax_request()) {
            $bag_id = $this->post('bagId',0);
            $uin = $this->post('uin',0);
            $sign = $this->post('sign',0);

            if(empty($bag_id) || empty($uin) || empty($sign)){
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }elseif(gen_sign($uin,$bag_id) !== $sign){
                $this->render_result(Lib_Errors::SIGN_ERROR);
            }

            $api_ret = $this->get_api('user_get_bag', array('uin'=>$uin, 'bag_id'=>$bag_id, 'to_user'=>$this->user['uin']));
            if ($api_ret['retCode'] == Lib_Errors::SUCC && $api_ret['retData']) {
                $this->render_result(Lib_Errors::SUCC, intval($api_ret['retData']));
            } else {
                $this->render_result($api_ret['retCode']);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }


    /**
     * 福袋详情页
     */
    public function info()
    {
        $bag_id = $this->get('bag_id',0);//有可能也传的是订单号
        $sign = $this->get('sign','');
        $uin = $this->get('uin','');
        $isload = $this->get('isload',0);

        if (!$bag_id) {
            show_error('参数错误!');
        }

        //订单号 取订单信息
        if (strlen($bag_id) == 24) {
            $api_ret= $this->get_api('bag_order_info', $uin ? array('uin'=>$uin, 'order_id'=>$bag_id) : array('order_id'=>$bag_id));
            $bag_order_info = empty($api_ret['retData'])? array() : $api_ret['retData'];
            if ($bag_order_info) {
                $bag_id = $bag_order_info['iBagId'];
                $uin = $bag_order_info['iUin'];
            }
        }
        if (empty($bag_id) || !$uin) {
            show_error('参数错误!');
        }

        //福袋信息
        $api_ret= $this->get_api('bag_info', array('uin'=>$uin, 'bag_id'=>$bag_id));
        $bag_info = empty($api_ret['retData'])? array() : $api_ret['retData'];
        if (empty($bag_info)) {
            $this->log->error('LuckyBag', '获取福袋信息失败! Param:'.json_encode(array($uin,$bag_id,$sign)).' | '.__METHOD__);
            show_error('获取福袋信息失败!');
        }

        //uri上uin用户
        $api_ret = $this->get_api('user_base_info', array('uin'=>$uin));
        $bag_user = empty($api_ret['retData'])? array() : $api_ret['retData'];

        //检查是否为福袋本人
        $is_my = 'false';
        if ($uin == $this->user['uin']) {
            //激活福袋
            if ($bag_info['iStatus'] == Lib_Constants::BAG_STATUS_NORMAL) {
                $this->get_api('active_bag', array('uin'=>$this->user['uin'],'bag_id'=>$bag_id));
            }
            $is_my = 'true';
        } elseif (empty($sign)) {
            $this->log->error('LuckyBag', 'sign参数为空! Param:'.json_encode(array($uin,$bag_id,$sign)).' | '.__METHOD__);
            show_error('sign参数不能为空!');
        } elseif (gen_sign($uin,$bag_id) !== $sign){
            $this->log->error('LuckyBag', 'sign参数检验不通过! Param:'.json_encode(array($uin,$bag_id,$sign)).' | '.__METHOD__);
            show_error('sign参数错误!');
        }
        $sign = empty($sign) ? gen_sign($uin,$bag_id) : $sign;
        $this->assign('sign',$sign);
        $this->assign('is_my',$is_my);

        //查看是否领取过
        $api_ret = $this->get_api('is_user_got_bag', array('uin'=>$uin,'to_uin'=>$this->user['uin'],'bag_id'=>$bag_id));
        $log_count = empty($api_ret['retData']) ? 0 : intval($api_ret['retData']);
        if($log_count){
            $is_log = 'true';
        }else{
            $is_log = 'false';
        }

        //福袋领取记录
        $api_ret = $this->get_api('bag_action_log_list', array('uin'=>$uin, 'action'=>Lib_Constants::ACTION_USE_COUPON, 'bag_id'=>$bag_id,'p_size'=>1000));
        $log_list = empty($api_ret['retData']) ? false : $api_ret['retData'];
        if(is_array($log_list) && $log_list['count'] > 0){
            foreach($log_list['list'] as &$log){
                $api_ret = $this->get_api('user_base_info', array('uin'=>$log['sExtend']));
                $to_user = empty($api_ret['retData'])? array() : $api_ret['retData'];
                $log['toUser'] = $to_user;
            }
        }else{
            $logList = array();
        }

        //中奖小喇叭
        $active_msg = $this->get_api('active_msg');
        $this->assign('active_msg',empty($active_msg['retData']['list']) ? array() : $active_msg['retData']['list']);

        //疯抢/1元夺宝推荐
        $api_ret = $this->get_api('active_search',array('p_size'=>3));
        $recommend = empty($api_ret['retData']['list']) ? array() : $api_ret['retData']['list'];

        $share_data = array(
            'shareImg' =>$this->config->item('luckybag_url').'images/share_logo.png',
            'shareUrl'=>gen_uri('/luckybag/info', array('bag_id'=>$bag_info['iBagId'], 'uin'=>$bag_info['iUin'], 'sign'=>$sign))
        );
        $this->set_wx_share('luckybag', $share_data);
        $this->assign('isload',$isload);
        $this->assign('recommend',$recommend);//推荐
        $this->assign('bag_user', $bag_user);//福袋用户信息
        $this->assign('is_log', $is_log);//是否已经领取过福袋
        $this->assign('log_list',$log_list);//福袋领取记录
        $this->assign('bag_info',$bag_info);//福袋基础信息

        $this->render();
    }

    /**
     * 个人中心-夺宝券记录
     */
    public function coupon()
    {
        $api_ret = $this->get_api('coupon_log_list', array('uin'=>$this->user['uin'], 'p_index'=>1, 'p_size'=>10));
        $log_list = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        $this->assign('log_list', $log_list);
        $this->render();
    }

    /**
     * ajax - 个人中心-夺宝券记录
     */
    public function ajax_coupon()
    {
        if ($this->input->is_ajax_request()) {
            $p_index = $this->get('p_index', 1);
            $api_ret = $this->get_api('coupon_log_list', array('uin'=>$this->user['uin'], 'p_index'=>$p_index, 'p_size'=>10));
            $log_list = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];
            if (!empty($log_list['list'])) {
                $log_list['list'] = $this->format_coupon_list($log_list['list']);
            }
            $this->render_result(Lib_Errors::SUCC, $log_list);
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * 已发送或可发福袋列表
     * @param $order
     */
    private function format_act_bag_list($list)
    {
        $ret = array();
        if ($list) {
            foreach ($list as $item) {
                $ret[] = array(
                    'bag_id' => $item['iBagId'],
                    'url' => gen_uri('/luckybag/info', array('uin'=>$item['iUin'], 'bag_id'=>$item['iBagId'], 'sign'=>gen_sign($item['iUin'], $item['iBagId']))),
                    'uin' => $item['iUin'],
                    'start_time' => date('Y-m-d H:i:s',$item['iStartTime']),
                    'type' => $item['iType'],
                    'bag' => $item['iType'] == Lib_Constants::BAG_TYPE_NORMAL ? '普通福袋':'拼手气福袋',
                    'not_use' => ($item['iCoupon'] - $item['iUsed']) <=0 ? 0 : ($item['iCoupon'] - $item['iUsed']),
                    'coupon' => $item['iCoupon']
                );
            }
        }
        return $ret;
    }

    /**
     * 收到的福袋
     * @param $list
     * @return array
     */
    private function format_my_bags($list)
    {
        $ret = array();
        if ($list) {
            foreach($list as $item) {
                $bag_info = json_decode($item['sExt'], true);
                $ret[] = array(
                    'log_id' => $item['iLogId'],
                    'uin' => $item['iUin'],
                    'action' => empty(Lib_Constants::$coupon_actions[$item['iAction']]) ? '--' : Lib_Constants::$coupon_actions[$item['iAction']],
                    'num' => $item['iNum'],
                    'add_time' => date('Y-m-d H:i:s', $item['iAddTime']),
                    'nickname' => isset($bag_info['nickname']) ? $bag_info['nickname'] : '',
                    'type' => isset($bag_info['type']) ? $bag_info['type'] : 0,
                    'url' => ($bag_info && is_array($bag_info)) ? gen_uri('/luckybag/info', array('uin'=>$bag_info['uin'], 'bag_id'=>$bag_info['bag_id'], 'sign'=>gen_sign($bag_info['uin'], $bag_info['bag_id']))) : '',
                );
            }
        }
        return $ret;
    }

    /**
     * 抵用券记录
     * @param $list
     * @return array
     */
    public function format_coupon_list($list)
    {
        $ret = array();
        if ($list) {
            foreach($list as $item) {
                $ret[] = array(
                    'log_id' => $item['iLogId'],
                    'uin' => $item['iUin'],
                    'action' => empty(Lib_Constants::$coupon_actions[$item['iAction']]) ? '--' : Lib_Constants::$coupon_actions[$item['iAction']],
                    'num' => Lib_Constants::ACTION_INCOME == $item['iType'] ? '+'.$item['iNum'] : '-'.$item['iNum'],
                    'add_time' => date('Y-m-d H:i:s', $item['iAddTime'])
                );
            }
        }
        return $ret;
    }
}
 