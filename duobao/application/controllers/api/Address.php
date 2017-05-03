<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 相关收货地址API
 * Class Address
 */
class Address extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    //新增收货地址
    public function add()
    {
        //检查参数
        $cdata = $this->cdata;
        if(empty($cdata['uin']) || empty($cdata['name']) || empty($cdata['province']) || empty($cdata['city']) || empty($cdata['district']) || empty($cdata['address']) || empty($cdata['mobile'])){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        if(strlen($cdata['mobile']) != 11){
            $this->render_result(Lib_Errors::MOBILE_ERROR);
        }

        $this->load->model('address_model');
        $result = $this->address_model->add_user_address($cdata['uin'],$cdata['name'],$cdata['province'],$cdata['city'],$cdata['district'],$cdata['address'],$cdata['mobile'],$cdata['remark'],$cdata['isDefault']);
        if($result){
            $this->render_result();
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //获取用户收货地址列表
    public function addr_list()
    {
        $cdata = $this->cdata;
        if(empty($cdata['uin'])){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('address_model');
        $list = $this->address_model->get_user_address_list($cdata['uin'],array(),array());
        if($list){
            $this->render_result(Lib_Errors::SUCC,$list);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }

    //获取默认地址
    public function default_addr()
    {
        $cdata = $this->cdata;
        if(empty($cdata['uin'])){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('address_model');
        $list = $this->address_model->get_row(array('iIsDefault'=>1,'iUin'=>$cdata['uin']));
        if($list){
            $this->render_result(Lib_Errors::SUCC,$list);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //获取地址详情
    public function get_addr_info()
    {
        $cdata = $this->cdata;
        if(empty($cdata['uin'])){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('address_model');
        $info = $this->address_model->get_address_info_by_id($cdata['id'],$cdata['uin']);
        if($info){
            $this->render_result(Lib_Errors::SUCC,$info);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }

    //删除地址
    public function del_addr()
    {
        $cdata = $this->cdata;
        if(empty($cdata['uin']) || empty($cdata['id'])){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('address_model');
        $info = $this->address_model->del_address($cdata['uin'],$cdata['id']);
        if($info){
            $this->render_result(Lib_Errors::SUCC,$info);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    //地址保存
    public function addr_save()
    {
        extract($this->cdata);
        if(empty($uin) || empty($name) || empty($address) || empty($area) || empty($phone)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $area = explode(' ',$area);
        $this->load->model('address_model');
        if(!empty($address_id)){
            $data = array(
                'sName' => $name,
                'sProvince' => $area[0],
                'sCity' => $area[1],
                'sDistrict' => $area[2],
                'sAddress' => $address,
                'sMobile' => $phone,
                'iIsDefault' => 1
            );
            $result = $this->address_model->update_address($data,$uin,$address_id);
            if (! empty($order_id)) {
                $this->load->service('order_service');
                if ($this->order_service->order_address($uin, $order_id, $address_id) != Lib_Errors::SUCC) {
                    $result = false;
                } else {
                    $result = true;
                }
            }
        }else{
            $result = $this->address_model->add_user_address($uin,$name,$area[0],$area[1],$area[2],$address,$phone, $remark = '',$default = 1);
        }

        if($result){
            $this->render_result(Lib_Errors::SUCC,$result);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}