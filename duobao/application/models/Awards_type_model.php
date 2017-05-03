<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 奖励类型-model
 */
class Awards_type_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB;           //分组名
    protected $table_name = 't_awards_type';            //表名
    protected $table_primary = 'iAwardsType';           //主键
    protected $cache_row_key_column = 'iAwardsType';    //缓存key字段  可自定义
    protected $need_cache_row = FALSE;                  //缓存key字段  可自定义
    protected $auto_update_time = false;                 //添加或修改时自动更新createtime 或updatetime
    protected $can_real_delete = true;                      //允许真删除


    public function get_onlone_awards_type()
    {
        $ret = array();
        $list = $this->row_list('iAwardsType,sName,sShortName,sNameEn', array('iState'=>Lib_Constants::PUBLISH_STATE_ONLINE));
        if (!empty($list['list'])) {
            foreach ($list['list'] as $row) {
                $ret[$row['iAwardsType']] = array(
                    'name' => $row['sName'],
                    'short_name' => $row['sShortName'],
                    'enname' => $row['sNameEn']
                );
            }
        }
        return $ret;
    }

    /**
     * 检查
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

        if ($valid == Lib_Constants::NEED_VALID_ONLINE) {
            if ($this->check_online($primary)) {
                return Lib_Errors::HAS_OL_AWARDS_ACTIVITY;
            }
        }
        return $this->update_row(array('iState'=>$state), array($this->get_table_primary() => $primary)) ? Lib_Errors::SUCC : Lib_Errors::SVR_ERR;
    }

    private function check_online($type)
    {
        $this->load->model('awards_activity_model');
        return $this->awards_activity_model->get_row(array('iAwardsType' => $type,'iState'=>Lib_Constants::PUBLISH_STATE_ONLINE)) ? true : false;
    }

    public function check_activity_online($type)
    {
        $this->load->model('awards_activity_model');
        return $this->awards_activity_model->get_row(array('sAwardsType' => $type,'iState'=>Lib_Constants::PUBLISH_STATE_ONLINE)) ? true : false;
    }
}