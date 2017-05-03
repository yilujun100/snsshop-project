<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团-service
 * Class Groupon_service
 */
class Groupon_service extends  MY_Service
{
    /**
     * 订单支付成功后的处理
     *
     * @param $order
     *
     * @return int
     */
    public function order_paid_process($order)
    {
        switch ($order['iBuyType']) {
            case Lib_Constants::GROUPON_ORDER_DIY: // 开团
                $refund_type = Lib_Constants::REFUND_TYPE_DIY_FAILED;
                $process = $this->diy_groupon($order['sOrderId']);
                break;
            case Lib_Constants::GROUPON_ORDER_JOIN: // 参团
                $refund_type = Lib_Constants::REFUND_TYPE_JOIN_FAILED;
                $process = $this->join($order['sOrderId']);
                break;
            case Lib_Constants::GROUPON_ORDER_DIRECT: // 任性购
                $refund_type = Lib_Constants::REFUND_TYPE_BUY;
                $process = $this->direct($order['sOrderId']);
                break;
            default:
                return Lib_Errors::GROUPON_ORDER_TYPE_ERROR;
        }
        if ($process < Lib_Errors::SUCC) {
            $this->log->error('groupon service | order_paid_process','process failed',array('code'=>$process,'refund_type'=>$refund_type,'order'=>$order));
            if ($this->groupon_order_refund($order['sOrderId'], $refund_type) < Lib_Errors::SUCC) {
                $this->log->error('groupon service | order_paid_process', 'refund failed', array('refund_type'=>$refund_type,'order'=>$order));
            }
        }
        return $process;
    }

    /**
     * 开团
     *
     * @param string $order_id
     * @return int
     */
    public function diy_groupon($order_id)
    {
        $this->load->model('groupon_order_model');
        $groupon_order = $this->groupon_order_model->valid($order_id);
        if (!is_array($groupon_order)) {
            $this->log->error('Groupon','groupon order valid failed | errror code:'.$groupon_order.' | error msg :'.Lib_Errors::get_error($groupon_order).' | '.__METHOD__, array('order_id'=>$order_id));
            return $groupon_order;
        }

        //不是开团类型订单
        if ($groupon_order['iBuyType'] != Lib_Constants::GROUPON_ORDER_DIY) {
            $this->log->error('Groupon', 'groupon order type error | is not diy  | groupon order :'.json_encode($groupon_order).' | '.__METHOD__);
            return Lib_Errors::GROUPON_NOT_DIY_ORDER;
        }

        $uin = $groupon_order['iUin'];
        $groupon_id = $groupon_order['iGrouponId'];
        $spec_id = $groupon_order['iSpecId'];
        if(!$uin  || !$groupon_id || !$spec_id) {
            $this->log->error('Groupon', 'uin grouponid specid error  | groupon order :'.json_encode($groupon_order).' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_uin($uin);
        //用户不存在
        if (empty($user_info)) {
            $this->log->error('Groupon', 'user not exist | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        $this->load->model('groupon_spec_model');
        $groupon_spec = $this->groupon_spec_model->get_row($spec_id, true);
        //规格不存在
        if (empty($groupon_spec)) {
            $this->log->error('Groupon', 'groupon active spec not found | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::GROUPON_SPEC_NOT_EXISTS;
        }

        $now = time();
        $this->load->model('groupon_diy_model');
        $ongoing_diy = $this->groupon_diy_model->row_count(array('iUin'=>$uin, 'iGrouponId'=>$groupon_id, 'iState'=>Lib_Constants::GROUPON_DIY_ING, 'iEndTime>'=>$now), true);
        //用户有进行中的拼团
        if (!empty($ongoing_diy)) {
            $this->log->error('Groupon', 'has ongoing diy groupon | ongoin diy groupon:'.json_encode($ongoing_diy).' | '.__METHOD__);
            return Lib_Errors::GROUPON_HAS_ONGOING_DIY;
        }

        $this->load->model('groupon_active_model');
        $groupon_active = $this->groupon_active_model->valid($groupon_id);
        if (!is_array($groupon_active)) {
            $this->log->error('Groupon', 'groupon active valid failed | errror code:'.$groupon_active.' | error msg :'.Lib_Errors::get_error($groupon_active).' | '.__METHOD__);
            return $groupon_active;
        }

        $groupon_diy = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
            'iGrouponId' => $groupon_id,
            'iSpecId' => $spec_id,
            'iGrouponType' => $groupon_active['iGrouponType'],
            'sNickName' => $user_info['sNickName'],
            'sHeadImg' => $user_info['sHeadImg'],
            'iGrouponPrice' => $groupon_spec['iDiscountPrice'],
            'iPeopleNum' => $groupon_spec['iPeopleNum'],
            'iBuyNum' => 0,
            'iOpenCount' => $groupon_active['iGrouponType'] == Lib_Constants::GROUPON_TYPE_FIX ? $groupon_spec['iPeopleNum'] : $groupon_active['iOpenCount'], //固定团等于成团数
            'iState' => Lib_Constants::GROUPON_DIY_ING,
            'iStartTime' => $now,
            'iEndTime' => ($now + $groupon_active['iExpiredTime']),
            'iCreateTime' => $now,
        );
        $this->load->model('groupon_diy_model');
        $try_time = 1;
        $flag = false;
        while($try_time <= 3 && $flag === false ) {
            $diy_id = $this->groupon_diy_model->add_row($groupon_diy);
            if (empty($diy_id)) {
                $this->log->error('Groupon', 'add diy groupon failed | sql: '.$this->groupon_diy_model->db->last_query().' | try time: '.$try_time.' | '.__METHOD__);
            } else {
                $flag = true;
            }
            $try_time++;
        }
        //开团失败
        if (empty($diy_id)) {
            return Lib_Errors::SVR_ERR;
        }

        $groupon_diy['iDiyId'] = $diy_id;

        //更新订单信息
        $ret = $this->groupon_order_model->update_row(array('iDiyId' => $diy_id), $order_id);
        if (empty($ret)) {
            $this->log->error('Groupon', 'update groupon order iDiyId failed | sql: '.$this->groupon_order_model->db->last_query().' | try time: '.$try_time.' | '.__METHOD__);
        }

        $ret = $this->join_record($order_id, $groupon_diy, $user_info, 1);
        //添加参团记录失败
        if ($ret != Lib_Errors::SUCC) {
            $this->log->error('Groupon', 'add join groupon record failed | errror code:'.$ret.' | error msg :'.Lib_Errors::get_error($ret).' | '.__METHOD__);
            return $ret;
        }

        return $groupon_diy;
    }

    /**
     * 参加拼团
     *
     * @param $order_id
     *
     * @return int
     */
    public function join($order_id)
    {
        $log_label = 'groupon service | join groupon';

        $this->load->model('groupon_order_model');
        if (($order = $this->groupon_order_model->valid($order_id)) < Lib_Errors::SUCC) { // 拼团 $orderId 无效
            $this->log->error($log_label, 'order invalid', array('code'=>$order, 'args'=>func_get_args()));
            return $order;
        }
        $this->load->model('user_model');
        if (! ($user = $this->user_model->get_user_by_uin($order['iUin']))) { // 订单用户信息异常
            $this->log->error($log_label, 'order user info error', array('args'=>func_get_args()));
            return $order;
        }
        if (($diy = $this->can_join($order['iUin'], $order['iDiyId'])) < Lib_Errors::SUCC) { // 用户不能参加该拼团
            $this->log->error($log_label, 'user can not join the diy', array('code'=>$diy, 'args'=>func_get_args() ,'order'=>$order));
            return $diy;
        }
        if (($join = $this->join_record($order_id, $diy, $user, 0)) < Lib_Errors::SUCC) { // 添加参团记录失败
            $this->log->error($log_label, 'add join_record failed', array('code'=>$diy, 'diy'=>$diy));
            return $join;
        }
        $diy['iBuyNum'] ++;
        if ($diy['iBuyNum'] == $diy['iPeopleNum']) { // 触发成团
            if (($succ = $this->set_groupon_succ($diy)) < Lib_Errors::SUCC) { // 成团失败
                $this->log->error($log_label, 'join open groupon failed', array('code'=>$succ, 'diy'=>$diy));
                return $succ;
            }
            if (($deliver = $this->groupon_succ_deliver($diy)) < Lib_Errors::SUCC) {
                $this->log->error($log_label, 'join open groupon deliver failed', array('code'=>$deliver,'diy'=>$diy));
            }
        }
        if ($diy['iBuyNum'] > $diy['iPeopleNum']) { // 成团后发货
            return $this->order_deliver($order);
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 任性购
     */
    public function direct($order_id)
    {
        $log_label = 'groupon service | direct groupon';

        $this->load->model('groupon_order_model');
        if (($order = $this->groupon_order_model->valid($order_id)) < Lib_Errors::SUCC) { // 拼团 $orderId 无效
            $this->log->error($log_label, 'order invalid', array('code'=>$order, 'args'=>func_get_args()));
            return $order;
        }
        $this->load->model('user_model');
        if (! ($user = $this->user_model->get_user_by_uin($order['iUin']))) { // 订单用户信息异常
            $this->log->error($log_label, 'order user info error', array('args'=>func_get_args()));
            return $order;
        }
        if (($groupon = $this->can_direct($order['iUin'], $order['iGrouponId'])) < Lib_Errors::SUCC) { // 用户不能任性购该团购
            $this->log->error($log_label, 'order user info error', array('code'=>$groupon,'args'=>func_get_args(),'order'=>$order));
            return $groupon;
        }
        if (($deliver = $this->order_deliver($order)) < Lib_Errors::SUCC) {
            return $deliver;
        }
        $this->load->model('groupon_active_model');
        if (! $this->groupon_active_model->increase($order['iGrouponId'], 'iSoldCount', 'iStock')) {
            $this->log->error($log_label, 'increase sold count failed', array('order'=>$order));
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 添加参团记录
     *
     * @param $order_id
     * @param $diy
     * @param $user
     * @param $isColonel
     *
     * @return int
     */
    private function join_record($order_id, $diy, $user, $isColonel)
    {
        $log_label = 'groupon service | join groupon record';

        $this->load->model('groupon_diy_model');
        $this->load->model('groupon_join_user_model');
        $this->load->model('groupon_join_groupon_model');
        $this->load->model('groupon_active_model');

        if (! $this->groupon_diy_model->increase($diy['iDiyId'], 'iBuyNum', 'iOpenCount')) {
            $this->log->error(
                $log_label,
                'increase iBuyNum failed',
                array('sql'=>$this->groupon_diy_model->db->last_query(),'params'=>array($diy['iDiyId'],'iBuyNum','iOpenCount'),'args'=>func_get_args()));
            return Lib_Errors::GROUPON_DIY_PEOPLE_MAX;
        }

        $data = array(
            'sOrderId' => $order_id,
            'iUin' => $user['iUin'],
            'iDiyId' => $diy['iDiyId'],
            'iGrouponId' => $diy['iGrouponId'],
            'iSpecId' => $diy['iSpecId'],
            'sNickName' => $user['sNickName'],
            'sHeadImg' => $user['sHeadImg'],
            'iIsColonel' => $isColonel,
        );
        if (! $this->groupon_join_user_model->add_row($data)) { // 添加 参团-user 记录失败
            $this->log->error($log_label, 'add join-user failed', array('sql'=>$this->groupon_join_user_model->db->last_query(),'data'=>$data,'args'=>func_get_args()));
            return Lib_Errors::GROUPON_JOIN_FAILED;
        }
        if (! $this->groupon_join_groupon_model->add_row($data)) { // 添加 参团-groupon 记录失败
            $this->log->error($log_label, 'add join-groupon failed', array('sql'=>$this->groupon_join_groupon_model->db->last_query(),'data'=>$data,'args'=>func_get_args()));
            return Lib_Errors::GROUPON_JOIN_FAILED;
        }
        if (! $this->groupon_active_model->increase($diy['iGrouponId'], 'iJoinNum')) { // 更新 iJoinNum 失败
            $this->log->error($log_label, 'increase iJoinNum failed', array('sql'=>$this->groupon_active_model->db->last_query(),'args'=>func_get_args()));
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 下单检查
     *
     * @param      $buyType
     * @param      $uin
     * @param      $grouponId
     * @param      $specId
     * @param      $diyId
     * @param int  $count
     *
     * @return array|int
     */
    public function create_order_check($buyType, $uin, $grouponId=null, $specId=null, $diyId=null, $count=1)
    {
        $log_label = 'groupon service | create_order_check';

        if (! in_array($buyType, array_keys(Lib_Constants::$groupon_order)) ||
            (Lib_Constants::GROUPON_ORDER_JOIN == $buyType && empty($diyId)) ||
            (Lib_Constants::GROUPON_ORDER_JOIN != $buyType && empty($grouponId)) ||
            empty($uin)) { // 检查参数
            $this->log->error($log_label, 'args error', array('args'=>func_get_args()));
            return Lib_Errors::PARAMETER_ERR;
        }

        $this->load->model('user_model');
        if (! ($user = $this->user_model->get_user_by_uin($uin))) { // 用户错误
            $this->log->error($log_label, 'user does not exist', array('args'=>func_get_args()));
            return Lib_Errors::GROUPON_ORDER_USER_ERROR;
        }

        if (Lib_Constants::GROUPON_ORDER_JOIN == $buyType) { // 参团
            $diyId = (int) $diyId;
            if ($diyId < 1 || ($diy = $this->can_join($uin, $diyId)) < Lib_Errors::SUCC) {
                $this->log->error($log_label, 'can not diy', array('code'=>$diy, 'args'=>func_get_args()));
                return $diy;
            }
            $grouponId = $diy['iGrouponId'];
            $specId = $diy['iSpecId'];
        } else if (Lib_Constants::GROUPON_ORDER_DIY == $buyType) {  // 开团
            if (($can_diy = $this->can_diy($uin, $grouponId)) < Lib_Errors::SUCC) {
                $this->log->error($log_label, 'can not diy', array('code'=>$can_diy, 'args'=>func_get_args()));
                return $can_diy;
            }
        }

        $this->load->model('groupon_active_model');
        if (($groupon = $this->groupon_active_model->valid($grouponId, $count)) < Lib_Errors::SUCC) { // 团购无效
            $this->log->error($log_label, 'groupon invalid', array('code' => $groupon, 'args'=>func_get_args()));
            return $groupon;
        }

        if (Lib_Constants::GROUPON_ORDER_DIRECT != $buyType) { // 非任性购
            $this->load->model('groupon_active_spec_model');
            if (Lib_Constants::GROUPON_TYPE_FIX == $groupon['iGrouponType']) { // 固定团
                $specId = (int) $specId;
                if ($specId < 1 || ! ($spec = $this->groupon_active_spec_model->get_row($specId))) { // 规格 $specId 错误
                    $this->log->error($log_label, 'spec does not exist', array('args'=>func_get_args()));
                    return Lib_Errors::GROUPON_SPEC_NOT_EXISTS;
                }
                if ($spec['iGrouponId'] != $groupon['iGrouponId']) { //  // 规格 $specId 不匹配
                    $this->log->error($log_label, 'spec does not match groupon', array('args'=>func_get_args()));
                    return Lib_Errors::GROUPON_SPEC_NOT_MATCH;
                }
            } else if (Lib_Constants::GROUPON_TYPE_STAIR == $groupon['iGrouponType']) { // 阶梯团
                if (($spec = $this->groupon_active_spec_model->get_stair_success_spec($grouponId)) < Lib_Errors::SUCC) { // 规格设置异常
                    $this->log->error($log_label, 'get_stair_success_spec error', array('code'=>$spec, 'args'=>func_get_args()));
                    return $spec;
                }
            } else { // 团购类型错误
                $this->log->error($log_label, 'groupon type invalid', array('args'=>func_get_args()));
                return Lib_Errors::GROUPON_TYPE_ERROR;
            }
        }

        return array($groupon, empty($spec)?array():$spec, empty($diy)?array():$diy);
    }

    /**
     * 判断用户是否可以开团
     *
     * @param $uin
     * @param $groupon_id
     * @param $specId
     *
     * @return int
     */
    public function can_diy($uin, $groupon_id, $specId = null)
    {
        $now = time();
        $this->load->model('groupon_diy_model');
        $where = array(
            'iUin'=> $uin,
            'iGrouponId'=> $groupon_id,
            'iState'=>Lib_Constants::GROUPON_DIY_ING,
            'iEndTime >' => $now
        );
        if ($specId) {
            $where['iSpecId'] = $specId;
        }
        if ($this->groupon_diy_model->row_count($where, '', true) > 0) {  //用户有进行中的拼团
            return Lib_Errors::GROUPON_HAS_ONGOING_DIY;
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 判断用户是否可以参加某个团
     *
     * @param $uin
     * @param $diy_id
     *
     * @return int
     */
    public function can_join($uin, $diy_id)
    {
        $this->load->model('groupon_diy_model');
        if (($diy = $this->groupon_diy_model->valid($diy_id)) < Lib_Errors::SUCC) { // 拼团 $diyId 无效
            return $diy;
        }
        $this->load->model('groupon_join_user_model');
        $where = array(
            'iUin'=> $uin,
            'iDiyId'=> $diy_id,
        );
        if ($this->groupon_join_user_model->row_count($where, '', true) > 0) { // 用户已经参与过该团了
            return Lib_Errors::GROUPON_JOINED;
        }
        return $diy;
    }

    /**
     * 是否可以任性购
     *
     * @param $uin
     * @param $groupon_id
     *
     * @return array|int
     */
    public function can_direct($uin, $groupon_id)
    {
        $this->load->model('groupon_active_model');
        if (($groupon = $this->groupon_active_model->valid($groupon_id)) < Lib_Errors::SUCC) { // 团购无效
            return $groupon;
        }
        return $groupon;
    }

    /**
     * 判断是否成团
     *  1. 此处只判断是否成团 不涉及强制开团
     *  2. 暂时只给定时脚本调用
     *  3. 不适用事件触发的开团
     * @param $diy
     */
    public function check_groupon_succ($diy)
    {
        if (empty($diy) || $diy['iState'] != Lib_Constants::GROUPON_DIY_ING) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if ($diy['iPeopleNum'] <= $diy['iBuyNum']) { //参团人数 > 开团人数  拼团成功
            $ret = $this->set_groupon_succ($diy);//
            if ($ret == Lib_Errors::SUCC) {
                return $this->set_groupon_finish($diy);
            }
            return $ret;
        } else { //拼团失败
            $this->log->notice('Groupon', 'groupon diy failed | diy'.json_encode($diy).' | '.__METHOD__);
            return $this->set_groupon_fail($diy);
        }
    }

    /**
     * 设置成团
     * @param $diy
     * @return int
     */
    public function set_groupon_succ($diy)
    {
        $this->load->model('groupon_active_model');
        if(!$this->groupon_active_model->check_stock($diy['iGrouponId'], $diy['iPeopleNum'])) {
            $this->log->error('Groupon', 'groupon active stock is not enough | '.__METHOD__);
            return $this->set_groupon_fail($diy);
        }

        $now = time();
        //修改拼团表成团状态
        $data = array(
            'iState' => Lib_Constants::GROUPON_DIY_DONE,
            'iSuccTime' => $now,
            'iUpdateTime' => $now
        );

        if ($diy['iGrouponType'] == Lib_Constants::GROUPON_TYPE_FIX) { //固定团
            $data['iRealPrice'] = $diy['iGrouponPrice'];  //最终价格
            $data['iFinished'] = Lib_Constants::GROUPON_DIY_FINISHED; //设置开团状态
            $data['iFinishedTime'] = $now;
        }

        $this->load->model('groupon_diy_model');
        if (!$this->groupon_diy_model->update_row($data, array('iDiyId' => $diy['iDiyId']))) {
            $this->log->error('Groupon', 'update groupon diy state | failed | sql['.$this->groupon_diy_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        } else {
            $this->log->notice('Groupon', 'update groupon diy state | success | diy['.json_encode($diy).'] | '.__METHOD__);
        }

        //增加拼团活动销量
        $this->load->model('groupon_active_model');
        $data = array(
            'iSuccCount' => 1,  //成功开团数
            'iSoldCount' => $diy['iBuyNum'], //增加真实已售卖库存
            'iUpdateTime' => $now
        );
        if (!$this->groupon_active_model->update_count($data, $diy['iGrouponId'])) {
            $this->log->error('Groupon', 'update groupon acitve stock| failed | sql['.$this->groupon_diy_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        } else {
            $this->log->notice('Groupon', 'update groupon acitve stock | success | diy['.json_encode($diy).'] | '.__METHOD__);
            if ($diy['iGrouponType'] == Lib_Constants::GROUPON_TYPE_FIX && $diy['iFree']) { //固定团 免单流程
                return $this->groupon_order_refund($diy['sOrderId'], Lib_Constants::REFUND_TYPE_GROUPON_FREE);
            }
            return Lib_Errors::SUCC;
        }
    }

    /**
     * 设置开团 - 此方法只对阶梯团
     * @param $diy
     * @return int
     */
    public function set_groupon_finish($diy)
    {
        if ($diy['iGrouponType'] == Lib_Constants::GROUPON_TYPE_STAIR) { //阶梯团
            $this->load->model('groupon_spec_model');
            $real_spec = $this->groupon_spec_model->get_real_spec($diy['iBuyNum']);
            if (empty($real_spec)) {
                $this->log->error('Groupon', 'get rebate spec failed | sql['.$this->groupon_spec_model->db->last_query().'] | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }

            $now = time();
            //修改开团表开团状态
            $data = array(
                'iRealPrice' => $real_spec['iDiscountPrice'],//最终价格
                'iFinished' => Lib_Constants::GROUPON_DIY_FINISHED,
                'iFinishedTime' => $now,
                'iUpdateTime' => $now
            );
            $this->load->model('groupon_diy_model');
            if (!$this->groupon_diy_model->update_row($data, array('iDiyId' => $diy['iDiyId']))) {
                $this->log->error('Groupon', 'update groupon diy state | failed | sql['.$this->groupon_diy_model->db->last_query().'] | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            } else {
                $this->log->notice('Groupon', 'update groupon diy state | success | diy['.json_encode($diy).'] | '.__METHOD__);
            }
            //团长免单
            if ($real_spec['iFree']) {
                return $this->groupon_order_refund($diy['sOrderId'], Lib_Constants::REFUND_TYPE_GROUPON_FREE);
            }

            $rebate_price = $this->groupon_spec_model->get_rebate_price($diy['iGrouponPrice'], $real_spec['iDiscountPrice']); //返利金额
            if ($rebate_price <=0) { //返利金额错误
                $this->log->error('Groupon', 'rebate price error| diy['.json_encode($diy).'] | spec['.json_encode($real_spec).'] | '.__METHOD__);
                return Lib_Errors::SUCC;
            }

            $this->load->model('groupon_join_groupon_model');
            $order_list = $this->groupon_join_groupon_model->get_rows(array('iDiyId' => $diy['iDiyId'], 'iGrouponId'=>$diy['iGrouponId']));
            if (!$order_list) {
                $this->log->error('Groupon', 'no order found:'.json_encode(array('iDiyId' => $diy['iDiyId'], 'iGrouponId'=>$diy['iGrouponId'])).' | diy:'.json_encode($diy));
                return Lib_Errors::SUCC;
            }

            $failed_rows = array();
            foreach ($order_list as $row) {
                if ($diy['iFree'] && ($row['sOrderId'] == $diy['sOrderId'])) { //团长免单单独处理
                    continue;
                }
                $ret = $this->groupon_order_refund($row['sOrderId'], Lib_Constants::REFUND_TYPE_REBATE, array('spec'=>$real_spec));
                if ($ret != Lib_Errors::SUCC) {
                    $failed_rows[] = $row['sOrderId'];
                }
            }

            if ($failed_rows) {
                $this->log->error('Groupon', 'rebate failed | failed orders :'.json_encode($failed_rows).' | diy:'.json_encode($diy));
            }
            return Lib_Errors::SUCC;
        } else {
            return Lib_Errors::SUCC;
        }
    }

    /**
     * 拼团失败
     *
     * @param $diy_id
     */
    private function set_groupon_fail($diy)
    {
        //修改拼团表
        $data = array(
            'iState' => Lib_Constants::GROUPON_DIY_FAILED,
            'iUpdateTime' => time()
        );
        $this->load->model('groupon_diy_model');
        if (!$this->groupon_diy_model->update_row($data, array('iDiyId' => $diy['iDiyId']))) {
            $this->log->error('Groupon', 'exception | set groupon diy secc failed | sql['.$this->groupon_diy_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
        //申请退款
        $this->load->model('groupon_join_groupon_model');
        $order_list = $this->groupon_join_groupon_model->get_rows(array('iDiyId' => $diy['iDiyId'], 'iGrouponId'=>$diy['iGrouponId']));
        if ($order_list) {
            $failed_rows = array();
            foreach ($order_list as $row) {  //拼团失败 全额退款
                $this->log->notice('Groupon', 'refund order | order:'.json_encode($row));
                $ret = $this->groupon_order_refund($row['sOrderId'], Lib_Constants::REFUND_TYPE_GROUPON_FAILED);
                if ($ret != Lib_Errors::SUCC) {
                    $failed_rows[] = $row['sOrderId'];
                }
            }
            if ($failed_rows) {
                $this->log->error('Groupon', 'refund failed | failed orders :'.json_encode($failed_rows).' | groupon diy:'.json_encode($diy));
            }
            return Lib_Errors::SUCC;
        } else  { //无需退款
            $this->log->error('Groupon', 'no refund order  | :'.$this->groupon_join_groupon_model->db->last_query().' | groupon diy:'.json_encode($diy));
            return Lib_Errors::SUCC;
        }
    }

    /**
     * 订单退款
     * @param $order_id
     * @param array $extend
     * @return int
     */
    public function groupon_order_refund($order_id, $refund_type, $extend=array())
    {
        if (!isset(Lib_Constants::$refund_types[$refund_type])) {
            return Lib_Errors::PARAMETER_ERR;
        }

        //查询申请退款  暂不支持分期退 后期要支持分期退要修改此处条件
        $this->load->model('order_refund_model');
        $refund_record = $this->order_refund_model->get_row(array('sOrderId'=>$order_id, 'iRefundType'=>$refund_type), true, false);
        if ($refund_record) {
            $this->log->error('Groupon', 'refund request is submited | '.json_encode($refund_record));
            return Lib_Errors::SUCC;
        }

        //订单详情
        $this->load->model('groupon_order_model');
        $order = $this->groupon_order_model->get_row($order_id, true, false);
        if (empty($order)) {
            $this->log->error('Groupon', 'order not found | '.$order_id);
            return Lib_Errors::SVR_ERR;
        }

        if (!$this->groupon_order_model->is_paid($order)) {
            $this->log->error('Groupon', 'order not pay | '.json_encode($order));
            return Lib_Errors::SVR_ERR;
        }

        //是否为返利退款类型
        $is_rebate = in_array($refund_type, Lib_Constants::$rebate_refund_type);
        if ($is_rebate) { //返利退款
            if ($order['iRebatingAmount'] > 0 || $order['iRebateStatus'] != Lib_Constants::GROUPON_REBATE_DONE) { //正在退款中或已退款    暂不支持分期退
                $this->log->error('Groupon', 'order is refunded or refunding | '.$order_id);
                return Lib_Errors::SUCC;
            }

            //计算返利价
            if ($refund_type == Lib_Constants::REFUND_TYPE_GROUPON_FREE) { //团长免单
                if ($order['iCount'] == 1) { //只买一件 直接退单价
                    $refund_price = $order['iUnitPrice'];
                } elseif ($order['iCount'] > 1) {
                    $this->load->model('groupon_spec_model');
                    $rebate_price = $this->groupon_spec_model->get_rebate_price($order['iUnitPrice'], $extend['spec']['iDiscountPrice']); //返利金额
                    $refund_price = $order['iUnitPrice'] + $rebate_price*($order['iCount']-1); //一件单价+剩余返利
                } else {
                    return Lib_Errors::PARAMETER_ERR;
                }
            } else { //参团返利
                $this->load->model('groupon_spec_model');
                $rebate_price = $this->groupon_spec_model->get_rebate_price($order['iUnitPrice'], $extend['spec']['iDiscountPrice']); //返利金额
                $refund_price = $rebate_price*$order['iCount'];
            }
            $real_price = $extend['spec']['iDiscountPrice'];
        } else { //直接退
            if ($order['iRefundingAmount'] > 0 || $order['iRefundStatus'] != Lib_Constants::REFUND_STATUS_DEFAULT) { //正在退款中或已退款   暂不支持分期退 后期可以打开此处限制
                $this->log->error('Groupon', 'order is refunded or refunding | '.json_encode($order));
                return Lib_Errors::SUCC;
            }
            if ($refund_type == Lib_Constants::REFUND_TYPE_GROUPON_FAILED) { //拼团失败退款
                $refund_count = $order['iCount'];
            } else {
                $refund_count = empty($extend['refund_count']) ? 1 : intval($extend['refund_count']) ;
            }
            $refund_price = $order['iUnitPrice']*$refund_count;
        }

        //退款金额异常 【退款金额 > (总金额 - 已退款金额 - 已返利金额)】
        if ($refund_price > ($order['iPayAmount']-$order['iRefundedAmount']-$order['iRebatedAmount'])) { //正在退款中不会到这步
            $this->log->error('Groupon', 'refund amount error| refund amount['.$refund_price.'] | pay amount['.$order['iPayAmount'].'] | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }
        $data = array(
            'sOrderId' => $order['sOrderId'],
            'iUin' => $order['iUin'],
            'sToken' => '',
            'iBuyType' => Lib_Constants::ORDER_TYPE_GROUPON,
            'iPayAgentType' => $order['iPayAgentType'],
            'iRefundPrice' => $refund_price,
            'iRefundCoupon' => 0,
            'sTransId' => $order['sTransId'],
            'sRemark' => date('YmdHis').' - '.Lib_Constants::$refund_types[$refund_type],
            'iRefundType' => $refund_type
        );
        if ($this->order_refund_model->create_refund_order($order['iUin'], $data)) { //更新订单退款数据
            if ($is_rebate) {
                $data = array(
                    'iRebatingAmount' => $refund_price,
                    'iUpdateTime' => time()
                );
                if(!empty($real_price)) {
                    $data['iRealPrice'] = $real_price;
                }
            } else {
                $data = array(
                    'iRefundingAmount' => $refund_price,
                    'iUpdateTime' => time()
                );
            }
            if (!$this->groupon_order_model->update_row($data, $order_id)) {
                $this->log->error('Groupon', 'update groupon order refunding data failed | sql['.$this->groupon_order_model->db->last_query().'] | '.__METHOD__);
            }
            return Lib_Errors::SUCC;
        } else {
            $this->log->error('Groupon', 'add refund record failed | data:'.json_encode($data));
            return Lib_Errors::SVR_ERR;
        }
    }

    /**
     * 确认发货
     */
    public function confirm_deliver($uin, $order_id)
    {
        $log_label = 'groupon service | confirm_deliver';

        $this->load->model('groupon_order_model');
        $order_data = array(
            'iDeliverStatus'=>Lib_Constants::ORDER_DELIVER_ING
        );
        if (! $this->groupon_order_model->update_row($order_data, $order_id)) {
            $this->log->error($log_label, 'update groupon order error', array('order_id'=>$order_id,'uin'=>$uin));
            return Lib_Errors::SVR_ERR;
        }
    }

    /**
     * 成团发货
     *
     * @param $diy
     *
     * @return int
     */
    public function groupon_succ_deliver($diy)
    {
        $log_label = 'groupon service | order_deliver_diy_succ';

        $this->load->model('groupon_join_groupon_model');
        $where = array(
            'iGrouponId' => $diy['iGrouponId'],
            'iDiyId' => $diy['iDiyId'],
        );
        $joins = $this->groupon_join_groupon_model->get_rows($where);
        if (empty($joins)) {
            $this->log->error($log_label, 'join record exception', $diy);
            return Lib_Errors::SVR_ERR;
        }

        $this->load->model('order_deliver_model');
        $this->load->model('groupon_order_model');

        foreach ($joins as $join) {
            $order = $this->groupon_order_model->get_row($join['sOrderId']);
            if (empty($order)) {
                $this->log->error($log_label, 'join order data not exist', $join);
                return Lib_Errors::ORDER_NOT_FOUND;
            }
            if (Lib_Constants::PAY_STATUS_PAID != $order['iPayStatus']) {
                $this->log->error($log_label, 'join order not paid', $join);
                return Lib_Errors::GROUPON_ORDER_UNPAID;
            }
            $where = array(
                'sOrderId' => $order['sOrderId']
            );
            if (! ($deliver_has = $this->order_deliver_model->get_row($where))) {
                if (($deliver = $this->order_deliver($order)) < Lib_Errors::SUCC) {
                    $this->log->notice($log_label,'has deliver',array('sql'=>$this->order_deliver_model->db->last_query(),'order'=>$order,'join'=>$join));
                    return $deliver;
                }
            } else {
                $this->log->notice($log_label,'has deliver',array('sql'=>$this->order_deliver_model->db->last_query(),'deliver'=>$deliver_has,'order'=>$order,'join'=>$join));
            }
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 发货
     *
     * @param $order
     *
     * @return int
     */
    public function order_deliver($order)
    {
        $log_label = 'groupon service | order_deliver';
        $this->load->model('order_deliver_model');
        $deliver_remark = '拼团 - '.Lib_Constants::$groupon_order[$order['iBuyType']];
        $now = time();
        $data = array(
            'iGoodsId' => $order['iGoodsId'],
            'sOrderId' => $order['sOrderId'],
            'iUin' => $order['iUin'],
            'iType' => Lib_Constants::ORDER_TYPE_GROUPON,
            'sExpressId' => '',
            'sExpressName' => '',
            'sAddress' => $order['sAddress'],
            'sName' => $order['sName'],
            'sMobile' => $order['sMobile'],
            'sRemark' => $deliver_remark,
            'sExtField' => json_encode(array('1'=>$now,'2'=>$now)),
            'iCreateTime' => $now,
            'iUpdateTime' => $now
        );
        if(! $this->order_deliver_model->add_row($data)) {
            $this->log->error($log_label,'add order deliver failed',array('sql'=>$this->order_deliver_model->db->last_query(),'order'=>$order));
            return Lib_Errors::ADD_DELIVER_FAILED;
        }
        return $data;
    }

    /**
     * 同步修改收货地址
     *
     * @param $uin
     * @param $order_id
     * @param $address_id
     *
     * @return int
     */
    public function order_address($uin, $order_id, $address_id)
    {
        $log_label = 'groupon service | order_address';

        $this->load->model('groupon_order_model');

        if (! ($order = $this->groupon_order_model->get_row($order_id, true, false))) { // 订单不存在
            return Lib_Errors::GROUPON_ORDER_NOT_EXISTS;
        }
        $this->load->model('address_model');
        if (! ($address = $this->address_model->get_address_info_by_id($address_id, $uin))) { // 收货地址错误
            $this->log->error($log_label, 'deliver address not exist', array('args'=>func_get_args()));
            return Lib_Errors::ADDRESS_NOT_FOUND;
        }
        $data = array(
            'sName'=>$address['sName'],
            'sMobile'=>$address['sMobile'],
            'sAddress'=>$address['sProvince'].' '.$address['sCity'].' '.$address['sDistrict'].' '.$address['sAddress'],
        );
        $where = array(
            'iUin' => $uin,
            'sOrderId' => $order_id,
        );
        if ($this->groupon_order_model->update_row($data, $where)) {
            return Lib_Errors::SUCC;
        }
        $this->log->error($log_label, 'update order address failed', array('sql'=>$this->groupon_order_model->db->last_query(),'args'=>func_get_args()));
        return Lib_Errors::SVR_ERR;
    }
}