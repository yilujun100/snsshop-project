<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Cart extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    //新增购物车
    public function add()
    {
        extract($this->cdata);

        if(empty($uin) || empty($goods_id) || empty($act_id)){
            $this->log->error('Cart','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        if(empty($count)){
            $count = 1;
        }

        $this->load->model('active_peroid_model');
        if(!$active_config = $this->active_peroid_model->get_row(array('iGoodsId'=>$goods_id,'iActId'=>$act_id))){
            $this->log->error('Cart','active not found | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
        }

        $this->load->model('active_cart_model');
        $data = array(
            'goods_id' => $goods_id,
            'act_id' => $act_id,
            'uin' =>  $uin,
            'count' => $count
        );
        $cart = $this->active_cart_model->get_row(array('iGoodsId'=>$goods_id,'iActId'=>$act_id,'iUin'=>$uin));
        if($cart){
            if($this->active_cart_model->update_user_cart($uin,$cart['iCartId'],$cart['iBuyCount']+1)){
                $this->render_result(Lib_Errors::SUCC);
            }else{
                $this->log->error('Cart','add cart fail | sql['.$this->active_cart_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        }else{
            if($this->active_cart_model->add_cart($uin,$data)){
                $this->render_result(Lib_Errors::SUCC);
            }else{
                $this->log->error('Cart','add cart fail | sql['.$this->active_cart_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        }
    }


    //更新购物车
    public function update_cart()
    {
        extract($this->cdata);

        if(empty($uin) || empty($cart_id) || empty($count)){
            $this->log->error('Cart','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_cart_model');
        $info = $this->active_cart_model->get_user_carts($uin,array('iCartId'=>$cart_id));
        if(!$info){
            $this->log->error('Cart','cart not found | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::CART_NOT_FOUND);
        }

        if($result = $this->active_cart_model->update_user_cart($uin,$cart_id,$count)){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->log->error('Cart','update cart fail | sql['.$this->active_cart_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //删除购物车
    public function del_cart()
    {
        extract($this->cdata);

        if(empty($uin) || empty($cart_id)){
            $this->log->error('Cart','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_cart_model');
        $info = $this->active_cart_model->get_user_carts($uin,array('iCartId'=>$cart_id));
        if(!$info){
            $this->log->error('Cart','cart not found | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::CART_NOT_FOUND);
        }

        if($resut = $this->active_model->del_user_cart($uin,$cart_id)){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->log->error('Cart','del cart fail | sql['.$this->active_cart_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //批量删除
    public function del_cart_list()
    {
        extract($this->cdata);

        if(empty($uin) || empty($act_ids) || !is_string($act_ids)){
            $this->log->error('Cart','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_cart_model');
        $table_name = $this->active_cart_model->map($uin)->get_cur_table();
        if($resut = $this->active_cart_model->query("DELETE FROM `".$table_name."` WHERE iUin='".$uin."' AND iActId IN(".$act_ids.")", true)){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->log->error('Cart','del cart fail | sql['.$this->active_cart_model->db->last_query().')] | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //用户购物车列表
    public function get_cart_list()
    {
        extract($this->cdata);

        if(empty($uin)){
            $this->log->error('Cart','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_cart_model');
        $list = $this->active_cart_model->get_user_carts($uin);//pr($this->active_cart_model->db->last_query());
        if(!$list){
            $list = array();
        }

        $where_in = array();
        foreach($list['list'] as $val){
            $where_in[] = $val['iActId'];
        }
        $where_in = empty($where_in) ? array(0) : $where_in;

        $this->load->model('active_config_model');
        $active = $this->active_config_model->get_active_configs('*',array('where_in'=>array('iActId',$where_in)));
        $list['active'] = $active['list'];

        $this->render_result(Lib_Errors::SUCC,$list);
    }


    public function get_cart_count()
    {
        extract($this->cdata);

        if(empty($uin)){
            $this->log->error('Cart','params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_cart_model');
        $count = $this->active_cart_model->row_count(array('iUin'=>$uin));
        $this->render_result(Lib_Errors::SUCC,$count ? $count : 0);
    }

}