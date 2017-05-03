<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 商品类目 api
 *
 * Class Goods_category
 */
class Goods_category extends API_Base
{
    /**
     * 返回顶级分类
     */
    public function top_cate()
    {
        $this->load->model('goods_category_model');
        $result_list = $this->goods_category_model->fetch_top(true);
        $this->output_json(Lib_Errors::SUCC, $result_list);
    }
}