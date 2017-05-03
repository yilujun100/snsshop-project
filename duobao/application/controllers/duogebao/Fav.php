<?php


class Fav extends Duogebao_Base
{
    protected $need_login_methods = array();

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 收藏列表
     */
    public function fav_list()
    {
        $this->render();
    }


    //删除收藏
    public function del_fav()
    {

    }
}