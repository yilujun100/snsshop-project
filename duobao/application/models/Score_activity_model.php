<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 积分兑换活动-model
 */
class Score_activity_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB;               //分组名
    protected $table_name = 't_score_activity';            //表名
    protected $table_primary = 'iActivityId';                 //主键
    protected $cache_row_key_column = 'iActivityId';        //缓存key字段  可自定义
    protected $auto_update_time = false;                     //自动带上创建或更新时间
    protected $can_real_delete = true;                      //允许真删除

    protected $columns = array('iActivityId','iGoodsId','sGiftName','iShortName','iOriScore','iPreScore','iStartTime','iEndTime','iSingle','iMaxLimit','iTotal','iCouponNum','sImg','iGoodsType','iUsed');

    public function get_score_activity_list($params, $order_by=array(), $p_index=1, $p_size=10)
    {
        $ret =  $this->row_list(implode(',', $this->columns), $params, $order_by, $p_index, $p_size);
        return $ret;
    }

    public function update_count($params, $act_id)
    {
        $table_name = $this->get_cur_table();
        $fileds = '';
        $fileds_arr = array('iUsed');
        foreach ($fileds_arr as $val) {
            if(isset($params[$val])) {
                if($params[$val]>0) {
                    $fileds .= $val.'='.$val.'+'.$params[$val].',';
                } else {
                    $fileds .= $val.'='.$val.'-'.abs($params[$val]).',';
                }
            }
        }
        $fileds = trim($fileds, ',');
        if ($fileds) {
            $sql = 'update '.$table_name.' set '.$fileds.' where iActivityId='.$act_id.' limit 1';

            $ret = $this->query($sql, true);
            if($this->need_cache_row && $ret) {
                $this->update_cache_row($act_id);
            }

            return $this->query($sql, true);
        }
        return true;
    }

    public function foramt_list($list) {
        if (!$list) {
            return array();
        }
        $ret = array();
        foreach ($list as $item) {
            $ret[] = array(
                'id' => $item['iActivityId'],
                'img' => $item['sImg'],
                'goods_name' => $item['sGiftName'],
                'ori_score' => $item['iOriScore'],
                'pre_score' => $item['iPreScore'],
                'goods_id' => $item['iGoodsId'],
                'single' => $item['iSingle'],
                'max' => $item['iMaxLimit'],
                'total' => $item['iTotal']
            );
        }
        return $ret;
    }
}