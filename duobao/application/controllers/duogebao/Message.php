<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 夺个包信息中心
 *
 * Class Message
 */
class Message extends Duogebao_Base
{
    /**
     * 是否需要验证登陆
     *
     * @var array
     */
    protected $need_login_methods = array('index', 'fetch_list', 'read', 'clean');

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->assign('menus_active_index', -1);
        parent::__construct();
    }

    /**
     * 信息中心
     */
    public function index()
    {
        $api_res = $this->uin_api('msg_list');

        if (Lib_Errors::SUCC == $api_res['retCode']) {
            $res_list = $api_res['retData'];
        } else {
            $res_list = array(
                'count' => 0,
                'list' => array(),
                'page_count' => 0,
                'page_size' => 0,
                'page_index' => 1
            );
        }
        $this->render(array('res_list'=>$res_list));
    }

    /**
     * 加载列表
     */
    public function fetch_list()
    {
        $page = $this->get_post('p_index', 1);
        $params = array(
            'p_index' => $page,
        );
        $api_res = $this->uin_api('msg_list', $params);
        if (Lib_Errors::SUCC == $api_res['retCode']) {
            $res_list = $api_res['retData'];
        } else {
            $res_list = array(
                'count' => 0,
                'list' => array(),
                'page_count' => 0,
                'page_size' => 0,
                'page_index' => 1
            );
        }
        $this->output_json(Lib_Errors::SUCC, $res_list);
    }

    /**
     * 标记消息为 已读
     */
    public function read()
    {
        $msg_id = (int)$this->post('msg_id', 0);
        if ($msg_id < 1) {
            $this->output_json(Lib_Errors::MESSAGE_ID_ERROR);
        }
        $params = array(
            'msg_id' => $msg_id
        );
        $api_res = $this->uin_api('msg_read', $params);
        if (isset($api_res['retCode']) && is_success($api_res['retCode'])) {
            $this->output_json(Lib_Errors::SUCC);
        } else {
            $this->output_json(Lib_Errors::MESSAGE_READ_FAILED);
        }
    }


    /**
     * 全部清除
     */
    public function clean()
    {
        $api_res = $this->uin_api('msg_clean');
        if (isset($api_res['retCode']) && is_success($api_res['retCode'])) {
            $this->output_json(Lib_Errors::SUCC);
        } else {
            $this->output_json(Lib_Errors::MESSAGE_READ_FAILED);
        }
    }
}