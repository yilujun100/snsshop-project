<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 数据库中tinyint字段键值映射
 * Class Lib_DataFormat
 */
class Lib_DataFormat {
    public static function active_detail($data)
    {
        $ret = array();
        $data = isset($data['retData']) ? $data['retData'] : $data;
        if (!empty($data)) {
            $tmp = isset($data['list']) ? $data['list'] : $data;
            $ci = & get_instance();

            $ci->load->config('api');
            $conf = $ci->config->item('api_map');
            $conf = $conf['active_detail'];
            $ci->load->driver('cache');

            foreach ($tmp as $item) {
                $key = Lib_CacheUtils::get_api_cache_key(array('act_id'=>$item['iActId'], 'peroid'=>$item['iPeroid']), $conf);
                $cache_item = $ci->cache->memcached->get($key);
                if ($cache_item) {
                    $ret[] = json_decode($cache_item, true);
                } else {
                    $detail = $ci->get_api('active_detail', array('act_id'=>$item['iActId'], 'peroid'=>$item['iPeroid']));
                    if (!empty($detail['retData'])) {
                        $ret[] = $detail['retData'];
                    }
                }
            }
        }
        if (isset($data['list'])) {
            $data['list'] = $ret;
            return $data;
        } else {
            return $ret;
        }
    }

    public static function active_detail11($data)
    {
        $ret = array();
        $data = isset($data['retData']) ? $data['retData'] : $data;
        if (!empty($data)) {
            $tmp = isset($data['list']) ? $data['list'] : $data;
            $ci = & get_instance();

            $ci->load->config('api');
            $conf = $ci->config->item('api_map');
            $conf = $conf['active_detail'];
            $ci->load->driver('cache');

            foreach ($tmp as $item) {
                $key = Lib_CacheUtils::get_api_cache_key(array('act_id'=>$item['iActId'], 'peroid'=>$item['iPeroid']), $conf);
                $cache_item = $ci->cache->memcached->get($key);
                if ($cache_item) {
                    $ret[] = json_decode($cache_item, true);
                } else {
                    $detail = $ci->get_api('active_detail', array('act_id'=>$item['iActId'], 'peroid'=>$item['iPeroid']));
                    if (!empty($detail['retData'])) {
                        $ret[] = $detail['retData'];
                    }
                }
            }
        }
        if (isset($data['list'])) {
            $data['list'] = $ret;
            return $data;
        } else {
            return $ret;
        }
    }

    public static function active_detail1($data, $order_by=array(), $p_index=0, $p_size=10)
    {
        $ret = array();
        $data = isset($data['retData']) ? $data['retData'] : $data;
        if (!empty($data)) {
            $tmp = isset($data['list']) ? $data['list'] : $data;
            $ci = & get_instance();
            $order = array();
            $i = 0;
            foreach ($tmp as $item) {
                $detail = $ci->get_api('active_detail', array('act_id'=>$item['iActId'], 'peroid'=>$item['iPeroid']));
                $i++;
                if (!empty($detail['retData'])) {
                    $ret[] = $detail['retData'];
                    unset($detail['retData']);
                    /*
                    if($order) {
                        foreach($order_by as $k => $v) {
                           $order[$k]['list'][] = $detail['retData'][$k];
                            if (empty($order[$k]['order_by'])) {
                                $order[$k]['order_by'] = strtolower($v) == 'desc' ? SORT_DESC : SORT_ASC;
                            }
                        }
                    }
                    */
                }

            }

            //排序
            if ($order) {
                $parmas = array();
                foreach ($order as $v) {
                    $parmas[] = $v['list'];
                    $parmas[] = $v['order_by'];
                }
                $parmas[] = $ret;
                $ret = call_user_func('array_multisort',$parmas);
            }

            //分页
            if ($p_index && $p_size) {
                $offset = ($p_index-1)*$p_size;
                $ret = array_slice($ret, $offset, $p_size);
            }
        }
        if (isset($data['list'])) {
            $data['list'] = $ret;
            return $data;
        } else {
            return $ret;
        }
    }
}