<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Luckycode_winner_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_luckycode_winner';                //表名
    protected $table_primary = 'iActId';                   //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num= 1;

    public function __construct()
    {
        parent::__construct();
    }




}