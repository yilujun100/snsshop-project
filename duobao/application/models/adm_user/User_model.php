<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_adm_user'; // 用户表
    protected $table_primary = 'iUserId'; // 表名主键名称
    protected $cache_row_key_column = 'iUserId'; // 单条表记录缓存字段
    protected $auto_update_time = true; // 自动更新createTime updateTime

    protected $table_name_role = 't_adm_role'; //角色表

    /**
     * 密码加密串长度
     */
    const PASSWORD_SALT_LEN = 13;

    /**
     * 用户ID
     *
     * @var int
     */
    protected $user_id;

    /**
     * User_model constructor
     *
     * @param $user_id
     */
    public function __construct($user_id = NULL)
    {
        parent::__construct();
        if ($user_id > 0) {
            $this->user_id = intval($user_id);
        }
    }

    /**
     * 获取用户列表（分页）
     *
     * 重载父类，$fields 和 $from_write 参数无效
     *
     * @param string $fields
     * @param array  $where
     * @param array  $order_by
     * @param int    $page_index
     * @param int    $page_size
     * @param array  $group_by
     * @param bool   $from_write
     *
     * @return array
     */
    public function row_list($fields='*', $where=array(), $order_by=array(), $page_index = 1, $page_size = parent::PAGE_SIZE, $group_by = array(), $from_write = false)
    {
        $ret = array(
            'count' => 0,
            'list' => array(),
            'page_count' => 0,
            'page_size' => $page_size,
            'page_index' => $page_index,
        );

        $db = $this->conn()
            ->select("{$this->table_name}.*, {$this->table_name_role}.sName as sRoleName")
            ->from($this->table_name)
            ->join($this->table_name_role, "{$this->table_name_role}.iRoleId={$this->table_name}.iRoleId", 'left')
            ->where($where);

        $count = $db->count_all_results('', FALSE);

        if ($count) {
            $ret['page_count'] = ceil($count/$page_size);
            $list = array();
            if ($page_index <= $ret['page_count']) {
                if (is_array($order_by)) {
                    foreach($order_by as $key=>$val) {
                        $db->order_by($key,$val);
                    }
                }
                $offset = ($page_index-1) * $page_size;
                $list = $db->offset($offset)->limit($page_size)->get()->result_array();
            }
            if($list) {
                $ret['list'] = $list;
            }
        } else {
            $count = 0;
        }
        $ret['count'] = $count;
        return $ret;
    }

    /**
     * 根据用户名获取用户信息
     *
     * @param $name
     *
     * @return int
     */
    public function get_user_by_name($name)
    {
        return $this->conn()->where('sName', $name)->limit(1)->get($this->table_name)->row_array();
    }

    /**
     * 添加用户
     *
     * @param array  $data
     *
     * @return int
     */
    public function add_row($data = array())
    {
        $data['sSalt'] = $this->generate_salt();
        $data['sPassword'] = $this->generate_password($data['sPassword'], $data['sSalt']);
        return parent::add_row($data);
    }

    /**
     * 修改用户
     *
     * @param array $data
     * @param       $primary
     *
     * @return int
     */
    public function update_row($data = array(), $primary = null)
    {
        if ($primary < 1) {
            $primary = $this->user_id;
        }
        if ($primary < 1) {
            return Lib_Errors::PARAMETER_ERR;
        }
        if (! empty($data['sPassword'])) {
            $data['sSalt'] = $this->generate_salt();
            $data['sPassword'] = $this->generate_password($data['sPassword'], $data['sSalt']);
        } else {
            unset($data['sSalt'], $data['sPassword']);
        }
        return parent::update_row($data, $primary);
    }

    /**
     * 验证用户名、密码
     *
     * 成功则返回用户信息
     *
     * @param $name
     * @param $password
     *
     * @return array|int
     */
    public function verify($name, $password)
    {
        $user = $this->get_user_by_name($name);
        if (! $user ||
            $user['sPassword'] !== $this->generate_password($password, $user['sSalt'])) {
            return Lib_Errors::USER_PASSWORD_ERROR;
        }
        if (1 != $user['iState']) {
            return Lib_Errors::USER_IS_INVALID;
        }
        return array(
            'iUserId' => $user['iUserId'],
            'iRoleId' => $user['iRoleId'],
            'sName' => $user['sName'],
            'sNickName' => $user['sNickName'],
            'sRemark' => $user['sRemark'],
            'iLastLoginTime' => $user['iLastLoginTime'],
        );
    }

    /**
     * 生成加密密码
     *
     * @param string $password
     * @param string $salt
     *
     * @return mixed|string
     */
    private function generate_password($password, $salt = '')
    {
        if (! $salt) {
            $salt = $this->generate_salt();
        }
        return hash_pbkdf2('sha256', md5(md5($password) . $salt), $salt, 1001, 32);
    }

    /**
     * 生成加密串
     *
     * @param int $len
     *
     * @return string
     */
    private function generate_salt($len = self::PASSWORD_SALT_LEN)
    {
        return substr(uniqid(), 0, $len);
    }
}