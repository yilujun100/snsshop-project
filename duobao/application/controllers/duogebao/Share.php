<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 晒单
 * Class Share
 */
class Share extends Duogebao_Base
{
    protected $need_login_methods = array('detail','operate', 'index','add','ajax_share_list','active_list');

    /**
     * 系统晒单列表
     */
    public function index()
    {
        $this->assign('menus_active_index', 2);
        $page_size = 10;
        $page_index = 1;
        $api_ret = $this->get_api('share_list', array('to_uin'=>$this->user['uin'], 'p_index'=>$page_index, 'p_size'=>$page_size));
        $share_list = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        $this->assign('share_list', $share_list);
        $this->render();
//        if ($share_list) {
//
//        }  else {
//            //无记录页面
//            $this->render(array(), 'share/no_result');
//        }
    }

    /**
     * ajax - 晒单列表
     */
    public function ajax_share_list()
    {
        $page_size = 10;
        $page_index = $this->get('p_index', 1);
        $api_ret = $this->get_api('share_list', array('to_uin'=>$this->user['uin'], 'p_index'=>$page_index, 'p_size'=>$page_size));
        $share_list = empty($api_ret['retData']['list']) ? array() : $api_ret['retData'];

        if (!empty($share_list['list'])) {
            $share_list['list'] = $this->format_ajax_share($share_list['list']);
        }
        $this->render_result(Lib_Errors::SUCC, $share_list);
    }

    /**
     * 晒单详情页面
     */
    public function detail()
    {

        $this->assign('menus_show', false);
        $share_id = $this->get_post('id', 0);
        if (!$share_id && !is_numeric($share_id)) {
            show_error('参数错误!');
        }

        $api_ret = $this->get_api('share_detail', array('share_id'=>$share_id));
        if ($api_ret['retCode'] != Lib_Errors::SUCC) {
            show_error('系统异常!');
        }
        if (empty($api_ret['retData'])) {
            show_error('记录不存在!');
        }

        $share_detail = $api_ret['retData'];
        $is_my = $share_detail['uin'] == $this->user['uin'] ? true : false;

        //是否点赞
        $api_ret = $this->get_api('share_liked', array('share_id'=>$share_id, 'uin'=>$this->user['uin']));
        if($api_ret['retCode'] == Lib_Errors::SUCC && $api_ret['retData'] > 0)
        {
            $is_liked = true;
        }
        else
        {
            $is_liked = false;
        }
//        $is_liked = $api_ret['retCode'] == Lib_Errors::SUCC ? true : false;

        $this->assign('share_detail', $share_detail);
        $this->assign('is_my', $is_my);
        $this->assign('is_liked', $is_liked);
        $this->render();
    }

    /**
     * ajax - 操作【查看 | 点赞】
     */
    public function operate()
    {
        if ($this->input->is_ajax_request()) {
            $type = $this->post('type', 0);
            $share_id = $this->post('share_id', 0);
            if (!$type ||  !in_array($type, Lib_Constants::$share_opts) || !$share_id) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $api_ret = $this->get_api('share_operate', array('share_id'=>$share_id, 'type'=>$type, 'uin'=>$this->user['uin']));
            $api_ret['retCode'] == Lib_Errors::SUCC ? $this->render_result(Lib_Errors::SUCC, $api_ret['retData']) : $this->render_result($api_ret['retCode']);
        }
        $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
    }

    /**
     * 活动详情-晒单列表
     */
    public function active_list()
    {
        $act_id = $this->get('act_id', 0);
        if (!$act_id) {
            show_error('参数错误');
        }

        $this->assign('menus_active_index', 2);
        $page_size = 10;
        $page_index = $this->get('page', 1);

        $api_ret = $this->get_api('share_list', array('to_uin'=>$this->user['uin'],'act_id'=>$act_id, 'p_index'=>$page_index, 'p_size'=>$page_size));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $share_list = (empty($api_ret['retData']['list']) || !is_array($api_ret['retData']['list'])) ? array() : $api_ret['retData'];
        } else {
            $share_list = array();
        }

        $this->assign('share_list', $share_list);
        $this->render(array(), 'share/index');
//        if ($share_list) {
//
//
//        } else {
//            //无记录页面
//            $this->render(array(), 'share/no_result');
//        }
    }

    /**
     * 晒单-添加/编辑
     */
    public function add()
    {
        $period_code = $this->get_post('period_code','');
        $share_id = $this->get_post('id',0);
        if (!$period_code_arr = period_code_decode($period_code)) {
            show_error('参数错误!1');
        }
        list($act_id, $period) = $period_code_arr;

        if ($share_id) {
            $api_ret = $this->get_api('share_detail', array('share_id'=>$share_id, 'uin'=>$this->user['uin']));
            $share_detail = empty($api_ret['retData']) ? array() : $api_ret['retData'];
            if(!$share_detail) {
                $share_detail = array();
            }
        } else {
            $share_detail = array();
        }

        if($this->input->is_ajax_request()) {
            if ($share_id && empty($share_detail)) {
                $this->render_result(Lib_Errors::PARAMETER_ERR,array(),'晒单记录不存在');
            }

            $con = $this->post('con', '');
            $imgs = $this->post('imgs', '');
            $img_arr = explode(',',$imgs);
            if(empty($img_arr) || !$con) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $share_imgs = array();
            foreach ($img_arr as $img) {
                $share_imgs[] = $img;
            }

            if(count($share_imgs) > 6)
            {
                $this->render_result(Lib_Errors::PARAMETER_ERR,array(),'图片最多上传6张');
            }

            $data = array(
                'uin' => $this->user['uin'],
                'con' => $con,
                'act_id' => $act_id,
                'period' => $period,
                'imgs' => $share_imgs,
                'share_id' => $share_id,
            );
            $api_ret = $this->get_api('share_add', $data);
            if ($api_ret['retCode'] == Lib_Errors::SUCC) {
                $this->render_result(Lib_Errors::SUCC, $api_ret['retData']);
            } else {
                $this->render_result($api_ret['retCode']);
            }
        }

        if ($share_id && empty($share_detail)) {
            show_error('参数错误!!');
        }
        $this->assign('share_detail', $share_detail);

        //取指定期数夺宝信息
        $api_ret = $this->get_api('active_detail', array('act_id'=>$act_id, 'peroid'=>$period));
        if (empty($api_ret['retData'])) {
            show_error('参数错误!!!');
        }
        $active_detail = $api_ret['retData'];

        // 商品图片上传
        $this->add_third('jQuery-File-Upload/css/jquery.fileupload.css');
        $this->add_third('jQuery-File-Upload/js/vendor/jquery.ui.widget.js');
        $this->add_third('jQuery-File-Upload/js/jquery.iframe-transport.js');
        $this->add_third('jQuery-File-Upload/js/jquery.fileupload.js');

        $this->assign('period_code', $period_code);
        $this->assign('share_id', $share_id);
        //校验夺宝期数
        $this->assign('active_detail', $active_detail);

        $this->render();
    }

    /**
     * 格式化分享晒单列表
     * @param $list
     * @return array
     */
    private function format_ajax_share($list)
    {
        if (empty($list)) {
            return $list;
        }

        $ret  = array();
        foreach($list as $item) {
            $period_code = period_code_encode($item['act_id'], $item['period']);
            $ret[] = array(
                'share_id' => $item['share_id'],
                'uin' => $item['uin'],
                'content' => $item['content'],
                'nickname' => $item['nickname'],
                'is_liked' => isset($item['is_liked']) ? $item['is_liked'] : 0,
                'headimg' => $item['headimg'],
                'act_id' => $item['act_id'],
                'period' => $item['period'],
                'area' => $item['area'],
                'like_count' => $item['like_count'],
                'period_code' => $period_code,
                'view_count' => $item['view_count'],
                'goods_name' => $item['goods_name'],
                'goods_img' => $item['goods_img'],
                'lucky_code' => $item['lucky_code'],
                'lot_time' => $item['lot_time'] ? date('Y-m-d H:i:s', $item['lot_time']) : '',
                'share_time' => $item['share_time'] ? date('Y-m-d H:i:s', $item['share_time']) : '',
                'lot_count' => $item['lot_count'],
                'imgs' => array_map(array($this, 'get_img_url'), $item['imgs']),
                'share_detail_url' => gen_uri('/share/detail', array('id'=>$item['share_id'])),
                'active_url' => gen_uri('/active/detail', array('id'=>$period_code)),
                'ip' => $item['ip']
            );
        }
        return $ret;
    }

    private function get_img_url($img) {
        if ($img) {
            return get_img_resize_url($img, Lib_Constants::SHARE_IMG_SMALL, Lib_Constants::SHARE_IMG_SMALL);
        } else {
            return $img;
        }

    }
}