<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 积分service
 * Class Score_service
 */
class Score_service extends  MY_Service
{
    /**
     * 积分兑换
     * @param $uin
     * @param $act_id
     * @param $platform
     */
    public function exchange($uin, $act_id, $count=0, $platform=Lib_Constants::PLATFORM_WX)
    {
        if(!$uin || !$act_id || !$count) {
            return Lib_Errors::PARAMETER_ERR;
        }

        $this->load->model('score_activity_model');
        $score_acitvity = $this->score_activity_model->get_row($act_id);
        if(empty($score_acitvity)) {
            $this->log->error('Socre', 'Exchange | score activity error or not exist | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        if ($score_acitvity['iState'] != Lib_Constants::PUBLISH_STATE_ONLINE) {//未上线
            $this->log->error('Socre', 'Exchange | score activity is offline | params:'.json_encode($score_acitvity).' | '.__METHOD__);
            return Lib_Errors::SCORE_ACTIVITY_OFFLINE;
        }

        $now = time();
        if ($score_acitvity['iStartTime'] > $now) {//未开始
            $this->log->error('Socre', 'Exchange | score activity is offline | params:'.json_encode($score_acitvity).' | '.__METHOD__);
            return Lib_Errors::SCORE_ACTIVITY_NOT_BEGIN;
        }

        if ($score_acitvity['iEndTime'] && $score_acitvity['iEndTime'] < $now) {//已结束
            $this->log->error('Socre', 'Exchange | score activity is offline | params:'.json_encode($score_acitvity).' | '.__METHOD__);
            return Lib_Errors::SCORE_ACTIVITY_TIMEOUT;
        }

        if ($score_acitvity['iTotal'] <= $score_acitvity['iUsed'] || ($score_acitvity['iUsed']+$count) >$score_acitvity['iTotal']  ) {//库存不足
            $this->log->error('Socre', 'Exchange | score activity stock not enough | params:'.json_encode($score_acitvity).' | '.__METHOD__);
            return Lib_Errors::SCORE_STOCK_NOT_ENOUGH;
        }

        $score = $score_acitvity['iPreScore'] ? $score_acitvity['iPreScore'] : $score_acitvity['iOriScore'];

        $this->load->model('user_ext_model');
        $user_ext = $this->user_ext_model->get_user_ext_info($uin);
        if(empty($user_ext)) {
            $this->log->error('Socre', 'Exchange | user ext info error or not exist | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::PARAMETER_ERR;
        }

        if($user_ext['score'] <= 0 || $user_ext['score'] < $score) {
            $this->log->error('Socre', 'Exchange | user ext info error or not exist | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::SCORE_NOT_ENOUGH;//余额不足
        }

        //单次限购
        if ($score_acitvity['iSingle'] < $count) {
            $this->log->error('Socre', 'Exchange | large than single exchange count | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::SCORE_LARGE_THAN_SINGLE;
        }

        //用户兑换总数
        $this->load->model('score_order_model');
        $order_count = $this->score_order_model->row_count(array('iActivityId'=>$act_id, 'iUin'=>$uin, 'iStatus'=>Lib_Constants::PAY_STATUS_PAID));
        //$this->log->error('Socre', 'Exchange | large than single exchange count | sql:'.$this->score_order_model->db->last_query().' | '.__METHOD__);

        //总限购
        if ($score_acitvity['iMaxLimit'] <= $order_count) {
            $this->log->error('Socre', 'Exchange | large than max exchange count | params:'.json_encode(func_get_args()).' | '.__METHOD__);
            return Lib_Errors::SCORE_LARGE_THAN_TOTAL;
        }

        //开始兑换
        $this->load->service('order_service');
        $order_id = $this->order_service->create_score_order($uin, $act_id,$score_acitvity['iGoodsId'], $score_acitvity['iOriScore'], $score, $count, $platform);
        if($order_id < 0) {
            return Lib_Errors::SVR_ERR;
        }

        //更新订单状态 $uin, $act_id, $order_id, $goods_id, $goods_num, $pre_price, $count, $goods_type, $plat_from
        $ret = $this->order_service->set_succ_score_order($uin, $act_id, $order_id, $score_acitvity['iGoodsId'], $score_acitvity['iCouponNum'], $score ,$count, $score_acitvity['iGoodsType'] , $platform);
        if ($ret < 0) {
            return $ret;
        }

        $ret = array(
            'order_id'=>$order_id,
            'score' => -1
        );
        $user_ext = $this->user_ext_model->get_user_ext_info($uin);
        if(isset($user_ext['score'])) {
            $ret['score'] = intval($user_ext['score']);
        }
        return $ret;
    }
}