<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Active extends Admin_Base
{
	protected $relation_model = 'active_config_model';

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
	 * 活动列表
	 */
	public function index()
	{
		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC'
		);

		$act_id = intval($this->get('act_id', 0));
		$goods_id = intval($this->get('goods_id', 0));
		$act_state = intval($this->get('act_state', -1));

		$where = array();
		if ($act_id > 0) {
			$where['iActId'] = $act_id;
		}
		if ($goods_id > 0) {
			$where['iGoodsId'] = $goods_id;
		}
		if ($act_state > -1) {
			$where['iState'] = $act_state;
		}

		$result_list = $this->active_config_model->get_active_configs('*', $where, $order_by, $page);

		$viewData = array(
			'result_list' => $result_list,
			'act_id' => $act_id,
			'goods_id' => $goods_id,
			'act_state' => $act_state,
			'publish_state' => Lib_Constants::$publish_states
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

			$this->render(array(), 'active/edit');

		} else {

			$this->load->library('form_validation');
			$this->set_form_validation();

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array('goodsId','goodsName','actCodePriceRadio','actCodePriceCustom','actTag','actCornetMark',
				'actLotCount','actBuyCount','actPeriodBuyCount',
				'actPeriodCount', 'actBegin', 'actEnd', 'actHeat');

			$input = $this->post($field);
			$data = array();

			$this->load->model('goods_item_model');
			$goods = $this->goods_item_model->get_row($input['goodsId']);

			$data['iGoodsId'] = (int)$input['goodsId'];
			$data['iCateId'] = $goods['iCateId'];
			$data['iCateId_1'] = $goods['iCateId_1'];
			$data['iCateId_2'] = $goods['iCateId_2'];
			$data['iCateId_3'] = $goods['iCateId_3'];
            $data['sGoodsName'] = !empty($input['goodsName']) ? $input['goodsName'] : $goods['sName'];
			$data['iCostPrice'] = $goods['iCostPrice'];
			$data['iLowestPrice'] = $goods['iLowestPrice'];
			$data['sImg'] = $goods['sImg'];
			$data['sImgExt'] = $goods['sImgExt'];
			$data['iActType'] = 1;
			$data['iHeat'] = (int)$input['actHeat'];

			if (-1 == $input['actCodePriceRadio']) {
				$data['iCodePrice'] = floor($input['actCodePriceCustom'] * 100);
			} else {
				$data['iCodePrice'] = (int)$input['actCodePriceRadio'];
			}

			$searchKey = $goods['sName'];
			if ($input['actTag'] && is_array($input['actTag'])) {
				$this->load->model('active_tag_model');
				foreach ($input['actTag'] as $tagId) {
					$tag = $this->active_tag_model->get_row($tagId);
					$searchKey .= '::' . $tag['sName'];
				}
			}
			$data['sSearchKey'] = $searchKey;

			if ($input['actCornetMark']) {
				$data['iCornerMark'] = $input['actCornetMark'];
			}

			$data['iLotCount'] = (int)$input['actLotCount'];
			$data['iTotalPrice'] = $data['iCodePrice'] * $data['iLotCount'];
			$data['iBuyCount'] = (int)$input['actBuyCount'];
			$data['iPeroidBuyCount'] = (int)$input['actPeriodBuyCount'];
			$data['iPeroidCount'] = (int)$input['actPeriodCount'];
			$data['iBeginTime'] = strtotime($input['actBegin']);
			$data['iEndTime'] = strtotime($input['actEnd']);

			if ($actId = $this->active_config_model->add_row($data)) {

				if ($input['actTag'] && is_array($input['actTag'])) {
					$this->load->model('active_tag_map_model');
					$this->active_tag_map_model->add_maps($actId, $input['actTag']);
				}

				$this->render_result(Lib_Errors::SUCC);

			} else {
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED);
			}
		}
	}


	/**
	 * 编辑
	 *
	 * @param $act_id
	 */
	public function edit($act_id)
	{
		$act_id = intval($act_id);

		$this->add_edit_asset();

		if (! $this->input->is_ajax_request()) {

			if ($act_id < 1 || ! ($row = $this->active_config_model->get_row($act_id))) {
				show_404();
			}

			$this->load->model('active_tag_map_model');
			$tagArr = $this->active_tag_map_model->get_rows(array('iActId'=>$row['iActId']));
			if ($tagArr) {
				$row['tags'] = array_column($tagArr, 'iTagId');
			}

			if (in_array($row['iCodePrice'], Lib_Constants::$code_price_opt)) {
				$row['codePriceRadio'] = $row['iCodePrice'];
			} else {
				$row['codePriceRadio'] = -1;
			}

			$this->render(array('item' => $row));
		} else {

			$this->load->library('form_validation');
			$this->set_form_validation();

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array('goodsId','goodsName','actCodePriceRadio','actCodePriceCustom','actTag','actCornetMark',
				'actLotCount','actBuyCount','actPeriodBuyCount',
				'actPeriodCount', 'actBegin', 'actEnd', 'actHeat');

			$input = $this->post($field);
			$data = array();

			$this->load->model('goods_item_model');
			$goods = $this->goods_item_model->get_row($input['goodsId']);

			$data['iGoodsId'] = (int)$input['goodsId'];
			$data['iCateId'] = $goods['iCateId'];
			$data['iCateId_1'] = $goods['iCateId_1'];
			$data['iCateId_2'] = $goods['iCateId_2'];
			$data['iCateId_3'] = $goods['iCateId_3'];
			$data['sGoodsName'] = !empty($input['goodsName']) ? $input['goodsName'] : $goods['sName'];
			$data['iCostPrice'] = $goods['iCostPrice'];
			$data['iLowestPrice'] = $goods['iLowestPrice'];
			$data['sImg'] = $goods['sImg'];
			$data['sImgExt'] = $goods['sImgExt'];
			$data['iActType'] = 1;
			$data['iHeat'] = (int)$input['actHeat'];

			if (-1 == $input['actCodePriceRadio']) {
				$data['iCodePrice'] = floor($input['actCodePriceCustom'] * 100);
			} else {
				$data['iCodePrice'] = (int)$input['actCodePriceRadio'];
			}

			$searchKey = $goods['sName'];

			$this->load->model('active_tag_map_model');
			if ($input['actTag'] && is_array($input['actTag'])) {
				$this->load->model('active_tag_model');
				foreach ($input['actTag'] as $tagId) {
					$tag = $this->active_tag_model->get_row($tagId);
					$searchKey .= '::' . $tag['sName'];
				}
				$this->active_tag_map_model->update_maps($act_id, $input['actTag']);
			} else {
				$this->active_tag_map_model->delete_rows(array('iActId'=>$act_id));
			}

			$data['sSearchKey'] = $searchKey;

			if ($input['actCornetMark']) {
				$data['iCornerMark'] = $input['actCornetMark'];
			} else {
				$data['iCornerMark'] = '';
			}

			$data['iLotCount'] = (int)$input['actLotCount'];
			$data['iTotalPrice'] = $data['iCodePrice'] * $data['iLotCount'];
			$data['iBuyCount'] = (int)$input['actBuyCount'];
			$data['iPeroidBuyCount'] = (int)$input['actPeriodBuyCount'];
			$data['iPeroidCount'] = (int)$input['actPeriodCount'];
			$data['iBeginTime'] = strtotime($input['actBegin']);
			$data['iEndTime'] = strtotime($input['actEnd']);

			if ($this->active_config_model->update_row($data, $act_id)) {
				$this->render_result(Lib_Errors::SUCC);
			} else {
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED);
			}
		}
	}

	/**
	 * 线上编辑
	 *
	 * @param $act_id
	 */
	public function edit_online($act_id)
	{
		$act_id = intval($act_id);

		if ($act_id < 1 || ! ($row = $this->active_config_model->get_row($act_id))) {
			show_404();
		}

		if (Lib_Constants::PUBLISH_STATE_ONLINE != $row['iState']) {
			show_error(Lib_Errors::get_error(Lib_Errors::PARAMETER_ERR));
		}

		$this->add_edit_asset();

		if (! $this->input->is_ajax_request()) {

			$this->load->model('active_tag_map_model');
			$tagArr = $this->active_tag_map_model->get_rows(array('iActId'=>$row['iActId']));
			if ($tagArr) {
				$row['tags'] = array_column($tagArr, 'iTagId');
			}

			$this->render(array('item' => $row));
		} else {

			$this->load->library('form_validation');

			$config = array(
				array(
					'field' => 'actHeat',
					'label' => '机器人开奖热度',
					'rules' => 'required|is_natural_no_zero',
				),
				array(
					'field' => 'actBuyCount',
					'label' => '单人单次最多购买码数',
					'rules' => 'required|is_natural_no_zero'
				),
				array(
					'field' => 'actPeriodBuyCount',
					'label' => '单人单期最多购买码数',
					'rules' => 'required|is_natural_no_zero'
				),
				array(
					'field' => 'actPeriodCount',
					'label' => '总期数',
					'rules' => 'required|is_natural_no_zero'
				),
			);
			$this->form_validation->set_rules($config);

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array('actTag','actCornetMark','actBuyCount','actPeriodBuyCount','actPeriodCount','actEnd','actHeat','actCurrentPeriod');

			$input = $this->post($field);

			$data = array(
				'iHeat' => (int)$input['actHeat']
			);

			$searchKey = $row['sGoodsName'];

			$this->load->model('active_tag_map_model');
			if ($input['actTag'] && is_array($input['actTag'])) {
				$this->load->model('active_tag_model');
				foreach ($input['actTag'] as $tagId) {
					$tag = $this->active_tag_model->get_row($tagId);
					$searchKey .= '::' . $tag['sName'];
				}
				$this->active_tag_map_model->update_maps($act_id, $input['actTag']);
			} else {
				$this->active_tag_map_model->delete_rows(array('iActId'=>$act_id));
			}

			$data['sSearchKey'] = $searchKey;

			if ($input['actCornetMark']) {
				$data['iCornerMark'] = $input['actCornetMark'];
			} else {
				$data['iCornerMark'] = '';
			}

			$data['iBuyCount'] = (int)$input['actBuyCount'];
			$data['iPeroidBuyCount'] = (int)$input['actPeriodBuyCount'];
			$data['iPeroidCount'] = (int)$input['actPeriodCount'];
			$data['iEndTime'] = strtotime($input['actEnd']);

			if (! $this->active_config_model->update_row($data, $act_id)) {
				$this->render_result(Lib_Errors::ACTIVE_EDIT_ONLINE_FAILED);
			} else {
				if (! empty($input['actCurrentPeriod']) && 1 == $input['actCurrentPeriod']) {
					$this->load->model('Active_peroid_model');
					$where = array(
						'iActId' => $act_id,
						'iIsCurrent' => 1
					);
					if (! $this->Active_peroid_model->update_row($data, $where)) {
						$this->render_result(Lib_Errors::ACTIVE_EDIT_ONLINE_PERIOD_FAILED);
					}
				}
				$this->render_result(Lib_Errors::SUCC);
			}
		}
	}

	/**
	 * 结单
	 *
	 * @param $act_id
	 */
	public function terminate($act_id)
	{
		$act_id = intval($act_id);
		if ($act_id < 1) {
			$this->output_json(Lib_Errors::PARAMETER_ERR);
		}
		$act_config = $this->active_config_model->get_row($act_id);
		if (! $act_config) {
			$this->output_json(Lib_Errors::ACTIVE_NOT_FOUND);
		}

		$log_label = 'admin | active terminate';

		$this->load->model('active_peroid_model');
		$where = array(
			'iActId' => $act_id,
			'iIsCurrent' => 1,
		);
		$act_period = $this->active_peroid_model->get_row($where);
		if ($act_period) {
			if ($act_period['iSoldCount'] > 0 || $act_period['iProcess'] > 0) {
				$this->output_json(Lib_Errors::ACTIVE_HAS_SOLD);
			}

			$this->log->alert($log_label, 'act_period', $act_period);

			if (! $this->active_peroid_model->delete_row($where)) {
				$this->log->alert($log_label, 'delete act_period failed', $act_period);
				$this->output_json(Lib_Errors::ACTIVE_TERMINATE_FAILED);
			}
		}
		$data = array(
			'iState' => Lib_Constants::PUBLISH_STATE_END
		);
		if (! $this->active_config_model->update_row($data, $act_id)) {
			$this->log->alert($log_label, 'terminate act_config failed', $act_period);
			$this->output_json(Lib_Errors::ACTIVE_TERMINATE_FAILED);
		}
		$this->output_json(Lib_Errors::SUCC);
	}

	/**
	 * 添加编辑前端资源
	 */
	private function add_edit_asset()
	{
		// 表单验证
		$this->add_js('jquery.validate.min');
		$this->add_js('jquery.validate.admin.min');

		$this->add_css('smart-forms');
		$this->add_css('smart-themes/red');
		$this->add_css('font-awesome.min');
		$this->add_js('jquery-ui-datepicker-zh-CN');
	}

	/**
	 * 设置登录表单验证规则
	 */
	private function set_form_validation()
	{
		$config = array(
			array(
				'field' => 'goodsId',
				'label' => '商品ID',
				'rules' => array(
					'required',
					array('check_goods', array($this, 'check_goods'))
				),
			),
			array(
				'field' => 'actHeat',
				'label' => '机器人开奖热度',
				'rules' => 'required|is_natural_no_zero',
			),
			array(
				'field' => 'actCodePrice',
				'label' => '单个夺宝码价格',
				'rules' => '',
			),
			array(
				'field' => 'actLotCount',
				'label' => '开奖码数',
				'rules' => 'required|is_natural_no_zero'
			),
			array(
				'field' => 'actBuyCount',
				'label' => '单人单次最多购买码数',
				'rules' => 'required|is_natural_no_zero'
			),
			array(
				'field' => 'actPeriodBuyCount',
				'label' => '单人单期最多购买码数',
				'rules' => 'required|is_natural_no_zero'
			),
			array(
				'field' => 'actPeriodCount',
				'label' => '总期数',
				'rules' => 'required|is_natural_no_zero'
			),
		);
		$this->form_validation->set_rules($config);
	}

	/**
	 * 检查商品
	 *
	 * @param $goods_id
	 *
	 * @return bool
	 */
	public function check_goods($goods_id)
	{
		if ($goods_id) {
			return true;
		}
		$this->form_validation->set_message('check_goods', Lib_Errors::get_error(Lib_Errors::GOODS_ID_NOT_EXIST));
		return false;
	}
}
