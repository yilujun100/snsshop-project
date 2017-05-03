<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 积分兑换活动-model
 */
class Recharge_activity_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB;               //分组名
    protected $table_name = 't_recharge_activity';            //表名
    protected $table_primary = 'iActivityId';                 //主键
    protected $cache_row_key_column = 'iActivityId';        //缓存key字段  可自定义
    protected $auto_update_time = false;                     //自动带上创建或更新时间
    protected $can_real_delete = true;                      //允许真删除


    public function get_activity_conf($platfrom = Lib_Constants::PLATFORM_WX)
    {
        $time = time();
        $conf = $this->get_row(array('iState'=> Lib_Constants::PUBLISH_STATE_ONLINE,'iStartTime <='=> $time,'iEndTime' > $time,'iPlatForm'=> $platfrom));

        return $conf;
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

        //检查当前时间区间是否有已上线
        if ($state == Lib_Constants::PUBLISH_STATE_ONLINE) {
            $sql = 'select iActivityId from '.$this->table_name.' where iState='.Lib_Constants::PUBLISH_STATE_ONLINE.' and iPlatForm='.$ori_row['iPlatForm'].' and (iStartTime between '.$ori_row['iStartTime'].' and '.$ori_row['iStartTime'].' or iStartTime between '.$ori_row['iStartTime'].' and '.$ori_row['iStartTime'].' or (iStartTime <= '.$ori_row['iStartTime'].' and iEndTime>= '.$ori_row['iStartTime'].')) limit 1;';
            $row = $this->query($sql);
            if ($row) {
                return Lib_Errors::HAVE_ONLINE_ACTIVITY;
            }
        }

        return $this->update_row(array('iState'=>$state), $primary) ? Lib_Errors::SUCC : Lib_Errors::SVR_ERR;
    }
}