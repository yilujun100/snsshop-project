<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 商品 api
 *
 * Class Goods_item
 */
class Goods_item extends API_Base
{
    /**
     * 商品列表
     */
    public function goods_list()
    {
        extract($this->cdata);
        $where = array(
            'iState' => Lib_Constants::PUBLISH_STATE_ONLINE
        );
        if (! empty($key)) {
            $where['like'] = array(
                'a.sName' => $key
            );
        }
        if (! empty($cate)) {
            $where['iCateId_1'] = $cate;
        }

        $order_by = array(
            'iCreateTime' => 'DESC'
        );

        $page_index = ! empty($p_index) ? intval($p_index) : 1;
        $page_size = ! empty($p_size) ? intval($p_size) : self::$PAGE_SIZE;

        $this->load->model('goods_item_model');

        if($list = $this->goods_item_model->fetch_list($where, $order_by, $page_index, $page_size)) {
            $list['sql'] = $this->goods_item_model->db->last_query();
            $list['where'] = $where;
            $this->output_json(Lib_Errors::SUCC, $list);
        } else {
            $this->log->error('Goods_item','fetch_list failed | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->output_json(Lib_Errors::SVR_ERR);
        }
    }

    /**
     * 商品详情
     */
    public function goods_detail()
    {
        extract($this->cdata);
        if (empty($goods_id)) {
            $this->log->error('Goods_item', 'params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->load->model('goods_item_model');
        if ($row = $this->goods_item_model->get_row($goods_id)) {
            $this->output_json(Lib_Errors::SUCC, $row);
        } else {
            $this->log->error('Goods_item','get_row failed | params['.json_encode($this->cdata).'] | '.__METHOD__);
            $this->output_json(Lib_Errors::SVR_ERR);
        }
    }
}