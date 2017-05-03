<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/***
 * 自动发码脚本，每30秒跑一次
 * @autor leo.zou
 * @date 2016-03-18
 */


class User_cache extends Script_Base
{
    const LIMIT = 10000;  //每次脚本处理的订单数

    protected $log_type = 'User_cache';
    private $cache_config = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('weixin_user_model');
        $this->load->driver('cache');
        $this->init_config();
    }


    private function init_config()
    {
        $this->cache_config = array(
           'get_wx_user' => array('uri'=>'user/get_wx_user', 'prefix'=>'wx_user_', 'cache_column' => array('openid'), 'open' => 1,  'ttl'=>86400*10),             //更新微信用户信息
            'get_wtg_wx_user' => array('uri'=>'user/get_wtg_wx_user', 'cache_column' => array('openid'), 'prefix'=>'wtg_user_', 'open' => 1, 'ttl'=>86400*10),     //更新微信用户信息
        );
    }

    public function run()
    {

        $max_page = 15;
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        $this->log('config:'.json_encode($this->cache_config));
        $this->log("----------------------------------------------list start-----------------------------------------------------------------");
        //循环取用户
        for ($page_index=1; $page_index<=$max_page; $page_index++) {
            $start = ($page_index-1)*self::LIMIT;
            $sql = 'select * from weixin_user order by loginTime desc limit '.$start.','.self::LIMIT;
            $row_list = $this->weixin_user_model->query($sql);
            if(!empty($row_list)) {
                $this->log('get row list | success | page index['.$page_index.']');
                foreach ($row_list as $row) {
                    //缓存微团购用户
                    $this->cache_wtg_user($row);
                    //缓存夺宝用户
                    $this->cache_wx_user($row);
                }
            } else {
                $this->log('get row list | failed | page index['.$page_index.'] | sql['.$sql.']');
            }
        }
        $this->log("----------------------------------------------list end-----------------------------------------------------------------");
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
        echo "DONE!!!!";
    }

    private function cache_wx_user($wtg_row)
    {

        $row = $this->user_model->get_row(array('sOpenId'=>$wtg_row['openid']));
        if(!$row) {
            $this->log('cache wx user | get duobao user | failed | openid['.$wtg_row['openid'].'] | sql['.$this->user_model->db->last_query().']');
        } else {
            $data = array(
                'iUin'=>$row['iUin'],
                'sNickName'=>$row['sNickName'],
                'iContactState'=>$row['iContactState'],
                'sHeadImg'=>$row['sHeadImg'],
                'sCity'=>$row['sCity'],
                'sProvince'=>$row['sProvince'],
                'sCountry'=>$row['sCountry'],
            );
            $params = array('openid'=>$wtg_row['openid']);
            $conf = $this->cache_config['get_wx_user'];
            $cache_key = $this->get_memcache_key($params, $conf);
            if ($this->cache->memcached->save($cache_key, json_encode($data), $conf['ttl'])) {
                $this->log('cache wx user | success | openid['.$wtg_row['openid'].']');
            } else {
                $this->log('cache wx user | failed | openid['.$wtg_row['openid'].']');
            }
        }
    }

    //缓存微团购用户
    private function cache_wtg_user($row)
    {
        $params = array('openid'=>$row['openid']);
        $conf = $this->cache_config['get_wtg_wx_user'];
        $cache_key = $this->get_memcache_key($params, $conf);
        if ($this->cache->memcached->save($cache_key, json_encode($row), $conf['ttl'])) {
            $this->log('cache wtg user | success | openid['.$row['openid'].']');
        } else {
            $this->log('cache wtg user | failed | openid['.$row['openid'].']');
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
}