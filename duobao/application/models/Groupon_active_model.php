<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团活动 model
 *
 * Class Groupon_active_model
 */
class Groupon_active_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; // 分组名
    protected $table_name  = 't_groupon_active'; // 表名
    protected $table_primary = 'iGrouponId'; // 主键
    protected $cache_row_key_column  = 'iGrouponId'; // 缓存key字段
    protected $table_num = 1;
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime
    protected $increase_fields = array('iJoinNum', 'iSoldCount');

    /**
     * 检查团购是否有效
     *
     * @param $grouponId
     * @param $count
     *
     * @return int|array
     */
    public function valid($grouponId, $count = 1)
    {
        $grouponId = (int)$grouponId;
        // 拼团不存在
        if ($grouponId < 1 || ! ($groupon = $this->get_row($grouponId, true, false))) {
            return Lib_Errors::GROUPON_NOT_EXISTS;
        }
        // 拼团非在线状态
        if ($groupon['iState'] != Lib_Constants::PUBLISH_STATE_ONLINE) {
            return Lib_Errors::GROUPON_NOT_ONLINE;
        }
        $time = time();
        // 拼团未开始
        if ($groupon['iStartTime'] > $time) {
            return Lib_Errors::GROUPON_NOT_START;
        }
        // 拼团已结束
        if ($groupon['iEndTime'] < $time) {
            return Lib_Errors::GROUPON_ENDED;
        }
        // 拼团无库存
        if ($groupon['iStock'] < $groupon['iSoldCount'] + $count) {
            return Lib_Errors::GROUPON_SOLD_OUT;
        }
        return $groupon;
    }

    public function update_count($params, $groupon_id, $stock_change=false)
    {
        $lock_key = 'goupon_active_'.$groupon_id;
        if (!set_lock($lock_key)) { //加锁
            return Lib_Errors::SVR_ERR;
        }

        $row = $this->get_row($groupon_id, true, false);
        if (empty($row)) {
            unset_lock($lock_key); //解锁
            return Lib_Errors::PARAMETER_ERR;
        }

        $fields = '';

        //成功拼团数 每次只+1
        if (!empty($params['iSuccCount'])) {
            $fields .= 'iSuccCount=iSuccCount+1,';
        }

        //销量数
        if (!empty($params['iSoldCount']) && $params['iSoldCount'] > 0) {
            $fields .= 'iSoldCount=iSoldCount+'.$params['iSoldCount'].',';
            if ($row['iStock'] == ($row['iSoldCount'] + $params['iSoldCount'])) { //已售完
                $fields .= 'iSoldOutTime='.time().',';
            }
        }

        //库存 修改库存
        if (!empty($params['iStock']) && $stock_change && $params['iStock'] > $row['iStock']) {
            $fields .= 'iStock='.$params['iStock'].',';
            $fields .= 'iSoldOutTime=0,';
        }

        //参团数量
        if (!empty($params['iJoinNum']) && $params['iJoinNum'] > 0) {
            $fields .= 'iJoinNum=iJoinNum+'.$params['iJoinNum'].',';
        }

        if (isset($params['iSoldOutTime'])) {
            $fields .= 'iSoldOutTime='.$params['iSoldOutTime'].',';
        }

        if (isset($params['iUpdateTime'])) {
            $fields .= 'iUpdateTime='.$params['iUpdateTime'].',';
        } else {
            $fields .= 'iUpdateTime='.time().',';
        }

        $fields = trim($fields, ',');

        if ($fields) {
            $table_name = $this->get_cur_table();
            $sql = 'update '.$table_name.' set '.$fields.' where iGrouponId='.$groupon_id.' limit 1';
            $ret = $this->query($sql, true);
            if($this->need_cache_row && $ret) {
                $this->update_cache_row($groupon_id);
            }
            unset_lock($lock_key); //解锁
            return $ret;
        }
        unset_lock($lock_key); //解锁
        return true;
    }

    /**
     * 检查库存
     * @param $groupon_id
     * @param $peoplenum
     */
    public function check_stock($groupon_id, $peoplenum)
    {
        $row = $this->get_row($groupon_id, true, false);
        if ($row) {
            return (($row['iSoldCount'] + $peoplenum) > $row['iStock']) ? false : true;
        } else {
            return false;
        }
    }
}