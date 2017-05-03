<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Active_daily_model 夺宝全盘统计
 */
class Active_daily_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_STATISTICS;     //分组名
    protected $table_name = 't_active_daily';                //表名
    protected $table_primary = 'iAutoId';                    //主键
}