<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 变量管理
 *
 */
class Config_activity extends Admin_Base
{
	/**
	 * Variable constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('variable_model');
	}

	/**
	 * 列表
	 */
	public function index()
	{
		$page = $this->get('page', 1);

		$fields = array(
			'variable_key' => array('operate'=>'like','map'=>'sKey'),
			'variable_name' => array('operate'=>'like','map'=>'sName'),
			'variable_type' => 'iType',
		);
		$where = $this->get_search_where($fields);
		$where['iIsDeleted'] = 0;
        $where['iType'] = Lib_Constants::VARIABLE_TYPE_OPERATE;

		$order_by = array(
			'sKey' => 'ASC',
			'iCreateTime' => 'ASC'
		);
		$result_list = $this->variable_model->row_list('*', $where, $order_by, $page);

		$viewData = array(
			'result_list' => $result_list,
			'variable_key' => isset($where['like']['sKey'])?$where['like']['sKey']:'',
			'variable_name' => isset($where['like']['sName'])?$where['like']['sName']:'',
			'variable_type' => isset($where['iType'])?$where['iType']:0,
		);

		$this->predefine_asset(array('validate'));
		$this->render($viewData);
	}

	/**
	 * 数据
	 */
	public function get_item()
	{
		$key = trim($this->post('unique', ''));
		if (empty($key)) {
			$this->output_json(Lib_Errors::PARAMETER_ERR);
		}
		$item = $this->variable_model->get_row($key);
		if (empty($item)) {
			$this->output_json(Lib_Errors::VARIABLE_NOT_EXISTS);
		}

		$this->output_json(Lib_Errors::SUCC, $item);
	}

	/**
	 * 添加
	 */
	public function add()
	{
		$this->set_form_validation('add');

		if (FALSE === $this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			$this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
		}
		$field = array('variable_key', 'variable_name', 'variable_value', 'variable_type', 'variable_remark');
		$input = $this->post($field);
		$data = array(
			'sKey' => $input['variable_key'],
			'sName' => $input['variable_name'],
			'sValue' => $input['variable_value'],
			'iType' => $input['variable_type'],
		);
		if (! empty($input['variable_remark'])) {
			$data['sRemark'] = $input['variable_remark'];
		}

		if ($this->variable_model->add_row($data)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::VARIABLE_MODIFY_FAILED);
	}

	/**
	 * 编辑
	 */
	public function edit()
	{
		$this->load->library('form_validation');
		$this->set_form_validation('edit');
		if (FALSE === $this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			$this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
		}
		$field = array('variable_unique', 'variable_name', 'variable_value', 'variable_type', 'variable_remark');
		$input = $this->post($field);
		$key = $input['variable_unique'];
		if (! $key) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'sName' => $input['variable_name'],
			'sValue' => $input['variable_value'],
			'iType' => $input['variable_type'],
		);
		if (! empty($input['variable_remark'])) {
			$data['sRemark'] = $input['variable_remark'];
		}
		if ($this->variable_model->update_row($data, $key)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::VARIABLE_MODIFY_FAILED);
	}

	/**
	 * 删除
	 */
	public function delete()
	{
		$key = $this->post('unique');
		if (empty($key)) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'iIsDeleted' => 1
		);
		if ($this->variable_model->update_row($data, $key)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::VARIABLE_DELETE_FAILED);
	}

	/**
	 * 设置表单验证规则
	 *
	 * @param $type
	 */
	private function set_form_validation($type)
	{
		$this->load->library('form_validation');

		$config = array(
			array(
				'field' => 'variable_name',
				'label' => '变量名称',
				'rules' => 'required|min_length[2]|max_length[255]',
			),
			array(
				'field' => 'variable_value',
				'label' => '变量值',
				'rules' => 'required',
			),
			array(
				'field' => 'variable_type',
				'label' => ' 变量类型',
				'rules' => 'required|integer|greater_than[0]',
			),
			array(
				'field' => 'variable_remark',
				'label' => '变量备注',
				'rules' => 'max_length[1022]',
			)
		);

		if ('add' == $type) {
			$config[] = array(
				'field' => 'variable_key',
				'label' => '变量 Key',
				'rules' => 'required|min_length[6]|max_length[255]'
			);
		} else if ('edit' == $type) {
			$config[] = array(
				'field' => 'variable_unique',
				'label' => '变量 Key',
				'rules' => 'required|min_length[6]|max_length[255]'
			);
		}
		$this->form_validation->set_rules($config);
	}
}
