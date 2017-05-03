<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    public function get_detail()
    {
        extract($this->cdata);

        if(empty($goods_id)){
            $this->log->error('Goods','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('goods_item_model');
        if($goods = $this->goods_item_model->get_row(array('iGoodsId'=>$goods_id))){
            $this->render_result(Lib_Errors::SUCC,$goods);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}