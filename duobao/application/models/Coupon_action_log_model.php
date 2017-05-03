<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 夺宝券兑换日志表-model
 */
class Coupon_action_log_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_YYDB_USER;         //分组名
    protected $table_name               =       't_coupon_action_log';       //表名
    protected $table_primary            =       'iLogId';                  //主键
    protected $cache_row_key_column     =       'iUin';                     //缓存key字段  可自定义
    protected $db_map_column            =       'iUin';                     //缓存key字段  可自定义
    protected $table_num                =       10;
    protected $logic_group              =       LOGIC_GROUP_USER;

    public function add_coupon($params)
    {
        $data = array(
            'iUin' => $params['uin'],
            'iAction' => $params['action'],
            'iNum' =>  abs($params['prize_count']),
            'iPlatForm' => $params['platform'],
            'iAddTime' => time(),
            'sExt' => is_array($params['ext']) ? ($params['ext'] ? (empty($params['ext']['key']) ? json_encode($params['ext']) : $params['ext']['key']) : '') : $params['ext'],
           // 'sKey' => empty($params['key']) ? '' : $params['key'],
            'iType' => isset($params['type']) ? $params['type'] : ($params['prize_count'] >=0 ? Lib_Constants::ACTION_INCOME : Lib_Constants::ACTION_OUTCOME)
        );
        return $this->add_row($data);
    }

    public function is_today_rewarded($uin, $platform, $awards_type)
    {
        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iLogId,iAddTime from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.' order by iLogId desc limit 1';
        $row_list = $this->query($sql);
        if ($row_list) {
            $time = strtotime(date("Y-m-d"));
            if ($row_list[0]['iAddTime'] >= $time && $row_list[0]['iAddTime'] < ($time+86400)) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    /***
     * 检查是否发放签到奖励
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_sign_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iLogId,iAddTime from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.' order by iLogId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_sign_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if($row_list) {
            $time = strtotime(date("Y-m-d"));
            if ($row_list[0]['iAddTime'] >= $time && $row_list[0]['iAddTime'] < ($time+86400)) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    /**
     * 检查是否已经领取点赞奖励
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_like_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        $share_id = is_array($extend) && !empty($extend['share_id']) ? intval($extend['share_id']) : intval($extend);
        if (!$share_id) {
            return false;
        }
        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iLogId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.' and sExt='.$extend['share_id'].' order by iLogId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_like_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }
        return true;
    }

    /**
     * 检查是否已经领取点赞奖励
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_share_invite_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        if(empty($uin) || empty($extend['key'])) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $where = '';
        if (!empty($extend['key'])) {
            $where .= ' and sExt=\''.$extend['key'].'\'';
        }

        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iLogId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.$where.' order by iLogId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_share_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }
        return true;
    }

    /**
     * 检查是否已经领取点赞奖励
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_share_newuser_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        if(!$uin) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $where = '';
        if (!empty($extend['key'])) {
            $where .= ' and sExt=\''.$extend['key'].'\'';
        }

        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iLogId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.$where.' order by iLogId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_share_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }
        return true;
    }

    /***
     * 检查是否发放签到奖励
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_share_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        $period_code = is_array($extend) && !empty($extend['share_id']) ? intval($extend['share_id']) : intval($extend);
        if (!$period_code) {
            return false;
        }

        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iLogId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.' and sExt='.$period_code.' order by iLogId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_share_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }
        return true;
    }

    protected $columns = array('iLogId','iUin','iAction','iNum','sExt','iAddTime','iType');

    public function get_action_log_list($params, $order_by, $p_index=1, $p_size=10)
    {
        return $this->row_list(implode(',',$this->columns), $params, $order_by, $p_index, $p_size);
    }

    public function format_list($list)
    {
        $ret = array();
        if ($list) {
            foreach($list as $item) {
                $ret[] = array(
                    'log_id' => $item['iLogId'],
                    'uin' => $item['iUin'],
                    'action' => empty(Lib_Constants::$coupon_actions[$item['iAction']]) ? '--' : Lib_Constants::$coupon_actions[$item['iAction']],
                    'num' => Lib_Constants::ACTION_INCOME == $item['iType'] ? '+'.$item['iNum'] : '-'.$item['iNum'],
                    'add_time' => date('Y-m-d H:i:s', $item['iAddTime'])
                );
            }
        }
        return $ret;
    }


    /***
     * 检查是否发放奖励 - 微团购活动
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_wtg_activity_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        $act_id = is_array($extend) && !empty($extend['act_id']) ? intval($extend['act_id']) : intval($extend);
        if (empty($act_id)) {
            return false;
        }

        $ext = json_encode($extend);
        $table_name = $this->map($uin)->get_cur_table();
        $act_str = '"act_id":"'.$act_id.'"';

        $sql = 'select iLogId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction='.$awards_type.' and sExt=\''.$ext.'\' order by iLogId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_wtg_activity_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }

        //最多给10次奖励
        $sql = 'select count(iLogId) as iTotal from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and iAction=\''.$awards_type.'\' and sExt like \'%'.$act_str.'_%\' limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Coupon | is_wtg_activity_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        $count = empty($row_list) ? 0 : (empty($row_list[0]) ? 0 : intval($row_list[0]['iTotal']));
        if ($count >= 5) {
            return false;
        }
        return true;
    }
}