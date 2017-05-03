<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/***
 * 分享有礼 - 邀请人发券 【在新用户参与夺宝之后发券】
 */


class ShareInvite extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数

    protected $log_type = 'ShareInvite';
    private $now_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('share_invite_succ_model');
        $this->load->model('wx_new_user_model');
        $this->load->model('coupon_action_log_model');
        $this->load->model('active_order_model');
    }


    public function run()
    {
        $this->now_time = time();
        $__startTime = microtime(true);
        $this->log("+---------------------------START---------------------------");
        $this->log("| script start. nowTime[".$this->now_time."]; nowDate[".date('Y-m-d H:i:s',$this->now_time)."]; microtime[".$__startTime."]");

        //新用户已领券 老用户邀请列表
        $sql = 'select count(*) as iTotal from '.$this->share_invite_succ_model->get_cur_table().' where iToStatus = '.Lib_Constants::STATUS_1.' and iActId='.Lib_Constants::ACTIVITY_ID.' and iStatus='.Lib_Constants::STATUS_0;
        $count = $this->share_invite_succ_model->query($sql, true);
        if (empty($count) || empty($count[0]) || empty($count[0]['iTotal'])) {
            $this->log("| no record of groupon diy is ended! sql[$sql]");
            return true;
        }

        $count = intval($count[0]['iTotal']);
        $total_page = ceil($count/self::LIMIT);

        $this->log("| total count[$count] | total page[$total_page] | sql[$sql]");
        $this->load->service('awards_service');
        for ($p_index=1; $p_index<=$total_page; $p_index++) {
            $sql = 'select iAutoId,iUin,iActId,iToUin,sExt,iCreateTime from '.$this->share_invite_succ_model->get_cur_table().' where iToStatus = '.Lib_Constants::STATUS_1.' and iActId='.Lib_Constants::ACTIVITY_ID.' and iStatus='.Lib_Constants::STATUS_0.' limit '.($p_index-1)*self::LIMIT.', '.self::LIMIT;
            $list = $this->share_invite_succ_model->query($sql, true);
            if (empty($list)) {
                $this->log("| no record of groupon diy is ended! sql[$sql]");
                continue;
            }

            foreach ($list as $item) {
                //检查用户是否已经参与过夺宝活动
                if ($this->active_order_model->get_row(array('iUin'=>$item['iToUin'], 'iLuckyCodeState'=>1, 'iUnitPrice>'=>0), true)) {
                    $ret = $this->awards_service->grant_awards($item['iUin'], Lib_Constants::AWARDS_TYPE_TAG_SHARE_INVITE, Lib_Constants::PLATFORM_WX, array('to_uin'=>$item['iToUin'], 'act_id'=>$item['iActId'],'key'=>$item['iUin'].'_'.$item['iToUin'].'_'.$item['iActId']), array('key'=>md5($item['iUin'].'_'.$item['iToUin'].'_'.$item['iActId']), 'uin'=>$item['iUin'],'data'=>array('awards_time'=>date('Y年m月d日H点i分'), 'create_time'=>date('Y年m月d日H点i分',$item['iCreateTime']))));
                    if (is_array($ret) ||  $ret == Lib_Errors::SUCC) { //更新状态
                        if (!$this->share_invite_succ_model->update_row(array('iStatus'=>Lib_Constants::STATUS_1, 'iUpdateTime'=>time()), array('iAutoId'=>$item['iAutoId']))) {
                            $this->log("| set invite succ status failed! | ".json_encode(array('iStatus'=>Lib_Constants::STATUS_1, 'iUpdateTime'=>time()), array('iAutoId'=>$item['iAutoId']))." | ".json_encode($item));
                        } else {
                            $this->log("| send share invite coupon succ | ".json_encode($item));
                        }
                    } else {
                        $this->log("| send coupon failed! | ".json_encode($item));
                    }
                }
            }
        }

        $__endTime = microtime(true);
        $this->log("| script end [".date('Y-m-d H:i:s')."];microtime[".$__endTime."];costTime[".($__endTime-$__startTime)."]");
        $this->log("+----------------------------END---------------------------");
        return true;
    }
}