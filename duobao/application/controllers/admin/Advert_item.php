<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Advert_item extends Admin_Base
{
	protected $relation_model = 'advert_item_model';

	/**
	 * 下拉框中最多广告位数目
	 */
	const MAX_POSITION_LIST_COUNT = 100;

	/**
	 * Advert_item constructor.
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
			'iPositionId' => 'DESC',
			'iSort' => 'DESC',
			'iCreateTime' => 'DESC',
		);

		$advert_position = (int)$this->get('advert_position', 0);
		$advert_title = trim($this->get('advert_title', ''));
		$advert_state = (int)($this->get('advert_state', -1));

		$where = array();
		if ($advert_position > 0) {
			$where['iPositionId'] = $advert_position;
		}
		if ($advert_title) {
			$where['like'] = array('sTitle'=>$advert_title);
		}
		if ($advert_state > -1) {
			$where['iState'] = $advert_state;
		}

		$result_list = $this->advert_item_model->row_list('*', $where, $order_by, $page);

		$this->load->model('advert_position_model');
		$position_list = $this->advert_position_model->row_list('*', array(), array('iPositionId'=>'DESC'), 1, self::MAX_POSITION_LIST_COUNT);

		$viewData = array(
			'advert_position' => $advert_position,
			'advert_title' => $advert_title,
			'advert_state' => $advert_state,
			'result_list' => $result_list,
			'position_list' => $position_list['list'],
		);

		$this->render($viewData);
	}

	/**
	 * 添加
	 */
	public function add()
	{
		$this->add_edit_asset();

		if (! $this->input->is_ajax_request()) {

			$this->load->model('advert_position_model');
			$position_list = $this->advert_position_model->row_list('*', array(), array(), 1, self::MAX_POSITION_LIST_COUNT);

			$this->render(array('position_list'=>$position_list['list']), 'advert_item/edit');

		} else {

			$this->load->library('form_validation');
			$this->set_form_validation();

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array(
				'advert_position',
				'advert_title','advert_img','advert_target','advert_desc','advert_sort',
				'advert_begin','advert_end'
			);

			$input = $this->post($field);
			$data = array(
				'iPositionId' => (int)$input['advert_position'],
				'sTitle' => trim($input['advert_title']),
				'sDesc' => trim($input['advert_desc']),
				'sImg' => trim($input['advert_img']),
				'sTarget' => trim($input['advert_target']),
				'iBeginTime' => strtotime(date('Y-m-d 00:00:00', strtotime($input['advert_begin']))),
				'iEndTime' => strtotime(date('Y-m-d 23:59:59', strtotime($input['advert_end']))),
			);
			if ($input['advert_sort']) {
				$data['iSort'] = (int)$input['advert_sort'];
			}

			if ($this->advert_item_model->add_row($data)) {

				$this->render_result(Lib_Errors::SUCC);

			} else {
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED);
			}
		}
	}

	/**
	 * 编辑
	 *
	 * @param $ad_id
	 */
	public function edit($ad_id)
	{
		$ad_id = intval($ad_id);

		$this->add_edit_asset('edit');

		if (! $this->input->is_ajax_request()) {

			if ($ad_id < 1 || ! ($row = $this->advert_item_model->get_row($ad_id))) {
				show_404();
			}

			$this->load->model('advert_position_model');
			$position_list = $this->advert_position_model->row_list('*', array(), array(), 1, self::MAX_POSITION_LIST_COUNT);

			$this->render(array('item'=>$row, 'position_list'=>$position_list['list']));
		} else {

			$this->load->library('form_validation');
			$this->set_form_validation('edit');

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array(
				'advert_title','advert_img','advert_target','advert_desc','advert_sort',
				'advert_begin','advert_end'
			);

			$input = $this->post($field);
			$data = array(
				'sTitle' => trim($input['advert_title']),
				'sDesc' => trim($input['advert_desc']),
				'sImg' => trim($input['advert_img']),
				'sTarget' => trim($input['advert_target']),
				'iBeginTime' => strtotime(date('Y-m-d 00:00:00', strtotime($input['advert_begin']))),
				'iEndTime' => strtotime(date('Y-m-d 23:59:59', strtotime($input['advert_end']))),
			);
			if ($input['advert_sort']) {
				$data['iSort'] = (int)$input['advert_sort'];
			} else {
				$data['iSort'] = 0;
			}

			if ($this->advert_item_model->update_row($data, $ad_id)) {
				$this->render_result(Lib_Errors::SUCC);
			} else {
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED);
			}
		}
	}

	/**
	 * 上传广告图
	 */
	public function img_upload()
	{
		$pos = intval($this->get('pos', 0));
		if ($pos < 1) {
			$this->output_json(Lib_Errors::ADVERT_POS_REQUIRED);
		}
		$this->load->model('advert_position_model');
		$position = $this->advert_position_model->get_row($pos);
		if (empty($position)) {
			$this->output_json(Lib_Errors::ADVERT_POS_REQUIRED);
		}
		$config = array(
			'max_size' => 300,
			'allowed_types' => 'jpg|png',
			'max_width' => $position['iImgWidth'],
			'max_height' => $position['iImgHeight'],
			'min_width' => $position['iImgWidth'],
			'min_height' => $position['iImgHeight'],
		);
		$res = upload_files('advert_img_file', 'advert', $config);
		if (0 != $res['error']) {
			$this->output_json(Lib_Errors::ADVERT_IMG_UPLOAD_FAILED, $res, $res['msg']);
		} else {
			$this->output_json(Lib_Errors::SUCC, array('uri' => $res['url']));
		}
	}

	/**
	 * 获取广告位信息
	 */
	public function ad_pos()
	{
		$pos = intval($this->get_post('pos', 0));
		if ($pos < 1) {
			$this->output_json(Lib_Errors::ADVERT_POS_REQUIRED);
		}
		$this->load->model('advert_position_model');
		$position = $this->advert_position_model->get_row($pos);
		if (empty($position)) {
			$this->output_json(Lib_Errors::ADVERT_POS_REQUIRED);
		}
		$retData = array(
			'width' => $position['iImgWidth'],
			'height' => $position['iImgHeight']
		);
		$this->output_json(Lib_Errors::SUCC, $retData);
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
	 *
	 * @param string $type
	 */
	private function set_form_validation($type = 'add')
	{
		$config = array(
			array(
				'field' => 'advert_title',
				'label' => '标题',
				'rules' => 'required|min_length[5]|max_length[50]',
			),
			array(
				'field' => 'advert_target',
				'label' => '链接',
				'rules' => 'required|valid_url|min_length[5]|max_length[200]',
			),
			array(
				'field' => 'advert_desc',
				'label' => '描述',
				'rules' => 'max_length[200]',
			),
			array(
				'field' => 'advert_sort',
				'label' => '排序',
				'rules' => 'is_natural_no_zero',
			),
			array(
				'field' => 'advert_begin',
				'label' => '开始时间',
				'rules' => 'required',
			),
			array(
				'field' => 'advert_end',
				'label' => '结束时间',
				'rules' => 'required',
			),
			array(
				'field' => 'advert_img',
				'label' => '图片',
				'rules' => 'required',
			)
		);
		if ('add' == $type) {
			array_unshift($config, array('field' => 'advert_position','label' => '广告位','rules' => 'required'));
		}

		$this->form_validation->set_rules($config);
	}

	/**
	 * 添加编辑前端资源
	 */
	private function add_edit_asset()
	{
		// 表单验证
		$this->add_js('jquery.validate.min');
		$this->add_js('jquery.validate.admin.min');

		// 上传
		$this->add_third('jQuery-File-Upload/css/jquery.fileupload.css');
		$this->add_third('jQuery-File-Upload/js/vendor/jquery.ui.widget.js');
		$this->add_third('jQuery-File-Upload/js/jquery.iframe-transport.js');
		$this->add_third('jQuery-File-Upload/js/jquery.fileupload.js');

		// 日期
		$this->add_css('smart-forms');
		$this->add_css('smart-themes/red');
		$this->add_css('font-awesome.min');
		$this->add_js('jquery-ui-datepicker-zh-CN');
	}
}
