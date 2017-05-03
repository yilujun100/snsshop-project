<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 变量配置-model
 */
class Variable_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;         //分组名
    protected $table_name = 't_variable';             //表名
    protected $table_primary = 'sKey';               //主键
    protected $cache_row_key_column = 'sKey';        //缓存key字段
    protected $auto_update_time = false;           //自动更新createtime 或updatetime
    protected $need_cache_row = true;
}