<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户奖励-serivce
 * Class Awards_service
 */
class Awards_service extends  MY_Service
{
    /**
     * 用户奖励发放
     * @param $platform 平台
     * @param $awards_type   奖励类型
     * @param $uin  用户ID
     */
    public function grant_awards($uin, $awards_type, $platform, $extend = array(), $push_extend=array())
    {
        if (empty($uin) || !array_key_exists($platform, Lib_Constants::$platforms) || empty($awards_type)) {
            return Lib_Errors::PARAMETER_ERR;
        }

        $this->load->model('awards_activity_model');
        if (!$awards_activity = $this->awards_activity_model->get_online_activity($awards_type, $platform)) { //无相关在线活动,直接处理,返回成功
            $this->log->error('AwardsService', 'GrantAwards | no awards activity online');
            return Lib_Errors::SUCC;
        }

        $ret = $this->check_rewarded($uin, $platform, $awards_type, $awards_activity, $extend);
        if (is_int($ret) && $ret < 0) {
            $this->log->error('AwardsService', 'GrantAwards params error | error code:'.$ret);
            return $ret;
        } elseif (!$ret) {
            $this->log->error('AwardsService', 'GrantAwards | have reawards');
            return Lib_Errors::SUCC;
        }

        //按线上对应的活动发放奖励
        if (isset(Lib_Constants::$awards_prizes[$awards_activity['iGiftType']])) {
            $func = 'grant_awards_'.Lib_Constants::$awards_prizes[$awards_activity['iGiftType']]['ename'];
            if (is_callable(array($this, $func)))
            {
                $params = array(
                    'uin' => $uin,
                    'prize_count' => $awards_activity['iGift'],
                    'platform' => $platform,
                    'awards_ename' => $awards_activity['sAwardsType'],
                    'awards_name' => $awards_activity['sAwardsName'],
                    'ext' => $extend,
                    'type' => Lib_Constants::ACTION_INCOME,
                    'action' => Lib_Constants::$awards_types[$awards_type]['action'],
                    'message' => isset(Lib_Constants::$awards_types[Lib_Constants::AWARDS_TYPE_TAG_SHARE_GIFT]['message']) ? Lib_Constants::$awards_types[Lib_Constants::AWARDS_TYPE_TAG_SHARE_GIFT]['message'] : '',
                );
                return  call_user_func(array($this, $func), $params, $push_extend);
            }
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 检查用户是否已经获得奖励
     * @param $awards_activity
     */
    private function check_rewarded($uin, $platform, $awards_type, $awards_activity, $extend)
    {
        switch ($awards_activity['iGiftType']) {
            case Lib_Constants::AWARDS_PRIZE_SCORE:
                $this->load->model('score_action_log_model');
                $func = 'is_'.$awards_type.'_rewarded';
                if (is_callable(array($this->score_action_log_model, $func))) {
                    return $this->score_action_log_model->$func($uin, $platform, $awards_type, $awards_activity, $extend);
                } else {
                    return true;
                }

                break;
            case Lib_Constants::AWARDS_PRIZE_COUPON:
                $this->load->model('coupon_action_log_model');
                $func = 'is_'.$awards_type.'_rewarded';
                if (is_callable(array($this->coupon_action_log_model, $func))) {
                    return $this->coupon_action_log_model->$func($uin, $platform, Lib_Constants::$awards_types[$awards_type]['action'], $awards_activity, $extend);
                } else {
                    return true;
                }
        }
    }


    /**
     * 发放积分奖励
     * @param $uin
     * @param $platform
     * @param $prize_count
     * @param $ext
     */
    public function grant_awards_score($params, $extend=array())
    {
        //添加积分兑换日志
        $this->load->model('score_action_log_model');
        if ($this->score_action_log_model->add_score($params)) {
            //更新用户汇总数据
            $this->load->model('user_ext_model');
            if( !$this->user_ext_model->update_count(array('iScore'=>$params['prize_count'],'iHisScore'=>$params['prize_count']),$params['uin'])) {
                $this->log->error('AwardsActivity','update user ext score failed |  params: '.json_encode($params).' | sql:'.$this->user_ext_model->db->last_query().' |'.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }
            //发送站内消息
            //$this->load->message();
            if (!empty($params['message']) && !empty($extend)) {
                $this->load->service('push_service');
                $this->push_service->add_task($params['message'], $extend['key'], $extend['uin'], $extend['data']);
            }

            $user_ext = $this->user_ext_model->get_user_by_uin($params['uin']);
            $score = empty($user_ext['iScore']) ? 0 : $user_ext['iScore'];
            return array('awards'=>$params['awards_name'],'type'=>'积分','num'=>$params['prize_count'], 'score'=>$score);

        } else {
            $this->log->error('AwardsActivity','add score action log failed |  params: '.json_encode($params).' | sql:'.$this->score_action_log_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
    }

    /**
     * 发放夺宝券奖励
     * @param $uin
     * @param $platform
     * @param $prize_count
     */
    public function grant_awards_coupon($params, $extend=array())
    {
        //添加积分兑换日志
        $this->load->model('coupon_action_log_model');
        if ($this->coupon_action_log_model->add_coupon($params)) {
            //更新用户汇总数据
            $this->load->model('user_ext_model');
            if( !$this->user_ext_model->update_count(array('iHisGiftCoupon'=>$params['prize_count'],'iCoupon'=>$params['prize_count'], 'iHisCoupon'=>$params['prize_count']),$params['uin'])) {
                $this->log->error('AwardsActivity','update user ext coupon failed |  params: '.json_encode($params).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }
            $this->log->error('AwardsActivity','send push before|  params: '.json_encode($params).' | extend: '.json_encode($extend).' | '.__METHOD__);
            if (!empty($params['message']) && !empty($extend)) {
                $this->load->service('push_service');
                $this->push_service->add_task($params['message'], $extend['key'], $extend['uin'], $extend['data']);
            }

            $user_ext = $this->user_ext_model->get_user_by_uin($params['uin']);
            $coupon = empty($user_ext['iCoupon']) ? 0 : $user_ext['iCoupon'];
            return array('awards'=>$params['awards_name'],'type'=>'夺宝券','num'=>$params['prize_count'], 'coupon'=>$coupon);
        } else {
            $this->log->error('AwardsActivity','add coupon action log failed |  params: '.json_encode($params).' | sql:'.$this->coupon_action_log_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
    }


    /**
     * 扣夺宝券
     * @param $uin
     * @param $action
     * @param $coupon_num
     * @param int $platform
     * @param array $ext
     */
    public function reduce_coupon_action($uin, $action, $coupon_num, $platform=Lib_Constants::PLATFORM_WX, $ext=array())
    {
        if(!array_key_exists($action, Lib_Constants::$coupon_actions)) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $params = array(
            'uin' => $uin,
            'action' => $action,
            'prize_count' => $coupon_num,
            'platform' => $platform,
            'ext' => $ext,
            'type' => Lib_Constants::ACTION_OUTCOME
        );
        //添加积分兑换日志
        $this->load->model('coupon_action_log_model');
        if ($this->coupon_action_log_model->add_coupon($params)) {
            //更新用户汇总数据
            $this->load->model('user_ext_model');
            if( !$this->user_ext_model->update_count(array('iCoupon'=>-$params['prize_count']),$params['uin'])) {
                $this->log->error('AwardsActivity','update user ext coupon failed |  params: '.json_encode($params).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            } else {
                return array('awards'=>Lib_Constants::$coupon_actions[$action],'type'=>'夺宝券','num'=>$params['prize_count']);
            }
        } else {
            $this->log->error('AwardsActivity','add coupon action log failed |  params: '.json_encode($params).' | sql:'.$this->coupon_action_log_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
    }

    /**
     * 增加夺宝券
     * @param $uin
     * @param $action
     * @param $coupon_num
     * @param int $platform
     * @param array $ext
     */
    public function add_coupon_action($uin, $action, $coupon_num, $gift_num=0, $platform=Lib_Constants::PLATFORM_WX, $ext=array())
    {
        $data = $gift_num>0 ?  array('iCoupon'=>($coupon_num+$gift_num),'iHisGiftCoupon'=>$gift_num, 'iHisCoupon'=>($coupon_num+$gift_num)) : array('iCoupon'=>$coupon_num ,'iHisCoupon'=>$coupon_num);
        //更新用户汇总数据
        $this->load->model('user_ext_model');
        if( !$this->user_ext_model->update_count($data, $uin)) {
            $this->log->error('AwardsActivity','update user ext coupon failed |  params: '.json_encode($data).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        $params = array(
            'uin' => $uin,
            'action' => $action,
            'prize_count' => $coupon_num,
            'platform' => $platform,
            'ext' => $ext,
            'type' => Lib_Constants::ACTION_INCOME
        );
        //添加积分兑换日志
        $this->load->model('coupon_action_log_model');
        if (!$this->coupon_action_log_model->add_coupon($params)) {
            $this->log->error('AwardsActivity','add coupon action log failed |  params: '.json_encode($params).' | sql:'.$this->coupon_action_log_model->db->last_query().' | '.__METHOD__);
        }

        if($gift_num >0) {
            $params = array(
                'uin' => $uin,
                'action' => $action+1,
                'prize_count' => $gift_num,
                'platform' => $platform,
                'ext' => $ext,
                'type' => Lib_Constants::ACTION_INCOME
            );
            //添加积分兑换日志
            $this->load->model('coupon_action_log_model');
            if (!$this->coupon_action_log_model->add_coupon($params)) {
                $this->log->error('AwardsActivity','add coupon action log failed |  params: '.json_encode($params).' | sql:'.$this->coupon_action_log_model->db->last_query().' | '.__METHOD__);
            }
        }
        return Lib_Errors::SUCC;
    }
}