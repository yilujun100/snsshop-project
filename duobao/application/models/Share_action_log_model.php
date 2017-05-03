<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 晒单用户操作日志表[晒单维度]-model
 */
class Share_action_log_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;               //分组名
    protected $table_name = 't_share_action_log';                //表名
    protected $table_primary = 'iLogId';                 //主键
    protected $table_num = 10;
    protected $db_map_column = 'iShareId';


    public function get_list($share_id, $platform, $type, $p_cur=1, $psize=10)
    {
        $params = array(
            'iShareId' => $share_id,
            //'iType' => $type,
            'iPlatForm' => $platform
        );

        $row_list = $this->row_list('*', $params, 'order by iOptTime desc', $p_cur, $psize);
        $row_list['list'] = $this->format_action_list($row_list['list']);
        return $row_list;
    }

    private function format_action_list($list)
    {
        return $list;
    }
}