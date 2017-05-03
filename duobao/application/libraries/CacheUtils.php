<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 处理缓存
 * Class Lib_CacheUtils
 */
class Lib_CacheUtils {
    private static $cache_map = array(
        'active_list' => array(
            'history_peroid' => array(), //已揭晓或即将揭晓
            'active_msg' => array(), //广播消息
            'active_crazy' => array(),//首页最后疯抢
            'active_free' => array(),//0元夺宝列表
            'active_zone' => array(),  //1元区/10元区/苹果专区
            'active_past' => array(),//当个活动的往期
            'active_winner' => array(),//所有活动中奖往期
            'my_active_winner' => array(),//用户所有中奖往期
            'active_currect_list' => array(), //当前可用活动列表
        )
    );

    /**
     * 更新夺宝活动相关缓存
     * @param $api_key
     */
    public static function update_active_list_cache($params=array())
    {
        $list = self::$cache_map['active_list'];
        foreach ($list as $key=>$val) {
            $is_pagination = empty($val['is_p']) ? false : true;
            $params = array_merge($val, $params);
            self::delete_api_cache($key, $params, $is_pagination);
        }
    }

    public function update_winner_list_cache()
    {

    }

    /**
     * Lib_CacheUtils
     * 更新api端缓存
     * @param $api_key api 映射key
     * @param $params 参数
     * @param $is_pagination 是否是分页列表
     */
    private static function delete_api_cache($api_key, $params=array(), $is_pagination=false)
    {
        $ci = & get_instance();
        $ci->log->notice('CacheUtils', 'update_api_cache | key['.$api_key.']');
        $ci->load->config('api');
        $api_map = self::$ci->config->item('api_map');
        if (empty($api_map) || empty($api_map[$api_key])) {
            $ci->log->error('CacheUtils', 'get api map conf failed | key['.$api_key.']');
        }
        $conf = $api_map[$api_key];

        if (isset($conf['cache_column']) && isset($conf['p_index']) && $is_pagination) { // 分页清除缓存
            for ($i=1; $i<=10; $i++) {
                $params['p_index'] = $i;
                $cache_key = self::get_api_cache_key($params, $conf);
                if ($cache_key) {
                    $ci->log->error('CacheUtils', 'get cache key failed | key['.$api_key.']');
                }
                $ci->load->driver('cache');
                $ci->cache->memcached->delete($cache_key);
            }
        } else {
            $cache_key = self::get_api_cache_key($params, $conf);
            if ($cache_key) {
                $ci->log->error('CacheUtils', 'get cache key failed | key['.$api_key.']');
            }
            $ci->load->driver('cache');
            $ci->cache->memcached->delete($cache_key);
        }
    }

    /**
     * 获取api接口调用缓存Key
     * @param $params
     * @param $conf
     * @return bool|string
     */
    public static function get_api_cache_key($params, $conf)
    {
        if (!empty($conf['cache_key'])) { //有设置缓存key 直接使用缓存key
            return $cache_key = $conf['cache_key'];
        } else if (!empty($conf['cache_column'])) { //有缓存字段
            $cache_key = array();
            if (is_array($conf['cache_column'])) {
                foreach ($conf['cache_column'] as $column) {
                    if (!isset($params[$column])) {
                        if($column == 'p_index') {
                            $cache_key[$column] = 1;
                        } elseif ($column == 'p_size') {
                            continue;
                        } else {
                            $cache_key = array();
                            break;
                        }
                    } else {
                        $cache_key[$column] = $params[$column];
                    }
                }
            }
            if ($cache_key) {
                $cache_key = implode('_', $cache_key);
            }
        } else { //其他组装参数作为key
            $cache_key = md5(http_build_query($params));
        }

        if (!$cache_key) {
            return false;
        }

        return empty($conf['prefix']) ? ($conf['uri'].'_'.$cache_key) : ($conf['prefix'].$cache_key);
    }
}