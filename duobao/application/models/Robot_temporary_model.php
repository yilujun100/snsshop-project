<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Robot_temporary_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_ACTIVE;          //分组名
    protected $table_name = 't_robot_temporary';          //表名
    protected $table_primary = 'sOrderId';                //主键
    protected $cache_row_key_column = 'sOrderId';         //缓存key字段  可自定义
    protected $table_num= 10;
    protected $auto_update_time = false; //自动更新createtime 或updatetime
    protected $need_cache_row = false;
    protected $db_map_column = 'iActId';  //分表字段

    /**
     * Robot_temporary_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }




}