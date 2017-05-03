<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Recommend extends Admin_Base
{
	protected $relation_model = 'active_config_model';

	/**
	 * Recommend constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model($this->relation_model);
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
			'iRecWeight' => 'DESC',
			'iCreateTime' => 'DESC',
		);

		$active_id = (int)$this->get('active_id', 0);
		$active_state = (int)($this->get('active_state', -1));

		$where = array(
			'iRecommend' => 1
		);
		if ($active_id > 0) {
			$where['iActId'] = $active_id;
		}
		if ($active_state > -1) {
			$where['iState'] = $active_state;
		}

		$result_list = $this->active_config_model->row_list('*', $where, $order_by, $page);

		$viewData = array(
			'active_id' => $active_id,
			'active_state' => $active_state,
			'result_list' => $result_list
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
		$field = array('act_id', 'rec_weight');
		$input = $this->post($field);
		$act_id = (int)$input['act_id'];
		$data = array(
			'iRecommend' => 1,
			'iRecWeight' => (int)$input['rec_weight']
		);
		if ($this->active_config_model->update_row($data, $act_id)) {
			$this->load->model('active_peroid_model');
			$where = array(
				'iActId'=>$act_id,
				'iIsCurrent'=>1
			);
			if ($this->active_peroid_model->get_row($act_id)) {
				if ($this->active_peroid_model->update_rows($data, $where)) {
					$this->render_result(Lib_Errors::SUCC);
				}
			} else {
				$this->render_result(Lib_Errors::SUCC);
			}
		}
		$this->render_result(Lib_Errors::RECOMMEND_MODIFY_FAILED);
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
		$field = array('act_id', 'rec_weight');
		$input = $this->post($field);
		$act_id = (int)$input['act_id'];
		$data = array(
			'iRecWeight' => (int)$input['rec_weight']
		);
		if ($this->active_config_model->update_row($data, $act_id)) {
			$this->load->model('active_peroid_model');
			$where = array(
				'iActId' => $act_id,
				'iIsCurrent' => 1
			);
			if ($this->active_peroid_model->get_row($act_id)) {
				if ($this->active_peroid_model->update_rows($data, $where)) {
					$this->render_result(Lib_Errors::SUCC);
				}
			} else {
				$this->render_result(Lib_Errors::SUCC);
			}
		}
		$this->render_result(Lib_Errors::RECOMMEND_MODIFY_FAILED);
	}

	/**
	 * 取消推荐
	 */
	public function cancel()
	{
		$this->load->library('form_validation');
		$act_id = (int)$this->post('act_id', 0);
		if ($act_id < 1) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}
		$data = array(
			'iRecommend' => 0,
			'iRecWeight' => -1
		);
		if ($this->active_config_model->update_row($data, $act_id)) {
			$this->load->model('active_peroid_model');
			$where = array(
				'iActId' => $act_id,
				'iIsCurrent' => 1
			);
			if ($this->active_peroid_model->get_row($act_id)) {
				if ($this->active_peroid_model->update_rows($data, $where)) {
					$this->render_result(Lib_Errors::SUCC);
				}
			} else {
				$this->render_result(Lib_Errors::SUCC);
			}
		}
		$this->render_result(Lib_Errors::RECOMMEND_MODIFY_FAILED);
	}

	/**
	 * 检测夺宝单
	 */
	public function check()
	{
		$this->load->library('form_validation');
		$act_id = (int)$this->get('act_id', 0);
		if ($act_id < 1) {
			echo json_encode(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
			exit;
		}
		if (! ($row = $this->active_config_model->get_row($act_id))) {
			echo json_encode(Lib_Errors::get_error(Lib_Errors::ACTIVE_ID_NOT_EXISTS));
			exit;
		}
		if (1 == $row['iRecommend']) {
			echo json_encode(Lib_Errors::get_error(Lib_Errors::ACTIVE_RECOMMEND));
			exit;
		}
		if (! in_array($row['iState'], array(0, 1))) {
			echo json_encode(Lib_Errors::get_error(Lib_Errors::ACTIVE_STATE_ERROR));
			exit;
		}
		echo 'true';
	}

	/**
	 * 设置表单验证规则
	 */
	private function set_form_validation()
	{
		$config = array(
			array(
				'field' => 'act_id',
				'label' => '夺宝单ID',
				'rules' => 'required|is_natural_no_zero',
			),
			array(
				'field' => 'rec_weight',
				'label' => '推荐权重',
				'rules' => 'required|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[9999999]',
			)
		);
		$this->form_validation->set_rules($config);
	}
}
