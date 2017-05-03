<?php


class Collect extends Duogebao_Base
{
    protected $need_login_methods = array('add','del','lists');

    public function __construct()
    {
        parent::__construct();
    }


    public function add()
    {
        $act_id = $this->get_post('act_id');

        if(empty($act_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $result = $this->get_api('add_collect',array('uin'=>$this->user['uin'],'act_id'=>$act_id));
        if($result['retCode'] == Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result($result['retCode'],$result['retData']);
        }
    }

    public function del()
    {
        $act_id = $this->get_post('act_id');

        if(empty($act_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $result = $this->get_api('del_collect',array('uin'=>$this->user['uin'],'act_id'=>$act_id));
        if($result['retCode'] == Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result($result['retCode'],$result['retData']);
        }
    }
}