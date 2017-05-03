<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends Admin_Base
{
	protected $relation_model = 'message_template_model';

	/**
	 * 构造函数
	 *
	 * Goods constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model($this->relation_model);
	}

	/**
	 * 模板列表
	 */
	public function index()
	{
		$this->add_js('jquery.validate.min');
		$this->add_js('jquery.validate.admin.min');

		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC'
		);

		$notify_type = intval($this->get('notify_type', -1));
        $name = trim($this->get('name', ''));
        $msg_business_type = get_variable('msg_business_type');
        $msgBusinessTypeArr = explode(',',$msg_business_type);

		$where = array();
		if ($notify_type > -1) {
			$where['iNotifyType'] = $notify_type;
		}
		if ($name) {
			$where['sName'] = $name;
		}

		$result_list = $this->message_template_model->row_list('*',$where, $order_by, $page);

		$viewData = array(
			'result_list' => $result_list,
			'notify_type' => $notify_type,
			'name' => $name,
            'msgBusinessTypeArr'   =>  $msgBusinessTypeArr
		);

		$this->render($viewData);
	}

    /**
     * 添加
     */
    public function add()
    {
        $this->load->library('form_validation');
        $this->set_form_validation();
        if (FALSE === $this->form_validation->run()) {
            $errors = $this->form_validation->error_array();
            $this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
        }
        $field = array('msgType', 'notifyType', 'name','template', 'dataConfig','extField','remark');
        $input = $this->post($field);
        $data = array(
            'sMsgType' => $input['msgType'],
            'iNotifyType' => (int)$input['notifyType'],
            'sName' => $input['name'],
            'sTemplate' => $input['template']
        );
        if ($input['extField']) {
            $data['sExtField'] = $input['extField'];
        }
        if ($input['dataConfig']) {
            $data['sDataConfig'] = $input['dataConfig'];
        }
        if ($input['remark']) {
            $data['sRemark'] = $input['remark'];
        }
        if ($this->message_template_model->add_row($data)) {
            $this->render_result(Lib_Errors::SUCC);
        }
        $this->render_result(Lib_Errors::USER_MODIFY_FAILED);
    }

    /**
     * 编辑
     */
    public function edit()
    {
        $this->load->library('form_validation');
        $this->set_form_validation();
        if (FALSE === $this->form_validation->run()) {
            $errors = $this->form_validation->error_array();
            $this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
        }
        $field = array('tempId','msgType', 'notifyType', 'name', 'template', 'dataConfig','extField','remark');
        $input = $this->post($field);
        $temp_id = (int) $input['tempId'];
        if (! $temp_id) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $data = array(
            'iTempId'   =>  $temp_id,
            'sMsgType' => $input['msgType'],
            'iNotifyType' => (int)$input['notifyType'],
            'sName' => $input['name'],
            'sTemplate' => $input['template']
        );
        if ($input['extField']) {
            $data['sExtField'] = $input['extField'];
        }
        if ($input['dataConfig']) {
            $data['sDataConfig'] = $input['dataConfig'];
        }
        if ($input['remark']) {
            $data['sRemark'] = $input['remark'];
        }
        if ($this->message_template_model->update_row($data, $temp_id)) {
            $this->render_result(Lib_Errors::SUCC);
        }
        $this->render_result(Lib_Errors::USER_MODIFY_FAILED);
    }

    /**
     * 获取模版信息
     *
     * @param $template_id
     */
    public function get_template($template_id)
    {
        $template_id = (int)$template_id;
        if ($template_id < 1) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $row = $this->message_template_model->get_row($template_id);
        if (! $row) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $data = array(
            'iTempId' => $row['iTempId'],
            'sMsgType' => $row['sMsgType'],
            'iNotifyType' => $row['iNotifyType'],
            'sExtField' =>  $row['sExtField'],
            'sName' => $row['sName'],
            'sTemplate' => $row['sTemplate'],
            'sDataConfig' => $row['sDataConfig'],
            'sRemark' => $row['sRemark']
        );
        $this->render_result(Lib_Errors::SUCC, $data);
    }

    /**
     * 删除
     *
     * @param null $template_id
     */
    public function delete($tempId = null)
    {
        $tempId = (int)$tempId;
        if ($tempId < 1) {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        if ($this->message_template_model->delete_row($tempId)) {
            $this->render_result(Lib_Errors::SUCC);
        }
        $this->render_result(Lib_Errors::USER_DELETE_FAILED);
    }

	/**
	 * 设置登录表单验证规则
	 */
	private function set_form_validation()
	{
		$config = array(
			array(
				'field' => 'name',
				'label' => '模版名称',
				'rules' => 'required',
			),
			array(
				'field' => 'template',
				'label' => '模版',
				'rules' => 'required',
			),
		);
		$this->form_validation->set_rules($config);
	}
}
