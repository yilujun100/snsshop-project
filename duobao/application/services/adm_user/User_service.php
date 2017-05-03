<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台用户相关功能
 *
 * Class User_service
 */
class User_service extends MY_Service
{
    /**
     * 用户信息 cookie 名称
     */
    const COOKIE_NAME = 'd5b68815de1443769eaf';

    /**
     * 用户信息 session 名称
     */
    const SESSION_NAME = 'e8f3ab5661604d70ad5e';

    /**
     * 用户信息
     *
     * @var
     */
    private $user;

    /**
     * 角色信息
     *
     * @var
     */
    private $role;

    /**
     * @var array
     */
    private $white_list;

    /**
     * Login_service constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->library('encryption');
    }

    /**
     * 运行服务
     *
     * @param $node
     * @param $white_list
     *
     * @return int
     */
    public function run($node, $white_list = array())
    {
        $this->white_list = $white_list;

        if (! $this->check_login()) { // 未登录
            return Lib_Errors::NOT_LOGIN;
        }

        if (! $this->check_purview($node)) { // 无权限
            return Lib_Errors::PERMISSION_DENIED;
        }
    }

    /**
     * 登陆验证
     *
     * @param $userName
     * @param $password
     *
     * @return bool|int
     */
    public function login($userName, $password)
    {
        $this->load->model('adm_user/user_model');
        $user = $this->user_model->verify($userName, $password);
        if (is_error_code($user)) {
            return $user;
        }

        $this->load->model('adm_user/role_model');
        $role = $this->role_model->get_row($user['iRoleId']);
        if (empty($role)) {
            return Lib_Errors::ROLE_ID_INVALID;
        }
        $this->role = $role; // 设置当前用户角色信息

        $time = time();
        $loginLogData = array(
            'iLastLoginTime' => $time,
            'iUpdateTime' => $time,
        );
        $this->user_model->update_row($loginLogData, $user['iUserId']);

        $user['home_node'] = $role['sHomeNode'];
        $user['role_name'] = $role['sName'];

        $this->user = $user; // 设置当前用户信息

        $this->set_user($user);

        return true;
    }

    /**
     * 注销当前用户
     */
    public function logout()
    {
        $this->unset_user();
    }

    /**
     * 获取用户首页节点
     *
     * 需先登陆
     *
     * @return bool
     */
    public function get_home_node()
    {
        if (empty($this->user)) {
            return false;
        }
        return isset($this->user['home_node']) ? $this->user['home_node'] : false;
    }

    /**
     * 获取用户ID
     *
     * 需先登陆
     *
     * @return bool
     */
    public function get_user_id()
    {
        if (empty($this->user)) {
            return false;
        }
        return $this->user['iUserId'];
    }

    /**
     * 获取用户信息
     *
     * @return bool|mixed
     */
    public function get_user_info()
    {
        if (empty($this->user)) {
            $this->check_login();
        }
        return $this->user;
    }

    /**
     * 获取用户ID
     *
     * 需先登陆
     *
     * @return bool
     */
    public function get_role_id()
    {
        if (empty($this->role)) {
            return false;
        }
        return $this->role['iRoleId'];
    }

    /**
     * 获取角色权限信息
     *
     * @param $role_id
     *
     * @return array
     */
    public function get_role_purview($role_id = null)
    {
        if (! $role_id) { // 不传role_id返回当前登陆用户的权限
            if (empty($this->role)) {
                return false;
            }
            return array_filter(array_map('trim', explode(',', $this->role['sPurviews'])));
        } else {
            static $roles;
            if (! $roles || ! isset($roles[$role_id])) {
                $this->load->model('adm_user/role_model');
                $roles[$role_id] = $this->role_model->get_row($role_id);
            }
            if (empty($roles[$role_id])) {
                return false;
            }
            return array_filter(array_map('trim', explode(',', $roles[$role_id]['sPurviews'])));
        }
    }

    /**
     * 获取角色信息
     *
     * @return bool|mixed
     */
    public function get_role_info()
    {
        if (empty($this->role)) {
            $this->check_login();
        }
        return $this->role;
    }

    /**
     * 判断节点是否需要登录
     *
     * @param $node
     * @param $white_list
     *
     * @return bool
     */
    public function login_required($node, $white_list)
    {
        if (in_array($node, $white_list)) {
            return false;
        }

        return true;
    }

    /**
     * 检查当前用户是否已授予指定节点的访问权限
     *
     * @param $node
     * @param $role_id
     *
     * @return bool
     */
    public function is_granted($node, $role_id = null)
    {
        return in_array($node, $this->white_list) ||
            in_array($node, $this->get_role_purview($role_id));
    }

    /**
     * 检查用户权限
     *
     * @param $node
     *
     * @return bool
     */
    private function check_purview($node)
    {
        if (empty($this->role)) {
            return false;
        }
        return in_array($node, $this->white_list) ||
            in_array($node, explode(',', $this->role['sPurviews']));
    }

    /**
     * 检查用户是否已登录
     *
     * 未登录返回false，已登陆则返回用户信息
     *
     * @return bool
     */
    private function check_login()
    {
        $user = array();
        if ($userStr = $this->session->{self::SESSION_NAME}) {
            $user = $this->decrypt($userStr);
        } else if ($userStr = get_cookie(self::COOKIE_NAME)) {
            $user = $this->decrypt($userStr);
            $this->session->set_userdata(self::SESSION_NAME, $userStr);
        }
        if ($user) {
            $this->load->model('adm_user/role_model');
            $role = $this->role_model->get_row($user['iRoleId']);
            $this->role = $role;
            $this->user = $user;
            return true;
        }
        return false;
    }

    /**
     * 设置用户信息到 session 和 cookie 中
     *
     * @param $userInfo
     */
    private function set_user($userInfo)
    {
        unset($userInfo['sPassword'], $userInfo['sSalt'], $userInfo['iCreateTime'], $userInfo['iUpdateTime']);
        $userStr = $this->encrypt($userInfo);
        $this->session->set_userdata(self::SESSION_NAME, $userStr);
        set_cookie(self::COOKIE_NAME, $userStr, mktime(23, 59, 59) - time());
        return $userInfo;
    }

    /**
     * 删除 session 和 cookie 中的用户信息
     */
    private function unset_user()
    {
        $this->session->unset_userdata(self::SESSION_NAME);
        delete_cookie(self::COOKIE_NAME);
    }

    /**
     * 加密用户信息
     *
     * @param $data
     *
     * @return mixed
     */
    private function encrypt($data)
    {
        return $this->encryption->encrypt(json_encode($data));
    }

    /**
     * 解密用户信息
     *
     * @param $ciphertext
     *
     * @return mixed
     */
    private function decrypt($ciphertext)
    {
        return json_decode($this->encryption->decrypt($ciphertext), true);
    }
}