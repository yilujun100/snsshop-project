<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户奖励类型
 * Class Awards_type
 */
class Awards_type extends Admin_Base {
    protected $relation_model = 'awards_type_model';
    public function __construct() {
        parent::__construct();
        $this->load->model('awards_type_model');
    }

    /**
     * 奖励类型
     */
    public function index(){
        $data['js'] = array('jquery.validate','jquery_validate_extend');
        $data['type_list'] = $this->awards_type_model->row_list('*', array(), array(), $this->get('page', 1));
        $this->render($data);
    }

    /**
     * 奖励类型-添加
     */
    public function add() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', '类型名称', 'required|max_length[50]',array('required'=>'请输入类型名称'));
            $this->form_validation->set_rules('short_name', '短标签', 'required|max_length[30]',array('required'=>'请输入短标签'));
            $this->form_validation->set_rules('en_name', '英文名称', 'required|max_length[30]',array('required'=>'请输入英文标签'));

            if (!$this->form_validation->run())
            {
                $errors = $this->form_validation->error_array();
                $this->render_result(Lib_Errors::PARAMETER_ERR, $errors);
            }

            $type_name = $this->post('name', '');
            $short_type_name = $this->post('short_name', '');
            $en_name = $this->post('en_name', '');

            $row =  $this->awards_type_model->get_row(array('sNameEn'=>$en_name));
            if ($row) {
                $this->render_result(Lib_Errors::SVR_ERR, array(), '奖励类型【'.$en_name.'】已存在,请重试~~');
            }

            $data = array(
                'sName' => $type_name,
                'sShortName' => $short_type_name,
                'sNameEn' => $en_name,
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->awards_type_model->add_row($data)) {
                $this->render_result(Lib_Errors::SUCC,$insert_id);
            } else {
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);//无效请求
        }
    }

    /**
     * 真删数据
     */
    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->post('id', 0);             //表主键
            if ($id) {
                $detail = $this->awards_type_model->get_row($id);
                if ($detail['iState'] == Lib_Constants::PUBLISH_STATE_ONLINE) {
                    $this->render_result(Lib_Errors::ONLINE_CAN_NOT_DELETE);
                }
                if($this->awards_type_model->check_activity_online($detail['sNameEn'])){
                    $this->render_result(Lib_Errors::HAS_OL_AWARDS_ACTIVITY);
                }
                if ($this->{$this->relation_model}->delete_row($id)) {
                    $this->render_result(Lib_Errors::SUCC);
                } else {
                    $this->render_result(Lib_Errors::SVR_ERR);
                }
            } else {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);//无效请求
        }
    }

    /**
     * 奖励类型-修改
     */
    public function edit() {
        if ($this->input->is_ajax_request()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', '类型名称', 'required|max_length[50]',array('required'=>'请输入类型名称'));
            $this->form_validation->set_rules('short_name', '短标签', 'required|max_length[30]',array('required'=>'请输入短标签'));

            if (!$this->form_validation->run())
            {
                $errors = $this->form_validation->error_array();
                $this->render_result(Lib_Errors::PARAMETER_ERR, $errors);
            }
            $id = $this->post('id', null);
            if (is_null($id)) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $type_name = $this->post('name', '');
            $short_type_name = $this->post('short_name', '');
            $data = array(
                'sName' => $type_name,
                'sShortName' => $short_type_name,
                'iUpdateTime' => time()
            );
            if ($this->awards_type_model->update_row($data, $id)) {
                $this->render_result(Lib_Errors::SUCC);
            } else {
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);//无效请求
        }
    }
}