<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 微团购魔法森林活动-model
 */
class Active_luckycode_config_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_WTG_WKD;               //分组名
    protected $table_name               =       't_active_luckycode_config';    //表名
    protected $table_primary            =       'iAutoId';                      //主键
    protected $cache_row_key_column     =       'iAutoId';                     //缓存key字段  可自定义
    protected $need_cache_row           =        false;
}