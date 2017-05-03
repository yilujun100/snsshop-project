<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户签到表-model
 */
class User_sign_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_user_sign';                  //表名
    protected $table_primary = 'iAutoId';                   //主键
    protected $cache_row_key_column = 'iAutoId';            //缓存key字段  可自定义
    protected $table_num = 10;
    protected $db_map_column = 'iUin';

    /**
     * 取用户信息
     * @param $platform
     */
    public function  add_sign($uin, $time, $platform=Lib_Constants::PLATFORM_WX)
    {
        $data = array(
            'iUin' => $uin,
            'iSignTime' => $time,
            'iPlatForm' => $platform,
        );
        return  $this->add_row($data);
    }

    /**
     * @param $uin
     */
    public function get_user_ext_info($uin, $platform)
    {
        $user_info = $this->get_user_by_uin($uin);
        if (empty($user_info)) {
            return false;
        }
        switch ($platform) {
            case Lib_Constants::PLATFORM_WX:
                return $this->wx_user_format($user_info);
                break;
            default:
                return $this->wx_user_format($user_info);
                break;
        }
    }
}