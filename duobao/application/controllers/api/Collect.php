<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Collect extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    /**
     * 新增收藏夹
     */
    public function add_collect()
    {
        extract($this->cdata);

        if(empty($uin) || empty($act_id)){
            $this->log->error('Collect','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        if(empty($count)){
            $count = 1;
        }

        $this->load->model('active_peroid_model');
        if(!$active_config = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iIsCurrent'=>1))){
            $this->log->error('Collect','active not found | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
        }

        $this->load->model('active_collect_model');
        if($result = $this->active_collect_model->add_collect($this->uin,array('goods_id'=>$active_config['iGoodsId'],'goods_name'=>$active_config['sGoodsName'],'img'=>$active_config['sImg'],'price'=>$active_config['iCodePrice'],'act_id'=>$act_id,'count'=>$count))){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->log->error('Collect','add collect fail | sql['.$this->active_collect_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //获取收藏列表
    public function get_list()
    {
        extract($this->cdata);

        if(empty($uin)){
            $this->log->error('Collect','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_collect_model');
        if($list = $this->active_collect_model->get_collect_list($uin)){
            $this->render_result(Lib_Errors::SUCC,$list);
        }else{
            $this->log->error('Collect','select collect list fail | sql['.$this->active_collect_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //更新收藏
    public function update_collect()
    {
        extract($this->cdata);

        if(empty($uin) || empty($act_id) || empty($count)){
            $this->log->error('Collect','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_collect_model');
        $result = $this->active_collect_model->update_collect($uin,$act_id,$count);
        if($result){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->log->error('Collect','update collect fail | sql['.$this->active_collect_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }

    //删除收藏
    public function del_collect()
    {
        extract($this->cdata);

        if(empty($uin) || empty($act_id)){
            $this->log->error('Collect','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_collect_model');
        if($result = $this->active_collect_model->del_collect($uin,$act_id)){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->log->error('Collect','del collect fail | sql['.$this->active_collect_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }

    public function get_collect()
    {
        extract($this->cdata);

        if(empty($uin) || empty($act_id)){
            $this->log->error('Collect','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_collect_model');
        $row = $this->active_collect_model->get_collect($uin,$act_id);
        $this->render_result(Lib_Errors::SUCC,$row);
    }
}