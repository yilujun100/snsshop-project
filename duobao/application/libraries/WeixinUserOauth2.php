<?php


class Lib_WeixinUserOauth2
{
    const AUTH2_SCOPE_USER = 'snsapi_userinfo';//需要用户确认的授权
    const AUTH2_SCOPE_BASE = 'snsapi_base';//无须用户确认的授权
    const COOKIE_TTL     = 604800; //用户cookie有效时间   3600*24*7 = 604800
    const REDIRECT_TTL   = 100;

    const YYDB_KEY        = 'anakinli_f_qq_tuan_baby_2012_c++_linux';
    const REFRESH_TOKEN = 'WeixinOauth2Refreshtoken-';  //refresh token 缓存key
    const ACCESS_TOKEN = 'WeixinUserOauth2accesstokenKey';
    const ACCESS_TOKEN_KEY = 'WeixinAccessToken';
    const ACCESS_TOKEN_LOCK = 'WeixinAccessTokenLock';


    public static function getCI()
    {
        $CI = &get_instance();
        $CI->load->driver('cache');

        return $CI;
    }


    /**
     * 获取用户缓存openId
     * @return string
     */
    public static function getUserOpenId(){
        $open_id = get_cookie('yydb_openid');
        if(!empty($open_id)){
            $open_id = str_decode($open_id, self::YYDB_KEY);
        }

        return empty($open_id) ? null : $open_id;
    }


    //跳转到用户授权界面
    public static function redirectAuth($backUrl = '',$scope = self::AUTH2_SCOPE_USER)
    {
        get_instance()->config->load('pay');//加载微信配置文件
        $wxconfig = config_item('weixinConfig');
        $backUrl || ($backUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        $redirectUrl  = implode('', array(
            'https://open.weixin.qq.com/connect/oauth2/authorize?appid=', $wxconfig['appId'],
            '&redirect_uri=', urlencode($backUrl),
            '&response_type=code&scope=', $scope, '&state=', time(),
            '#wechat_redirect'
        ));

        redirect($redirectUrl);
    }


    /**
     * 通过微信页面授权取用户信息
     * @param $code
     * @param $state
     */
    public static function getWxUserOauth2($code, $state)
    {
        if(!$code || !$state) {
            self::getCI()->log->notice('WxUserOauth2','params code or state error | '.__METHOD__);
            return null;
        }

        // 1. get access token
        get_instance()->config->load('pay');//加载微信配置文件
        $wxconfig = config_item('weixinConfig');
        try {
            $auth2accesstokens = Lib_Weixin::portal($wxconfig)->auth2accesstoken($code);

            if ($auth2accesstokens === false || is_null($auth2accesstokens)) {
                self::getCI()->log->notice('WxUserOauth2','get auth2accesstoken by code fail | code['.$code.'] | '.__METHOD__);
                return null;
            }
            self::getCI()->log->notice('WxUserOauth2','get auth2accesstoken by code success | code['.$code.'] | auth2accesstokens['.json_encode($auth2accesstokens).'] | '.__METHOD__);
        } catch (Exception $e) {
            self::getCI()->log->notice('WxUserOauth2','get access_token exception | exception['.$e->getMessage().'] | '.__METHOD__);
            return null;
        }

        // 3. save to db, cache access token and refresh token to memcache
        self::cacheAccessRefreshToken(
            $auth2accesstokens['openid'],
            $auth2accesstokens['access_token'],
            $auth2accesstokens['refresh_token'],
            $auth2accesstokens['expires_in']
        );

        return self::getUserInfoOauth2($auth2accesstokens);
    }


    /**
     * 根据openid取用户信息
     */
    public static function getWxUserByOpenId($openId)
    {
        $wxuser = array();
        if ($openId){
            get_instance()->config->load('pay');//加载微信配置文件
            $wxconfig = config_item('weixinConfig');
            try{
                $wxuser = Lib_Weixin::portal($wxconfig)->getUserInfo($openId);
                self::getCI()->log->notice('WxUserOauth2','getUserInfo from weixin api | wxuser['.json_encode($wxuser).'] | '.__METHOD__);
            } catch (Exception $e) {
                self::getCI()->log->notice('WxUserOauth2','getUserInfo from weixin Exception | openId['.$openId.'] | '.__METHOD__);
            }
        }

        return $wxuser;
    }



    public static function cacheUserOauth2RefreshToken($openid, $refresh_token, $expire = 720000)
    {
        $cacheKey    = self::getWeixinUserCacheKey(self::REFRESH_TOKEN,$openid);
        self::getCI()->cache->memcached->save($cacheKey, $refresh_token, $expire);
    }

    public static function cacheUserOauth2Accesstoken($openid, $access_token, $expire = 7200)
    {
        $cacheKey    = self::getWeixinUserCacheKey(self::ACCESS_TOKEN,$openid);
        self::getCI()->cache->memcached->save($cacheKey, $access_token, $expire);
    }


    /**
     * y统一获取对应的cache key
     * @param $prefix
     * @param $key
     * @return string
     */
    public static function getWeixinUserCacheKey($prefix,$key)
    {
        return $prefix.$key;
    }




    private static function cacheAccessRefreshToken($openId, $accessToken, $refreshToken, $accessTokenExpire)
    {
        self::cacheUserOauth2RefreshToken($openId, $refreshToken, 7 * 86400);//refresh_token 7天有效期
        self::cacheUserOauth2Accesstoken($openId, $accessToken, ($accessTokenExpire - 100));//适当减短一点
        return;
    }



    private static function getUserInfoOauth2($auth2accesstokens)
    {
        if (!$auth2accesstokens) {
            return null;
        }

        set_cookie('wtg_openid', str_encode($auth2accesstokens['openid'],  self::YYDB_KEY), time() + self::COOKIE_TTL, get_first_domain($_SERVER['HTTP_HOST']));
        return self::getWxUserByOpenId($auth2accesstokens['openid']);
    }

}