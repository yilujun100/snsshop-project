<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    public function get_detail()
    {
        extract($this->cdata);

        if(empty($order_id) || empty($uin)){
            $this->log->error('Deliver','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('order_deliver_model');
        if($detail = $this->order_deliver_model->get_row(array('iUin'=>$uin,'sOrderId'=>$order_id))){
            $this->render_result(Lib_Errors::SUCC,$detail);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    public function update_deliver_addr()
    {
        extract($this->cdata);

        if(empty($uin) || empty($order_id) || empty($name) || empty($address) || empty($mobile)){
            $this->log->error('Deliver','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('order_deliver_model');
        $detail = $this->order_deliver_model->get_row(array('iUin'=>$uin,'sOrderId'=>$order_id));
        $ext = empty($detail['sExtField']) ? array() : json_decode($detail['sExtField'],true);
        $ext[2] = time();
        $ext = json_encode($ext);
        if(!$this->order_deliver_model->update_deliver_row($uin,$order_id,$name,$mobile,$address,$ext)){
            $this->render_result(Lib_Errors::SVR_ERR);
        }else{
            $this->render_result(Lib_Errors::SUCC);
        }
    }


    public function confirm_deliver()
    {
        extract($this->cdata);

        if(empty($uin) || empty($order_id)){
            $this->log->error('Deliver','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('order_deliver_model');
        $detail = $this->order_deliver_model->get_row(array('iUin'=>$uin,'sOrderId'=>$order_id));
        $ext = empty($detail['sExtField']) ? array() : json_decode($detail['sExtField'],true);
        $ext[5] = time();
        $ext = json_encode($ext);
        if(!$this->order_deliver_model->update_row(array('iConfirmStatus'=>Lib_Constants::DELIVER_CONFIRM_STATUS,'sExtField'=>$ext),array('iUin'=>$uin,'sOrderId'=>$order_id))){
            $this->render_result(Lib_Errors::SVR_ERR);
        }else{
            $this->render_result(Lib_Errors::SUCC);
        }
    }


    /**
     * 获取用户中奖没有填写地址记录
     */
    public function get_empty_address_deliver()
    {
        extract($this->cdata);
        if(empty($uin)){
            $this->log->error('Deliver','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('order_deliver_model');
        $this->load->model('active_peroid_model');
        $list = $this->order_deliver_model->get_row(array('iUin'=>$uin,'iType'=>Lib_Constants::DELIVER_TYPE_ACTIVE,'sMobile'=>''));
        $peroid = array();
        if(!empty($list)){
            $peroid = $this->active_peroid_model->get_row(array('sWinnerOrder'=>$list['sOrderId'],'iWinnerUin'=>$list['iUin']));
        }

        $this->render_result(Lib_Errors::SUCC,$peroid);
    }
}