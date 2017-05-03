<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 系统消息 model
 *
 * Class Message_model
 */
class Message_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB_USER; // 分组名
    protected $table_name  = 't_message'; // 表名
    protected $table_primary = 'iMsgId'; // 主键
    protected $cache_row_key_column  = 'iToUin'; // 缓存key字段
    protected $table_num = 10;
    protected $logic_group = LOGIC_GROUP_USER;
    protected $db_map_column = 'iToUin'; //分表字段
    protected $auto_update_time = true; //自动更新createtime 或updatetime
}