<?php
/**
 * 计划每小时跑一次
 * 清除相关临时表数据
 * @date 2016-06-06
 * @autor leo.zou
 */


class Rubbish extends Script_Base
{
    protected $log_type = 'Rubbish';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('active_task_model');
        $this->load->model('active_temporary_model');
        $this->load->model('robot_temporary_model');
        $this->load->model('active_task_model');
        $this->load->model('luckycode_record_model');
        $this->load->model('active_peroid_model');
    }



    public function run()
    {
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        $this->log("============================active_temporary_model start==============================");
        $return = $this->clear_active_temporary();
        $state = is_bool($return) && !$return ? 'false' : 'true';
        $this->log("============================clear ".intval($return)." rows | clear state[".$state."]==============================");
        $this->log("============================active_temporary_model end==============================");

        $this->log("============================active_task_model start==============================");
        $return = $this->clear_active_task();
        $state = is_bool($return) && !$return ? 'false' : 'true';
        $this->log("============================clear status[".$state."]==============================");
        $this->log("============================active_task_model end==============================");

        $this->log("============================active_task_model start==============================");
        $return = $this->clear_robot_temporary();
        $state = is_bool($return) && !$return ? 'false' : 'true';
        $this->log("============================clear status[".$state."]==============================");
        $this->log("============================active_task_model end==============================");

        $this->log("============================luckycode_record_model start==============================");
        $return = $this->clear_luckycode_record();
        $state = is_bool($return) && !$return ? 'false' : 'true';
        $this->log("============================clear status[".$state."]==============================");
        $this->log("============================luckycode_record_model end==============================");

        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n");
    }


    /**
     * 清滁所有用户参与活动数据
     */
    protected function clear_active_temporary()
    {
        $total_rows = $this->active_temporary_model->query("select count(*) as total from t_active_temporary ");
        $total_rows = isset($total_rows[0]) ? $total_rows[0]['total'] : 0;

        $hour_ago = strtotime("-3 days");
        $hour_rows = $this->active_temporary_model->query("select count(*) as total from t_active_temporary where iCreateTime <= ".$hour_ago);
        $hour_rows = isset($hour_rows[0]) ? $hour_rows[0]['total'] : 0;
        if($total_rows-$hour_rows <= 200){
            return true;
        }
        $this->active_temporary_model->delete_rows(array('iMsecTime <='=>$hour_ago));

        if($this->active_temporary_model->delete_rows(array('iMsecTime <='=>$hour_ago))){
            return $hour_rows;
        }else{
            $this->log($this->active_temporary_model->db->last_query());
            return false;
        }
    }

    /**
     * 临时机器人参与任务表，此表并没有分配夺宝码,相当于用户的订单表
     */
    protected function clear_robot_temporary()
    {
        $return = true;
        $hour_ago = strtotime("-3 days");
        for($i = 0;$i < 10; $i++){
            if(!$this->robot_temporary_model->query("DELETE FROM `t_robot_temporary".$i."` WHERE iCreateTime <=".$hour_ago." AND iLuckyCodeState = 1")){
                $return = false;
            }
            //$this->robot_temporary_model->update_cache_rows(array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid']));//更新缓存
        }
        return $return;
    }


    /**
     * 临时机器人任务表,每分钟任务表
     */
    protected function clear_active_task()
    {
        $hour_ago = strtotime("-1 days");
        if($this->active_task_model->delete_rows(array('iRunTime <='=>$hour_ago,'iLock'=>1,'iState'=>2))){
            return true;
        }else{
            $this->log($this->active_task_model->db->last_query());
            return false;
        }
    }


    /**
     * 此表由于是一个夺宝码对应一条记录，不清除数据造成海量数据
     * 此表用于通过单夺宝码查询具体订单及相关的用户
     * k目前可以删除已经开过奖的记录
     */
    protected function clear_luckycode_record()
    {
        $return = true;
        for($i = 0;$i < 10; $i++){
            $hour_ago = strtotime("-1 hour");
            $rs = $this->luckycode_record_model->query("SELECT COUNT(*),iActId,iPeroid FROM t_luckycode_record".$i." WHERE iCreateTime <= ".$hour_ago." GROUP BY iActId,iPeroid");
            foreach($rs as $val){
                $peroid = $this->active_peroid_model->get_row(array('iActId'=>$val['iActId'],'iPeroid'=>$val['iPeroid']));
                if($peroid['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_OPENED){
                    if(!$this->luckycode_record_model->delete_rows(array('iActId'=>$peroid['iActId'],'iPeroid'=>$peroid['iPeroid']))){
                        $return = false;
                    }
                }
            }
        }

        return $return;
    }
}
