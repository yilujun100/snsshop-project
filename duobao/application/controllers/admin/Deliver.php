<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver extends Admin_Base
{
	protected $relation_model = 'order_deliver_model';

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
	 * 夺宝发货列表
	 */
	public function index()
	{
		$this->predefine_asset('validate');

		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC'
		);

		$deliver_type = intval($this->get('deliver_type', -1));
		$deliver_order = $this->get('deliver_order', 0);
		$deliver_uin = $this->get('deliver_uin', 0);

		$where = array(
			'iType !=' => Lib_Constants::ORDER_TYPE_GROUPON
		);
		if ($deliver_type > -1) {
			$where['iType'] = $deliver_type;
		}
		if ($deliver_order > 0) {
			$where['sOrderId'] = $deliver_order;
		}
		if ($deliver_uin > 0) {
			$where['iUin'] = $deliver_uin;
		}

		$result_list = $this->order_deliver_model->fetch_list($where, $order_by, $page);

		$this->load->model('express_company_model');
		$express_list = $this->express_company_model->get_express_list();

		$viewData = array(
			'result_list' => $result_list,
			'deliver_type' => $deliver_type,
			'deliver_order' => $deliver_order,
			'deliver_uin' => $deliver_uin,
			'express_list' => $express_list,
		);

		$this->render($viewData);
	}

	/**
	 * 夺宝确认发货
	 */
	public function confirm()
	{
		$this->load->library('form_validation');
		$this->set_form_validation();
		if (FALSE === $this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			$this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
		}
		$field = array('deliver_id', 'deliver_express_num', 'deliver_express');
		$input = $this->post($field);

		$autoId = (int) $input['deliver_id'];
		$row = $this->order_deliver_model->get_row($autoId);
		if (! $row) {
			$this->render_result(Lib_Errors::DELIVER_ID_NOT_EXIST);
		}

		if (empty($row['sName']) || empty($row['sMobile']) || empty($row['sAddress'])) {
			$this->render_result(Lib_Errors::DELIVER_USER_INCOMPLETE);
		}
		if (empty($row['sExtField']) ||
			! ($ext = json_decode($row['sExtField'], true))
			|| empty($ext[1]) ||
			empty($ext[2])) {
			$this->render_result(Lib_Errors::DELIVER_TRACE_INCOMPLETE);
		}
		$ext[3] = time();
		$ext[4] = time();

		$this->load->model('express_company_model');
		$express = $this->express_company_model->get_row($input['deliver_express']);

		$data = array(
			'sExpressId' => $input['deliver_express_num'],
			'iExpId' => $express['iExpId'],
			'sExpressName' => $express['sName'],
			'sExtField' => json_encode($ext),
			'iDeliverStatus' => 1,
		);
		if ($this->order_deliver_model->update_row($data, (int) $input['deliver_id'])) {
			$this->load->service('order_service');
			if (($order = $this->order_service->confirm_deliver($row['iUin'], $row['sOrderId'])) != Lib_Errors::SUCC) {
				$this->log->warning('admin | deliver confirm', 'order_service confirm_deliver failed', array('code'=>$order,'deliver'=>$row,'user'=>$this->user_service->get_user_info()));
			}
//			$this->send_deliver_msg(array_merge($row, $data));
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::DELIVER_CONFIRM_FAILED);
	}

	/**
	 * 向用户发送发货通知
	 *
	 * @param $deliver
	 */
	private function send_deliver_msg($deliver)
	{
		$this->load->service('message_service');
		$this->load->model('goods_item_model');
		$goods = $this->goods_item_model->get_row($deliver['iGoodsId']);
		$deliver['sGoodsName'] = $goods['sName'];
		$deliver['url'] = gen_uri('/my/active');
		$this->message_service->send($deliver['iUin'], $deliver, Lib_Constants::MSG_TEM_DELIVER);
	}

	/**
	 * 夺宝发货详情
	 *
	 * @param $deliver_id
	 */
	public function detail($deliver_id)
	{
		$item = $this->order_deliver_model->get_deliver($deliver_id);

		if (empty($item)) {
			show_404(Lib_Errors::get_error(Lib_Errors::DELIVER_ID_NOT_EXIST));
		}

		$view_data = array(
			'item' => $item
		);
		$this->render($view_data);
	}

	/**
	 * 拼团发货列表
	 */
	public function groupon()
	{
		$this->predefine_asset('validate');

		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC'
		);

		$deliver_type = intval($this->get('deliver_type', -1));
		$deliver_order = $this->get('deliver_order', 0);
		$deliver_uin = $this->get('deliver_uin', 0);

		$where = array(
			'iType =' => Lib_Constants::ORDER_TYPE_GROUPON
		);

		if ($deliver_order > 0) {
			$where['sOrderId'] = $deliver_order;
		}
		if ($deliver_uin > 0) {
			$where['iUin'] = $deliver_uin;
		}

		$result_list = $this->order_deliver_model->fetch_list($where, $order_by, $page);

		$this->load->model('express_company_model');
		$express_list = $this->express_company_model->get_express_list();

		$viewData = array(
			'result_list' => $result_list,
			'deliver_type' => $deliver_type,
			'deliver_order' => $deliver_order,
			'deliver_uin' => $deliver_uin,
			'express_list' => $express_list,
		);

		$this->render($viewData);
	}

	/**
	 * 拼团确认发货
	 */
	public function groupon_ok()
	{
		$this->load->library('form_validation');
		$this->set_form_validation();
		if (FALSE === $this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			$this->render_result(Lib_Errors::PARAMETER_ERR, array($errors), reset($errors));
		}
		$field = array('deliver_id', 'deliver_express_num', 'deliver_express');
		$input = $this->post($field);

		$autoId = (int) $input['deliver_id'];
		$row = $this->order_deliver_model->get_row($autoId);
		if (! $row) {
			$this->render_result(Lib_Errors::DELIVER_ID_NOT_EXIST);
		}

		if (empty($row['sName']) || empty($row['sMobile']) || empty($row['sAddress'])) {
			$this->render_result(Lib_Errors::DELIVER_USER_INCOMPLETE);
		}
		if (empty($row['sExtField']) ||
			! ($ext = json_decode($row['sExtField'], true))
			|| empty($ext[1]) ||
			empty($ext[2])) {
			$this->render_result(Lib_Errors::DELIVER_TRACE_INCOMPLETE);
		}
		$ext[3] = time();
		$ext[4] = time();

		$this->load->model('express_company_model');
		$express = $this->express_company_model->get_row($input['deliver_express']);

		$data = array(
			'sExpressId' => $input['deliver_express_num'],
			'iExpId' => $express['iExpId'],
			'sExpressName' => $express['sName'],
			'sExtField' => json_encode($ext),
			'iDeliverStatus' => 1,
		);
		if ($this->order_deliver_model->update_row($data, (int) $input['deliver_id'])) {
			$this->load->service('order_service');
			if (($order = $this->order_service->confirm_deliver($row['iUin'], $row['sOrderId'])) != Lib_Errors::SUCC) {
				$this->log->warning('admin | deliver confirm', 'order_service confirm_deliver failed', array('code'=>$order,'deliver'=>$row,'user'=>$this->user_service->get_user_info()));
			}
//			$this->send_deliver_msg(array_merge($row, $data));
			$this->render_result(Lib_Errors::SUCC);
		}
		$this->render_result(Lib_Errors::DELIVER_CONFIRM_FAILED);
	}

	/**
	 * 拼团发货详情
	 *
	 * @param $deliver_id
	 */
	public function groupon_detail($deliver_id)
	{
		$item = $this->order_deliver_model->get_deliver($deliver_id);

		if (empty($item)) {
			show_404(Lib_Errors::get_error(Lib_Errors::DELIVER_ID_NOT_EXIST));
		}

		$this->load->model('groupon_order_model');

		$order = $this->groupon_order_model->get_row($item['sOrderId']);
		if (empty($order)) {
			show_404(Lib_Errors::get_error(Lib_Errors::ORDER_NOT_FOUND));
		}

		$view_data = array(
			'item' => $item,
			'order' => $order
		);
		$this->render($view_data);
	}

	/**
	 * 设置登录表单验证规则
	 */
	private function set_form_validation()
	{
		$config = array(
			array(
				'field' => 'deliver_id',
				'label' => '发货ID',
				'rules' => 'required',
			),
			array(
				'field' => 'deliver_express_num',
				'label' => '快递单号',
				'rules' => 'required|min_length[6]|max_length[20]',
			),
			array(
				'field' => 'deliver_express',
				'label' => '快递公司',
				'rules' => 'required',
			),
		);
		$this->form_validation->set_rules($config);
	}
}
