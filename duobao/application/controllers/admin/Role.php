<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends Admin_Base
{
	/**
	 * Role constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('adm_user/role_model');
	}

	/**
	 * 列表
	 */
	public function index()
	{
		$this->add_js('jquery.validate.min');
		$this->add_js('jquery.validate.admin.min');

		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC',
			'iRoleId' => 'DESC',
		);

		$this->load->model('adm_user/role_model');
		$role_list = $this->role_model->row_list('*', array(), $order_by, $page);

		$this->render(array('role_list'=>$role_list, 'home_node'=>$this->config->item('home_node')));
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
			$this->render_result(Lib_Errors::USER_MODIFY_FAILED, array($errors), reset($errors));
		}
		$field = array('roleName', 'roleHome', 'roleRemark');
		$input = $this->post($field);
		$data = array(
			'sName' => $input['roleName']
		);
		if ($input['roleHome'] != $this->config->item('home_default')) {
			$data['sHomeNode'] = $input['roleHome'];
			$data['sPurviews'] = $input['roleHome'];
		}
		if ($input['roleRemark']) {
			$data['sRemark'] = $input['roleRemark'];
		}
		if ($this->role_model->add_row($data)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::ROLE_MODIFY_FAILED);
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
		$field = array('role_id', 'roleName', 'roleHome', 'roleRemark');
		$input = $this->post($field);
		$role_id = (int) $input['role_id'];
		if (! $role_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$role = $this->role_model->get_row($role_id);
		if (! $role) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'sName' => $input['roleName']
		);
		if ($input['roleHome'] != $this->config->item('home_default')) {
			$data['sHomeNode'] = $input['roleHome'];
			if (! empty($role['sPurviews'])) {
				$purview = explode(',', $role['sPurviews']);
				if (! in_array($data['sHomeNode'], $purview)) {
					$purview[] = $data['sHomeNode'];
					$data['sPurviews'] = implode(',', $purview);
				}
			}
		}
		if ($input['roleRemark']) {
			$data['sRemark'] = $input['roleRemark'];
		}
		if ($this->role_model->update_row($data, $role_id)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::ROLE_MODIFY_FAILED);
	}

	/**
	 * 授权
	 *
	 * @param $role_id
	 */
	public function purview($role_id)
	{
		$role_id = (int)$role_id;

		if (! $this->input->is_ajax_request()) {
			if ($role_id < 1 || ! ($row = $this->role_model->get_row($role_id))) {
				show_404();
			}
			$viewData = array(
				'item' => $row,
				'admin_menu' => config_item('admin_menus'),
			);
			$this->render($viewData);
		} else {
			$purview_arr = $this->post('purview');

			if (empty($purview_arr) || ! is_array($purview_arr)) {
				$this->render_result(Lib_Errors::PARAMETER_ERR);
			}
			$data = array('sPurviews'=>implode(',', array_unique($purview_arr)));
			if ($this->role_model->update_row($data, $role_id)) {
				$this->render_result(Lib_Errors::SUCC);
			}
			$this->render_result(Lib_Errors::ROLE_PURVIEW_FAILED);
		}
	}

	/**
	 * 删除
	 *
	 * @param null $role_id
	 */
	public function delete($role_id = null)
	{
		$role_id = (int)$role_id;
		if ($role_id < 1) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}

		if ($this->role_model->delete_row($role_id)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::ROLE_DELETE_FAILED);
	}

	/**
	 * 获取用户信息
	 *
	 * @param $role_id
	 */
	public function get_role($role_id)
	{
		$role_id = (int)$role_id;
		if ($role_id < 1) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$row = $this->role_model->get_row($role_id);
		if (! $row) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'roleId' => $row['iRoleId'],
			'roleName' => $row['sName'],
			'roleHome' => $row['sHomeNode'],
			'roleRemark' => $row['sRemark']
		);
		$this->render_result(Lib_Errors::SUCC, $data);
	}

	/**
	 * 验证角色名
	 *
	 * @param $role_name
	 * @return bool
	 */
	public function check_role($role_name)
	{
		if ($role_name || 'edit' == $this->post('op')) {
			return true;
		}
		$this->form_validation->set_message('check_role', Lib_Errors::get_error(Lib_Errors::ROLE_EXISTS));
		return false;
	}

	/**
	 * 设置登录表单验证规则
	 */
	private function set_form_validation()
	{
		$config = array(
			array(
				'field' => 'roleName',
				'label' => '角色名',
				'rules' => array(
					'required',
					'min_length[2]',
					'max_length[10]',
					array('check_role', array($this, 'check_role'))
				),
			),
			array(
				'field' => 'roleHome',
				'label' => '首页节点',
				'rules' => 'required|min_length[3]|max_length[30]',
			),
			array(
				'field' => 'roleRemark',
				'label' => '备注',
				'rules' => 'max_length[100]',
			)
		);
		$this->form_validation->set_rules($config);
	}
}
