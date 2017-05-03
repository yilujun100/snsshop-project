<?php

class Index extends API_Base
{

    public function __construct()
    {
        parent::__construct();
    }


    public function test(){
        //$my = new MY_Controller();
        $cdata = encrypt(array('uin'=>232323232312,'id'=>2),$this->skey);pr($cdata);
        $cdata = decrypt($cdata,$this->skey);

        pr($cdata);

        $this->render_result('0',array('uin'=>29292));

        //redirect('http://baidu.com');
    }
}