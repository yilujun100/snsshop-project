<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团
 * Class User
 */
class Groupon extends API_Base {
    /**
     * 拼团活动 - 活动列表
     */
    public function active_list()
    {
        extract($this->cdata);

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        $params = array(
            'iState' => Lib_Constants::PUBLISH_STATE_ONLINE
        );

        $this->load->model('groupon_active_model');
        $data =  $this->groupon_active_model->row_list('*', $params, array(), $p_index, $p_size);
        if (!empty($data['list'])) {
            if (!empty($need_spec) || !empty($need_goods)) {
                $this->load->model('groupon_spec_model');
                $this->load->model('goods_item_model');
                foreach ($data['list'] as $key => $row) {
                    if (!empty($need_spec)) {
                        $spec = $this->groupon_spec_model->get_groupon_spec($row['iGrouponId']);
                        $data['list'][$key]['spec'] = $spec ? $spec : array();
                    }
                    if (!empty($need_goods)) {
                        $goods = $this->goods_item_model->get_row($row['iGoodsId']);
                        $data['list'][$key]['goods_detail'] = $goods ? $goods : array();
                    }
                }
            }
        }
        $this->log->error('Groupon', 'diy_list| data:'.json_encode($data).' | params:'.json_encode($this->cdata).' | '.__METHOD__);
        $this->render_result(Lib_Errors::SUCC, $data);
    }

    /**
     * 拼团活动 - 活动详情
     */
    public function active_detail()
    {
        extract($this->cdata);

        if (empty($groupon_id)) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('groupon_active_model');
        $active_detail = $this->groupon_active_model->get_row($groupon_id);
        if (!empty($active_detail)) {
            $this->load->model('groupon_spec_model');
            $spec = $this->groupon_spec_model->get_groupon_spec($active_detail['iGrouponId']);
            $active_detail['spec'] = $spec ? $spec : array();
        }
        $this->render_result(Lib_Errors::SUCC, $active_detail);
    }

    /**
     * 拼团活动 - 开团列表
     */
    public function diy_list()
    {
        extract($this->cdata);

        if (empty($groupon_id)) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        $this->load->model('groupon_diy_model');
        $data =  $this->groupon_diy_model->row_list('*', array('iGrouponId'=>$groupon_id, 'iState'=>Lib_Constants::GROUPON_DIY_ING), array('iBuyNum' => 'desc','iEndTime'=>'asc'), $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $data);
    }

    /**
     * 拼团活动 - 开团详情
     */
    public function diy_detail()
    {
        extract($this->cdata);

        if (empty($diy_id)) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('groupon_diy_model');
        $groupon_diy = $this->groupon_diy_model->get_row($diy_id);
        $this->render_result(Lib_Errors::SUCC, $groupon_diy);
    }

    /**
     * 拼团活动 - 指定开团的参团记录
     */
    public function diy_join_list()
    {
        extract($this->cdata);

        if (empty($diy_id) || empty($groupon_id)) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $diy_id = intval($diy_id);
        $this->load->model('groupon_diy_model');
        $groupon_diy = $this->groupon_diy_model->get_row($diy_id);
        if (empty($groupon_diy)) {
            $this->log->error('Groupon', 'get groupon diy failed | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::GROUPON_DIY_NOT_EXISTS);
        }

        if ($groupon_diy['iGrouponId'] != $groupon_id) {
            $this->log->error('Groupon', 'parameter error | params:'.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        $this->load->model('Groupon_join_groupon_model');
        $diy_join_list = $this->Groupon_join_groupon_model->row_list('*', array('iDiyId'=>$diy_id, 'iGrouponId' => $groupon_id), array('iCreateTime'=> 'asc'), $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $diy_join_list);
    }

    /**
     * 拼团活动 - 个人中心我的团
     */
    public function my_groupons()
    {
        extract($this->cdata);

        if (empty($uin)) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;
        $diy_type = isset($diy_type) ? intval($diy_type) : -1;
        if (!array_key_exists($diy_type, Lib_Constants::$groupon_diy_states)) {
            $diy_type = -1;
        }

        $this->load->model('groupon_join_user_model');
        $list = $this->groupon_join_user_model->get_groupon_list($uin, $diy_type, $p_index, $p_size);
        $this->render_result(Lib_Errors::SUCC, $list);

    }
}