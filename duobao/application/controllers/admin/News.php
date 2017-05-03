<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *       促销信息
 *
 *       class News
 */
class News extends Admin_Base {

    protected $relation_model = 'news_model';

    public function __construct() {
        parent::__construct();
        $this->load->model('news_model');
    }

    public function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
    /**
     * 公告列表
     */
    public function index(){

        //$data['js'] = array('jquery.validate','jquery_validate_extend');
        //$start = $this->getMillisecond() ;
        $data['news_list'] = $newslist = $this->news_model->get_news('*', array('iType'=>1), array(), $this->get('page', 1));
        /*
        //memcached 缓存测试代码
        echo ($this->getMillisecond()-$start).'---<br />';
        //$start = $this->getMillisecond() ;
        $data['news_list'] = $newslist = $this->news_model->row_list('*', array('iType'=>1,'iIsDelete'=>0), array(), $this->get('page', 1));
        //echo ($this->getMillisecond()-$start).'---<br />';
        //exit;
        $cache_keynamelist = 't_news_keynamelist';
        $this->load->driver('cache');
        $this->cache->memcached->is_supported();
        $keynamelist_mem = $this->cache->memcached->get($cache_keynamelist);
        $data['keynamelist_mem'] = json_decode($keynamelist_mem) ;
        */
        $this->render($data);
    }

    /**
     * 添加
     */
    public function add() {

        $this->add_edit_asset();

        if (! $this->input->is_ajax_request()) {

            $this->render(array('top_cate'=>''), 'news/edit');

        } else {

            $this->load->library('form_validation');
            $this->set_form_validation();

            if (FALSE === $this->form_validation->run()) {
                $errors = explode('<x>', validation_errors('<x>', '<x>'));
                $this->render_result(Lib_Errors::GOODS_MODIFY_FAILED, array(), empty($errors[1]) ? '' : $errors[1]);
            }

            $field = array('sTitle','news_img_primary', 'sContent');
            $input = $this->post($field);
            $data = array();
            $data['sTitle'] = $input['sTitle'];
            $data['sContent'] = $input['sContent'];
            $data['sImg'] = $input['news_img_primary'];
            $data['iState'] = 0 ;
            $data['iCreateTime'] = time();

            if ($this->news_model->add_row($data)) {
                $this->render_result(Lib_Errors::SUCC);
            } else {
                $this->render_result(Lib_Errors::GOODS_MODIFY_FAILED);
            }
        }

    }

    /**
     * 奖励类型-修改
     */
    public function edit($news_id)
    {
        $news_id = intval($news_id);

        $this->add_edit_asset();

        if (! $this->input->is_ajax_request()) {

            if ($news_id < 1 || ! ($row =  $this->news_model->get_row($news_id))) {
                show_404();
            }

            $this->render(array('news' => $row));
        } else {

            $this->load->library('form_validation');
            $this->set_form_validation();

            if (FALSE === $this->form_validation->run()) {
                $errors = explode('<x>', validation_errors('<x>', '<x>'));
                $this->render_result(Lib_Errors::GOODS_MODIFY_FAILED, array(), empty($errors[1]) ? '' : $errors[1]);
            }

            $field = array('sTitle','news_img_primary', 'sContent');
            $input = $this->post($field);
            $data = array();
            $data['sTitle'] = $input['sTitle'];
            $data['sContent'] = $input['sContent'];
            $data['sImg'] = $input['news_img_primary'];
            $data['iCreateTime'] = time();

            if ($this->news_model->update_row($data, $news_id)) {
                $this->render_result(Lib_Errors::SUCC);
            } else {
                $this->render_result(Lib_Errors::GOODS_MODIFY_FAILED);
            }
        }
    }

    /**
     * 添加编辑前端资源
     */
    private function add_edit_asset()
    {
        // 表单验证
        $this->add_js('jquery.validate');

        // 商品图片上传
        $this->add_third('jQuery-File-Upload/css/jquery.fileupload.css');
        $this->add_third('jQuery-File-Upload/js/vendor/jquery.ui.widget.js');
        $this->add_third('jQuery-File-Upload/js/jquery.iframe-transport.js');
        $this->add_third('jQuery-File-Upload/js/jquery.fileupload.js');

        // 商品富文本编辑框
        $this->add_third('ueditor/themes/default/css/ueditor.css');
        $this->add_third('ueditor/ueditor.config.admin.min.js');
        $this->add_third('ueditor/ueditor.all.min.js');
        $this->add_third('ueditor/lang/zh-cn/zh-cn.js');
    }

    /**
     * 设置登录表单验证规则
     */
    private function set_form_validation()
    {
        $config = array(
             array(
                'field' => 'sTitle',
                'label' => '标题',
                'rules' => array(
                    'required',
                    'max_length[60]'
                )
            ),
            array(
                'field' => 'news_img_primary',
                'label' => '主图',
                'rules' => array(
                    'required'
                )
            ),
            array(
                'field' => 'sContent',
                'label' => '内容',
                'rules' => array(
                    'required'
                )
            )
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_message('is_natural_no_zero', '{field} 错误');
        $this->form_validation->set_message('greater_than', '{field} 不能为空');
    }

    /**
     * 检查类目商品类目
     *
     * @param $cate_id
     *
     * @return bool
     */
    public function check_cate($cate_id)
    {
        if (! $cate_id) {
            return true;
        }
        $this->form_validation->set_message('check_cate', '商品类目不存在');
        return true;
    }

    /**
     * 获取商品信息
     *
     * @param null $goods_id
     */
    public function get_goods_info($goods_id = null)
    {
        $goods_id = (int)$goods_id;
        if ($goods_id < 0) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $item = $this->goods_item_model->get_row($goods_id);

        if (empty($item)) {
            $this->render_result(Lib_Errors::GOODS_ID_NOT_EXIST);
        }

        if (Lib_Constants::PUBLISH_STATE_ONLINE != $item['iState']) {
            $this->render_result(Lib_Errors::GOODS_NOT_ONLINE);
        }

        $outData = array(
            'id' => $goods_id,
            'name' => $item['sName'],
            'cost' => $item['iCostPrice'],
            'lowest' => $item['iLowestPrice'],
        );
        $this->render_result(Lib_Errors::SUCC, $outData);
    }

    /**
     * Index::upload()
     * 异步上传图片
     *
     * @return void
     */
    public function img_upload() {
        $FILES = array_keys($_FILES);
        $fileInput = $FILES[0];
        $path = $this->input->get_post('path', true) ? $this->input->get_post('path', true) : 'other';
        $return = upload_files($fileInput, $path);
        exit(json_encode($return));
    }

}