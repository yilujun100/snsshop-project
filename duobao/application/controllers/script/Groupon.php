<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 成团定时脚本
 * 每分钟执行一次
 * 处理已过期的拼团成团或失败 并进行相应操作
 * Class Groupon
 */
class Groupon extends Script_Base
{
    const LIMIT = 10000;  //每次脚本处理的订单数

    protected $log_type = 'Groupon';
    private $now_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('groupon_diy_model');
        $this->load->model('groupon_active_model');
        $this->load->model('groupon_spec_model');
    }


    public function run()
    {
        $this->now_time = time();
        $__startTime = microtime(true);
        $this->log("+---------------------------START---------------------------");
        $this->log("| script start. nowTime[".$this->now_time."]; nowDate[".date('Y-m-d H:i:s',$this->now_time)."]; microtime[".$__startTime."]");

        //更新拼团活动发布状态  在线->已结束
        if ($this->groupon_active_model->update_rows(array('iState'=>Lib_Constants::PUBLISH_STATE_END,'iUpdateTime'=>$this->now_time), array('iState'=>Lib_Constants::PUBLISH_STATE_ONLINE, 'iEndTime<='=>$this->now_time))) {
            $this->log("| update groupon active iState | success | ".$this->groupon_active_model->db->last_query());
        } else {
            $this->log("| update groupon active iState | failed or no row affected | ".$this->groupon_active_model->db->last_query());
        }

        //查询超时开团
        $sql = 'select * from '.$this->groupon_diy_model->get_cur_table().' where iEndTime<'.$this->now_time.' and iState='.Lib_Constants::GROUPON_DIY_ING.' LIMIT '.self::LIMIT;
        $diy_list = $this->groupon_diy_model->query($sql, true);
        if (empty($diy_list)) {
            $__endTime = microtime(true);
            $this->log("| no record of groupon diy is ended! sql[$sql]");
            $this->log("| script end [".date('Y-m-d H:i:s')."];microtime[".$__endTime."];costTime[".($__endTime-$__startTime)."]");
            $this->log("+----------------------------END---------------------------");
            return true;
        }

        $this->load->service('groupon_service');
        foreach ($diy_list as $diy) {
            $ret = $this->groupon_service->check_groupon_succ($diy);
            $this->log("| check groupon diy; ret[".$ret."]; row:".json_encode($diy)."]");
        }

        $__endTime = microtime(true);
        $this->log("| script end [".date('Y-m-d H:i:s')."];microtime[".$__endTime."];costTime[".($__endTime-$__startTime)."]");
        $this->log("+----------------------------END---------------------------");
        return true;
    }
}