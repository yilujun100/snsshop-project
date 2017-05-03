<?php
/**
 * 拼团活动
 * Class Active
 */
class Active extends Groupon_Base
{
    const PAGE_SIZE_DIY = 2; //详情页面开团列表
    const PAGE_SIZE_DIY_MORE = 10; //详情页面开团列表更多
    const PAGE_SIZE_DIY_JOIN = 10; //开团参团列表

    protected $need_login_methods = array('diy_detail');

    public function detail()
    {
        $groupon_id = intval($this->get('gid', 0));
        $spec_id = intval($this->get('spec_id', 0));
        if (!$groupon_id) {
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }

        $this->assign('menus', 'diy_menus');
        $groupon_detail = $this->get_groupon_active_detail($groupon_id, $spec_id);
        if (empty($groupon_detail)) {
            show_error(Lib_Errors::get_error(Lib_Errors::GROUPON_NOT_EXISTS));
        }
        $this->assign('groupon_detail', $groupon_detail);
        $this->assign('is_detail', 1);
        //活动是否结束
        $active_is_end = $this->check_is_end($groupon_detail['iEndTime']);
        $this->assign('active_is_end', $active_is_end);

        //开团列表
        $groupon_diy_list = $this->get_api('groupon_diy_list', array('groupon_id'=>$groupon_id, 'p_index'=>1,'p_size'=>self::PAGE_SIZE_DIY));
        $groupon_diy_list = empty($groupon_diy_list['retData']) ? array() : $groupon_diy_list['retData'];
        $this->assign('groupon_diy_list', $groupon_diy_list);

        $this->render();
    }

    /**
     * 开团详情
     */
    public function diy_detail()
    {
        $diy_id = intval($this->get('diy_id', 0));
        $sign = intval($this->get('sign', ''));
        $is_share = intval($this->get('share', 0));
        $is_load = intval($this->get('isload', 0));
        if (!$diy_id) {
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }

        $this->assign('menus', 'diy_menus');

        //开团详情
        $groupon_diy = $this->get_api('groupon_diy_detail', array('diy_id' => $diy_id));
        $groupon_diy = empty($groupon_diy['retData']) ? array() : $groupon_diy['retData'];
        if (empty($groupon_diy)) {
            show_error(Lib_Errors::get_error(Lib_Errors::GROUPON_DIY_NOT_EXISTS));
        }

        //校验参数sign
        /*if (gen_sign($groupon_diy['iUin'], $diy_id) != $sign) {
            show_error(Lib_Errors::get_error(Lib_Errors::EXCEPTION_REQUEST));
        }*/

        $this->assign('groupon_diy', $groupon_diy);

        //是否为本人
        $is_my = $groupon_diy['iUin'] == $this->user['uin'];
        $this->assign('is_my', $is_my);
        $this->assign('is_share', $is_share);
        $this->assign('is_load', $is_load);

        //拼团活动详情
        $groupon_detail = $this->get_groupon_active_detail($groupon_diy['iGrouponId'], $groupon_diy['iSpecId']);
        if (empty($groupon_detail)) {
            show_error(Lib_Errors::get_error(Lib_Errors::GROUPON_NOT_EXISTS));
        }
        $this->assign('groupon_detail', $groupon_detail);

        //活动是否结束
        $active_is_end = $this->check_is_end($groupon_detail['iEndTime']);
        $this->assign('active_is_end', $active_is_end);

        //开团是否结束
        $diy_is_end = $this->check_is_end($groupon_diy['iEndTime']);
        $this->assign('diy_is_end', $diy_is_end);

        //参团列表
        $diy_join_list = $this->get_api('groupon_diy_join_list', array('diy_id'=>$diy_id, 'groupon_id'=>$groupon_diy['iGrouponId'], 'p_index'=>1,'p_size'=>self::PAGE_SIZE_DIY_JOIN));
        $diy_join_list = empty($diy_join_list['retData']) ? array() : $diy_join_list['retData'];
        $this->assign('diy_join_list', $diy_join_list);
        //本人 设置分享内容
        $this->set_groupon_share($groupon_diy, array('active_is_end'=>$active_is_end, 'is_my'=>$is_my, 'shareImg'=>get_img_resize_url($groupon_detail['sImg'], Lib_Constants::SHARE_IMG_SMALL, Lib_Constants::SHARE_IMG_SMALL)));

        $this->render();
    }

    /**
     * 更多开团记录
     */
    public function diy_more()
    {
        $groupon_id = intval($this->get('gid', 0));
        if (!$groupon_id) {
            show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
        }
        $this->assign('groupon_id', $groupon_id);
        $this->assign('is_more', 1);

        //开团列表
        $groupon_diy_list = $this->get_api('groupon_diy_list', array('groupon_id'=>$groupon_id, 'p_index'=>1,'p_size'=>self::PAGE_SIZE_DIY_MORE));
        $groupon_diy_list = empty($groupon_diy_list['retData']) ? array() : $groupon_diy_list['retData'];
        $this->assign('groupon_diy_list', $groupon_diy_list);

        $this->render();
    }

    /**
     * ajax - 更多开团列表分页
     */
    public function ajax_diy_more()
    {
        if ($this->input->is_ajax_request()) {
            $groupon_id = intval($this->get('gid', 0));
            if (!$groupon_id) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
            $p_index = intval($this->get('p_index', 1));

            //开团列表
            $groupon_diy_list = $this->get_api('groupon_diy_list', array('groupon_id'=>$groupon_id, 'p_index'=>$p_index,'p_size'=>self::PAGE_SIZE_DIY_MORE));
            $groupon_diy_list = empty($groupon_diy_list['retData']) ? array() : $groupon_diy_list['retData'];
            $groupon_diy_list['html'] = $this->widget('diy_list', array('diy_list' => $groupon_diy_list), true);
            unset($groupon_diy_list['list']);
            $this->render_result(Lib_Errors::SUCC, $groupon_diy_list);
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * 拼团活动详情
     * @param $groupon_id
     * @param int $spec_id
     * @return array|bool|mixed
     */
    private function get_groupon_active_detail($groupon_id, $spec_id=0)
    {
        //拼团活动详情
        $groupon_detail = $this->get_api('groupon_active_detail', array('groupon_id'=>$groupon_id));
        $groupon_detail = empty($groupon_detail['retData']) ? array() : $groupon_detail['retData'];
        if (empty($groupon_detail)) {
            $this->log->error('Groupon', 'get groupon detail failed | groupon_id['.$groupon_id.'] | '.__METHOD__);
            return array();
        }

        //当前规格
        $groupon_spec = array();
        $spec_list = empty($groupon_detail['spec']) ? array() : $groupon_detail['spec'];
        if ($spec_list) {
            if ($spec_id) {
                foreach ($spec_list as $spec) {
                    if ($spec['iSpecId'] == $spec_id) {
                        $groupon_spec = $spec;
                        break;
                    }
                }
            } else {
                $groupon_spec = $groupon_detail['spec'][0];
            }
        }

        if (empty($groupon_spec)) {
            $this->log->error('Groupon', 'get groupon spec failed | groupon_id['.$groupon_id.'] | spec_id['.$spec_id.'] | '.__METHOD__);
            return array();
        }

        $groupon_detail['groupon_spec'] = $groupon_spec;
        //商品详情
        $goods_detail = $this->get_api('goods_detail', array('goods_id'=>$groupon_detail['iGoodsId']));
        $goods_detail = empty($goods_detail['retData']) ? array() : $goods_detail['retData'];
        $groupon_detail['goods_detail'] = $goods_detail;

        return $groupon_detail;
    }
}