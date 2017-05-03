<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 新用户用户-model
 */
class WX_new_user_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_YYDB_USER;         //分组名
    protected $table_name               =       't_wx_new_user';                   //表名
    protected $table_primary            =       'iAutoId';                     //主键
    protected $table_num                =       1;

    /**
     * 取用户信息
     * @param $platform
     */
    public function get_user_by_uin($uin)
    {
        return  $this->get_row(array('iUin'=>$uin));
    }

    /**
     * 取用户信息
     * @param $platform
     */
    public function get_user_by_openid($openid)
    {
        return  $this->get_row($openid);
    }
}