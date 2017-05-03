<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Admin_Base
{
	/**
	 * 禁用视图布局
	 *
	 * @var string
	 */
	protected $disable_layout = true;

	/**
	 * 登陆页
	 */
	public function index()
	{
		$this->load->library('form_validation');

		$this->set_form_validation();

		if (FALSE == $this->form_validation->run()) {
			$this->render(array('redirect' => $this->get_post('redirect', '')));
		} else {
			$home = $this->user_service->get_home_node();
			if (empty($home)) {
				$home = $this->config->item('home_default');
			}
			$redirect = $this->post('redirect') ?
				$this->post('redirect') :
				'admin/' . $home;
			redirect($redirect);
		}
	}

	/**
	 * 注销
	 */
	public function logout()
	{
		$this->user_service->logout();
		redirect('admin/login');
	}

	/**
	 * 设置登录表单验证规则
	 */
	private function set_form_validation()
	{
		$config = array(
			array(
				'field' => 'username',
				'label' => '用户名',
				'rules' => 'required',
			),
			array(
				'field' => 'password',
				'label' => '密码',
				'rules' => array(
					'required',
					array('verify', array($this, 'verify_password'))
				)
			)
		);
		$this->form_validation->set_rules($config);
	}

	/**
	 * 校验密码
	 *
	 * @return bool
	 */
	public function verify_password()
	{
		$name = $this->post('username');
		$password = $this->post('password');
		$login_res = $this->user_service->login($name, $password);
		if (true === $login_res) {
			return true;
		}
		$this->form_validation->set_message('verify', Lib_Errors::get_error($login_res));
		return false;
	}
}
