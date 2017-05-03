<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Groupon_Base extends Groupon_Common
{
    protected $api_map = array();
    protected $platform = Lib_Constants::PLATFORM_WX;   //平台
    protected $need_login_methods = null;              //是否需要验证登陆
    public $user = null;                             //当前用户

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
    protected $sub_view = array('header', 'footer');

    /**
     * 全局共享视图数据
     *
     * @var array
     */
    public $share_view_data = array('js'=>array(), 'css'=>array(), 'third'=>array());

    /**
     * 渲染视图变量容器
     * @var array
     */
    public $view_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->benchmark->mark("groupon_base_construct_start");
        $this->load->config('api');
        $this->api_map = $this->config->item('api_map');
        $this->load->library('layout');
        $this->init();
        $this->benchmark->mark("groupon_base_construct_end");
    }

    /**
     * 向视图中第三方组件
     *
     * @param string|array $css
     */
    public function add_third($css)
    {
        if (! is_array($css)) {
            $css = array($css);
        }
        foreach ($css as $v) {
            $this->share_view_data['third'][] = $v;
        }
    }

    public function init()
    {
        $this->benchmark->mark("groupon_base_init_start");
        $asset_version = get_variable('asset_version');
        $this->assign('version', $asset_version ? $asset_version : '1.0.1');
        $resource_url = $this->config->item('resource_url');
        $this->assign('resource_url',$resource_url);
        $this->assign('cdn_common_url',$resource_url.'common/');
        $this->assign('cdn_third_url',$resource_url.'third/');
        $this->assign('cdn_groupon_url',$resource_url.'groupon/');
        $this->assign('passport_wx_url',$this->config->item('passport_wx_url'));
        //校验登录
        if ($this->need_login_methods && is_array($this->need_login_methods) && in_array($this->router->method, $this->need_login_methods)) {
            $this->get_wx_user();
        }
        if(Lib_Weixin::isFromWeixin()){
            $this->get_share_sign();
            $this->set_wx_share('groupon', array('shareUrl'=>gen_uri('/home/index', array(),'groupon'),'shareImg'=>''));
        }
        $this->benchmark->mark("groupon_base_init_end");
    }

    /**
     * @param      $key
     * @param      $val
     *
     * @return
     */
    public function assign($key,$val=null)
    {
        if (null !== $val) {
            $this->view_data[$key] = $val;
        }
        return isset($this->view_data[$key])?$this->view_data[$key]:null;
    }

    /**
     * @param $arr
     * @return bool
     */
    public function assign_array($arr){
        if(!is_array($arr)) return false;
        $this->view_data = array_merge($this->view_data,$arr);
    }

    /**
     * 取微信用户信息
     */
    public function get_wx_user()
    {
        $this->load->service('user_service');
        //校验登陆
        if($uin = $this->user_service->valid_user_login()) {
            $api_ret = $this->get_api('user_base_info', array('uin' => $uin));
            if ($api_ret['retCode'] == Lib_Errors::SUCC && !empty($api_ret['retData'])) {
                $this->user = $api_ret['retData'];
            }
        }

        if (empty($this->user)) {
            if ($this->input->is_ajax_request()) { //异步直接返回未登录
                $this->render_result(Lib_Errors::NOT_LOGIN);
            } else { //微信授权取用户信息
                $passport_num = get_cookie('p_n');
                if(intval($passport_num) >=3) {
                    $this->log->error('Duogebao', 'get user passport url too many times|| count:'.$passport_num.' || url: '.current_url());
                    show_error(Lib_Errors::get_error(Lib_Errors::EXCEPTION_REQUEST));
                }
                set_cookie('p_n', $passport_num+1, 60, $_SERVER['HTTP_HOST']);
                redirect($this->config->item('passport_wx_url').'?ref='.urlencode(current_url()));
            }
        } else {
            set_cookie('p_n', 0, time()-3600, $_SERVER['HTTP_HOST']);
        }

        if (empty($this->user) || empty($this->user['uin'])) {
            show_error('未登录');
        }

        if (empty($this->user['contact_state'])) {
            $this->load->config('passport');
            $white_list = $this->config->item('host_white_list');
            if (!empty($white_list) && !empty($white_list[$_SERVER['HTTP_HOST']])) {
                redirect($white_list[$_SERVER['HTTP_HOST']]['subscribe_url']);
            }
        }

        if (!$this->input->is_ajax_request()) {
            $this->assign('user', $this->user);
        }
    }

    /**
     * @param $uri
     * @param array $params
     * @param bool $direct 调试的时候可以设置为true,会不管结果如何，都将直接返回
     * @return array|bool|mixed
     */
    public function get_api($uri,$params =  array(),$direct = false)
    {
        $default = array(
            'client_id'  => $this->platform,
            'version'   => Lib_Constants::VERSION,
            'ip' => ip2long(get_ip()),
        );
        $this->log->notice('IP', 'ip['.get_ip().'] | ip2long['.ip2long(get_ip()).']');
        $sky = $this->config->item('skey');
        $params = array_merge($default,$params);

        if(!$uri_map = isset($this->api_map[$uri]) ? $this->api_map[$uri] : null) {
            $this->render_result(Lib_Errors::API_URL_MAP);
        }
        if(!$uri = isset($uri_map['uri']) ? $uri_map['uri'] : null){
            $this->render_result(Lib_Errors::API_URL_MAP);
        }
        $url = $uri;
        if(strstr($url,'http') === false){
            $url = $this->config->item('api_url').$uri;
        }

        $this->benchmark->mark("get_api_($uri)_start");
        //打开了缓存开关 且uri配置了缓存开关
        $need_api_cache = $this->config->item('need_api_cache');
        if ($need_api_cache && !empty($uri_map['open'])) {
            $cache_key = $this->get_memcache_key($params, $uri_map);
            if ($cache_key) {
                $cache_data = $this->cache->memcached->get($cache_key);
                if ($cache_data && $data = json_decode($cache_data, true)) {
                    if (!empty($uri_map['format']) && count($uri_map['format']) == 2) {
                        list($model_name, $format_action) = $uri_map['format'];
                        $this->load->model($model_name);
                        $data = $this->$model_name->$format_action($data);
                    }
                    //$this->log->error('get_api', 'get data from cache | key: '.$cache_key.' | data: '.json_encode($data));
                    $this->benchmark->mark("get_api_($uri)_end_cache");
                    return array(
                        'retCode' => Lib_Errors::SUCC,
                        'retMsg' => '',
                        'retData' => $data
                    );
                }
            }
        }
        
        try{
            $res = $this->getJsonResponse($url,$params,$sky[$this->platform] ,$direct);
            if ($need_api_cache &&
                !empty($uri_map['open']) &&
                $res['retCode'] == Lib_Errors::SUCC &&
                ((isset($res['retData']['list']) && !empty($res['retData']['list'])) || (!isset($res['retData']['list']) && !empty($res['retData'])))  &&
                !empty($cache_key) &&
                empty($uri_map['table_cache'])
            ) {//非表缓存不执行set操作
                $this->load->driver('cache');
                if(!$this->cache->memcached->save($cache_key, json_encode($res['retData']), $uri_map['ttl'])) {
                    $this->log->notice('get_api', 'memcache | set data to cache | failed | key: '.$cache_key.' | data: '.json_encode($res['retData']));
                }
            } else if ($need_api_cache && !empty($uri_map['open']) && empty($uri_map['table_cache'])) { // 返回数据异常
                $this->log->warning('get_api', 'memcache | response exception | key: '.$cache_key.' | res: '.json_encode($res));
            }
            $this->benchmark->mark("get_api_($uri)_end");
            return $res;
        } catch (Exception $e) {
            $this->benchmark->mark("get_api_($uri)_exception");
            return array(
                'retCode' => $e->getCode(),
                'retMsg' => $e->getMessage(),
                'redData' => array($e->getFile(),$e->getLine())
            );
        }
    }

    private function get_memcache_key($params, $conf)
    {
        if (!empty($conf['cache_column'])) {
            $cache_key = array();
            if (is_array($conf['cache_column'])) {
                foreach ($conf['cache_column'] as $column) {
                    if (!isset($params[$column])) {
                        $cache_key = array();
                        break;
                    }
                    $cache_key[$column] = $params[$column];
                }
            }
            if ($cache_key) {
                $cache_key = implode('_', $cache_key);
            }
        } else {
            $cache_key = md5(http_build_query($params));
        }
        if (!$cache_key) {
            return false;
        }

        return empty($conf['prefix']) ? ($conf['uri'].'_'.$cache_key) : ($conf['prefix'].$cache_key);
    }

    /**
     * 请求api，自动添加当前用户uin参数
     *
     * @param       $uri
     * @param array $params
     *
     * @return array|bool|mixed
     */
    public function uin_api($uri, $params = array())
    {
        if (empty($params['uin'])) {
            $params['uin'] = $this->user['uin'];
        }
        return $this->get_api($uri, $params);
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
        $view_data = array_merge($this->view_data,$view_data);
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
     * 生成微信分享signpageck
     */
    public function get_share_sign($url = null)
    {
        $url || $url = current_url();
        //生成微信分享代码
        $signPackage = Lib_WeixinJssdk::getSignPackage($url);
        if ( $signPackage instanceof Exception || empty($signPackage)) {
            show_error('生成微信分享代码失败');
        }
        $this->assign('signPackage',$signPackage);
    }

    /**
     * 设置微信分享
     *
     * @param string $share_item
     * @param array  $data
     */
    protected function set_wx_share($share_item = 'default', $data = array())
    {
        if (! is_array($data)) {
            return;
        }
        if (! isset($data['resource_url'])) {
            $data['resource_url'] = $this->config->item('resource_url');
        }
        if (! isset($data['user']) && $this->user) {
            $data['user'] = $this->user;
        }
        if (! empty($this->wx_share_key)) {
            $share_item = $this->wx_share_key;
        }

        $share_data = array();

        $wx_share_config = get_variable(Lib_Constants::VAR_WX_SHARE);

        if ($wx_share_config && ! empty($wx_share_config['default']) && ! empty($wx_share_config['default_share_img'])) {

            $share_config = isset($wx_share_config[$share_item])?$wx_share_config[$share_item]:$wx_share_config['default'];
            $share_items = array('shareTitle','sendFriendTitle','sendFriendDesc','shareImg');
            foreach ($share_items as $share_item) {
                if (! empty($data[$share_item])) {
                    $share_data[$share_item] = $data[$share_item];
                } else if (isset($share_config[$share_item])) {
                    if ($data) {
                        $share_data[$share_item] = compile_template($share_config[$share_item], $data);
                    } else {
                        $share_data[$share_item] = $share_config[$share_item];
                    }
                }
            }

            if (! empty($data['shareImg'])) {
                $share_data['shareImg'] = $data['shareImg'];
            } else if (empty($share_data['shareImg'])) {
                $share_data['shareImg'] = $wx_share_config['default_share_img'];
            }
            if (false === strpos($share_data['shareImg'], 'http')) {
                $share_data['shareImg'] = rtrim($this->config->item('resource_url'), '/').'/'.$share_data['shareImg'];
            }

            if (! empty($data['shareUrl'])) {
                $share_data['shareUrl'] = $data['shareUrl'];
            } else if (! empty($share_config['shareUrl'])) {
                $params = array();
                $url = '';
                if (is_array($share_config['shareUrl'])) {
                    if (is_string($share_config['shareUrl'][0])) {
                        $url = $share_config['shareUrl'][0];
                    }
                    if (! empty($share_config['shareUrl'][1])) {
                        $params = $url;
                    }
                } else if (is_string($share_config['shareUrl'])) {
                    $url = $share_config['shareUrl'];
                }
                if ($url) {
                    $share_data['shareUrl'] = gen_uri($url, $params);
                }
            }
        }
        $this->assign('shareData', $share_data);
    }

    /**
     * 设置拼团活动是否过期
     * @param $end_time
     */
    protected function check_is_end($end_time)
    {
        if($end_time <= time()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 拼团分享
     * 只有本人 进行中和成团分享提示 自定义  其他均采用拼团默认分享文案
     * @param $groupon_diy
     * @param array $extend
     */
    protected function set_groupon_share($groupon_diy, $extend=array())
    {
        $sign = gen_sign($groupon_diy['iUin'], $groupon_diy['iDiyId']);
        if ($groupon_diy['iState'] == Lib_Constants::GROUPON_DIY_ING || $groupon_diy['iFinished'] != Lib_Constants::GROUPON_DIY_FINISHED || !empty($extend['active_is_end'])) { //未结束) { //进行中的
            $share_data = array(
                'shareUrl'=>gen_uri('/active/diy_detail', array('diy_id'=>$groupon_diy['iDiyId'], 'sign'=>$sign), 'groupon')
            );
            $this->set_wx_share('groupon_ing', $share_data);
        } elseif ($groupon_diy['iState'] == Lib_Constants::GROUPON_DIY_DONE) { //成团
            $this->set_wx_share('groupon_done');
        }
    }
}