<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 私人定制
 *
 * Class Active_custom
 */
class Active_custom extends Duogebao_Base
{
    /**
     * 自定义活动默认参与人数
     */
    const DEFAULT_CUSTOM_PEOPLE = 1;

    /**
     * 活动名称允许的最大长度
     */
    const ACTIVE_NAME_LENGTH_MAX = 25;

    /**
     * 是否需要验证登陆
     *
     * @var array
     */
    protected $need_login_methods = array('index', 'ajax_my_list', 'choose', 'setting', 'generate');

    /**
     * Active_custom constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->assign('menus_active_index', -1);
    }

    /**
     * 首页
     */
    public function index()
    {
        $api_res = $this->uin_api('act_custom_list');
        foreach ($api_res['retData']['list'] as & $v) {
            $v['periodNum'] = period_code_encode($v['iActId'], $v['iPeroid']);
            $v['detailUrl'] = gen_uri('/active/detail', array('id'=>$v['periodNum']));
            $v['shareUrl'] = gen_uri('/active/detail', array('id'=>$v['periodNum'], 'share'=>1));
            $v['winDetailUrl'] = gen_uri('/my/active_win_order',array('order_id'=>$v['sWinnerOrder'],'peroid_str'=>period_code_encode($v['iActId'],$v['iPeroid'])));
            $v['sLotDateTime'] = empty($v['iLotTime'])? '' : date(TIME_FORMATTER, $v['iLotTime']);
            $v['sEndTime'] = empty($v['iLotTime'])? '' : date('Y/m/d H:i:s', $v['iLotTime']);
            $v['sCurTime'] = date('Y/m/d H:i:s');
            $v['uin'] = $this->user['uin'];
        }
        $view_data = array(
            'result_data' => $api_res['retData']
        );
        $this->render($view_data);
    }

    /**
     * 私人定制列表
     */
    public function ajax_my_list()
    {
        $page = $this->get_post('p_index', 1);
        $params = array(
            'p_index' => $page
        );
        if ($this->get_post('win')) {
            $params['win'] = 1;
        }
        $api_res = $this->uin_api('act_custom_list', $params);
        foreach ($api_res['retData']['list'] as & $v) {
            $v['periodNum'] = period_code_encode($v['iActId'], $v['iPeroid']);
            $v['detailUrl'] = gen_uri('/active/detail', array('id'=>$v['periodNum']));
            $v['shareUrl'] = gen_uri('/active/detail', array('id'=>$v['periodNum'], 'share'=>1));
            $v['addressUrl'] = gen_uri('/address/index', array('id'=>$v['periodNum']));
            $v['winDetailUrl'] = gen_uri('/my/active_win_order',array('order_id'=>$v['sWinnerOrder'],'peroid_str'=>period_code_encode($v['iActId'],$v['iPeroid'])));
            $v['sLotDateTime'] = empty($v['iLotTime'])? '' : date(TIME_FORMATTER, $v['iLotTime']);
            $v['sEndTime'] = empty($v['iLotTime'])? '' : date('Y/m/d H:i:s', $v['iLotTime']);
            $v['sCurTime'] = date('Y/m/d H:i:s');
            $v['uin'] = $this->user['uin'];
        }
        $this->render_result(Lib_Errors::SUCC, is_success($api_res['retCode']) ? $api_res['retData'] : array());
    }


    /**
     * 私人定制夺宝列表
     */
    public function custom_list()
    {
        $page = $this->get_post('p_index', 1);
        $page_size = $this->get_post('p_size',20);
        $order_by = $this->get_post('order_by','hot');
        $list = $this->get_api('custom_list',array('p_index'=>$page,'p_size'=>$page_size,'order_by'=>$order_by));

        foreach($list['retData']['list'] as &$val){
            $val['detailUrl'] = gen_uri('/active/detail', array('id'=>$val['iPeroidCode']));
            $val['shareUrl'] = gen_uri('/active/detail', array('id'=>$val['iPeroidCode'], 'share'=>1));
        }
        if ($this->input->is_ajax_request()) {
            $this->render_result(Lib_Errors::SUCC,$list['retData']);
        }

        $list = empty($list) || $list['retCode'] != Lib_Errors::SUCC ? array() : $list['retData'];
        $this->assign('list',$list);
        $this->render();
    }

    /**
     * 选择商品
     */
    public function choose()
    {
        $this->assign('menus_show', false);

        $api_res = $this->get_api('goods_category');
        $view_data = array(
            'category_list' => $api_res['retData']
        );

        $api_res = $this->get_api('goods_list');

        $view_data['result_data'] = $api_res['retData'];

        $this->render($view_data);
    }

    /**
     * 商品列表
     */
    public function choose_list()
    {
        $page = $this->get_post('p_index', 1);
        $params = array(
            'p_index' => $page
        );
        if ($key = $this->get_post('k')) {
            $params['key'] = $key;
        }
        if ($cate = $this->get_post('cate')) {
            $params['cate'] = $cate;
        }
        $api_res = $this->get_api('goods_list', $params);
        $this->render_result(Lib_Errors::SUCC, is_success($api_res['retCode']) ? $api_res['retData'] : array());
    }

    /**
     * 设置夺宝
     *
     * @param $goods_id
     */
    public function setting($goods_id)
    {
        $api_res = $this->get_api('goods_item', array('goods_id'=>$goods_id));
        if (! isset($api_res['retCode']) || ! is_success($api_res['retCode'])) {
            show404('商品ID不存在');
        }
        $this->assign('menus_show', false);

        $view_data = array(
            'item' => $api_res['retData'],
            'default_people' => self::DEFAULT_CUSTOM_PEOPLE,
            'active_length_max' => self::ACTIVE_NAME_LENGTH_MAX,
        );

        $this->render($view_data);
    }

    /**
     * 生成夺宝单
     */
    public function generate()
    {
        $goods_id = (int)$this->post('goods_id');
        $active_name = trim($this->post('active_name'));
        $lot_count = (int)($this->post('code_num'));
        $is_open = (int)($this->post('is_open'));

        if (! $lot_count) {
            $this->output_json(Lib_Errors::CUSTOM_PEOPLE_ERROR);
        }

        if (empty($active_name) || utf8_strlen($active_name) > self::ACTIVE_NAME_LENGTH_MAX) {
            $this->output_json(Lib_Errors::CUSTOM_ACTIVE_NAME_ERROR);
        }

        if (! $goods_id) {
            $this->output_json(Lib_Errors::CUSTOM_GOODS_NOT);
        }
        $api_res = $this->get_api('goods_item', array('goods_id'=>$goods_id));
        if (! isset($api_res['retCode']) || ! is_success($api_res['retCode'])) {
            $this->output_json(Lib_Errors::CUSTOM_GOODS_NOT);
        }
        $goods = $api_res['retData'];

        if ($lot_count > ceil($goods['iLowestPrice'] / 100)) {
            $this->output_json(Lib_Errors::CUSTOM_PEOPLE_ERROR);
        }

        $params = array(
            'goods_id' => $goods_id,
            'lot_count' => $lot_count,
            'active_name' => $active_name,
            'is_open' => $is_open
        );

        $api_res = $this->uin_api('custom_generate', $params);
        if (! isset($api_res['retCode'])) {
            $this->output_json(Lib_Errors::CUSTOM_ADD_FAILED, $api_res);
        } else if (! is_success($api_res['retCode'])) {
            $this->output_json($api_res['retCode']);
        }

        $this->output_json(Lib_Errors::SUCC, array('id'=> period_code_encode($api_res['retData']['actId'], 1)));
    }
}