<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 统计
 * User: alanwang
 * Date: 2016/6/07
 * Time: 11:45
 */

class Real_time_order_detail extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB_STATISTICS; // 分组名
    protected $db_name = 'yydb_statistics';              //库名
    protected $table_name  = 't_real_time_order_detail'; // 表名
    protected $db_num = 1;                          //分库数
    protected $table_num = 1;                       //分表数

}