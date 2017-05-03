<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 消息推送service
 * Class Score_service
 */
class Push_service extends  MY_Service
{

    /**
     * 统一添加消息推送任务表
     * @param $business 业务标识
     * @param $key 外键，防止插入多条
     * @param $uin 用户$uin
     * @param $data  json内容
     * @param $begin_time   开始时间，0为不限
     * @return int
     */
    public function add_task($business,$key,$uin,$data,$begin_time = 0)
    {
        //检查业务类型
        $business_arr = get_variable('msg_business_type',array());
        $business_arr = is_string($business_arr) ? explode(',',$business_arr) : $business_arr;
        if(empty($business) || !in_array($business,$business_arr)){
            return Lib_Errors::MESSAGE_BUSINESS_TYPE_FAILED;
        }

        //判断外键
        if(empty($key)){
            return Lib_Errors::SCORE_STOCK_NOT_ENOUGH;
        }

        //判断用户
        $this->load->model('user_model');
        if(empty($uin) || !$user = $this->user_model->get_user_by_uin($uin)){
            return Lib_Errors::MESSAGE_TO_UIN_ERROR;
        }

        //判断模板
        $data = is_array($data) ? $data : json_encode($data,true);
        $this->load->model('message_template_model');
        $temp = $this->message_template_model->get_row(array('sMsgType' => $business));
        if (! $temp || empty($temp)) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $mode = $temp['iNotifyType'];
        if (true !== ($variable = check_temp_data($temp['sTemplate'], $data))) {
            $this->log->error('Push_service', 'template data error', array('variable'=>$variable,'template'=>$temp,'data'=>$data));
            return Lib_Errors::MESSAGE_TEM_DATA_ERROR;
        }
        $content = compile_temp($temp['sTemplate'], $data);//编译模板

        //判断消息类型
        if(empty(Lib_Constants::$msg_notify[$mode]) || $temp['iNotifyType'] != $mode){
            return Lib_Errors::MESSAGE_NOTIFY_TYPE_INVALID;
        }


        $data = array(
            'iBusiness' => $business,
            'sKey' => $key,
            'sOpenId' => $user['sOpenId'],
            'iPushMode' => $mode,
            'sContent' => $content,
            'sTempId' => $mode == Lib_Constants::MSG_NOTIFY_WX ? $temp['sExtField'] : '',
            'iCreateTime' => time(),
            'iLastModTime' => time(),
            'iBeginTime' => intval($begin_time)
        );

        $this->load->model('push_task_model');
        if($rs = $this->push_task_model->add_row($data)){
            return $rs;
        }else{
            $this->log->error('Push_service', 'add task fail | sql['.$this->push_task_model->db->last_query().'] | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
    }


    /**
     * 发送微信模板消息
     * @param $data
     * @return int
     */
    public function send_wx($data)
    {
        if (empty($data) || !is_array($data)) {
            $this->log->error('Push_service', 'wx template data error', array('data'=>$data));
            return Lib_Errors::MESSAGE_TEM_DATA_ERROR;
        }

        return $rs = Lib_WeixinNotify::sendNotify($data);
    }



    /**
     * 发送手机短信消息
     * @param $toUin
     * @param $data
     * @param $tem
     *
     * @return int
     */
    public function send_sms($toUin, $data, $tem)
    {
        return Lib_Errors::SUCC;
    }









    /**
     * 增加微信消息
     * @param $data
     */
    public function add_msg_wx($data)
    {
        $data = array(
            'iBusiness' => isset($data['iBusiness']) ? $data['iBusiness'] : '',
            'sKey' => isset($data['sKey']) ? $data['sKey'] : '',
            'sOpenId' => $data['sOpenId'],
            'iPushMode' => Lib_Constants::MSG_NOTIFY_WX,
            'sContent' => json_encode($data['params']),
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );

        //检查参数
        if(!isset($data['sKey']) || empty($data['sContent']) || empty($data['iBusiness']) || empty($data['sOpenId'])){
            $this->log->error('PushService','params error | params['.json_encode($data).'] | '.__METHOD__);
            return false;
        }

        $this->load->model('push_task_model');
        if($rs = $this->push_task_model->add_row($data)){
            return $rs;
        }else{
            return false;
        }
    }
}