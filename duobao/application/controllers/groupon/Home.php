<?php
/**
 *
 * Class Home
 */

class Home extends Groupon_Base
{
    const BANNER_AD_POSITION = 1;
    const PAGE_SIZE = 1;
    protected $need_login = true;
    protected $need_login_methods = array('index');

    public function index()
    {
        $this->assign('menus_active_index', 1);

        $groupon_list = $this->get_api('groupon_active_list', array('need_spec'=>1, 'need_goods'=>1,'p_index' =>1, 'p_size'=>self::PAGE_SIZE));
        $groupon_list = empty($groupon_list['retData']) ? array() : $groupon_list['retData'];
        $this->assign('groupon_active_list', $groupon_list);

        if(!empty($groupon_list['list'])) {
            $groupon_active = $groupon_list['list'][0];
            $active_is_end = $this->check_is_end($groupon_active['iEndTime']);
            $this->assign('active_is_end', $active_is_end);
        }
        /*
        //banner广告
        $banner_advert = $this->get_api('ad_list', array('position_id'=>self::BANNER_AD_POSITION));
        $this->assign('banner_advert',empty($banner_advert['retData']) ? array() : $banner_advert['retData']);
        */
        $this->render();
    }
}