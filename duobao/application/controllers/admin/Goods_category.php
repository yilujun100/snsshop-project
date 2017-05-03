<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_category extends Admin_Base
{
	/**
	 * 商品类目列表
	 */
	public function index()
	{
		$this->load->model('goods_category_model');

		$cate_list = $this->goods_category_model->fetch_list();
		$top_cate = $this->goods_category_model->fetch_top();

		$this->add_js('jquery.validate');

		$this->render(array('cate_list'=>$cate_list, 'top_cate'=>$top_cate));
	}

	/**
	 * 新增类目
	 */
	public function add()
	{
		$keys = array('cateName', 'cateHide', 'cateSort', 'cateLvl1', 'cateLvl2', 'cateLvl3', 'cateRemark');
		$input = $this->post($keys);

		if (! $input['cateName'] || utf8_strlen($input['cateName']) > 10) {
			$this->render_result(Lib_Errors::CATE_NAME_ERROR);
		}

		if ($input['cateRemark'] && utf8_strlen($input['cateRemark']) > 100) {
			$this->render_result(Lib_Errors::CATE_REMARK_ERROR);
		}

		$parent_id = 0;
		for ($i = 1; $i < 4; $i ++) {
			if ($input['cateLvl' . $i]) {
				$parent_id = $input['cateLvl' . $i];
			}
		}

		if ($input['cateHide']) {
			$isShow = 0;
		} else {
			$isShow = 1;
		}

		$sort = intval($input['cateSort']);

		$this->load->model('goods_category_model');

		$result = $this->goods_category_model->add_cate($parent_id, $input['cateName'], $isShow, $sort, $input['cateRemark']);

		if ($result) {
			$code = Lib_Errors::SUCC;
		} else {
			$code = Lib_Errors::HANDLE_FAILED;
		}

		$this->render_result($code);
	}

	/**
	 * 编辑类目
	 */
	public function edit()
	{
		$keys = array('cate_id', 'cateName', 'cateHide', 'cateSort', 'cateRemark');
		$input = $this->post($keys);

		$cate_id = intval($input['cate_id']);

		if (! $cate_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
		}

		if (! $input['cateName'] || utf8_strlen($input['cateName']) > 10) {
			$this->render_result(Lib_Errors::CATE_NAME_ERROR);
		}

		if ($input['cateRemark'] && utf8_strlen($input['cateRemark']) > 100) {
			$this->render_result(Lib_Errors::CATE_REMARK_ERROR);
		}

		if ($input['cateHide']) {
			$isShow = 0;
		} else {
			$isShow = 1;
		}

		$sort = intval($input['cateSort']);

		$this->load->model('goods_category_model');

		$updData = array(
			'sName' => $input['cateName'],
			'iIsShow' => $isShow,
			'iSort' => $sort,
			'sRemark' => $input['cateRemark'],
		);

		$result = $this->goods_category_model->update_cate($cate_id, $updData);

		if ($result) {
			$code = Lib_Errors::SUCC;
		} else {
			$code = Lib_Errors::HANDLE_FAILED;
		}

		$this->render_result($code);
	}

	/**
	 * 将类目设置为显示
	 */
	public function show()
	{
		$cate_id = intval($this->post('cate_id'));
		if (! $cate_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
			return;
		}

		$this->load->model('goods_category_model');

		$result = $this->goods_category_model->show_cate($cate_id);

		if ($result) {
			$code = Lib_Errors::SUCC;
		} else {
			$code = Lib_Errors::HANDLE_FAILED;
		}

		$this->render_result($code);
	}

	/**
	 * 将类目设置为隐藏
	 */
	public function hide()
	{
		$cate_id = intval($this->post('cate_id'));
		if (! $cate_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
			return;
		}

		$this->load->model('goods_category_model');

		$result = $this->goods_category_model->hide_cate($cate_id);

		if ($result) {
			$code = Lib_Errors::SUCC;
		} else {
			$code = Lib_Errors::HANDLE_FAILED;
		}

		$this->render_result($code);
	}

	/**
	 * 删除类目
	 */
	public function delete()
	{
		$cate_id = intval($this->post('cate_id'));
		if (! $cate_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
			return;
		}

		$this->load->model('goods_category_model');

		$result = $this->goods_category_model->delete_cate($cate_id);

		if ($result) {
			$code = Lib_Errors::SUCC;
		} else {
			$code = Lib_Errors::HANDLE_FAILED;
		}

		$this->render_result($code);
	}

	/**
	 * 获取类目信息
	 */
	public function get_cate()
	{
		$cate_id = intval($this->post('cate_id'));
		if (! $cate_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
			return;
		}
		$this->load->model('goods_category_model');
		$cate = $this->goods_category_model->get_cate($cate_id);
		if (! $cate) {
			$this->render_result(Lib_Errors::CATE_NOT_EXIST);
		} else {
			$this->render_result(Lib_Errors::SUCC, $cate);
		}

	}

	/**
	 * 获取下级类目列表
	 */
	public function children()
	{
		$cate_id = intval($this->post('cate_id'));
		if (! $cate_id) {
			$this->render_result(Lib_Errors::PARAMETER_ERR);
			return;
		}
		$this->load->model('goods_category_model');
		$children_list = $this->goods_category_model->fetch_children($cate_id);
		if ($children_list) {
			$this->render_result(Lib_Errors::SUCC, $children_list);
		} else {
			$this->render_result(Lib_Errors::NO_CHILDREN_CATE);
		}
	}
}
