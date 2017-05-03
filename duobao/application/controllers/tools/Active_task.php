<?php

header("Content-type: text/html; charset=utf-8");

/**
 * 此工具是初始化原始夺宝活动
 * Class Active_task
 */
class Active_task extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('active_peroid_model');
        $this->load->model('active_task_model');
        $this->load->service('robot_service');
        $this->load->model('active_task_model');
    }

    //只初始化某一个夺宝活动
    public function run()
    {
        $peroid_code = $this->get_post('peroid_code');


        if(!empty($peroid_code)){
            list($act_id,$peroid) = period_code_decode($peroid_code);
            $active = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iPeroid'=>$peroid));
            if(empty($active)){
                pr($active);die('夺宝活动查询失败');
            }

            //
            $task_data = true;
            if($active['iActType'] == Lib_Constants::ACTIVE_TYPE_SYS){
                echo "====================".period_code_encode($active['iActId'],$active['iPeroid'])."=========================";
                $task_data = $this->robot_service->lottery_rand_task($active['iLotCount'],$active,isset($active['iHeat']) && !empty($active['iHeat']) && $active['iHeat']>= 10 ? $active['iHeat'] : 60,$active['iPeroidCode']);
                $this->active_task_model->query("UPDATE t_active_task SET iRunTime=iRunTime+".(time()+30).",iState=1 WHERE iPeroidCode = ".period_code_encode($active['iActId'],$active['iPeroid']),true);
                $this->active_peroid_model->query("UPDATE t_active_peroid SET iPredictMinute = '".intval($task_data)."' WHERE iPeroidCode = ".period_code_encode($active['iActId'],$active['iPeroid']),true);
                $this->active_task_model->update_cache_rows(array('iPeroidCode'=>period_code_encode($active['iActId'],$active['iPeroid'])),true);//更新缓存
            }

            if($task_data){
                echo "==========SUCCESS==================";
            }else{
                echo "============FAIL==================";
            }
        }

        echo "==============DONE===================";
    }

    //初始化所有在线夺宝活动add share invite succ
    public function all()
    {
        $actives = $this->active_peroid_model->get_rows(array('iLotState'=>0,'iProcess <'=>100,'iIsCurrent'=>1,'iHeat >' => 0,'iPredictMinute' => 0));
        $i = $j = 0;
        foreach($actives as $active){
            if($active['iActType'] == Lib_Constants::ACTIVE_TYPE_SYS){
                echo "====================".period_code_encode($active['iActId'],$active['iPeroid'])."=========================";
                $task_data = $this->robot_service->lottery_rand_task($active['iLotCount'],$active,isset($active['iHeat']) && !empty($active['iHeat']) && $active['iHeat']>= 10 ? $active['iHeat'] : 60,$active['iPeroidCode']);
                $this->active_task_model->query("UPDATE t_active_task SET iRunTime=iRunTime+".(time()+30).",iState=1 WHERE iPeroidCode = ".period_code_encode($active['iActId'],$active['iPeroid']),true);
                $this->active_peroid_model->query("UPDATE t_active_peroid SET iPredictMinute = '".intval($task_data)."' WHERE iPeroidCode = ".period_code_encode($active['iActId'],$active['iPeroid']),true);
                $this->active_task_model->update_cache_rows(array('iPeroidCode'=>period_code_encode($active['iActId'],$active['iPeroid'])),true);//更新缓存
                if($task_data){
                    echo "==========SUCCESS | period_code[".period_code_encode($active['iActId'],$active['iPeroid'])."]==================";
                    $i++;
                }else{
                    echo "==========FAIL | period_code[".period_code_encode($active['iActId'],$active['iPeroid'])."]==================";
                    $j++;
                }
            }
        }

        echo "================SUCCESS['.$i.']============FAIL['.$j.']==================";
    }
}