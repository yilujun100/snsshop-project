<?php


class Address extends Duogebao_Base
{
    protected $need_login_methods = array('ajax_save');

    public function __construct()
    {
        parent::__construct();
    }


    public function ajax_save()
    {
        $name = $this->get_post('name');
        $phone = $this->get_post('phone');
        $area = $this->get_post('area');
        $address = $this->get_post('address');
        $address_id = $this->get_post('address_id',0);
        $order_id = $this->get_post('order_id',0);

        $data = array('uin'=>$this->user['uin'],'name'=>$name,'phone'=>$phone,'area'=>$area,'address'=>$address,'address_id'=>$address_id,'order_id'=>$order_id);
        if(empty($name) || empty($phone) || empty($address) || empty($area)){
            $this->log->error('Active',Lib_Errors::PARAMETER_ERR.' | params: '.json_encode($data).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $result = $this->get_api('addr_save', $data, true);
        if(isset($result['retCode']) && $result['retCode'] == Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::SUCC,$result['retData']);
        }else{
            $this->render_result(isset($result['retCode']) ? $result['retCode'] : Lib_Errors::SVR_ERR);
        }
    }
}