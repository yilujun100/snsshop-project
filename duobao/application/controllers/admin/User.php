<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Base
{
	/**
	 * 下拉框中最多角色数目
	 */
	const MAX_ROLE_LIST_COUNT = 100;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('adm_user/user_model');
	}

	/**
	 * 用户列表
	 */
	public function index()
	{
		$this->add_js('jquery.validate.min');
		$this->add_js('jquery.validate.admin.min');

		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC',
			'iUserId' => 'DESC',
		);

		$user_list = $this->user_model->row_list('*', array(), $order_by, $page);

		$this->load->model('adm_user/role_model');
		$role_list = $this->role_model->row_list('iRoleId, sName', array(), array(), 1, self::MAX_ROLE_LIST_COUNT);

		$this->render(array('user_list'=>$user_list, 'role_list' => $role_list['list']));
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
		$field = array('adminUsername', 'adminNickname', 'adminRole', 'adminPassword', 'adminRemark', 'adminActivate');
		$input = $this->post($field);
		$data = array(
			'sName' => $input['adminUsername'],
			'iRoleId' => (int)$input['adminRole'],
			'sNickName' => $input['adminNickname'],
			'sPassword' => $input['adminPassword']
		);
		if ($input['adminRemark']) {
			$data['sRemark'] = $input['adminRemark'];
		}
		if (1 == $input['adminActivate']) {
			$data['iState'] = 1;
		}

		if ($this->user_model->add_row($data)) {
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
		$field = array('user_id', 'adminNickname', 'adminRole', 'adminPassword', 'adminRemark', 'adminActivate');
		$input = $this->post($field);
		$user_id = (int) $input['user_id'];
		if (! $user_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'iRoleId' => (int)$input['adminRole'],
			'sNickName' => $input['adminNickname']
		);
		if (! empty($input['adminPassword'])) {
			$data['sPassword'] = $input['adminPassword'];
		}
		if ($input['adminRemark']) {
			$data['sRemark'] = $input['adminRemark'];
		}
		if (1 == $input['adminActivate']) {
			$data['iState'] = 1;
		}

		if ($this->user_model->update_row($data, $user_id)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::USER_MODIFY_FAILED);
	}

	/**
	 * 启用/禁用用户
	 *
	 * @param $user_id
	 */
	public function state($user_id)
	{
		$user_id = (int)$user_id;
		if ($user_id < 1) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$state = $this->post('state');
		if (null === $state) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}

		$data = array('iState' => (int)$state);

		if ($this->user_model->update_row($data, $user_id)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::USER_STATE_FAILED);
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

		if ($this->user_model->delete_row($role_id)) {
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::USER_DELETE_FAILED);
	}

	/**
	 * 验证用户名
	 *
	 * @param $user_name
	 * @return bool
	 */
	public function check_user($user_name)
	{
		if ($user_name || 'edit' == $this->post('op')) {
			return true;
		}
		$this->form_validation->set_message('check_user', Lib_Errors::get_error(Lib_Errors::USER_EXISTS));
		return false;
	}

	/**
	 * 验证角色
	 *
	 * @param $role_id
	 * @return bool
	 */
	public function check_role($role_id)
	{
		if ($role_id) {
			return true;
		}
		$this->form_validation->set_message('check_role', Lib_Errors::get_error(Lib_Errors::ROLE_ID_INVALID));
		return false;
	}

	/**
	 * 获取用户信息
	 *
	 * @param $user_id
	 */
	public function get_user($user_id)
	{
		$user_id = (int)$user_id;
		if ($user_id < 1) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$row = $this->user_model->get_row($user_id);
		if (! $row) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'iUserId' => $row['iUserId'],
			'iRoleId' => $row['iRoleId'],
			'sName' => $row['sName'],
			'sNickName' => $row['sNickName'],
			'iState' => $row['iState'],
			'sRemark' => $row['sRemark']
		);
		$this->render_result(Lib_Errors::SUCC, $data);
	}

	/**
	 * 用户名校验
	 */
	public function name_valid()
	{
		$user_name = $this->get_post('adminUsername');
		$error = 0;
		if (! $user_name) {
			$error = Lib_Errors::PARAMETER_ERR;
		} else {
			$row = $this->user_model->get_user_by_name($user_name);
			if ($row) {
				$error = Lib_Errors::USER_EXISTS;
			}
		}
		echo $error ? json_encode(Lib_Errors::get_error($error)) : 'true';
	}

	/**
	 * 设置登录表单验证规则
	 */
	private function set_form_validation()
	{
		$adminUsername = array(
			'min_length[5]',
			'max_length[12]',
			array('check_user', array($this, 'check_user'))
		);
		$adminPassword = 'min_length[6]|max_length[13]';
		if ('add' == $this->post('op')) {
			$adminPassword = 'required|' . $adminPassword;
			array_unshift($adminUsername, 'required');
		}
		$config = array(
			array(
				'field' => 'adminUsername',
				'label' => '用户名',
				'rules' => $adminUsername,
			),
			array(
				'field' => 'adminNickname',
				'label' => '姓名',
				'rules' => 'required|min_length[2]|max_length[5]',
			),
			array(
				'field' => 'adminRole',
				'label' => '角色',
				'rules' => array(
					'required',
					array('check_role', array($this, 'check_role'))
				),
			),
			array(
				'field' => 'adminPassword',
				'label' => '密码',
				'rules' => $adminPassword,
			),
			array(
				'field' => 'adminPassword',
				'label' => '备注',
				'rules' => 'max_length[100]',
			),
		);
		$this->form_validation->set_rules($config);
	}
}
