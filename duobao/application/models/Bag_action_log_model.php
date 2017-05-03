<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 福袋模型-model
 */
class Bag_action_log_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;      //分组名
    protected $table_name = 't_bag_action_log';              //表名
    protected $table_primary = 'iLogId';                //主键
    protected $cache_row_key_column = 'iLogId';         //缓存key字段  可自定义
    protected $need_cache_row = FALSE;                  //缓存key字段  可自定义
    protected $auto_update_time = false;                //添加或修改时自动更新createtime 或updatetime
    protected $can_real_delete = false;                 //允许真删除
    protected $table_num = 10;
    protected $db_map_column = 'iBagId';

    protected $columns = array('iLogId','iUin','iBagId','iAction','iNum','sExtend','iAddTime','iType','sNickName','sHeadImg');

    public function get_action_log_list($params, $order_by, $p_index=1, $p_size=10)
    {
        return $this->row_list(implode(',',$this->columns), $params, $order_by, $p_index, $p_size);
    }

    /**
     * 验证用户是否已领取
     * @param $uin
     * @param $bag_id
     * @param $to_uin
     */
    public function is_user_got_bag($uin, $bag_id, $to_uin)
    {
        $params = array(
            'iUin' => $uin,
            'iBagId' => $bag_id,
            'iAction' => Lib_Constants::ACTION_USE_COUPON,
            'sExtend' => $to_uin
        );

        return $this->bag_action_log_model->row_count($params);
    }
}