<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 计划每分钟跑一次
 * 加速开奖“机器人”任务脚本
 * 把任务执行成临时订单
 * @date 2016-05-31
 * @autor leo.zou
 */


class ActiveTask extends Script_Base
{
    protected $log_type = 'ActiveTask';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('active_task_model');
        $this->load->service('robot_service');
    }



    public function run()
    {
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        $runtime = strtotime(date('Y-m-d H:i'));


        $list = $this->active_task_model->get_rows(array('iLock'=>0,'iState'=>1,'iRunTime >='=> $runtime,'iRunTime <'=> $runtime+60));
        if(is_array($list) && !empty($list)){
            $this->log("STEP1: =============get task list[".count($list)."]=============");
            //如果有任务，则把任务先锁定
            $this->log("STEP2: =============lock task=============");
            if(!$this->active_task_model->update_rows(array('iLock'=>1),array('iState'=>1,'iRunTime >='=> $runtime,'iRunTime <'=> $runtime+60))){
                $this->log("STEP3: =============task fail=============");
                return false;
            }

            //执行任务
            $this->log("STEP4: =============task run=============");
            while(!isset($stop) && !empty($list)){
                $temp = $list;
                foreach($temp as $k => $task){
                    $time = $task['iRunTime'];
                    if($time <= time()){ //如果等于或超过当前时间，参与夺宝
                        //调参与夺宝service
                        list($act_id,$peroid) = period_code_decode($task['iPeroidCode']);
                        $respon = $this->robot_service->add_robot(
                            $task['iUin'],
                            $task['iGoodsId'],
                            $task['sGoodsName'],
                            $act_id,
                            $peroid,
                            $task['iLotCount'],
                            $task['sIP'],
                            $task['sLocation']
                        );
                        $this->log("STEP5: =============task[".$task['sKey']."] start=============");

                        //如果参与成功,改变任务状态，并且unset掉这个值
                        if($respon > Lib_Errors::SUCC){
                            //$this->log("STEP6: =============inset task success[".$respon."]=============");
                            unset($list[$k]);
                        }else{
                            unset($list[$k]);
                            $this->active_task_model->update_rows(array('iState'=>-1),array('iState'=>1,'iRunTime >='=> $runtime,'iRunTime <'=> $runtime+60,'sKey'=>$task['sKey']));
                            $this->log("STEP6: =============task fail errorcode[".$respon."]  skey[".$task['sKey']."]=============");
                        }
                    }
                }

                //防止出现死循环，最多跑2分钟
                if($runtime+60*2 < time()){
                    $this->log("STEP7: =============task runtime too long a time=============");
                    $stop = true;
                }
            }

            //更新任务状态
            $times = 1;
            $query = true;
            do{
                if(!$this->active_task_model->update_rows(array('iState'=>2),array('iRunTime >='=> $runtime,'iRunTime <'=> $runtime+60,'iState'=>1))){
                    $this->log("STEP8: =============task fail(times'.$times.')=============");
                    $query = false;
                }
                $times++;
            }while($times < 5 && $query == false);
        }

        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n");
    }
}