<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_summary_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_order_summary';                //表名
    protected $table_primary = 'sOrderId';                   //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num= 10;
    protected $db_map_column = 'iUin';  //分表字段

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取用户参与某期活动的总次数（购买的总码数）
     *
     * @param $uin
     * @param $actId
     * @param $period
     *
     * @return int
     */
    public function get_join_count($uin, $actId, $period)
    {
        $params = array(
            'iActId' => $actId,
            'iPeroid' => $period,
            'iUin' => $uin,
        );
        $rows = $this->get_rows($params);
        if (! $rows) {
            return 0;
        }
        $total = 0;
        foreach ($rows as $row) {
            $total += $row['iLotCount'];
        }
        return $total;
    }

}