<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 奖励活动-model
 */
class Awards_activity_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB;               //分组名
    protected $table_name = 't_awards_activity';            //表名
    protected $table_primary = 'iAwardsId';                 //主键
    protected $cache_row_key_column = 'iAwardsId';          //缓存key字段  可自定义
    protected $auto_update_time = true;                     //自动带上创建或更新时间
    protected $can_real_delete = true;                      //允许真删除

    public function get_online_activity($awards_type, $platform)
    {
        $now_time = time();
        $where = array(
            'iPlatForm' => intval($platform),
            'iState' => Lib_Constants::PUBLISH_STATE_ONLINE,
            'sAwardsType' => $awards_type,
            'iStartTime <=' => $now_time,
            'iEndTime >=' => $now_time,
        );
        $ret = $this->get_row($where);
        $this->log->error('AwardsActivity', 'SQL'.$this->db->last_query());
        return $this->get_row($where);
    }

    public function get_all_online_activity($platform)
    {
        $now_time = time();
        $where = array(
            'iPlatForm' => intval($platform),
            'iState' => Lib_Constants::PUBLISH_STATE_ONLINE,
            'iStartTime <=' => $now_time,
            'iEndTime >=' => $now_time,
        );
        $row_list = $this->row_list('*', $where, array(), 1, count(Lib_Constants::$awards_prizes));
        return $row_list;
    }

    /**
     * 更新发布字段状态
     * @param $primary
     */
    public function update_state($primary, $state)
    {
        $ori_row = $this->get_row($primary);
        if (!$ori_row) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (!$valid = Lib_Constants::valid_publish_state($ori_row['iState'], $state)) {
            return Lib_Errors::PARAMETER_ERR;
        }
        if($state == Lib_Constants::PUBLISH_STATE_ONLINE) {
            $this->load->model('awards_type_model');
            if(!$this->awards_type_model->get_row(array('sNameEn'=>$ori_row['sAwardsType'], 'iState'=>Lib_Constants::PUBLISH_STATE_ONLINE))) {
                return Lib_Errors::AWARDS_TYPE_NOT_ONLINE;;
            }
            //检查当前时间区间是否有已上线
            if ($state == Lib_Constants::PUBLISH_STATE_ONLINE) {
                $sql = 'select iAwardsId from '.$this->table_name.' where sAwardsType=\''.$ori_row['sAwardsType'].'\' and iState='.Lib_Constants::PUBLISH_STATE_ONLINE.' and iPlatForm='.$ori_row['iPlatForm'].' and (iStartTime between '.$ori_row['iStartTime'].' and '.$ori_row['iStartTime'].' or iStartTime between '.$ori_row['iStartTime'].' and '.$ori_row['iStartTime'].' or (iStartTime <= '.$ori_row['iStartTime'].' and iEndTime>= '.$ori_row['iStartTime'].')) limit 1;';
                $row = $this->query($sql);
                if ($row) {
                    return Lib_Errors::HAVE_ONLINE_ACTIVITY;
                }
            }

        }

        return $this->update_row(array('iState'=>$state), $primary) ? Lib_Errors::SUCC : Lib_Errors::SVR_ERR;
    }
}