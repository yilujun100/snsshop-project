<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 晒单用户操作日志表[用户维度]-model
 */
class Share_user_action_log_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;               //分组名
    protected $table_name = 't_share_user_action_log';                //表名
    protected $table_primary = 'iLogId';                 //主键
    protected $table_num = 10;
    protected $db_map_column = 'iUin';


    public function get_list($uin, $platform, $type, $p_cur=1, $psize=10)
    {
        $params = array(
            'iUin' => $uin,
            'iType' => $type,
            'iPlatForm' => $platform
        );

        $row_list = $this->row_list('iLogId,iUin,iType,iActId,iPeriod,sGoodsName,iLuckyCode,iLotTime,iLotCount,iWinnerId,iPlatForm,iIp,sLocation,iOptTime,iShareId', $params, 'order by iOptTime desc', $p_cur, $psize);
        $row_list['list'] = $this->format_action_list($row_list['list']);
        return $row_list;
    }

    private function format_action_list($list)
    {
        return $list;
    }

    /**
     * 指定用户
     * @param $uin
     * @param $share_ids
     */
    public function get_user_liked_list($uin, $share_ids)
    {
        $ret = $this->row_list('iShareId', array('iUin'=>$uin,'where_in'=>array('iShareId',$share_ids)));

        $res = array();
        if (!empty($ret['list'])) {
            foreach($ret['list'] as $row) {
                $res[] = $row['iShareId'];
            }
        }
        $this->log->error('USER_LIKE','res:'.json_encode($res). 'params: '.json_encode($share_ids).'| sql:'.$this->db->last_query());
        return $res;
    }
}