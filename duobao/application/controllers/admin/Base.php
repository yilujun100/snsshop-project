<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Base extends MY_Controller
{
	/**
	 * 视图布局
	 *
	 * 为空则不使用布局
	 *
	 * @var string
	 */
	public $layout_name = 'layout';

	/**
	 * 禁用视图布局
	 *
	 * @var string
	 */
	protected $disable_layout = false;

	/**
	 * 布局中会用到的子视图
	 *
	 * @var array
	 */
	protected $sub_view = array('header', 'menu', 'footer');

	/**
	 * 全局共享视图数据
	 *
	 * @var array
	 */
	public $share_view_data = array('js'=>array(), 'css'=>array(), 'third'=>array());

	/**
	 * 当前功能节点（页面）
	 *
	 * @var
	 */
	public $node;

    /**
     * 当前控制器关联的model
     *
     * @var
     */
    protected $relation_model;

	/**
	 * Admin_Base constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->config('admin');

		$this->init_layout();

		$this->init_node();

		$this->init_site();

		$this->run_common_service();
	}

	/**
	 * 加载并运行公共 service
	 */
	protected function run_common_service()
	{
		$this->load->service('adm_user/user_service');
		if (in_array($this->node, $this->config->item('login_white_list'))) {
			return;
		}
		$user_check = $this->user_service->run($this->node, $this->config->item('admin_white_list'));
		if (Lib_Errors::NOT_LOGIN === $user_check) {
			$login_url =  'admin/login?redirect=' . urlencode(uri_string());
			redirect($login_url);
		} else if (Lib_Errors::PERMISSION_DENIED == $user_check) {
			if ($this->input->is_ajax_request()) {
				$this->render_result(Lib_Errors::PERMISSION_DENIED);
				exit;
			} else {
				echo Lib_Errors::get_error(Lib_Errors::PERMISSION_DENIED);
				exit;
			}
		}
		$this->share_view_data['user'] = $this->user_service->get_user_info();

		$this->load->service('adm_user/menu_service');
		$this->menu_service->run($this->node, $this->config->item('admin_menus'), $this->config->item('admin_white_list'));
		$this->share_view_data['admin_position'] = $this->menu_service->get_position();
		$this->share_view_data['admin_menus'] = $this->menu_service->get_role_menu();
		if (defined('MODULE') && MODULE) {
			$this->share_view_data['menu_dir'] = '/' . MODULE . '/';
		} else {
			$this->share_view_data['menu_dir'] = '/';
		}
	}

	/**
	 * 视图渲染
	 *
	 * @param array  $data
	 * @param string $view
	 * @param bool   $return
	 */
	public function render($data = array(), $view = '', $return = false)
	{
		if (! $view) {
			$view = $this->router->fetch_class() . '/' . $this->router->fetch_method();
		}
		$view_data = array_merge($this->share_view_data, $data);
		if (empty($this->layout_name) || $this->disable_layout) {
			$this->layout->view($view, $view_data, $return, true);
		} else {
			$this->layout->add_sub_view($this->sub_view)->view($view, $view_data, $return);
		}
	}


	/**
	 * 视图小部件
	 *
	 * @param string $widget
	 * @param array  $data
	 * @param bool   $return
	 *
	 * @return string|$this
	 */
	public function widget($widget = '', $data = array(), $return = FALSE)
	{
		$view_data = array_merge($this->share_view_data, $data);

		if ($return) {
			$output = $this->layout->view('_widget/' . $widget, $view_data, true, true);
			return $output;
		} else {
			$this->layout->view('_widget/' . $widget, $view_data, false, true);
			return $this;
		}
	}

	/**
	 * 向视图中增加js
	 *
	 * @param string|array $js
	 */
	public function add_js($js)
	{
		if (! is_array($js)) {
			$js = array($js);
		}
		foreach ($js as $v) {
			$this->share_view_data['js'][] = $v;
		}
	}

	/**
	 * 向视图中增加css
	 *
	 * @param string|array $css
	 */
	public function add_css($css)
	{
		if (! is_array($css)) {
			$css = array($css);
		}
		foreach ($css as $v) {
			$this->share_view_data['css'][] = $v;
		}
	}

	/**
	 * 向视图中增加第三方组件
	 *
	 * @param string|array $third
	 */
	public function add_third($third)
	{
		if (! is_array($third)) {
			$third = array($third);
		}
		foreach ($third as $v) {
			$this->share_view_data['third'][] = $v;
		}
	}

	/**
	 * 初始化网站信息
	 */
	protected function init_site()
	{
		$this->share_view_data['site_name'] = get_variable(Lib_Constants::VAR_SITE_NAME) . '管理后台';
		$this->share_view_data['version'] = get_variable(Lib_Constants::VAR_ASSERT_VERSION);
	}

	/**
	 * 初始化当前节点
	 */
	protected function init_node()
	{
		$this->node = $this->router->fetch_class() . '/' . $this->router->fetch_method();
		$this->share_view_data['node'] = $this->node;
	}

	/**
	 * 初始化布局
	 */
	protected function init_layout()
	{
		$this->load->library('layout');
	}

    /**
     * 真删数据
     */
    public function delete()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->post('id', 0);             //表主键
            if ($id && $this->relation_model) {
                $detail = $this->{$this->relation_model}->get_row($id);
                if ($detail['iState'] == Lib_Constants::PUBLISH_STATE_ONLINE) {
                    $this->render_result(Lib_Errors::ONLINE_CAN_NOT_DELETE);
                }
                if ($this->{$this->relation_model}->delete_row($id)) {
                    $this->render_result(Lib_Errors::SUCC);
                } else {
                    $this->render_result(Lib_Errors::SVR_ERR);
                }
            } else {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);//无效请求
        }
    }

	/**
	 * 修改发布状态
	 */
    public function audit()
    {
        if ($this->input->is_ajax_request()) {
            $opt = $this->get_post('opt', null);    //发布状态
            $id = $this->get_post('id', 0);         //表主键
            if (!array_key_exists($opt, Lib_Constants::$publish_opts) || !isset(Lib_Constants::$publish_opts[$opt]['state']) || !$id) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }

            $state = Lib_Constants::$publish_opts[$opt]['state'];
            if ($this->relation_model) {
                $res = $this->{$this->relation_model}->update_state($id, $state);
                $this->render_result($res);
            } else {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);//异常请求
        }
    }

	/**
	 * 获取查询条件
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	protected function get_search_where(array $fields)
	{
		static $operate_valid = array(
			'equal', 'like', 'where_in', 'where_not_in'
		);
		$where = array();
		foreach ($fields as $field => $config) {
			if (is_int($field)) {
				$field = $config;
			}
			if (is_string($config)) {
				$config = array(
					'operate' => 'equal',
					'map' => $config,
				);
			}
			if (! is_string($field) ||
				! isset($config['operate'], $config['map']) ||
				! in_array($config['operate'], $operate_valid)) {
				continue;
			}
			if ('i' == substr($config['map'], 0 , 1)) {
				$value = $this->get_post($field);
				if (is_null($value)) {
					continue;
				}
				$value = intval($value);
				if ($value < 0) {
					continue;
				}
			} else {
				$value = trim($this->get_post($field, ''));
				if ('' === $value) {
					continue;
				}
			}
			switch ($config['operate']) {
				case 'equal':
					$where[$config['map']] = $value;
					break;
				default:
					@$where[$config['operate']][$config['map']] = $value;
			}
		}
		return $where;
	}

	/**
	 * 加载预定义前端资源
	 *
	 * @param $asset
	 */
	protected function predefine_asset($asset)
	{
		if (! is_array($asset)) {
			$asset = array($asset);
		}
		$config = $this->config->item('asset');
		foreach ($asset as $item) {
			if (! isset($config[$item]) || ! is_array($config[$item])) {
				continue;
			}
			$asset_config = $config[$item];
			if (isset($asset_config['css']) && is_array($asset_config['css'])) {
				foreach ($asset_config['css'] as $v) {
					$this->add_css($v);
				}
				unset($asset_config['css']);
			}
			if (isset($asset_config['js']) && is_array($asset_config['js'])) {
				foreach ($asset_config['js'] as $v) {
					$this->add_js($v);
				}
				unset($asset_config['js']);
			}
			foreach ($asset_config as $v) {
				$this->add_third($v);
			}
		}
	}
}
