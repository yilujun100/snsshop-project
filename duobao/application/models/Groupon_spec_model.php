<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团规格 model
 *
 * Class Groupon_spec_model
 */
class Groupon_spec_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; // 分组名
    protected $table_name  = 't_groupon_spec'; // 表名
    protected $table_primary = 'iSpecId'; // 主键
    protected $cache_row_key_column  = 'iSpecId'; // 缓存key字段
    protected $table_num = 1;
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime

    /**
     * 取拼团成功之后的最终价格
     * @param $groupon_id
     * @param $buy_num
     * @return array
     */
    public function get_real_spec($groupon_id, $buy_num) {
        $sql = 'select iSpecId,iGrouponId,iPeopleNum,iDiscountPrice,iFree from '.$this->get_cur_table().' where iGrouponId='.intval($groupon_id).' and iPeopleNum <='.$buy_num.' order by iPeopleNum desc limit 1';
        $spec = $this->query($sql, true);
        if ($spec) {
            return $spec[0];
        } else {
            return array();
        }
    }

    /**
     * 返利金额
     * @param $buy_price
     * @param $discount_price
     * @return mixed
     */
    public function get_rebate_price($buy_price, $discount_price)
    {
        return ($buy_price - $discount_price);
    }

    /**
     * 取拼团活动的规格配置
     * @param $groupon_id
     */
    public function get_groupon_spec($groupon_id)
    {
        $sql = 'select iSpecId,iGrouponId,iPeopleNum,iDiscountPrice,iFree from '.$this->get_cur_table().' where iGrouponId='.intval($groupon_id).' order by iPeopleNum asc;';
        return $this->query($sql);
    }
}