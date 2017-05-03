<?php


class Home extends Duogebao_Base
{
    const BANNER_AD_POSITION = 1;
    const PAGE_SIZE = 6;
    protected $need_login = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->assign('menus_active_index', 1);

        $data = array();
        //已揭晓或即将揭晓
        $history_list = $this->get_api('history_peroid',array('p_size'=>5));
        $history_list = isset($history_list['retData']) ? $history_list['retData'] : array();
        if (!isset($history_list['opened']) || !isset($history_list['soon'])) {
            foreach($history_list as $list){
                if($list['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_OPENED){
                    $data['opened'][] = $list;
                } else {
                    $data['soon'][] = $list;
                }
            }
        }
        $this->assign('show_tab',!empty($data['soon']) ? 2 : 1);
        $this->assign('history',$data);

        //最后疯抢
        $active_crazy = $this->get_api('active_crazy');
        $this->assign('active_crazy',empty($active_crazy['retData']['list']) ? array() : $active_crazy['retData']['list']);


        //商品分类
        $goods_cate = $this->get_api('goods_cate');
        $this->assign('goods_cate',empty($goods_cate['retData']) ? array() : $goods_cate['retData']);

        //消息
        $active_msg = $this->get_api('active_msg');
        $this->assign('active_msg',empty($active_msg['retData']['list']) ? array() : $active_msg['retData']['list']);

        //一元区
        //$list_one = $this->get_api('active_zone',array('cls'=>'1000','p_size'=>self::PAGE_SIZE));
        //$this->assign('active_list',empty($list_one['retData']['list']) ? array() : $list_one['retData']['list']);


        //人气区
        $where['orderby'] = 'review';
        $where['ordertype'] = 'asc';
        $where['p_index'] = 1;
        $where['p_size'] = 10;
        $list = $this->get_api('active_search',$where);
        $list = $list['retCode'] == 0 ? $list['retData'] : array();
        $this->assign('active_list',$list['list']);

        //banner广告
        $banner_advert = $this->get_api('ad_list', array('position_id'=>self::BANNER_AD_POSITION));
        $this->assign('banner_advert',empty($banner_advert['retData']) ? array() : $banner_advert['retData']);

        //中奖提示
        $uin = $this->get_uin();
        if(!empty($uin)){
           $deliver = $this->get_api('empty_deliver',array('uin'=>$uin));
        }

        $this->assign('empty_deliver',!isset($deliver['retData']) || empty($deliver['retData']) ? array() : $deliver['retData']);

				/*
        //分享有礼领券提示
        if(!empty($uin)){
            $share_invite = $this->get_api('get_share_invite_succ', array('to_uin'=>$uin));
        }
        if (!empty($share_invite['retData'])) {
            $share_invite_succ = $share_invite['retData'];
            if ($share_invite_succ['iToStatus'] == Lib_Constants::STATUS_0) {//领券
                $this->get_wx_user();
                $this->get_api('get_share_invite_awards', array('to_uin'=>$uin,'act_id'=>Lib_Constants::ACTIVITY_ID, 'sign'=>gen_sign($share_invite_succ['iUin'], Lib_Constants::ACTIVITY_ID)));
            }
        }
				*/

        $this->render();
    }


    public function ajax_list()
    {
        $p_index = $this->get_post('p_index',1);
        $cls = $this->get_post('cls',100);

        $list = $this->get_api('active_zone',array('cls'=>$cls,'p_size'=>self::PAGE_SIZE,'p_index'=>$p_index));
        $list = $list['retCode'] == 0 ? $list['retData'] : array();
        foreach($list['list'] as &$li){
            $li['url'] = gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])));
            $li['buy_url'] = gen_uri('/active/active_buy',array('peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])));
            $li['peroid_str'] = period_code_encode($li['iActId'],$li['iPeroid']);
        }
        $this->render_result(Lib_Errors::SUCC,$list);
    }

    public function ajax_soon()
    {
        if (!$this->input->is_ajax_request()) {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }

        $data = array();
        $history_list = $this->get_api('history_peroid',array('p_size'=>5));
        $history_list = isset($history_list['retData']) ? $history_list['retData'] : array();
        if (!isset($history_list['opened']) || !isset($history_list['soon'])) {
            foreach($history_list as $list){
                $tmp = array(
                    'peroid_code' => $list['iPeroidCode'],
                    'img' => get_img_resize_url($list['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST),
                    'goods_name' => $list['sGoodsName'],
                    'lot_time' => date('Y/m/d H:i:s',$list['iLotTime']),
                    'min' => date('i',$list['iLotTime']),
                    'sec' => date('s',$list['iLotTime']),
                );
                if($list['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_OPENED){

                    $data['opened'][] = $tmp;
                } else {
                    $data['soon'][] = $tmp;
                }
            }
        }
        $this->render_result(Lib_Errors::SUCC, $data);
    }

    private function get_uin()
    {
        $this->load->service('user_service');
        //校验登陆
        if($uin = $this->user_service->valid_user_login()) {
            return $uin;
        }

        return false;
    }
}