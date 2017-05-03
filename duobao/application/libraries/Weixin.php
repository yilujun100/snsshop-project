<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Leo Zou
 * Date: 15-03-11
 * Time: 下午1:16
 * To change this template use File | Settings | File Templates.
 */

class Lib_Weixin
{
	private $appId = '';
	private $appSecret = '';
	private $token = '';
	private $connectTimeout = 30;
	private $tokenLockTimeout = 3;
	private $userInfo;
	public static $app = null;


	public function __construct($config)
	{
		$this->appId         = isset($config['appId']) ? $config['appId'] : '';
		$this->appSecret     = isset($config['appSecret']) ? $config['appSecret'] : '';
		$this->token         = isset($config['token']) ? $config['token'] : '';
		$this->loginHost     = !empty($config['testApiHost']) ? $config['testApiHost'] : 'http://login.weixin.qq.com';
		$this->apiHost       = !empty($config['testApiHost']) ? $config['testApiHost'] : 'http://api.weixin.qq.com';
		$this->apiSecretHost = !empty($config['testApiHost']) ? $config['testApiHost'] : 'https://api.weixin.qq.com';
	}

	/**
	 * 微信接口入口
	 * @param $config
	 * @return null|WTuan_Lib_Weixin
	 */
	public static function portal($config)
	{
		if (is_null(self::$app)) {
			self::$app = new Lib_Weixin($config);
		}

		return self::$app;
	}

    /**
     * 获取用户信息
     * @param $openId
     * @return string
     */
    public function getUserInfo($openId)
    {
        if (empty($openId)) {
            throw new Exception('openid not empty.');
        }
        if (empty($this->userInfo[$openId])) {
            $accssToken = $this->getToken();
            if (empty($accssToken)) {
                throw new Exception('get access_token error.');
            }
            $url                     = $this->apiHost . '/cgi-bin/user/info?access_token=' . $accssToken . '&openid=' . $openId;
            $this->userInfo[$openId] = $this->http('get', $url);
        }

        return $this->userInfo[$openId];
    }

    /**
     * 微信页面授权 accesstoken获得
     *
     */
    public function auth2accesstoken($code)
    {
        $url = $this->apiSecretHost . '/sns/oauth2/access_token?';
        $params = array(
            'appid'     => $this->appId,
            'secret'    => $this->appSecret,
            'code'      => $code,
            'grant_type'=> 'authorization_code'
        );
        $url .= http_build_query($params);
        $rs = $this->http('get', $url);
        return $rs;
    }


    /**
     * 访问接口
     * @param        $method  协议
     * @param        $url  地址
     * @param string $body  内容，如果不为空，则发送post请求
     * @return array
     */
    private function http($method, $url, $body = '', $curlopt = array())
    {
        $resp           = '';
        $wxapiStartTime = microtime(true);
        $repeat         = 3;
        $count          = 1;
        $httpParam      = array();
        if (strtoupper($method) == 'POST') {
            $httpParam[CURLOPT_POST] = true;
            if (!empty($body)) {
                $httpParam[CURLOPT_POSTFIELDS] = $body;
            }
        }

        if(strpos($url, 'http://114.251.141.34:8008/sns/oauth2/access_token') !== false || strpos($url, 'http://114.251.141.34:8008/sns/oauth2/refresh_token') !== false
        ){
            $url = str_replace('http://114.251.141.34:8008', 'https://api.weixin.qq.com', $url);
        }
        //debug($url);
        //debug($httpParam);
        while ($count <= $repeat) {
            $return = Lib_CurlRequest::CallCURL($url, $httpParam, $resp, $curlopt, $this->connectTimeout);
            //debug('$return = '.$return);
            switch ($return) {
                case  0:
                    $result = 'ok';
                    break;
                case -1:
                    $result = 'contect error';
                    break;
                case -2:
                    $result = 'responseCode not 200';
                    break;
            }
            //debug($resp);
            if(strpos($url,'https://api.weixin.qq.com/sns/oauth2/refresh_token')!==false){
                $resp = '{"errcode":40030,"errmsg":"invalid code"}';
            }
            Lib_WeixinUserOauth2::getCI()->log->notice('WxUserOauth2','CallCURL | url['.$url.'] | body[' . $body . '] count[' . $count . '] | result['.$result.'] | resp['.$resp.'] | '.__METHOD__);
            if ($return === 0) {
                $json = json_decode($resp, true);
                if (isset($json['errcode']) && $json['errcode'] != 0) {
                    if ($json['errcode'] == '40001') {
                        //删除 accessToken
                        $this->accessTokenDeleteCache();
                        Lib_WeixinUserOauth2::getCI()->log->notice('WxUserOauth2','accessTokenDeleteCache 40001 | '.__METHOD__);
                    } else {
                        throw new Exception($json['errmsg'] . '[' . $json['errcode'] . ']');
                    }
                }
                if (isset($json['error']) && $json['error'] != '') {
                    throw new Exception($json['error']);
                }

                return $json;
            }
            $count++;
            usleep(500000);
        }
        throw new Exception('connect error.');
    }


    public function createAccessToken()
    {
        $url  = $this->apiSecretHost . '/cgi-bin/token?grant_type=client_credential&appid=' . $this->appId . '&secret=' . $this->appSecret;
        $json = $this->http('get', $url);
        if ($json !== false) {
            $token = $json['access_token'];
            $ttl   = $json['expires_in'];
            $this->accessTokenSaveCache($token, $ttl);
        } else {
            throw new Exception('connect error.');
        }

        return $token;
    }

    private function getToken()
    {
        $key         = Lib_WeixinUserOauth2::ACCESS_TOKEN_KEY;
        $repeat      = 10;
        $count       = 0;
        $isLock      = 1;
        do {
            $token = Lib_WeixinUserOauth2::getCI()->cache->memcached->get($key);
            if ($token === false) {
                Lib_WeixinUserOauth2::getCI()->log->notice('WxUserOauth2','getToken | cache accessToken = false | key['.$key.'] | '.__METHOD__);
                $lockKey = Lib_WeixinUserOauth2::ACCESS_TOKEN_LOCK;

                if(Lib_WeixinUserOauth2::getCI()->cache->memcached->get($lockKey) === false || $isLock == 0){
                    $isLock = 0;
                    Lib_WeixinUserOauth2::getCI()->cache->memcached->save($lockKey,1,3);
                    try{
                        $token = $this->createAccessToken();
                    }catch (Exception $e) {
                        Lib_WeixinUserOauth2::getCI()->log->notice('WxUserOauth2','getToken | create token exception | message['.$e->getMessage().'] | '.__METHOD__);
                    }

                    if ($token != false) {
                        return $token;
                    }
                }else{
                    Lib_WeixinUserOauth2::getCI()->log->notice('WxUserOauth2','getToken | create accessToken lock. | count['.$count.'] | '.__METHOD__);
                }
                usleep(500000);
            } else {
                Lib_WeixinUserOauth2::getCI()->log->notice('WxUserOauth2','getToken | cache accessToken success | accessToken['.$token.'] | '.__METHOD__);
            }
            $count++;
        } while ($token === false && $count < $repeat);

        return $token;
    }


    /**
     * 发送模板消息
     * @param string $openId
     * @param string $templateId
     * @param array  $data
     * @return bool
     */
    public function sendTemplateMsg($msg)
    {
        if (empty($msg['touser'])) {
            throw new Exception('openid not empty.');
        }
        if (empty($msg['template_id'])) {
            throw new Exception('template_id not empty.');
        }
        if (empty($msg['data'])) {
            throw new Exception('data not empty.');
        }

        $accessToken = $this->getToken();
        if (empty($accessToken)) {
            throw new Exception('get access_token error.');
        }
        $url  = $this->apiSecretHost . '/cgi-bin/message/template/send?access_token=' . $accessToken;
        $body = $this->encodeJson($msg);
        $json = $this->http('post', $url, $body);

        if ($json['errcode'] == 0) {
            return true;
        }

        return false;
    }

    public function accessTokenDeleteCache()
    {
        Lib_WeixinUserOauth2::getCI()->cache->memcached->delete(Lib_WeixinUserOauth2::ACCESS_TOKEN_KEY);
    }

    public function accessTokenSaveCache($token, $ttl)
    {
        Lib_WeixinUserOauth2::getCI()->cache->memcached->save(Lib_WeixinUserOauth2::ACCESS_TOKEN_KEY,$token,$ttl - 100);
    }

    private function encodeJson($arr, $parentKey = '')
    {
        $ret = array();
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                if (is_int($key)) {
                    $ret[] = $this->encodeJson($value, $key);
                } else {
                    $ret[] = '"' . $key . '":' . $this->encodeJson($value, $key);
                }
            } else {
                //$value = str_replace('{', '\{', $value);
                //$value = str_replace('}', '\}', $value);
                //$value = str_replace('[', '\[', $value);
                //$value = str_replace(']', '\]', $value);
                //$value = str_replace(',', '\,', $value);
                //$value = str_replace(':', '\:', $value);
                $value  = str_replace('"', '\"', $value);
                $ret [] = '"' . $key . '":"' . $value . '"';
            }
        }
        if (substr($parentKey, -6) !== 'button') {
            $retStr = '{' . join(',', $ret) . '}';
        } else {
            $retStr = '[' . join(',', $ret) . ']';
        }

        return $retStr;
    }

    /**
     * 获取JS_SDK jsapi_ticket
     * jsapi_ticket
     */
    public function getJsApiTicket()
    {
        $key = Lib_WeixinJssdk::WX_JSAPI_TICKET_KEY;
        $jsApiTicket = self::memcache_get($key);
        if ($jsApiTicket === false) {
            $accssToken = $this->getToken();
            if (empty($accssToken)) {
                throw new Exception('get access_token error.');
            }
            $lock_key = Lib_WeixinJssdk::WX_JSAPI_TICKET_LOCK_KEY;
            $repeat      = 10;
            $count       = 0;
            $can_opt     = 0; //能否操作
            do {
                if ($can_opt || self::memcache_get($lock_key) ===false) {//未加锁状态 可以执行操作
                    self::memcache_set($lock_key, 1, 30);//加锁
                    $can_opt = 1;
                    try {
                        $jsApiTicket = $this->createJsApiTicket($accssToken);
                    } catch (Exception $e) {
                        self::log('error','JSSDK', 'WXAPI | create jsapi_ticket error'.$e->getMessage());
                    }

                    if ($jsApiTicket != false) {
                        //清空锁定
                        self::memcache_delete($lock_key);
                        return $jsApiTicket;
                    }
                    if($count == 9) {
                        self::memcache_delete($lock_key);
                    }
                } else {
                    self::log('error','JSSDK', 'create jsapi_ticket lock'.$count);
                }
                $count++;
            } while ($jsApiTicket === false && $count < $repeat);
        }

        return $jsApiTicket;
    }

    /**
     * 获取jsapi_ticket值
     * @param $accessToken
     * @return mixed
     * @throws Exception
     */
    private function createJsApiTicket($accessToken)
    {
        $url  = $this->apiSecretHost . '/cgi-bin/ticket/getticket?access_token='.$accessToken.'&type=jsapi';
        $json = $this->http('get', $url);
        if ($json !== false) {
            $ticket = $json['ticket'];
            $ttl   = $json['expires_in'];
            $this->jsApiTicketSaveCache($ticket, $ttl);
        } else {
            throw new Exception('connect error.');
        }

        return $ticket;
    }

    /**
     * jsapi_ticket 删除缓存
     */
    public function jsApiTicketDeleteCache()
    {
        $key    = WTuan_Lib_KeyManager::GetWeixinJsApiTicketKey();
        $server = System_Lib_App::app()->getConfig('memcache');
        System_Lib_Memcache::Delete($key, $server);
    }

    /**
     * jsapi_ticket 缓存
     */
    public function jsApiTicketSaveCache($ticket, $ttl)
    {
        self::log('error','JSSDK', 'jsApiTicketSaveCache accessToken = ' . $ticket . ', expires_in = ' . ($ttl - 100));
        self::memcache_set(Lib_WeixinJssdk::WX_JSAPI_TICKET_KEY, $ticket,($ttl - 100));
    }

    /**
     * 1. 判断访问者是不是微信
     * 2. 判断访问者微信版本 等于/大于/小于/大于等于/小于等于 指定版本
     * @param string $ver // like "5.0.0.1"
     * @param string $opt // in array('eq', 'lte', 'gte', 'lt', 'gt')
     */
    public static function isFromWeixin($headers='', $ver='', $opt=''){
        $isFromWX = false;
        $opts = array('eq', 'lte', 'gte', 'lt', 'gt');
        if (empty($headers)) {
            $headers = getallheaders();
        }

        if (! empty($headers['User-Agent'])) {
            $ua = strtolower($headers['User-Agent']);
        } else if (! empty($headers['user-agent'])) {
            $ua = strtolower($headers['user-agent']);
        }

        if (empty($ua)) {
            return $isFromWX;
        }

        $wxkeyword = 'micromessenger';

        //1. 是不是微信
        if (strpos($ua, $wxkeyword) !== false || strpos($ua, 'wechatdevtools') !== false){
            $isFromWX = true;
        }

        if ($isFromWX && !empty($ver) && !empty($opt)){
            $arrWxVersion = explode(' ', strstr($ua, $wxkeyword));
            $arrWxVersionNo = explode('/', $arrWxVersion[0]);
            $curver = $arrWxVersionNo[1];
            if (!in_array($opt, $opts)){
                $isFromWX = false;
            }else{
                //2. 是不是指定版本的微信
                switch($opt){
                    case 'eq':
                        $isFromWX = version_compare($curver, $ver, '==');
                        break;
                    case 'lt':
                        $isFromWX = version_compare($ver, $curver, '<');
                        break;
                    case 'lte':
                        $isFromWX = version_compare($ver, $curver, '<=');
                        break;
                    case 'gt':
                        $isFromWX = version_compare($ver, $curver, '>');
                        break;
                    case 'gte':
                        $isFromWX = version_compare($ver, $curver, '>=');
                        break;
                }
            }
        }
        return $isFromWX;
    }

    public function valid($signature, $timestamp, $nonce)
    {
        return $this->checkSignature($signature, $timestamp, $nonce);
    }

    private function checkSignature($signature, $timestamp, $nonce)
    {
        $token  = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_weixin_config()
    {
        return self::ci()->config->item('weixinConfig');
    }

    public static function log($level, $type,  $msg)
    {
        return self::ci()->log->$level('WeiXin', $type .' | '.$msg);
    }

    public static function memcache_get($key)
    {
        return self::ci()->cache->memcached->get($key);
    }

    public static function memcache_delete($key)
    {
        return self::ci()->cache->memcached->delete($key);
    }

    public static function memcache_set($key, $data, $expired=600)
    {
        return self::ci()->cache->memcached->save($key, $data, $expired, TRUE);
    }

    private static $CI;

    public static function ci()
    {
        if(!self::$CI) {
            self::$CI = &get_instance();
            self::$CI->config->load('pay');
            self::$CI->load->driver('cache');
        }
        return self::$CI;
    }
}