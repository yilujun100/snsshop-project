<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 拼团规格 model
 *
 * Class Groupon_active_spec_model
 */
class Groupon_active_spec_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; // 分组名
    protected $table_name  = 't_groupon_spec'; // 表名
    protected $table_primary = 'iSpecId'; // 主键
    protected $cache_row_key_column  = 'iSpecId'; // 缓存key字段
    protected $table_num = 1;
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime

    /**
     * 获取阶梯团成团成功的规格
     *
     * @param $grouponId
     * @return int
     */
    public function get_stair_success_spec($grouponId)
    {
        $grouponId = (int)$grouponId;
        if ($grouponId < 1) {
            return Lib_Errors::PARAMETER_ERR;
        }
        $sql = "SELECT * FROM {$this->table_name} WHERE iGrouponId={$grouponId} ORDER BY iPeopleNum ASC LIMIT 1";
        $res = $this->query($sql);
        if (empty($res)) {
            return Lib_Errors::GROUPON_SPEC_NOT_EXISTS;
        }
        return $res[0];
    }
}