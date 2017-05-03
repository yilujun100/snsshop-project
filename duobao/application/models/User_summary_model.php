<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_summary_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_ACTIVE;            //分组名
    protected $table_name = 't_user_summary';           //表名
    protected $table_primary = '';                              //主键
    protected $table_num = 10;
    protected $db_num = 10;
    protected $db_map_column = 'iUin';                   //分表字段
}