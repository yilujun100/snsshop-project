<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 积分兑换订单表-model
 */
class Score_order_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_YYDB;         //分组名
    protected $table_name               =       't_score_order';       //表名
    protected $table_primary            =       'sOrderId';                 //主键
    protected $cache_row_key_column     =       'sOrderId';                 //缓存key字段  可自定义

    protected $columns = array('sOrderId','iUin','iActivityId','iCount','iUnitPrice','iTotalPrice','iCreateTime','iPlatForm','iPayTime','iPlatForm','iStatus','iOriPrice');
}