<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 发起拼团 model
 *
 * Class Groupon_diy_model
 */
class Groupon_diy_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; // 分组名
    protected $table_name  = 't_groupon_diy'; // 表名
    protected $table_primary = 'iDiyId'; // 主键
    protected $cache_row_key_column  = 'iDiyId'; // 缓存key字段
    protected $table_num = 1;
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime
    protected $increase_fields = array('iBuyNum');

    /**
     * 验证拼团
     *
     * @param $diyId
     *
     * @return int|array
     */
    public function valid($diyId)
    {
        $diyId = (int)$diyId;
        // 拼团不存在
        if ($diyId < 1 || ! ($diy = $this->get_row($diyId, true, false))) {
            return Lib_Errors::GROUPON_DIY_NOT_EXISTS;
        }
        $time = time();
        // 拼团未开始
        if ($diy['iStartTime'] > $time) {
            return Lib_Errors::GROUPON_DIY_NOT_START;
        }
        // 拼团已结束
        if ($diy['iEndTime'] < $time) {
            return Lib_Errors::GROUPON_DIY_ENDED;
        }
        // 已开团
        if (Lib_Constants::GROUPON_DIY_FINISHED == $diy['iFinished']) {
            return Lib_Errors::GROUPON_DIY_ENDED;
        }
        // 拼团已达到开团人数
        if ($diy['iBuyNum'] >= $diy['iOpenCount']) {
            return Lib_Errors::GROUPON_DIY_PEOPLE_MAX;
        }
        return $diy;
    }

    /**
     * 更新
     * @param $data
     * @param $params
     */
    public function update_diy($data, $diy_id)
    {
        return $this->update_row($data, $diy_id);
    }
}