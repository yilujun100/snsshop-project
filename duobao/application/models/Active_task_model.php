<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Active_task_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_ACTIVE;          //分组名
    protected $table_name = 't_active_task';          //表名
    protected $table_primary = 'sKey';                //主键
    protected $cache_row_key_column = 'sKey';         //缓存key字段  可自定义
    protected $table_num= 1;
    protected $auto_update_time = false; //自动更新createtime 或updatetime
    protected $need_cache_row = false;
    protected $can_real_delete = true;

    /**
     * Active_task_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}