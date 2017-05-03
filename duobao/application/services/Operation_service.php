<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户操作-serivce
 * Class Awards_service
 */
class Operation_service extends  MY_Service
{
    /**
     * 签到
     * @param $uin
     * @param $platform
     * @param array $extend
     */
    public function add_action_sign($uin, $platform, $extend=array())
    {
        //检查是否已签到
        $this->load->model('user_ext_model');
        $user_ext = $this->user_ext_model->get_row($uin);
        if(!$user_ext) {
            $this->log->error('AwardsActivity', 'Sign | check failed | uin:'.$uin.',platform:'.$platform.' | '.$this->user_ext_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }
        if (is_today($user_ext['iSignTime'])) {
            return Lib_Errors::USER_SIGNED;
        }

        $now = time();
        //添加用户签到日志
        $this->load->model('user_sign_model');
        if (!$this->user_sign_model->add_sign($uin, $now, $platform)) {
            $this->log->error('AwardsActivity', 'Sign | add sign log failed | uin:'.$uin.',platform:'.$platform.' | '.$this->user_sign_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
        //更新用户汇总数据
        $this->load->model('user_ext_model');
        if( !$this->user_ext_model->update_count(array('iSign'=>1,'iSignTime'=>$now,'iUpdateTime'=>$now),$uin)) {
            $this->log->error('AwardsActivity','Sign | update user ext sign num failed |  params: '.json_encode(array('iSign'=>1,'iSignTime'=>$now,'iUpdateTime'=>$now)).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        $this->load->service('awards_service');
        return $this->awards_service->grant_awards($uin, Lib_Constants::AWARDS_TYPE_TAG_SIGN, $platform, $extend);
    }

    /**
     * 免费券领取/参与次数领取
     * @param $uin
     * @param $platform
     * @param array $extend
     */
    public function add_free_coupon($uin, $platform, $extend=array())
    {
        //检查是否已领取
        $this->load->model('user_ext_model');
        $user_ext = $this->user_ext_model->get_row($uin);
        if(!$user_ext) {
            $this->log->error('AwardsActivity', 'Free | check failed | uin:'.$uin.',platform:'.$platform.' | '.$this->user_ext_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }
        if (is_today($user_ext['iGetFreeTime'])) {
            return Lib_Errors::BAG_HAVE_GET;
        }

        $now = time();
        $this->load->model('user_ext_model');
        if( !$this->user_ext_model->update_count(array('iFreeCoupon'=>1,'iGetFreeTime'=>$now,'iUpdateTime'=>$now),$uin)) {
            $this->log->error('AwardsActivity','Sign | update user ext free coupon failed |  params: '.json_encode(array('iFreeCoupon'=>1,'iGetFreeTime'=>$now,'iUpdateTime'=>$now)).' | sql:'.$this->user_ext_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        return Lib_Errors::SUCC;
    }

    /**
     * 晒单
     * @param int $uin
     * @param int $platform
     * @param int $period
     * @param array $data
     * @param array $extend
     */
    public function add_action_share($uin, $platform, $form_data, $extend=array())
    {
        $this->load->model('active_peroid_model');
        $peroid_params = array(
            'iActId' => $form_data['act_id'],
            'iPeroid' => $form_data['period']
        );
        //当前期不存在
        if (!$period_info = $this->active_peroid_model->get_row($peroid_params)) {
            $this->log->error('AwardsActivity', 'Share | peroid not exist | params:'.json_encode($peroid_params).' | sql: '.$this->active_peroid_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::ACTIVE_OVER_TIME;
        }
        //未开奖
        if ($period_info['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_OPENED) {
            $this->log->error('AwardsActivity', 'Share | active lot not opened | params:'.json_encode($period_info).' | '.__METHOD__);
            return Lib_Errors::ACTIVE_IS_NOT_LOTTEY;
        }
        //不是中奖者
        if ($period_info['iWinnerUin'] != $uin) {
            $this->log->error('AwardsActivity', 'Share | active lot not opened | params:'.json_encode($period_info).' | uin: '.$uin.' | '.__METHOD__);
            return Lib_Errors::NOT_WINNER_USER;
        }

        //是否已确认收货
        $deliver_params = array(
            'iGoodsId' => $period_info['iGoodsId'],
            'sOrderId' => $period_info['sWinnerOrder']
        );
        $this->load->model('order_deliver_model');
        if (!$deliver_info = $this->order_deliver_model->get_row($deliver_params)) {
            $this->log->error('AwardsActivity', 'Share | goods not deliver | params: '.json_encode($deliver_params).'  | sql: '.$this->active_peroid_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::GOODS_NOT_DELIVER;
        }

        //未确认收货
        if ($deliver_info['iConfirmStatus'] != Lib_Constants::DELIVER_CONFIRM_STATUS) {
            $this->log->error('AwardsActivity', 'Share | deliver not confirm | params: '.json_encode($deliver_params).'  | deliver_info: '.json_encode($deliver_info).'  | '.__METHOD__);
            return Lib_Errors::DELIVER_NOT_CONFIRM;
        }

        $this->load->model('user_model');
        $user_info = $this->user_model->get_user_by_uin($uin);
        if (!$user_info) {
            $this->log->error('AwardsActivity', 'Share | get user info failed | params: '.json_encode($deliver_params).'  | sql: '.$this->user_model->db->last_query().' | '.__METHOD__);
        }
        $sArea = $user_info ? ($user_info['sProvince'].$user_info['sCity']) : '';
        $share_data = array(
            'iUin' => $uin,
            'sContent' => $form_data['con'],
            'sNickName' => $period_info['sWinnerNickname'],
            'sHeadImg' => $period_info['sWinnerHeadImg'],
            'iPeriod' => $form_data['period'],
            'iActId' => $form_data['act_id'],
            'sImg' => json_encode($form_data['imgs']),
            'iCreateTime' => time(),
            'iGoodsId' => $period_info['iGoodsId'],
            'sGoodsName' => $period_info['sGoodsName'],
            'sGoodsImg' => $period_info['sImg'],
            'iLuckyCode' => $period_info['sWinnerCode'],
            'iLotTime' => $period_info['iLotTime'],
            'iLotCount' => $period_info['iLotCount'],
            'iWinnerCount' => $period_info['iWinnerCount'],
            'iPlatForm' => $platform,
            'iIp' => empty($form_data['ip']) ? 0 : $form_data['ip'],
            'sArea' => $sArea,
        );
        $this->load->model('share_model');
        if (!$share_id = $this->share_model->add_row($share_data)) {
            $this->log->error('AwardsActivity', 'Share | add share failed | params: '.json_encode($share_data).' | sql: '.$this->share_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }

        $this->load->service('awards_service');
        return $this->awards_service->grant_awards($uin, Lib_Constants::AWARDS_TYPE_TAG_SHARE, $platform, period_code_encode($form_data['act_id'], $form_data['period']));
    }

    /**
     * 晒单查看或点赞操作
     * @param $uin
     * @param $platform
     * @param $type
     */
    public function add_share_operate($uin, $platform, $share_id, $type)
    {
        //检查用户
        $this->load->model('user_model');
        if (!$user_info = $this->user_model->get_row($uin)) {
            $this->log->error('AwardsActivity', 'Share | Operate | user not exist | uin:'.$uin.' | sql'.$this->user_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::USER_NOT_EXISTS;
        }

        //检查晒单ID
        $this->load->model('share_model');
        if (!$share_info = $this->share_model->get_row($share_id)) {
            $this->log->error('AwardsActivity', 'Share | Operate | share not exist | share_id:'.$share_id.' | sql'.$this->share_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        if ($type == Lib_Constants::SHARE_OPT_VIEW) {
            return $this->share_model->update_count($share_id, array('iViewCount' => 1));
        } else {
            $this->load->model('share_user_action_log_model');//用户维度表
            if ($this->share_user_action_log_model->get_row(array('iUin'=>$uin, 'iShareId'=>$share_id))) {
                return Lib_Errors::USER_LIKED;
            }

            $data = array(
                'iShareId' => $share_id,
                'iUin'  => $uin,
                'iType'  => $type,
                'sGoodsName'  => $share_info['sGoodsName'],
                'iLuckyCode'  => $share_info['iLuckyCode'],
                'iLotTime'  => $share_info['iLotTime'],
                'iLotCount'  => $share_info['iLotCount'],
                'iPlatForm' => $platform,
                'iIp'   => ip2long(get_ip())
            );

            if (!$this->share_user_action_log_model->add_row($data)) {
                $this->log->error('AwardsActivity', 'Share | Operate | add share action log failed | share_id:'.$share_id.' | sql'.$this->share_user_action_log_model->db->last_query().' | '.__METHOD__);
            }

            //查看操作 只更新总数
            if ($type != Lib_Constants::SHARE_OPT_VIEW) {
                $data = array(
                    'iShareId' => $share_id,
                    'iUin'  => $uin,
                    'sNickName'  => $uin,
                    'sHeadImg'  => $uin,
                    'iOptTime'  => time(),
                    'iPlatForm' => $platform,
                    'iIp'   => ip2long(get_ip())
                );
                $this->load->model('share_action_log_model');//晒单维度表
                if (!$this->share_action_log_model->add_row($data)) {
                    $this->log->error('AwardsActivity', 'Share | Operate | add share action log failed | share_id:'.$share_id.' | sql'.$this->share_action_log_model->db->last_query().' | '.__METHOD__);
                }
            }

            //更新点赞数
            if (!$this->share_model->update_count($share_id, array('iLikeCount' => 1))) {
                $this->log->error('AwardsActivity', 'Share | Operate | add share action log failed | share_id:'.$share_id.' | sql'.$this->share_action_log_model->db->last_query().' | '.__METHOD__);
            }

            //奖励
            $this->load->service('awards_service');
            return $this->awards_service->grant_awards($uin, Lib_Constants::AWARDS_TYPE_TAG_LIKE, $platform, $share_id);
        }

        return Lib_Errors::SUCC;
    }
}