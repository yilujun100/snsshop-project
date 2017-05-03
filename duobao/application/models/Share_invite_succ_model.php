<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 分享有礼 - 邀请成功model
 */
class Share_invite_succ_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_ACTIVE;          //分组名
    protected $table_name = 't_share_invite_succ';                //表名
    protected $table_primary = 'iAutoId';                   //主键

    protected $need_cache_row = true;
    protected $cache_row_expired = 691200;
    protected $cache_key_prefix = 'share_invite_';
    protected $cache_row_key_column     =       'iToUin';                     //缓存key字段  可自定义
}