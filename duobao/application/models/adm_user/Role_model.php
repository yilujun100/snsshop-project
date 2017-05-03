<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_adm_role';   // 表名
    protected $table_primary = 'iRoleId';   // 表名主键名称
    protected $cache_row_key_column = 'iRoleId'; // 单条表记录缓存字段
    protected $auto_update_time = true; // 自动更新createTime updateTime

    protected $table_name_role = 't_adm_role'; //角色表

    /**
     * 角色ID
     *
     * @var int
     */
    private $role_id;

    /**
     * Role_model constructor
     *
     * @param null $role_id
     */
    public function __construct($role_id = null)
    {
        parent::__construct();
        if ($role_id > 0) {
            $this->role_id = intval($role_id);
        }
    }
}