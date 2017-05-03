<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * mptools微信用户-model
 */
class Tuan_coupon_order_model extends MY_Model
{
	protected $db_num = 10;
    protected $db_group_name            =       DATABASE_WTG_ACTIVE;               //分组名
    protected $table_name               =       't_coupon_order';                   //表名
    protected $table_primary            =       'iUin';                           //主键
    protected $cache_row_key_column     =       'iUin';                     //缓存key字段  可自定义
    protected $need_cache_row           =       false;
	protected $table_num= 10;
    protected $db_map_column = array('iUin', 'sOrderId');  //分表字段


    
}