<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Robot_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; //分组名
    protected $table_name = 't_robot'; // 表名
    protected $table_primary = 'iUin'; // 表名主键名称
    protected $cache_row_key_column = 'iUin'; // 单条表记录缓存字段
    protected $auto_update_time = TRUE; // 自动更新createtime或updatetime
    protected $need_cache_row = FALSE; // 是否需要缓存表记录

    /**
     * 最大id
     */
    const MAX_ID = 214455;

    /**
     * 随机取出指定数目的机器人
     *
     * @param $count
     *
     * @return array
     */
    public function random($count)
    {
        $address_ratio = get_variable('robot_address_ratio');

        $robots = array();
        $left = $count;
        $ids = array();

        $sql_t = "SELECT * FROM {$this->table_name} WHERE iState = 1 AND iId IN (%s);";

        while (count($ids) < $count) {
            $left_ids = array();
            while (count($left_ids) < $left) {
                $id = mt_rand(1, self::MAX_ID);
                if (! in_array($id, $ids)) {
                    $left_ids[] = $id;
                }
            }
            $sql = sprintf($sql_t, implode(',', $left_ids));
            $arr = $this->query($sql);
            foreach ($arr as $item) {
                if ($item['sAddressIp'] && ($addressIp = json_decode($item['sAddressIp'], TRUE))) {
                    $address = $this->random_address($address_ratio, $addressIp);
                    $item['sAddress'] = $address['address'];
                    $item['sIp'] = $address['ip'];
                }
                $ids[] = $item['iId'];
                $robots[] = $item;
                $left --;
            }
        }
        return $robots;
    }

    /**
     * 获取机器人详情
     *
     * @param $uin
     *
     * @return bool|mixed|void
     */
    public function get_robot($uin)
    {
        if (! $uin) {
            return;
        }
        if (! ($row = $this->get_row($uin))) {
            return;
        }
        $address_ratio = get_variable('robot_address_ratio');
        if ($row['sAddressIp'] && ($addressIp = json_decode($row['sAddressIp'], TRUE))) {
            $address = $this->random_address($address_ratio, $addressIp);
            $row['sAddress'] = $address['address'];
            $row['sIp'] = $address['ip'];
        }
        return $row;
    }

    /**
     * 随机取出地址
     *
     * @param $address_ratio
     * @param $addressIp
     *
     * @return int
     */
    protected function random_address($address_ratio, $addressIp)
    {
        if (! $address_ratio) {
            $index = 0;
        } else {
            $index = array_ratio_random($address_ratio);
            if (empty($addressIp[$index])) {
                $index = 0;
            }
        }
        return $addressIp[$index];
    }
}