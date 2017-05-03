<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每30秒跑一次
 * 自动推送消息，默认为微信消息
 * @date 2016-05-04
 * @autor leo.zou
 */

class Push extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'Push';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('push_task_model');
    }


    public function run(){
        //while(true){
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        set_time_limit(0);

        $user_list = array();
        //$list = $this->push_task_model->get_rows(array('iState'=>0,'iCount <'=> 3,'iBeginTime'=>0));
        $list = $this->push_task_model->query("SELECT * FROM t_push_task WHERE iState = 0 AND iCount < 3 AND (iBeginTime = 0 OR iBeginTime <= ".time().")");

        //开始循环推送消息
        $list = empty($list) ? array() : $list;
        foreach($list as $li){
            //先更新次数
            $this->push_task_model->update_row(array('iCount'=>$li['iCount']+1),array('iAutoId'=>$li['iAutoId']));

            $content = json_decode($li['sContent'],true);
            $params = array(
                'openId' => $li['sOpenId'],
                'template_id' => $li['sTempId'],
            );
            $params = !empty($content) ? array_merge($params,$content) : $params;

            $this->load->service('push_service');
            switch($li['iPushMode']){
                case Lib_Constants::MSG_NOTIFY_WX:
                    $rs = $this->push_service->send_wx($params);
                    if($rs === true){
                        if(!$this->push_task_model->update_row(array('iState'=>1),array('iAutoId'=>$li['iAutoId']))){
                            $this->log("update push_task fail | sql[".$this->push_task_model->db->last_query()."]");
                        }
                        $this->log("=====================push success=========================");
                    }else{
                        $this->push_task_model->update_row(array('sError'=>$rs),array('iAutoId'=>$li['iAutoId']));
                        $this->log("=====================push fail=========================");
                    }
                    break;

                case Lib_Constants::MSG_NOTIFY_SMS:
                default:
                    $this->push_task_model->update_row(array('sError'=>'notify type nonsupport','iCount'=>3),array('iAutoId'=>$li['iAutoId']));
                    break;
            }
        }

        sleep(10);
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
        //}
    }


}