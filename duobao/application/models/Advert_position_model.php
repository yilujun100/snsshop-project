<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Advert_position_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_advert_position';   //表名
    protected $table_primary = 'iPositionId';   //表名主键名称
    protected $cache_row_key_column = 'iPositionId'; //单条表记录缓存字段
    protected $auto_update_time = true; //自动更新createtime 或updatetime
    protected $can_real_delete = true;   //允许真删除
}