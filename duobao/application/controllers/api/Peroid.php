<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Address
 */
class Peroid extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    public function get_list(){
        extract($this->cdata);

        $where = isset($where) ? $where : array();
        $this->load->model('active_peroid_model');
        return $this->active_peroid_model->get_rows($where);
    }


    //获取已经完成了的夺宝单，此数据可以永久cache
    public function get_static_active()
    {
        extract($this->cdata);

        if(empty($act_id) || empty($peroid)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_peroid_model');
        $detail = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iPeroid'=>$peroid,'iLotState !='=>Lib_Constants::ACTIVE_LOT_STATE_DEFAULT));

        $this->render_result(Lib_Errors::SUCC,$detail);
    }

    //获取当个活动当前期
    public function get_current_active()
    {
        extract($this->cdata);

        if(empty($act_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_peroid_model');
        $detail = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iIsCurrent'=>1));
        $this->render_result(Lib_Errors::SUCC,$detail);
    }
}