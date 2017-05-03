<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 积分订单
 *
 * Class Score_order
 */
class Score_order extends Admin_Base
{

	/**
	 * 构造函数
	 *
	 * Score_order constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 订单列表
	 */
	public function index()
	{
		$page = $this->get('page', 1);

		$order_activity = trim($this->get('order_activity', ''));
		$order_uin = trim($this->get('order_uin', ''));
		$order_id = trim($this->get('order_id', ''));

		$where = array();
		if ($order_activity) {
			$where['iActivityId'] = $order_activity;
		}
		if ($order_id) {
			$where['sOrderId'] = $order_id;
		}
		if ($order_uin) {
			$where['iUin'] = $order_uin;
		}

		$this->load->model('score_order_model');
		$order_by = array(
			'iCreateTime' => 'DESC'
		);
		$result_list = $this->score_order_model->row_list('*', $where, $order_by, $page);
		if ($result_list['count'] > 0) {
			$this->load->model('user_model');
			foreach ($result_list['list'] as & $v) {
				$user = $this->user_model->get_user_by_uin($v['iUin']);
				$v['sNickName'] = $user['sNickName'];
			}
		}

		$viewData = array(
			'result_list' => $result_list,
			'order_activity' => $order_activity,
			'order_id' => $order_id,
			'order_uin' => $order_uin,
		);

		$this->render($viewData);
	}

	/**
	 * 订单详情
	 *
	 * @param $order_id
	 */
	public function detail($order_id)
	{

		$this->load->model('score_order_model');
		$item = $this->score_order_model->get_row($order_id);

		if (empty($item)) {
			show_404('订单不存在');
		}

		$this->load->model('user_model');

		$user = $this->user_model->get_user_by_uin($item['iUin']);
		$item['sNickName'] = $user['sNickName'];

		$view_data = array(
			'item' => $item
		);
		$this->render($view_data);
	}
}
