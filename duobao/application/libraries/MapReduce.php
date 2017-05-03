<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 简单实现数据库读写分离及多台配置
 * Class Map_reduce
 */
class Lib_MapReduce {

    private static $map_reduce = NULL;

    public static function init() {
        if (is_null(self::$map_reduce)) {
            $CI = & get_instance();
            $CI->config->load('database_group');
            self::$map_reduce = $CI->config->item('map_reduce');
        }
    }

    /**
     * 简单的读写分离及多服务器配置
     * @param $group_name
     * @param bool $is_write
     * @return string
     */
    public static function map($group_name, $is_write=FALSE) {
        self::init();
        if (is_array(self::$map_reduce) && isset(self::$map_reduce[$group_name])) {
            $group = self::$map_reduce[$group_name];
            if (is_string($group)) {
                return $group;
            }
            if(!is_array($group)) {
                return '';
            }

            $len = count($group);
            if ($len < 1) {
                return '';
            }

            if ($len == 1 || $is_write) {
                return $group[0];
            }

            $index = rand(1, ($len-1));
            return $group[$index];
        }
        return '';
    }
}