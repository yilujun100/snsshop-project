<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Clss API_Base
 */
class API_Base extends MY_Controller
{
    public $client_id = null;
    public $skey = null;
    public $cdata = null;
    public $version = null;
    public $errors = null;
    public $uin = null;
    public static $PAGE_SIZE = 5;

    const version = Lib_Constants::VERSION;

    public function __construct($login =  true)
    {
        parent::__construct();

        $this->config->load('api');
        $skey = $this->config->item('skey');
        $this->cdata = $this->input->get_post('cdata',true);
        $this->client_id = $this->input->get_post('client_id',true);
        $this->version = $this->input->get_post('version',true);
        $this->skey = isset($skey[$this->client_id]) ? $skey[$this->client_id] : '';
        $this->check_params();

        if($login){
            $this->check_login();
        }
    }

    /**
     * API统一输出函数
     * @param int $code
     * @param array $data
     * @param string $msg
     * @param string $format
     * @throws Exception
     */
    public function render_result($code = 0, $data = "" , $msg = '',$format = 'json')
    {
        $code = (int)$code;
        $this->config->load('api');
        $skey = $this->config->item('skey');
        $result = array(
            'retCode' => $code,
            'retMsg' => empty($msg) ? Lib_Errors::get_error($code) : $msg,
            //'retData' => empty($data) ? array() : encrypt($data, $this->skey)
            'retData' =>  $data
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


    /**
     * 检查请求所带的参数
     */
    protected function check_params()
    {
        if(empty($this->client_id) || empty($this->cdata) || empty($this->version)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }elseif($this->version != self::version){
            $this->render_result(Lib_Errors::CLIENT_VERSION_ERROR);
        }elseif(!isset($this->skey[$this->client_id])){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        //开发和测试的时候，不需要加密
        if(ENVIRONMENT == 'development' && empty($this->cdata) && $this->client_id != Lib_Constants::PLATFORM_WTG ){
            $this->cdata = encrypt(isset($_GET['client_id'])?$_GET:$_POST,$this->skey);
        }

        //解析参数
        $this->cdata = $cdata = decrypt($this->cdata,$this->skey);

        if(!is_array($cdata) || $cdata === false){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        if(isset($this->cdata['uin'])){
            $this->uin = $this->cdata['uin'];
        }
    }


    /**
     * 检查登陆状态
     * 暂时先预留此方法，后面再完善
     */
    protected function check_login()
    {

    }

    /**
     * 输出 json 内容
     *
     * @param int    $code
     * @param null  $data
     * @param string $msg
     */
    public function output_json($code = 0, $data = null, $msg = '')
    {
        parent::output_json($code, $data, $msg);
    }

    /**
     * 检查uin是否有效
     *
     * @param $uin
     *
     * @return bool
     */
    public function check_uin($uin)
    {
        $this->load->model('user_model');

        if (! $this->user_model->get_user_by_uin($uin)) {
            return false;
        }
        return true;
    }
}