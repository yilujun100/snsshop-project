<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Luckycode_summary_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_ACTIVE;         //分组名
    protected $table_name = 't_luckycode_summary';           //表名
    protected $table_primary = 'sOrderId';                   //主键
    protected $cache_row_key_column = 'iUin';                //缓存key字段  可自定义
    protected $table_num = 10;
    protected $db_map_column = 'iActId';                    //分表字段

    public function __construct()
    {
        parent::__construct();
    }
}