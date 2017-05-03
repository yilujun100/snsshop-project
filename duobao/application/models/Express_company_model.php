<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 快递公司 model
 *
 * Class Express_company
 */
class Express_company_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; // 分组名
    protected $table_name  = 't_express_company'; // 表名
    protected $table_primary = 'iExpId'; // 主键
    protected $cache_row_key_column  = 'iExpId'; // 缓存key字段
    protected $table_num = 1;
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime

    /**
     * 返回快递公司列表
     */
    public function get_express_list()
    {
        $sql = "SELECT * FROM `{$this->table_name}` ORDER BY iExpId LIMIT 200";
        return $this->query($sql);
    }
}