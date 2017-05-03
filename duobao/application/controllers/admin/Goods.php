<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Goods extends Admin_Base
{
	protected $relation_model = 'goods_item_model';

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
	 * 商品列表
	 */
	public function index()
	{
		$page = $this->get('page', 1);

		$order_by = array(
			'iCreateTime' => 'DESC',
		);

		$goods_id = intval($this->get('goods_id', 0));
		$goods_name = trim($this->get('goods_name', ''));
		$goods_cate = intval($this->get('goods_cate', -1));
		$goods_state = intval($this->get('goods_state', -1));

		$where = array(
			'like' => array(),
		);
		if ($goods_id > 0) {
			$where['a.iGoodsId'] = $goods_id;
		}
		if ($goods_name) {
			$where['like']['a.sName'] = $goods_name;
		}
		if ($goods_cate > 0) {
			$where['a.iCateId_1'] = $goods_cate;
		}
		if ($goods_state > -1) {
			$where['a.iState'] = $goods_state;
		}

		$goods_list = $this->goods_item_model->fetch_list($where, $order_by, $page);

		$this->load->model('goods_category_model');
		$top_cate = $this->goods_category_model->fetch_top();

		$viewData = array(
			'goods_list' => $goods_list,
			'top_cate' => $top_cate,
			'goods_id' => $goods_id,
			'goods_name' => $goods_name,
			'goods_cate' => $goods_cate,
			'goods_state' => $goods_state,
			'publish_state' => Lib_Constants::$publish_states
		);

		$this->render($viewData);
	}

	/**
	 * 添加类目
	 */
	public function add()
	{
		$this->add_edit_asset();

		if (! $this->input->is_ajax_request()) {

			$this->load->model('goods_category_model');
			$top_cate = $this->goods_category_model->fetch_top();
			$this->render(array('top_cate'=>$top_cate), 'goods/edit');

		} else {

			$this->load->library('form_validation');
			$this->set_form_validation();

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array('cateLvl1','cateLvl2','cateLvl3','cateLvl4',
				'goodsName','goodsType','goodsCostPrice','goodsLowestPrice','goodsIntro',
				'goods_img_primary','goods_img_ext1','goods_img_ext2','goods_img_ext3','goods_img_ext4','goods_img_ext5',
				'goodsContent');
			$input = $this->post($field);
			$data = array();
			for ($i = 1; $i < 5; $i ++) {
				$key = 'cateLvl' . $i;
				if (! empty($input[$key])) {
					if ($i < 4) {
						$data['iCateId_' . $i] = $input[$key];
					}
					$data['iCateId'] = $input[$key];
				}
			}
			$data['sName'] = $input['goodsName'];
			$data['iType'] = $input['goodsType'];
			$data['iCostPrice'] = floor($input['goodsCostPrice'] * 100);
			$data['iLowestPrice'] = floor($input['goodsLowestPrice'] * 100);
			if ($input['goodsIntro']) {
				$data['sIntro'] = $input['goodsIntro'];
			}
			$data['sContent'] = $input['goodsContent'];
			$data['sImg'] = $input['goods_img_primary'];
			$extImg = array();
			for ($i = 1; $i < 6; $i ++) {
				$key = 'goods_img_ext' . $i;
				if (! empty($input[$key])) {
					$extImg[strval($i)] = $input[$key];
				}
			}
			if (! empty($extImg)) {
				$data['sImgExt'] = json_encode($extImg);
			}

			if ($this->goods_item_model->add_row($data)) {
				$this->render_result(Lib_Errors::SUCC);
			} else {
				$this->render_result(Lib_Errors::GOODS_MODIFY_FAILED);
			}
		}
	}

	/**
	 * 编辑
	 *
	 * @param $goods_id
	 */
	public function edit($goods_id)
	{
		$goods_id = intval($goods_id);

		$this->add_edit_asset();

		if (! $this->input->is_ajax_request()) {

			if ($goods_id < 1 || ! ($row = $this->goods_item_model->get_row($goods_id))) {
				show_404();
			}

			$cate = array();
			for ($i = 1; $i < 4; $i ++) {
				if ($row['iCateId_'  . $i]) {
					$cate['cateLvl' . $i] = $row['iCateId_'  . $i];
				} else {
					$cate['cateLvl' . $i] = 0;
				}
			}
			if (! in_array($row['iCateId'], $cate)) {
				$cate['cateLvl4'] = $row['iCateId'];
			}
			$row['cate'] = $cate;
			if ($row['sImgExt']) {
				$row['img_ext'] = json_decode($row['sImgExt'], true);
			} else {
				$row['img_ext'] = array();
			}
			$this->load->model('goods_category_model');
			$top_cate = $this->goods_category_model->fetch_top();

			$this->render(array('top_cate'=>$top_cate, 'goods' => $row));
		} else {

			$this->load->library('form_validation');
			$this->set_form_validation();

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::ACTIVE_MODIFY_FAILED, array($errors), reset($errors));
			}

			$field = array('cateLvl1','cateLvl2','cateLvl3','cateLvl4',
				'goodsName','goodsType','goodsCostPrice','goodsLowestPrice','goodsIntro',
				'goods_img_primary','goods_img_ext1','goods_img_ext2','goods_img_ext3','goods_img_ext4','goods_img_ext5',
				'goodsContent');
			$input = $this->post($field);
			$data = array();
			for ($i = 1; $i < 5; $i ++) {
				$key = 'cateLvl' . $i;
				if (! empty($input[$key])) {
					if ($i < 4) {
						$data['iCateId_' . $i] = $input[$key];
					}
					$data['iCateId'] = $input[$key];
				}
			}
			$data['sName'] = $input['goodsName'];
			$data['iType'] = (int)$input['goodsType'];
			$data['iCostPrice'] = floor($input['goodsCostPrice'] * 100);
			$data['iLowestPrice'] = floor($input['goodsLowestPrice'] * 100);
			if ($input['goodsIntro']) {
				$data['sIntro'] = $input['goodsIntro'];
			}
			$data['sContent'] = $input['goodsContent'];
			$data['sImg'] = $input['goods_img_primary'];
			$extImg = array();
			for ($i = 1; $i < 6; $i ++) {
				$key = 'goods_img_ext' . $i;
				if (! empty($input[$key])) {
					$extImg[strval($i)] = $input[$key];
				}
			}
			if (! empty($extImg)) {
				$data['sImgExt'] = json_encode($extImg);
			}

			if ($this->goods_item_model->update_row($data, $goods_id)) {
				$this->render_result(Lib_Errors::SUCC);
			} else {
				$this->render_result(Lib_Errors::GOODS_MODIFY_FAILED);
			}
		}
	}

	/**
	 * 在线编辑
	 *
	 * @param $goods_id
	 */
	public function edit_online($goods_id)
	{
		$goods_id = intval($goods_id);

		$this->add_edit_asset();

		if (! $this->input->is_ajax_request()) {

			if ($goods_id < 1 || ! ($row = $this->goods_item_model->get_row($goods_id))) {
				show_404();
			}

			$cate = array();
			for ($i = 1; $i < 4; $i ++) {
				if ($row['iCateId_'  . $i]) {
					$cate['cateLvl' . $i] = $row['iCateId_'  . $i];
				} else {
					$cate['cateLvl' . $i] = 0;
				}
			}
			if (! in_array($row['iCateId'], $cate)) {
				$cate['cateLvl4'] = $row['iCateId'];
			}
			$row['cate'] = $cate;
			if ($row['sImgExt']) {
				$row['img_ext'] = json_decode($row['sImgExt'], true);
			} else {
				$row['img_ext'] = array();
			}
			$this->load->model('goods_category_model');
			$top_cate = $this->goods_category_model->fetch_top();

			$this->render(array('top_cate'=>$top_cate, 'goods' => $row));
		} else {

			$this->load->library('form_validation');
			$this->set_form_validation('edit_online');

			if (FALSE === $this->form_validation->run()) {
				$errors = $this->form_validation->error_array();
				$this->render_result(Lib_Errors::GOODS_EDIT_ONLINE_FAILED, array($errors), reset($errors));
			}

			$field = array('cateLvl1','cateLvl2','cateLvl3','cateLvl4',
				'goodsName','goodsIntro',
				'goods_img_primary','goods_img_ext1','goods_img_ext2','goods_img_ext3','goods_img_ext4','goods_img_ext5',
				'goodsContent');
			$input = $this->post($field);

			$data = $active = $activity = array();
			for ($i = 1; $i < 5; $i ++) {
				$key = 'cateLvl' . $i;
				if (! empty($input[$key])) {
					if ($i < 4) {
						$data['iCateId_' . $i] = $input[$key];
						$active['iCateId_' . $i] = $input[$key];
					}
					$data['iCateId'] = $input[$key];
					$active['iCateId'] = $input[$key];
				} else if ($i < 4) {
					$data['iCateId_' . $i] = 0;
					$active['iCateId_' . $i] = 0;
				}
			}

			$data['sName'] = $input['goodsName'];
			$active['sGoodsName'] = $data['sName'];
			$activity['sGiftName'] = $data['sName'];
			if ($input['goodsIntro']) {
				$data['sIntro'] = $input['goodsIntro'];
			}
			$data['sContent'] = $input['goodsContent'];
			$data['sImg'] = $input['goods_img_primary'];
			$active['sImg'] = $data['sImg'];
			$activity['sImg'] = $data['sImg'];
			$extImg = array();
			for ($i = 1; $i < 6; $i ++) {
				$key = 'goods_img_ext' . $i;
				if (! empty($input[$key])) {
					$extImg[strval($i)] = $input[$key];
				}
			}
			if (! empty($extImg)) {
				$data['sImgExt'] = json_encode($extImg);
				$active['sImgExt'] = $data['sImgExt'];
			}

			$time = time();

			// 编辑商品
			if (! $this->goods_item_model->update_row($data, $goods_id)) {
				$this->log->error('admin', 'edit_online goods_item_model update_row', array('goods_id'=>$goods_id, 'data'=>$data));
				$this->render_result(Lib_Errors::GOODS_EDIT_ONLINE_FAILED);
			}

			// 同步更新夺宝单配置
			$this->load->model('active_config_model');
			if (! $this->active_config_model->sync_goods($goods_id, $active)) {
				$this->log->error('admin', 'edit_online active_config_model sync_goods', array('goods_id'=>$goods_id,'sql'=>$this->active_config_model->db->last_query(),'active'=>$active));
				$this->render_result(Lib_Errors::GOODS_EDIT_ONLINE_FAILED);
			}

			// 同步更新当前期夺宝单
			$this->load->model('active_peroid_model');
			if (! $this->active_peroid_model->sync_goods($goods_id, $active)) {
				$this->log->error('admin', 'edit_online active_peroid_model sync_goods', array('goods_id'=>$goods_id,'sql'=>$this->active_peroid_model->db->last_query(),'active'=>$active));
				$this->render_result(Lib_Errors::GOODS_EDIT_ONLINE_FAILED);
			}

			// 编辑积分兑换活动
			$this->load->model('score_activity_model');
			$params = array(
				'iGoodsId' => $goods_id,
				'iEndTime >=' => $time,
				'iState !=' => Lib_Constants::PUBLISH_STATE_END,
			);
			if (false === $this->score_activity_model->update_rows($activity, $params)) {
				$this->log->error('admin', 'edit_online score_activity_model update_rows', array('params'=>$params, 'data'=>$data));
				$this->render_result(Lib_Errors::GOODS_EDIT_ONLINE_FAILED);
			}

			$this->render_result(Lib_Errors::SUCC);
		}
	}

	/**
	 * 上传商品图片
	 */
	public function img_upload()
	{
		$config = array(
			'allowed_types' => 'jpg|png',
			'max_size' => 300,
			'max_width' => 640,
			'max_height' => 640,
			'min_width' => 640,
			'min_height' => 640
		);
		$res = upload_files('goods_img', 'goods', $config);
		if (0 != $res['error']) {
			$this->output_json(Lib_Errors::GOODS_IMG_UPLOAD_FAILED, $res, $res['msg']);
		} else {
			$this->output_json(Lib_Errors::SUCC, array('uri' => $res['url']));
		}
	}

	/**
	 * 图文详情编辑框设置
	 */
	public function detail_img()
	{
		$action = $this->get('action', 'config');

		if ('config' == $action) {
			$config = array(
				'imageActionName' => 'detail_img_upload',
				'imageFieldName' => 'goods_detail_img',
				'imageMaxSize' => '307200',
				'imageAllowFiles' => array(".png", ".jpg", ".jpeg", ".gif"),
				'imageUrlPrefix' => '',
			);
			echo json_encode($config);
		}

		$callable = array($this, $action);
		if (is_callable($callable)) {
			call_user_func($callable);
		}
	}

	/**
	 * 图文详情编辑框设置
	 */
	public function detail_img_upload()
	{
		$config = array(
			'allowed_types' => 'jpg|png',
			'max_size' => 1024,
			'max_width' => 960,
			'max_height' => 3200,
			'min_width' => 640,
			'min_height' => 0
		);
		$res = upload_files('goods_detail_img', 'goods_detail', $config);
		$response = array(
			"state" => '',
			"url" => '',
			"title" => '',
			"original" => '',
			"type" => '',
			"size" => ''
		);
		if (0 != $res['error']) {
			$response['state'] = $res['msg'];
		} else {
			$response['state'] = 'SUCCESS';
			$response['url'] = $res['url'];
		}
		echo json_encode($response);
		exit;
	}

	/**
	 * 添加编辑前端资源
	 */
	private function add_edit_asset()
	{
		// 表单验证
		$this->add_js('jquery.validate.min');
		$this->add_js('jquery.validate.admin.min');

		// 商品图片上传
		$this->add_third('jQuery-File-Upload/css/jquery.fileupload.css');
		$this->add_third('jQuery-File-Upload/js/vendor/jquery.ui.widget.js');
		$this->add_third('jQuery-File-Upload/js/jquery.iframe-transport.js');
		$this->add_third('jQuery-File-Upload/js/jquery.fileupload.js');

		// 商品富文本编辑框
		$this->add_third('ueditor/themes/default/css/ueditor.css');
		$this->add_third('ueditor/ueditor.config.admin.min.js');
		$this->add_third('ueditor/ueditor.all.min.js');
		$this->add_third('ueditor/lang/zh-cn/zh-cn.js');
	}

	/**
	 * 设置登录表单验证规则
	 *
	 * @param $operate
	 */
	private function set_form_validation($operate = '')
	{
		if ('edit_online' == $operate) {
			$config = array(
				array(
					'field' => 'goodsName',
					'label' => '商品名称',
					'rules' => array(
						'required',
						'max_length[60]'
					)
				),
				array(
					'field' => 'goodsIntro',
					'label' => '商品简介',
					'rules' => array(
						'max_length[200]'
					)
				),
				array(
					'field' => 'goods_img_primary',
					'label' => '主图',
					'rules' => 'required'
				),
				array(
					'field' => 'goodsContent',
					'label' => '图文详情',
					'rules' => 'required|max_length[102400]'
				),
			);
		} else {
			$config = array(
				array(
					'field' => 'cateLvl1',
					'label' => '一级类目',
					'rules' => array(
						'required',
						'greater_than[0]',
						array('check_cate', array($this, 'check_cate'))
					),
				),
				array(
					'field' => 'cateLvl2',
					'label' => '二级类目',
					'rules' => array(
						array('check_cate', array($this, 'check_cate'))
					),
				),
				array(
					'field' => 'cateLvl3',
					'label' => '三级类目',
					'rules' => array(
						array('check_cate', array($this, 'check_cate'))
					),
				),
				array(
					'field' => 'cateLvl4',
					'label' => '四级类目',
					'rules' => array(
						array('check_cate', array($this, 'check_cate'))
					),
				),
				array(
					'field' => 'goodsName',
					'label' => '商品名称',
					'rules' => array(
						'required',
						'max_length[60]'
					)
				),
				array(
					'field' => 'goodsCostPrice',
					'label' => '商品成本价',
					'rules' => array(
						'required',
						'greater_than[0]',
					)
				),
				array(
					'field' => 'goodsLowestPrice',
					'label' => '商品最低售价',
					'rules' => array(
						'required',
						'greater_than[0]',
					)
				),
				array(
					'field' => 'goodsIntro',
					'label' => '商品简介',
					'rules' => array(
						'max_length[200]'
					)
				),
				array(
					'field' => 'goods_img_primary',
					'label' => '主图',
					'rules' => 'required'
				),
				array(
					'field' => 'goodsContent',
					'label' => '图文详情',
					'rules' => 'required|max_length[102400]'
				),
			);
		}
		$this->form_validation->set_rules($config);
		$this->form_validation->set_message('greater_than', '{field} 不能为空');
	}

	/**
	 * 检查类目商品类目
	 *
	 * @param $cate_id
	 *
	 * @return bool
	 */
	public function check_cate($cate_id)
	{
		if ($cate_id) {
			return true;
		}
		$this->form_validation->set_message('check_cate', '商品类目不存在');
		return true;
	}

	/**
	 * 获取商品信息
	 *
	 * @param null $goods_id
	 */
	public function get_goods_info($goods_id = null)
	{
		$goods_id = (int)$goods_id;
		if ($goods_id < 0) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}

		$item = $this->goods_item_model->get_row($goods_id);

		if (empty($item)) {
			$this->render_result(Lib_Errors::GOODS_ID_NOT_EXIST);
		}

		if (Lib_Constants::PUBLISH_STATE_ONLINE != $item['iState']) {
			$this->render_result(Lib_Errors::GOODS_NOT_ONLINE);
		}

		$outData = array(
			'id' => $goods_id,
			'name' => $item['sName'],
			'cost' => $item['iCostPrice'],
			'lowest' => $item['iLowestPrice'],
		);
		$this->render_result(Lib_Errors::SUCC, $outData);
	}
}
