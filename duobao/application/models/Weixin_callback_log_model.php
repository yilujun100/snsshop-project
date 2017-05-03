<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * mptools微信用户回调-model
 */
class Weixin_callback_log_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_MPTOOLS;         //分组名
    protected $table_name               =       'weixin_callback_log';                   //表名
    protected $table_primary            =       'id';                     //主键
    protected $need_cache_row           =       false;


}