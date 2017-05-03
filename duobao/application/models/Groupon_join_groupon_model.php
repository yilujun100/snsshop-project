<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 参团-diy model
 *
 * Class Groupon_join_groupon_model
 */
class Groupon_join_groupon_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB_ACTIVE; // 分组名
    protected $table_name  = 't_groupon_join'; // 表名
    protected $table_primary = 'iJoinId'; // 主键
    protected $cache_row_key_column  = 'iJoinId'; // 缓存key字段
    protected $table_num = 10;
    protected $db_map_column = 'iGrouponId'; //分库分表字段
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime
}