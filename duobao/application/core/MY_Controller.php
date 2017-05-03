<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * MY_Controller constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->init_module();
    }

    /**
     * 初始化模块
     *
     * @todo 目前根据目录判断，后期部署时在看看是否要调整
     */
    protected function init_module()
    {
        if ($directory = $this->router->fetch_directory()) {
            define('MODULE', trim($directory, '/'));
        } else {
            define('MODULE', '');
        }
    }

    /**
     * 获取用户输入
     *
     * 对 CI get 的封装，没找到则返回默认值
     *
     * @param      $index
     * @param null $default
     * @param bool $xss_clean
     *
     * @return mixed|null
     */
    protected function get($index, $default = NULL, $xss_clean = TRUE)
    {
        return $this->get_from_input('get', $index, $default, $xss_clean);
    }

    /**
     * 获取用户输入
     *
     * 对 CI post 的封装，没找到则返回默认值
     *
     * @param      $index
     * @param null $default
     * @param bool $xss_clean
     *
     * @return mixed|null
     */
    protected function post($index, $default = NULL, $xss_clean = TRUE)
    {
        return $this->get_from_input('post', $index, $default, $xss_clean);
    }

    /**
     * 获取用户输入
     *
     * 对 CI get_post 的封装：先查找 GET，再查找 POST，都没找到则返回默认值
     *
     * @param      $index
     * @param null $default
     * @param bool $xss_clean
     *
     * @return mixed|null
     */
    protected function get_post($index, $default = NULL, $xss_clean = TRUE)
    {
        return $this->get_from_input('get_post', $index, $default, $xss_clean);
    }

    /**
     * 获取用户输入
     *
     * @param      $method
     * @param      $index
     * @param null $default
     * @param bool $xss_clean
     *
     * @return array|null
     */
    private function get_from_input($method, $index, $default = NULL, $xss_clean = TRUE)
    {
        if (is_string($index)) {
            if (NULL === ($value = $this->input->{$method}($index, $xss_clean))) {
                return $default;
            }
            return $value;
        } else if (is_array($index)) {
            $data = array();
            foreach ($index as $item) {
                $data[$item] = $this->get_from_input($method, $item, $default, $xss_clean);
            }
            return $data;
        }
    }

    /**
     * 输出内容
     *
     * @param        $content
     * @param string $content_type
     */
    public function output_content($content, $content_type = 'json')
    {
        $mime = get_mime($content_type);
        $this->output->set_content_type($mime, 'utf-8')
            ->set_output($content)
            ->_display();
        exit();
    }

    /**
     * 输出 json 内容
     *
     * @param int    $code
     * @param array  $data
     * @param string $msg
     */
    public function output_json($code = 0, $data = array(), $msg = '')
    {
        $code = (int) $code;
        $result = array(
            'retCode' => $code,
            'retMsg' => empty($msg) ? Lib_Errors::get_error($code) : $msg,
            'retData' => $data
        );
        $this->output_content(json_encode($result), 'json');
    }

    /**
     * API统一输出函数
     * @param int $code
     * @param array $data
     * @param string $msg
     * @throws Exception
     */
    public function render_result($code = 0, $data = array() , $msg = '',$format = 'json')
    {
        $code = (int)$code;
        $result = array(
            'retCode' => $code,
            'retMsg' => empty($msg) ? Lib_Errors::get_error($code) : $msg,
            'retData' => $data
        );

        switch($format){
            case 'json':
                exit(json_encode($result));
                break;

            case 'xml':
            default:
                throw new Exception('support json return only');
        }
    }
}
