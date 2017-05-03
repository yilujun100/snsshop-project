<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Passport_Base extends Passport_Common
{
    protected static  $hot_white_list = array();
    protected $platform = Lib_Constants::PLATFORM_WX;   //平台
    protected $need_login_methods = null;               //是否需要验证登陆
    public $user = null;                                //当前用户
    protected $api_map = null;


    public function __construct($resource_key='')
    {
        parent::__construct();
        $this->init();
    }

    public function init()
    {
        $this->load->config('passport');
        $this->load->config('api');
        self::$hot_white_list = $this->config->item('host_white_list');
        $this->api_map = $this->config->item('api_map');
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
            }
            return $res;
        }catch (Exception $e){
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
}