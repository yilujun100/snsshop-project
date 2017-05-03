<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 积分兑换日志表-model
 */
class Score_action_log_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_YYDB_USER;         //分组名
    protected $table_name               =       't_score_action_log';       //表名
    protected $table_primary            =       'iAutoId';                  //主键
    protected $cache_row_key_column     =       'iUin';                     //缓存key字段  可自定义
    protected $db_map_column            =       'iUin';                     //缓存key字段  可自定义
    protected $table_num                =       10;
    protected $logic_group              =       LOGIC_GROUP_USER;

    protected $columns = array('iAutoId','iUin','iScoreCount','sAwardsType','iExchangeTime','sExt','sAwardsName','iPlatForm','iAction','iType');

    public function add_score($params)
    {
        $data = array(
            'iUin' => $params['uin'],
            'iAction' => Lib_Constants::ACTION_INCOME,
            'iScoreCount' =>  $params['prize_count'],
            'iPlatForm' => $params['platform'],
            'sAwardsName' => $params['awards_name'],
            'sAwardsType' => $params['awards_ename'],
            'iExchangeTime' => time(),
            'iType' => isset($params['type']) ? $params['type'] : ($params['prize_count'] >=0 ? Lib_Constants::ACTION_INCOME : Lib_Constants::ACTION_OUTCOME),
            'sExt' => is_array($params['ext']) ? ($params['ext'] ? json_encode($params['ext']) : '') : $params['ext'],
        );
        $re = $this->add_row($data);
        return $re;
    }

    public function get_score_action_list($uin, $platform, $page_cur, $page_size, $order_by='')
    {
        $where = array(
            'iUin' => $uin,
            'iPlatForm' => $platform
        );
        $order_by || $order_by = array('iExchangeTime'=>'desc');

        $list = $this->row_list(implode(',', $this->columns), $where, $order_by, $page_cur, $page_size);
        /*if($list) {
            $list['list'] = $this->format_score_action_list($list['list']);
        }*/
        return $list;
    }

    public function format_score_action_list($list)
    {
        if (!$list) {
            return array();
        }
        $ret = array();
        foreach ($list as $item) {
            $ret[] = array(
                'id' => $item['iAutoId'],
                'score' => Lib_Constants::ACTION_INCOME == $item['iType'] ?  '+'.$item['iScoreCount'] : '-'.$item['iScoreCount'] ,
                'awards' => $item['sAwardsName'],
                'exchange_time' => date('Y-m-d H:i:s', $item['iExchangeTime'])
            );
        }
        return $ret;
    }


    /***
     * 检查是否发放奖励 - 签到
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
        $sql = 'select iAutoId,iExchangeTime from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and sAwardsType=\''.$awards_type.'\' order by iAutoId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Score | is_sign_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if($row_list) {
            $time = strtotime(date("Y-m-d"));
            if ($row_list[0]['iExchangeTime'] >= $time && $row_list[0]['iExchangeTime'] < ($time+86400)) {
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    /**
     * 检查是否已发放奖励 - 晒单点赞
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
        $sql = 'select iAutoId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and sAwardsType=\''.$awards_type.'\' and sExt='.$extend['share_id'].' order by iAutoId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Score | is_like_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }
        return true;
    }

    /***
     * 检查是否发放奖励 - 分享晒单
     * @param $uin
     * @param $platform
     * @param $awards_type
     * @param array $params
     * @param array $extend
     * @return bool
     */
    public function is_share_rewarded($uin, $platform, $awards_type, $params=array(), $extend=array())
    {
        $share_id = is_array($extend) && !empty($extend['share_id']) ? intval($extend['share_id']) : intval($extend);
        if (!$share_id) {
           return false;
        }

        $table_name = $this->map($uin)->get_cur_table();
        $sql = 'select iAutoId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and sAwardsType=\''.$awards_type.'\' and sExt='.$share_id.' order by iAutoId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Score | is_share_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }
        return true;
    }

    /***
     * 检查是否发放奖励 - 微团购活动赠送积分
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
        $sign = $extend['sign'];
        $ext = $act_id.'_'.$sign;
        $table_name = $this->map($uin)->get_cur_table();

        $sql = 'select iAutoId from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and sAwardsType=\''.$awards_type.'\' and sExt=\''.$ext.'\' order by iAutoId desc limit 1';
        $row_list = $this->query($sql, true);
        $this->log->error('Awards', 'Score | is_wtg_activity_rewarded | sql:'.$this->db->last_query().' | '.__METHOD__);
        if(!empty($row_list[0])) {
            return false;
        }

        //最多给10次奖励
        $sql = 'select count(iAutoId) as iTotal from '.$table_name.' where iUin='.$uin.' and iPlatForm='.$platform.' and sAwardsType=\''.$awards_type.'\' and sExt like \''.$act_id.'_%\' and  limit 1';
        $row_list = $this->query($sql, true);
        $count = empty($row_list) ? 0 : (empty($row_list[0]) ? 0 : intval($row_list[0]['iTotal']));
        if ($count >= 5) {
            return false;
        }
        return true;
    }
}