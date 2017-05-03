<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summary extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    public function active_summary()
    {
        extract($this->cdata);

        if(empty($peroid) || empty($act_id)){
            $this->log->error('Summary','active summary | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $page_index = isset($page_index) ? $page_index : 1;
        $page_size = isset($page_size) ? $page_size : 20;


        $this->load->model('luckycode_summary_model');
        $this->load->model('active_summary_model');
        $peroid_code = period_code_encode($act_id, $peroid);
        $lists =  $this->active_summary_model->row_list('*',array('iPeroidCode'=>$peroid_code),$order_by=array('iCreateTime'=>'DESC'),$page_index, $page_size );
        //$this->log->error('sql', $this->active_summary_model->db->last_query());
        $this->render_result(Lib_Errors::SUCC,$lists);
    }


    public function order_summary()
    {
        extract($this->cdata);
        if(empty($where['iUin'])){
            $this->log->error('Summary','order summary | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $page_index = isset($page_index) ? $page_index : 1;
        $page_size = isset($page_size) ? $page_size : 5;

        $where = isset($where) ? $where : array();
        $this->load->model('order_summary_model');
        $this->load->model('user_summary_model');
        //$table = $this->order_summary_model->map($where['iUin'])->get_cur_table();
        //$lists =  $this->order_summary_model->query("SELECT * FROM `".$table."` WHERE iUin='".$where['iUin']."' GROUP BY `iActId`,`iPeroid` LIMIT ")
        $lists = $this->user_summary_model->row_list('iActId,iPeroid,iGoodsId,sGoodsName,SUM(iLotCount) AS iLotCount,iLotState,iLotTime,SUM(iIsWin) AS iIsWin',$where, $order_by=array('iLotTime'=>'DESC'), $page_index, $page_size , $group_by = array('iActId','iPeroid'));
        //$this->log->error('xxx',$this->user_summary_model->db->last_query());
        $this->render_result(Lib_Errors::SUCC,$lists);
    }

    public function get_peroid_order_summary()
    {
        extract($this->cdata);
        if(empty($uin) || empty($act_id) || empty($peroid)){
            $this->log->error('Summary','order summary | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        //$this->load->model('order_summary_model');
        $this->load->model('user_summary_model');
        $lists = $this->user_summary_model->get_rows(array('iActId'=>$act_id,'iPeroid'=>$peroid,'iUin'=>$uin));
        $this->render_result(Lib_Errors::SUCC,$lists);
    }
}