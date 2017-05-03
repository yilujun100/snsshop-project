<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 消息模板 model
 *
 * Class Message_template_model
 */
class Message_template_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; // 分组名
    protected $table_name  = 't_message_template'; // 表名
    protected $table_primary = 'iTempId'; // 主键
    protected $cache_row_key_column  = 'iTempId'; // 缓存key字段
    protected $table_num = 1;
    protected $auto_update_time = true; // 自动更新createtime 或updatetime
}