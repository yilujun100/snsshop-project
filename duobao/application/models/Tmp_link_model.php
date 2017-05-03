<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tmp_link_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB_TMP; //分组名
    protected $auto_update_time = TRUE; // 自动更新createtime或updatetime
}