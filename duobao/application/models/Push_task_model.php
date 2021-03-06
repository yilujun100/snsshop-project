<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 消息推送任务表
 */
class Push_task_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_YYDB;               //分组名
    protected $table_name               =       't_push_task';    //表名
    protected $table_primary            =       'iAutoId';                      //主键
    protected $cache_row_key_column     =       'iAutoId';                     //缓存key字段  可自定义
    protected $need_cache_row           =        false;

    public function __construct()
    {
        parent::__construct();
    }
}