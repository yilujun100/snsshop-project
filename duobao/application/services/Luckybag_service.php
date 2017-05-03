<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 福袋相关service
 * Class Luckybag_service
 */
class Luckybag_service extends  MY_Service
{
    /**
     * 发福袋
     * @param $params array
     *  $uin 用户UIN
     *  $platform 平台
     *  $type 发福袋类型  [普通 | 拼手气]
     *  $people 发放人数
     *  $num  券总数
     *  $people_num  每人发放数量
     *  $need_pay 需要支付金额
     *  $wish 祝福语
     */
    public function pull_bag($params)
    {
        extract($params);
        //参数校验
        if (empty($uin) || empty($person) ||  (empty($per_coupon) && empty($coupon)) || empty($type) || !in_array($type, Lib_Constants::$bag_types)) {
            $this->log->error('LuckyBagService', 'PullBag | params error | '.json_encode($params).' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //用户扩展信息
        $this->load->model('user_ext_model');
        $user_ext = $this->user_ext_model->get_user_ext_info($uin, true);
        if (empty($user_ext)) {
            $this->log->error('LuckyBagService', 'PullBag | user ext info not exist or fetch error | uin :'.$uin.' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        !empty($wish) OR $wish=Lib_Constants::LUCKY_BAG_WISH;

        $per_coupon = empty($per_coupon) ? 0 : intval($per_coupon);//[普通福袋]每人领取夺宝券数
        $coupon = empty($coupon) ? 0 : intval($coupon);            //[拼手气福袋]发券总数

        switch ($type) {
            case Lib_Constants::BAG_TYPE_NORMAL:
                $total_coupon = $person*$per_coupon;
                break;
            case Lib_Constants::BAG_TYPE_RAND:
                $total_coupon = $coupon;
                break;
        }

        $use_coupon = $user_ext['coupon'] > $total_coupon ? $total_coupon : $user_ext['coupon'];
        $need_pay_amout = $user_ext['coupon'] > $total_coupon ?  0 : ($total_coupon-$user_ext['coupon']);

        $now_time = time();
        $params = array(
            'iUin' => $uin,
            'iType' => $type,
            'sWish' => $wish,
            'iCoupon' => $total_coupon,
            'iPayAmount' =>  ($total_coupon*Lib_Constants::COUPON_UNIT_PRICE),
            'iPerson' => $person,
            'iPerCoupon' => $per_coupon,
            'iStartTime' => $now_time,
            'iEndTime' => ($now_time+Lib_Constants::BAG_TIME_OUT*3600),
            'iUpdateTime' => $now_time
        );

        //新增福袋记录
        $this->load->model('lucky_bag_model');
        if (!$bag_id = $this->lucky_bag_model->add_row($params)) {
            $this->log->error('LuckyBagService', 'PullBag | add lucky bag failed | '.json_encode($params).' | '.$this->lucky_bag_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        //创建订单
        $this->load->service('order_service');
        $order_id = $this->order_service->create_luckybag_order($uin,$bag_id, $need_pay_amout, $use_coupon, $platform);
        if (is_numeric($order_id) && $order_id < 0) {
            $this->log->error('LuckyBagService', 'PullBag | update create bag order failed | '.json_encode($params).' | order_id:'.$order_id.' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        $data = array(
            'bag_id' =>$bag_id,
            'user_ext'=>$user_ext,
            'platform'=>$platform,
        );
        $is_paid = 0;
        if ($user_ext['coupon'] >= $total_coupon) {
            //更新福袋订单状态
            $is_paid = $this->order_service->set_succ_bag_order($uin, $order_id,$data);
            if ( $is_paid < 0 ) {
                return Lib_Errors::SVR_ERR;
            }
        }

        return array('order_id'=>$order_id,'is_paid'=>intval($is_paid), 'bag_id' => $bag_id);
    }

    /**
     * 领取福袋/自己也可以领取自己的福袋
     */
    public function user_get_bag($uin, $bag_id, $to_uin, $platform=Lib_Constants::PLATFORM_WX)
    {
        $data = array('iUin'=>$uin, 'iBagId'=>$bag_id, 'toUin'=>$to_uin);
        //判断是否已经领取过了
        $this->load->model('bag_action_log_model');
        if($this->bag_action_log_model->is_user_got_bag($uin, $bag_id, $to_uin)) {
            $this->log->error('LuckyBagService', 'user have get the bag | params ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::BAG_HAVE_GET;
        }

        //福袋信息
        $this->load->model('lucky_bag_model');
        $bag_info = $this->lucky_bag_model->get_row(array('iUin'=>$uin, 'iBagId'=>$bag_id));
        if (empty($bag_info)) {
            $this->log->error('LuckyBagService', 'params error ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //福袋未支付
        if ($bag_info['iIsPaid'] != Lib_Constants::PAY_STATUS_PAID) {
            $this->log->error('LuckyBagService', 'bag not paid | params ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::BAG_NOT_PAID;
        }

        //福袋未激活
        if ($bag_info['iStatus'] != Lib_Constants::BAG_STATUS_ACTIVE) {
            $this->log->error('LuckyBagService', 'bag not start or has done | params ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::BAG_NOT_ACTIVE;
        }

        if ($bag_info['iIsDone'] == Lib_Constants::BAG_IS_DONE) {
            $this->log->error('LuckyBagService', 'bag have been done 1 | params ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::BAG_HAVE_DONE;
        }

        $surplusNum = $bag_info['iCoupon']-$bag_info['iUsed'];//剩余券数量
        $surplusPeople = $bag_info['iPerson']- $bag_info['iUsedPerson']; //剩余人数
        //福袋已领完
        if($surplusNum <= 0) {
            $this->log->error('LuckyBagService', 'bag have been done | params ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::BAG_HAVE_DONE;
        }

        //发福袋用户
        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_uin($uin);
        if (!$user_info) {
            $this->log->error('LuckyBagService', 'bag uin error error ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //领福袋用户
        $this->load->model('user_ext_model');
        $to_user_ext = $this->user_ext_model->get_user_by_uin($to_uin);
        if (!$to_user_ext) {
            $this->log->error('LuckyBagService', 'to uin error error ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //判断福袋的类型及分配给用户的券的数量
        $num = 0;
        if ($bag_info['iType'] == Lib_Constants::BAG_TYPE_NORMAL){
            $num = $bag_info['iPerCoupon'];
        } elseif ($bag_info['iType'] == Lib_Constants::BAG_TYPE_RAND){
            $num = $this->rank_num($surplusNum, $surplusPeople);
        }else{
            $this->log->error('LuckyBagService', 'bag type error | params ['.json_encode($data).'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        //福袋中券的使用数量|领取人券增加|领取记录
        $data = array(
            'iUsed'=>($bag_info['iUsed']+$num),
            'iUsedPerson'=>($bag_info['iUsedPerson']+1),
        );
        if(($surplusNum-$num <= 0 || ($surplusPeople-1) <= 0)) {
            $data['iIsDone'] = Lib_Constants::BAG_IS_DONE;
        }
        if (!$this->lucky_bag_model->update_row($data, array('iUin'=>$uin, 'iBagId'=>$bag_id))) {
            $this->log->error('LuckyBagService', 'update bag failed | params ['.json_encode($data).'] | sql['.$this->lucky_bag_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
        //增加领取人夺宝券数量
        if (!$this->user_ext_model->update_row(array('iCoupon' => ($to_user_ext['iCoupon']+$num), 'iHisCoupon' => ($to_user_ext['iHisCoupon']+$num)), $to_uin)) {
            $this->log->error('LuckyBagService', 'update to_user coupon num failed | params ['.json_encode($data).'] | sql['.$this->user_ext_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        //增加领取人夺宝券收入日志
        $this->load->model('coupon_action_log_model');
        $data = array(
            'iUin'=>$to_uin,
            'iAction'=>Lib_Constants::BAG_ACTION_GET,//福袋领取
            'iNum'=>$num,
            'sExt'=>json_encode(array('uin'=>$uin, 'bag_id'=>$bag_info['iBagId'], 'nickname'=>$user_info['sNickName'], 'type'=>$bag_info['iType'])),
            'iAddTime'=>time(),
            'iPlatForm'=>$platform,
            'iType'=>Lib_Constants::ACTION_INCOME,
        );

        if (!$this->coupon_action_log_model->add_row($data)) {
            $this->log->error('LuckyBagService', 'add to_user coupon action log  failed | params ['.json_encode($data).'] | sql['.$this->coupon_action_log_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        /*
        //增加用户领取福袋记录
        $data = array(
            'iUin' => $to_uin,
            'iBagId' => $bag_id,
            'iAction' => Lib_Constants::BAG_ACTION_GET,
            'iNum' => $num,
            'sExtend' => $uin,
            'sNickName' => $user_info['sNickName'],
            'sHeadImg' => $user_info['sHeadImg'],
            'iType' => $bag_info['iType'],
            'iAddTime' => time(),
        );
        if (!$this->bag_action_log_model->add_row($data)) {
            $this->log->error('LuckyBagService', 'add to_user get bag action log  failed | params ['.json_encode($data).'] | sql['.$this->coupon_action_log_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
        */

        //增加福袋领券记录
        $data = array(
            'iUin' => $uin,
            'iBagId' => $bag_id,
            'iAction' => Lib_Constants::ACTION_USE_COUPON,
            'iNum' => $num,
            'sExtend' => $to_uin,
            'iAddTime' => time(),
        );
        if (!$this->bag_action_log_model->add_row($data)) {
            $this->log->error('LuckyBagService', 'add bag use action log  failed | params ['.json_encode($data).'] | sql['.$this->coupon_action_log_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
        return $num;
    }

    public function active_bag($uin, $bag_id)
    {
        if (!$uin || !$bag_id) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $this->load->model('lucky_bag_model');
        $data = array(
            'iStatus'=>Lib_Constants::BAG_STATUS_ACTIVE,
            'iUpdateTime'=>time()
        );
        $params = array(
            'iUin' => $uin,
            'iBagId' => $bag_id
        );
        //更新福袋状态为激活
        if ($this->lucky_bag_model->update_row($data, $params)) {
            return Lib_Errors::SUCC;
        } else {
            $this->log->error('LuckyBag', 'ActiveBag | active bag failed | params['.json_encode($params).'] | sql ['.$this->lucky_bag_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
    }

    /**
     * 拼手气|分配券规则
     * @param $total   剩下的券总数
     * @param $num  剩下的人数
     */
    private function rank_num($total,$num)
    {
        if($total<= 0 || $num<= 0) return 0;

        $couponNum = 0;
        if($num == 1){
            $couponNum = $total;
        }else{
            $couponNum = rand(1,$total-1);
        }

        return $couponNum;
    }
}