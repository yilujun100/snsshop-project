<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Active_tag_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_active_tag';            //表名
    protected $table_primary = 'iTagId';               //主键
    protected $cache_row_key_column = 'iTagId';        //缓存key字段  可自定义
    protected $can_real_delete = true;                 //允许真删除
}