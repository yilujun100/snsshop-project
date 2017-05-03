<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * model配置模板  仅供参考
 */
class Template_model extends MY_Model {
    protected $db_group_name = 'dbqb_admin';        //数据库分组
    protected $db_name = 'yydb_admin';              //库名
    protected $table_name = 't_awards_type';               //表名
    protected $table_primary = 'iAwardsType';           //主键
    protected $db_num = 1;                          //分库数
    protected $table_num = 1;                       //分表数
    protected $need_cache_row = FALSE;               //缓存cachekey
    protected $cache_row_key_column = 'iAwardsType';    //缓存key字段  可自定义
    protected $db_map_column = 'iAwardsType';           //用来分库分表的字段


    /**
     * 校验登陆
     */
    public function check_login() {
        $data = array(
            'sUserName' => 'test_1'.rand(0,111111),
            'sPassword' => md5(123456),
            'sName' => '测试11',
            'iRoleId' => 1,
            'iState' => 1,
            'iLastModifyTime' => time(),
            'iLastModifyer' => 1,
        );
				
				//插入记录 
				//不分库：
				$this->add_row($data); 
				//---------------------
				//分库分表
				$this->map($key);
				$this->add_row($data);
				//或者
				$this->map($key)->add_row($data);
				
				
				//数据库链接有两种取法
				$this->conn($type);  //$type[ s:从库 m：主库] 默认连从库]
				//或者直接调用
				$this->db; [需要切换主从时调用$this->conn($type)]
				
				//分库分表取链接
				$this->map($key)->conn();
				//也可以先执行
				$this->map($key);
				//后面直接使用
				$this->db;
				
				//取用户单条信息 方法一：
        $user = $this->db->get_where($this->table_name, array('iRoleId'=>1))->row_array();
        print_r($user);
				//取用户单条信息 方法二：
				$user = $this->conn('m')->get_where($this->table_name, array('iRoleId'=>1))->row_array();
        print_r($user);
				
				//按主键取单条信息
				$user = $this->conn('m')->get_where($this->table_name, array('iRoleId'=>1))->row_array();
        print_r($user);
				
				//取用户信息
        $user = $this->conn('m')->get_where($this->table_name, array('iRoleId'=>1))->row_array();
        print_r($user);
        echo "<br/>";
        $user = $this->conn('s')->get_where($this->table_name, array('iRoleId'=>2))->row_array();
        print_r($user);
        echo "<br/>";
        $user = $this->conn()->get_where($this->table_name, array('iRoleId'=>1))->row_array();
        print_r($user);
        echo "<br/>";
				$user = $this->get_row_by_primary(1);
        return TRUE;
    }

    /**
     * 根据用户账户取用户信息
     * @param $user_name
     */
    public function get_admin_user_by_name ($user_name) {

    }

    public function is_disable() {

    }
}