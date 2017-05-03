<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户奖励
 * Class Awards
 */
class Awards extends Admin_Base {
    public function __construct() {
        parent::__construct();
        $this->load->model('awards_type_model');
    }

    /**
     * 奖励类型
     */
    public function type(){
        $data['list'] = $this->awards_type_model->row_list();
        $this->render($data);
    }

    /**
     * 奖励管理
     */
    public function index() {
        //上线的奖励类型列表
        $data['awards_type_list'] = $this->awards_type_model->row_list('iAwardsType,sName', array('iState'=>Lib_Constants::PUBLISH_STATE_ONLINE));

        $this->load->model('awards_activity_model');
        $data['activity_list'] = $this->awards_activity_model->row_list();
        $this->render($data);
    }

    /**
     * 奖励类型-添加
     */
    public function add_type() {
        if (!$this->input->is_ajax_request()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', '类型名称', 'required|max_length[50]',array('required'=>'请输入类型名称'));
            $this->form_validation->set_rules('short_name', '短标签', 'required|max_length[30]',array('required'=>'请输入短标签'));

            if (!$this->form_validation->run())
            {
                $errors = $this->form_validation->error_array();
                $this->render_result(-100001, $errors);
            }

            $type_name = $this->get_post('name', '', TRUE);
            $short_type_name = $this->get_post('short_name', '', TRUE);
            $data = array(
                'sName' => $type_name,
                'sShortName' => $short_type_name,
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->awards_type_model->add_row($data)) {
                $this->render_result(0,$insert_id);
            } else {
                $this->render_result(-100005);
            }
        } else {
            $this->render_result(-100006);//无效请求
        }
    }

    public function audit_type()
    {
        $state = $this->get_post('state', null);    //发布状态
        $id = $this->get_post('id', 0);             //表主键

        if (!array_key_exists($state, Lib_Constants::$publish_states) || !$id) {
            $this->render_result(-100001);
        }

        $ori_row = $this->awards_type_model->get_row($id);
        if (!$ori_row) {
            $this->render_result(-100001);
        }

        if (!is_null($state) && !$valid = Lib_Constants::valid_publish_state($ori_row['iState'], $state)) {
            $this->render_result(-100001);
        }

        //下线操作 需校验是否有上线中的活动
        if ($valid == Lib_Constants::NEED_VALID_ONLINE) {
            $this->load->model('awards_activity_model');
            if ($this->awards_activity_model->get_single_row(array($this->awards_activity_model->table_primary => $id,'iAwardsType'=>Lib_Constants::PUBLISH_STATE_ONLINE))) {
                $this->render_result(-100001);//已有上线活动
            }
        }

        if ($state == Lib_Constants::PUBLISH_STATE_DELETE) {
            if ($this->awards_type_model->delete_row($id)) {
                $this->render_result(0);
            } else {
                $this->render_result(-100001);
            }
        } else {
            if ($this->awards_type_model->update_row(array('state'=>$state), $id)) {
                $this->render_result(0);
            } else {
                $this->render_result(-100001);
            }
        }
    }

    /**
     * 奖励类型-修改
     */
    public function update_type() {
        if (!$this->input->is_ajax_request()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', '类型名称', 'required|max_length[50]',array('required'=>'请输入类型名称'));
            $this->form_validation->set_rules('short_name', '短标签', 'required|max_length[30]',array('required'=>'请输入短标签'));

            if (!$this->form_validation->run())
            {
                $errors = $this->form_validation->error_array();
                $this->render_result(-100001, $errors);
            }
            $id = $this->get_post('id', null);
            if (is_null($id)) {
                $this->render_result(-100001);
            }

            $type_name = $this->get_post('name', '', TRUE);
            $short_type_name = $this->get_post('short_name', '', TRUE);
            $data = array(
                'sName' => $type_name,
                'sShortName' => $short_type_name,
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->awards_type_model->update_row($data, $id)) {
                $this->render_result(0,$insert_id);
            } else {
                $this->render_result(-100005);
            }
        } else {
            $this->render_result(-100006);//无效请求
        }
    }


    /**
     * 编辑
     */
    public function edit_activity() {

    }

    /**
     * 奖励管理
     */
    public function add_activity() {
        if (!$this->input->is_ajax_request()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', '类型名称', 'required|max_length[50]',array('required'=>'请输入类型名称'));
            $this->form_validation->set_rules('short_name', '短标签', 'required|max_length[30]',array('required'=>'请输入短标签'));

            if (!$this->form_validation->run())
            {
                $errors = $this->form_validation->error_array();
                $this->render_result(-100000, $errors);
            }

            $type_name = $this->get_post('name', '', TRUE);
            $short_type_name = $this->get_post('short_name', '', TRUE);
            $data = array(
                'sName' => $type_name,
                'sShortName' => $short_type_name,
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->awards_type_model->add_row($data)) {
                $this->render_result(0,$insert_id);
            } else {
                $this->render_result(-100003);
            }
        } else {
            $this->render_result(-100001);
        }
    }

    /**
     * 奖励类型-修改
     */
    public function update_activity() {
        if (!$this->input->is_ajax_request()) {
            $state = $this->get_post('state', null);
            $id = $this->get_post('id', 0);

            if (!array_key_exists($state, Lib_Constants::$publish_states) || !$id) {
                $this->render_result(-100001);
            }
            $ori_awards_type = $this->awards_type_model->get_row($id);
            if (!$ori_awards_type) {
                $this->render_result(-100001);
            }

            if (!is_null($state) && !Lib_Constants::valid_publish_state($ori_awards_type['iState'], $state)) {
                $this->render_result(-100001);
            }

            //下线操作 需校验是否有上线中的活动
            if ($ori_awards_type['iState'] == Lib_Constants::PUBLISH_STATE_ONLINE && $state == Lib_Constants::PUBLISH_STATE_OFFLINE ) {

            } else {

            }


            $this->load->model('awards_type_model');
            if ($this->awards_type_model->update_row(array('state'=>$state), $id)) {
                $this->render_result(0);
            } else {
                $this->render_result(-100001);
            }
        }
    }
}