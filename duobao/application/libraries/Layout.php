<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $CI; // CI 实例
    private $module = ''; // CI 实例
    private $theme = 'default'; // 模块主题，在config中配置
    private $layout = ''; // 视图，通过构控制器中的 layout_name 属性指定
    private $sub_view = array(); // 子视图，通过构控制器中的 $sub_view 属性指定

    /**
     * Layout constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->set_layout();
    }

    /**
     * 设置布局
     *
     * @return $this
     */
    public function set_layout()
    {
        $themes = config_item('theme');
        if (defined('MODULE') && ! empty($themes[MODULE])) {
            $this->module = MODULE;
            $this->theme = $themes[MODULE];
        }
        if ($this->CI->layout_name) {
            $this->layout = $this->CI->layout_name;
        }
        return $this;
    }

    /**
     * 增加子视图
     *
     * @param $sub_view
     *
     * @return $this
     */
    public function add_sub_view($sub_view)
    {
        if (is_array($sub_view)) {
            $this->sub_view = array_merge($this->sub_view, $sub_view);
        } else {
            $this->sub_view[] = $sub_view;
        }
        return $this;
    }

    /**
     * 渲染视图
     *
     * @param      $view
     * @param null $data
     * @param bool $return
     * @param bool $no_layout
     *
     * @return $this
     */
    public function view($view, $data = null, $return = false, $no_layout = false)
    {
        if (! $data) {
            $data = array();
        }

        $theme_dir = 'themes/' . $this->theme . '/';

        if ($this->module) {
            $data['theme_dir'] = $this->module . '/' . $theme_dir;
        } else {
            $data['theme_dir'] = $theme_dir;
        }

        if ($this->layout && ! $no_layout) {
            $sub_view_dir = $theme_dir . '_' . $this->layout . '/';
            $main_view = $theme_dir . '_' . $this->layout;

            $data['layout_content'] = $this->CI->load->view($theme_dir . $view, $data, true);

            foreach ($this->sub_view as $sub_view) {
                $data['layout_' . $sub_view] = $this->CI->load->view($sub_view_dir . $sub_view, $data, true);
            }
        } else {
            $main_view = $theme_dir . $view;
        }
        if ($return) {
            $output = $this->CI->load->view($main_view, $data, true);
            return $output;
        } else {
            $this->CI->load->view($main_view, $data, false);
            return $this;
        }
    }
}