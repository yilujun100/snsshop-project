<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Category extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }


    /**
     * 返回所有分类
     */
    public function get_category()
    {
        $this->load->model('goods_category_model');
        $list = $this->goods_category_model->query("SELECT * FROM `t_goods_category` WHERE `iIsShow` = 1"." AND iLvl= 1");

        $this->render_result(Lib_Errors::SUCC,$list);
    }
}