<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_service extends  MY_Service
{
    const COOKIE_USER_LOGIN     =       'uu';                            //用户cookie名称
    const COOKIE_USER_LOGIN_WTG =       'uw';                            //用户cookie名称 微团购用户
    const COOKIE_TTL            =       2592000;                        //用户cookie有效时间   3600*24*30 = 2592000
    const CACHE_TTL             =       2592000;                        //用户cache有效时间   3600*24*30 = 2592000

    const LOGIN_CRYPT_SALT      =       'QAZwsx!@#$%qazWSXedcRFV!@#$%EDCrfv';           //用户cookie加密key
    const SING_CRYPT_SALT       =       'wsxEDC!@#$%WSXedcQAZwsx!@#$%qazWSX';           //用户cookie中sign生成key
    const TOKEN_CRYPT_SALT      =       'edcRFV!@#$%EDCrfvwsxEDC!@#$%WSXedc';           //用户cookie中token生成key

    const CACHE_USER_PREFIX     =       'uu_';                                          //用户登录信息缓存前缀
    const VALIDATE_TIMEOUT       =       10;                                            //校验频率 防止快速重复刷新导致写cookie失败

    /**
     * 取微信用户[缓存或数据库]
     * @return bool
     */
    public function get_db_wx_user()
    {
        if ($uin = $this->valid_user_login()) {
            $this->load->model('user_model');
            if ($user_info = $this->user_model->get_user_by_uin($uin)) {
                return $this->user_model->format_wx_user($user_info);
            }
        }
        return false;
    }

    /**
     * 更新或添加微信用户到db
     * @param $wx_user
     */
    public function add_or_update_wx_user($wx_user)
    {
        if (empty($wx_user['openid'])) {
            $this->log->error('User', 'openid is empty |  params:'.json_encode($wx_user).' | '.__METHOD__);
            return Lib_Errors::OPENID_IS_EMPTY;
        }

        $this->load->model('user_model');
        if ($db_wx_user = $this->user_model->get_wx_user_by_openid($wx_user['openid'])) {
            $uin = $db_wx_user['iUin'];
            $ret = $this->user_model->update_wx_user($uin, $wx_user);
            if ($ret) {
                $this->load->model('wx_new_user_model');
                $this->wx_new_user_model->update_row(array('iStatus'=>Lib_Constants::STATUS_1,'iUpdateTime'=>time()), array('iUin'=>$uin,'iStatus'=>Lib_Constants::STATUS_0));
                return $uin;
            } else {
                $this->log->error('User', 'update wx user base failed | params:'.json_encode($wx_user).' | sql: '.$this->user_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }
        } else {
            return $this->add_wx_user($wx_user);
        }
    }

    /**
     * 更新或添加微信用户到db
     * @param $wx_user
     */
    public function add_or_update_wtg_user($wx_user)
    {
        if (empty($wx_user['openid'])) {
            $this->log->error('User', 'openid is empty |  params:'.json_encode($wx_user).' | '.__METHOD__);
            return Lib_Errors::OPENID_IS_EMPTY;
        }

        $this->load->model('weixin_user_model');
        if ($db_wx_user = $this->weixin_user_model->get_wx_user_by_openid($wx_user['openid'])) {
            $ret = $this->weixin_user_model->update_wtg_wx_user($db_wx_user['id'], $wx_user);
            if ($ret) {
                return $ret;
            } else {
                $this->log->error('User', 'update wtg wx user base failed | params:'.json_encode($wx_user).' | sql: '.$this->weixin_user_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }
        } else {
            return $this->weixin_user_model->add_wtg_wx_user($wx_user);
        }
    }


    /**
     * 添加微信新用户
     * @param $wx_user
     * @return int
     */
    public function add_wx_user($wx_user)
    {
        $this->load->model('user_model');
        if ($uin = $this->user_model->add_wx_user($wx_user)) {
            $this->load->model('user_ext_model');
            $data = array('iUin'=>$uin);
            if ($this->user_ext_model->add_row($data)) {
                return $uin;
            } else {
                $this->log->error('User', 'add wx user ext failed | uin: '.$uin.' | params:'.json_encode($wx_user).' | sql: '.$this->user_ext_model->db->last_query().' | '.__METHOD__);
                return Lib_Errors::SVR_ERR;
            }
        } else {
            $this->log->error('User', 'add wx user base failed | params:'.json_encode($wx_user).' | sql: '.$this->user_model->db->last_query().' | '.__METHOD__);
            return Lib_Errors::SVR_ERR;
        }
    }

    /**
     * 微信用户信息格式化
     * @param $wx_user
     */
    public function format_wx_user($wx_user)
    {
        $this->load->model('user_model');
        if ($db_wx_user = $this->user_model->get_wx_user_by_openid($wx_user['openid'])) {
            $uin = $db_wx_user['iUin'];
        } else {
            $uin = $this->user_model->add_wx_user($wx_user);
        }
        $wx_user = $this->user_model->format_wx_oauth2_user($wx_user);
        $wx_user['uin'] = $uin;
        return $wx_user;
    }

    /**
     * @param $uin
     */
    public function fresh_user_login($uin) {
        $sign = $this->generate_sign();
        $this->fresh_token($uin, $sign);
    }


    /**
     * 校验用户合法性
     * 比较cookie和缓存中的值进行校验
     * 校验通过返回用户uin
     * @return bool
     */
    public function valid_user_login()
    {
        $user_login_cookie = $this->get_user_login_cookie();
        if (!$user_login_cookie) {
            //$this->log->error('User', 'login cookie not exist | cookie:'.json_encode($user_login_cookie));
            return false;   //cookie失效 认定不合法
        }
        list($uin_c, $sign_c, $token_c, $time_c, $timeout_c) = $user_login_cookie;
        $user_login_server = $this->get_user_login_cache($uin_c);
        if (!$user_login_server) {
            /*
            $user_login_server = $this->get_user_login_db($uin); //缓存失效 从数据库查询
            if (!$user_login_server) {
                return false; //服务端信息失效 认定为不合法
            }
            */
            $this->log->error('User', 'login caceh server not exist | cookie:'.json_encode($user_login_server));
            return false;
        }
        list($uin_s, $sign_s, $token_s, $time_s, $timeout_s) = $user_login_server;

        /*
        if ($uin_c != $uin_s || $sign_c != $sign_s || $token_c != $token_s || $time_c != $time_s || $timeout_c!= $timeout_s) {
            $this->log->error('User', 'User check login failed | cookie:'.json_encode($user_login_cookie).' | cache:'.json_encode($user_login_server));
            return false;
        }
        $now = time();
        if (($now-$time_c) > self::VALIDATE_TIMEOUT) {
            $this->fresh_token($uin_c, $sign_c);
        }
        */
        return $uin_c;
    }

    /**
     * 取用户登录cookie
     * @return string
     */
    private function get_user_login_cookie()
    {
        $user_login_cookie = get_cookie(self::COOKIE_USER_LOGIN, false);
        if (!$user_login_cookie) {
            return false;
        }

        return $this->valid_login_info($user_login_cookie);
    }

    /**
     * 取用户登录cache
     * @return string
     */
    private function get_user_login_cache($uin)
    {
        if (!$this->valid_uin($uin)) {
            return false;
        }
        $cache_key = self::CACHE_USER_PREFIX.$uin;
        $user_login_cache = $this->cache->memcached->get($cache_key);
        if(!$user_login_cache) {
            return false;
        }

        return $this->valid_login_info($user_login_cache);
    }

    /**
     * 取用户登录cache
     * @return string
     */
    private function get_user_login_db($uin)
    {
        if (!$this->valid_uin($uin)) {
            return false;
        }
        $cache_key = $this->generate_login_cache_key($uin);
        $user_login_cache = $this->cache->memcached->get($cache_key);
        if(!$user_login_cache) {
            return false;
        }

        return $this->valid_login_info($user_login_cache);
    }

    /**
     * cookie中存储用户登录
     * @param $cookie_str
     */
    private function set_user_login_cookie($cookie_str)
    {
        $domain = get_first_domain($_SERVER['HTTP_HOST']);
        return set_cookie(self::COOKIE_USER_LOGIN, $cookie_str, self::COOKIE_TTL, $domain);
    }

    /**
     * 微团购用户uin存储cookie
     * @param $cookie
     */
    public function set_wtg_user_login_cookie($openid)
    {
        $domain = get_first_domain($_SERVER['HTTP_HOST']);
        $openid = str_encode($openid, self::LOGIN_CRYPT_SALT);
        return set_cookie(self::COOKIE_USER_LOGIN_WTG, $openid, self::COOKIE_TTL, $domain);
    }

    /**
     * 微团购用户uin存储cookie
     * @param $cookie
     */
    public function get_wtg_user_login_cookie()
    {
        $cookie = get_cookie(self::COOKIE_USER_LOGIN_WTG);
        return $cookie ? str_decode($cookie, self::LOGIN_CRYPT_SALT) : false;
    }

    /**
     * 缓存用户登录信息
     * @param $uin
     * @param $cookie_str
     */
    private function set_user_login_cache($uin, $cookie_str)
    {
        $cache_key = $this->generate_login_cache_key($uin);
        //return $this->cache->memcached->save($cache_key, $cookie_str, time()-36000);
        return $this->cache->memcached->save($cache_key, $cookie_str, self::CACHE_TTL);
    }

    /**
     * 刷新登录token
     * @param $uin
     * @param $sign
     * @return bool
     */
    private function fresh_token($uin, $sign)
    {
        $new_token = $this->generate_token();
        $login_str = $this->generate_login_str($uin, $sign, $new_token);
        $this->log->error('UserService','fresh_token | cache:'.$uin.'-'. $sign.'-'.$new_token);
        if($this->set_user_login_cache($uin, $login_str)){
            $this->set_user_login_cookie($login_str);
        } else {
            $this->log->error('UserService','cache failed | cookie:'.$login_str.',cache:'.$uin.'-'. $sign.'-'.$new_token);
        }
        return true;
    }



    /**
     * 用户登录态信息存储格式
     * @param $uin
     * @param $sign
     * @param $token
     * @return string
     */
    private function generate_login_str($uin, $sign, $token)
    {
        return str_encode($uin.'_'.$sign.'_'.$token.'_'.time().'_'.self::VALIDATE_TIMEOUT, self::LOGIN_CRYPT_SALT);
    }


    /**
     * 统一获取登录缓存key
     * @param $uin 用户ID
     * @return string
     */
    private function generate_login_cache_key($uin)
    {
        return self::CACHE_USER_PREFIX.$uin;
    }

    /**
     * 校验用户登录信息合法
     * @param $login_info
     * @return array|bool|string
     */
    private function valid_login_info($login_info)
    {
        $login_info = str_decode($login_info, self::LOGIN_CRYPT_SALT);
        if (!$login_info) {
            return false;
        }
        $login_info = explode('_', $login_info);
        if (count($login_info) != 5) {
            return false;
        }
        if (!$this->valid_uin($login_info[0])) {
            return false;
        }
        return $login_info;
    }

    /**
     * uin校验 只做格式校验 不做存在校验
     * @param $uin
     * @return bool
     */
    private function valid_uin($uin)
    {
        return (!$uin || !is_numeric($uin)) ? false : true;
    }

    /**
     * 生成唯一登录序列
     * @return string
     */
    private static function generate_sign()
    {
        return md5(self::SING_CRYPT_SALT.'_'.microtime(true).'_'.rand());
    }

    /**
     * 生成唯一token
     * @return string
     */
    private static function generate_token()
    {
        return md5(self::SING_CRYPT_SALT.'_'.microtime(true).'_'.rand());
    }
}