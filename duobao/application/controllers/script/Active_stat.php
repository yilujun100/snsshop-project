<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 夺宝平台盈亏统计
 */

class Active_stat extends Script_Base
{
    protected $log_type = 'Active_stat';
    const SECTION_TIME = 600;

    public function run()
    {
        $now = time();
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        set_time_limit(0);

        $end_time = floor($now/self::SECTION_TIME)*self::SECTION_TIME;
        $start_time = ($end_time - self::SECTION_TIME);

        $this->log('====================================[diy_run][start:'.date('Y-m-d H:i:s', $start_time).'][end:'.date('Y-m-d H:i:s', $end_time).']=============================================');

        $this->do_run($start_time, $end_time);

        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    public function diy_run($start_time=0, $end_time=0) {
        if (!$start_time || !$end_time || ($end_time <= $start_time)) {
            $this->log("params error");
        }

        $now = time();
        $start_sec = floor($start_time/self::SECTION_TIME)*self::SECTION_TIME;
        $end_sec = floor($end_time/self::SECTION_TIME)*self::SECTION_TIME;
        echo '====================================[diy_run][start:'.date('Y-m-d H:i:s', $start_sec).'][end:'.date('Y-m-d H:i:s', $end_sec).']============================================='."\n";
        while($start_sec <= $end_sec) {
            $start_sec +=self::SECTION_TIME;
            if($start_sec <= $now) {
                $this->do_run($start_sec, ($start_sec+self::SECTION_TIME));
            }
        }
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    private function do_run($start_time, $end_time)
    {
        $this->log('====================================[do_run][sec:'.date('Y-m-d H:i:s', $start_time).'][time:'.date('Y-m-d H:i:s', $start_time).']=============================================');
        //echo '====================================[do_run][sec:'.date('Y-m-d H:i:s', $start_time).'][time:'.date('Y-m-d H:i:s', $start_time).']============================================='."\n";
        $open_count = $open_amount = $join_count = $join_amount = $cost_amount = $win_count = $win_amount = 0;

        $this->load->model('active_peroid_model');
        $active_peroid_table = $this->active_peroid_model->get_cur_table();
        /*开奖总次数 开奖金额 成本*/
        $sql = 'select count(*) open_count, sum(iTotalPrice) open_amount from '.$active_peroid_table.' where iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and iLotTime >='.$start_time.' and iLotTime<'.$end_time.';';
        $res = $this->active_peroid_model->query($sql);
        if (!empty($res) && !empty($res[0])) {
            $open_count = intval($res[0]['open_count']); // 开奖总次数
            $open_amount = intval($res[0]['open_amount']);//开奖金额

        }

        /*用户中奖次数 用户中奖金额*/
        $sql = 'select count(a.iPeroidCode) win_count, sum(a.iTotalPrice) win_amount, sum(b.iLowestPrice) cost_amount from '.$active_peroid_table.' a left join t_goods_item b on a.iGoodsId = b.iGoodsId  where a.iIsRobot = 0 and  a.iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and a.iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and a.iLotTime >='.$start_time.' and a.iLotTime<'.$end_time.';';
        $res = $this->active_peroid_model->query($sql);
        if (!empty($res) && !empty($res[0])) {
            $win_count = intval($res[0]['win_count']);//用户中奖次数
            $win_amount = intval($res[0]['win_amount']);//用户中奖金额
            $cost_amount = intval($res[0]['cost_amount']);//奖品成本
        }

        /*用户参与次数 参与金额*/
        $table_name = 't_user_summary';
        $db_name = 'yydb_active';
        $active_table_name = $this->active_peroid_model->get_cur_database().'.'.$this->active_peroid_model->get_cur_table();
        $db_num = $table_num = 10;
        for ($i=0; $i<$db_num; $i++) {
            for ($j=0; $j<$table_num; $j++) {
                $tmp_table = $db_name.$i.'.'.$table_name.$j;
                $sql = 'select sum(a.iLotCount) join_count, sum(b.iCodePrice*a.iLotCount) join_amount from '.$tmp_table.' a left join '.$active_table_name.' b on a.iActId=b.iActId and a.iPeroid=b.iPeroid where a.iIsRobot = 0 and  b.iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' and b.iLotState='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and b.iLotTime >='.$start_time.' and b.iLotTime<'.$end_time.';';
                $res = $this->active_peroid_model->query($sql);
                if (!empty($res) && !empty($res[0])) {
                    $join_count += intval($res[0]['join_count']);//用户中奖次数
                    $join_amount += intval($res[0]['join_amount']);//用户中奖金额
                }
            }
        }


        $this->load->model('active_daily_model');

        $now = time();
        $data = array(
            'iStatTime' => strtotime(date('Y-m-d', $start_time)),
            'iStatSecTime' => $start_time,
            'iOpenCount' => $open_count,//开奖总次数
            'iOpenAmount' => $open_amount,//开奖总金额
            'iJoinCoupon' => $join_count,//参与次数
            'iJoinAmount' => $join_amount,//参与金额
            'iWinCount' => $win_count,//中奖次数
            'iWinAmount' => $win_amount,//中奖金额
            'iFloatAmount' => ($win_amount-$join_amount),//用户盈亏 = 中奖金额 - 用户参与金额
            'iCostAmount' => $cost_amount,//用户奖品成本
            'iFloatSourAmount' => ($win_amount - $cost_amount), //采购盈亏 = 用户中奖金额 - 用户奖品成本
            'iFloatPlatAmount' => (($win_amount - $cost_amount) - ($win_amount-$join_amount)),//平台盈亏 = 采购盈亏 - 用户盈亏
            'iCreateTime' => $now,
            'iUpdateTime' => $now,
        );

        if(!$this->active_daily_model->get_row(array('iStatSecTime'=>$start_time))) {
            if ($this->active_daily_model->add_row($data)) {
                $this->log("add stat record succ | data:".json_encode($data));
            } else {
                $this->log("add stat record failed | data:".json_encode($data));
            }
        }

        $this->log("====================================do_run END(".date('Y-m-d H:i:s').")=============================================");
    }
}