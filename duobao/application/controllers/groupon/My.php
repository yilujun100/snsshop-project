<?php
/**
 *
 * Class My
 */

class My extends Groupon_Base
{
    const BANNER_AD_POSITION = 1;
    const PAGE_SIZE_GROUPON = 5;

    protected $need_login_methods = array('groupons', 'orders', 'ajax_my_groupons');

    /**
     * 我的团
     */
    public function groupons()
    {
        $this->assign('menus_active_index', 2);

        //全部
        $all_groupons = $this->get_api('groupon_my_groupon', array('uin'=>$this->user['uin'], 'p_index'=>1, 'p_size'=>self::PAGE_SIZE_GROUPON));
        $all_groupons = empty($all_groupons['retData']) ? array() : $all_groupons['retData'];
        $this->assign('my_join_list', $all_groupons);
        $this->render();
    }


    /**
     * ajax - 拼团活动-我的团
     */
    public function ajax_my_groupons()
    {
        if (!$this->input->is_ajax_request()) {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }

        $diy_type = intval($this->get('diy_type', -1));
        if (!array_key_exists($diy_type, Lib_Constants::$groupon_diy_states) && $diy_type && $diy_type != -1) {//只能为指定类型
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }
        $p_index = intval($this->get('p_index', 1));

        //全部
        $groupons = $this->get_api('groupon_my_groupon', array('uin'=>$this->user['uin'], 'p_index'=>$p_index, 'p_size'=>self::PAGE_SIZE_GROUPON, 'diy_type'=>$diy_type));
        $groupons = empty($groupons['retData']) ? array() : $groupons['retData'];

        $groupons['html'] = $this->widget('my_join', array('my_join_list'=>$groupons),true);
        unset($groupons['list']);
        $this->render_result(Lib_Errors::SUCC, $groupons);
    }

    /**
     * 我的订单
     */
    public function orders()
    {
        $this->assign('menus_active_index', 3);
        $this->render();
    }
}