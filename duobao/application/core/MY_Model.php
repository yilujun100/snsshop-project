<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class MY_Model
 * 自定义基础Model
 */
class MY_Model extends CI_Model {
    const PAGE_SIZE = 20;       //分页每页记录数
    const MAX_PAGE_SIZE = 1000; //分页每页记录最大限制数

    const DB_GROUP_SUFFIX_M  = '_master';  // 数据库主库分组后缀
    const DB_GROUP_SUFFIX_S  = '_slave';   //数据库从库分组后缀

    protected $db_group_name = ''; //数据库分组名称
    protected $cur_db_group_name;  //当前分组名
    protected $logic_group = '';   //业务逻辑分组
    private $is_write = FALSE;

    protected $table_name;      //表名
    protected $db_name;         //库名

    protected $cur_table_name;  //当前表名
    protected $cur_db_name;     //当前库名

    protected $table_primary;   //表名主键名称


    protected $db_num = 1;      //数据库分库数 不分库： 1
    protected $table_num = 1;   //数据库分表数  不分表： 1
    protected $db_map_column;   //分库分表字段

    protected $cur_db_num = 0;      //当前数据库分库数 不分库： 0
    protected $cur_table_num = 0;   //当前数据库分表数 不分表： 0

    protected $ci_conn_name;        //数据库连接在conn中的名称
    protected $model_conn_name;     //数据库连接在model中的名称

    protected $need_cache_row = FALSE;      //是否需要缓存表记录 默认缓存  如不需要可在子model中重置
    protected $cache_row_key_column = null; //单条表记录缓存字段
    protected $cache_row_expired = 600;     //单条表记录缓存周期 单位：秒

    protected $auto_update_time = false;    //添加或修改记录时自动更新createtime 或updatetime
    protected $can_real_delete = false;     //表是否能真删数据

    protected $read_master_db = false;
    protected $cache_key_prefix = ''; //缓存前缀 填写直接使用该项  分库分表慎用

    protected $increase_fields = array(); // 可累加字段配置，参见 increase 方法

    public function __construct() {
        parent::__construct();

        $this->init();
    }

    private function init() {
        $this->db_name OR $this->db_name = $this->db_group_name;
        if (!$this->is_multi_db()) {
            $this->cur_table_name = $this->table_name;
            $this->cur_db_name = $this->db_name;
            $this->cur_db_group_name = $this->db_group_name;
            if(!$this->db_map_column) {
                $this->db_map_column = $this->table_primary;
            }
        }

        if ($this->need_cache_row && is_null($this->cache_row_key_column)) {
            $this->cache_row_key_column = $this->table_primary;
        }
    }

    /**
     * @param string $frome_write
     * @return bool
     */
    private function init_conn($frome_write= false) {
        $this->is_write = FALSE;
        if ($frome_write) {
            $suffix = self::DB_GROUP_SUFFIX_M;
            $this->is_write = TRUE;
        } else {
            $suffix =  self::DB_GROUP_SUFFIX_S;
        }

        $this->ci_conn_name = 'db_'.$this->cur_db_group_name.$suffix;
        $this->model_conn_name = 'db_'.$this->cur_db_group_name.$suffix;

        return TRUE;
    }

    /**
     * 子类中调用读/写连接
     * @param $type
     * @param $frome_write 是否从主库
     * @return mixed
     */
    protected function conn($frome_write= false) {
        try{
            if(!empty($this->db)){
                $this->db->reconnect(); //Fixed MySQL server has gone away
            }
            $this->init_conn($frome_write);
            if ((isset($this->db) && !empty($this->db->conn_id)) && isset($this->{$this->model_conn_name}) && is_object($this->{$this->model_conn_name})) {
                return $this->{$this->model_conn_name};
            }
            $this->load_conn();
            return $this->{$this->model_conn_name};
        } catch(Exception $e) {
            //写日志
            return null;
        }
    }

    /**
     * 加载数据库连接
     * @param $ci_conn_name db连接在超级类$CI中的名称
     * @param $model_conn_name db连接在model中的名称
     * @param $group_name   数据库分组名称
     */
    private function load_conn() {
        $CI = & get_instance();
        if(isset($CI->{$this->ci_conn_name}) &&  !empty($CI->{$this->ci_conn_name}->conn_id) &&  is_object($CI->{$this->ci_conn_name})) {
            $this->{$this->model_conn_name} = $this->db = $CI->{$this->ci_conn_name};
        } else {
            $map_group_name = Lib_MapReduce::map($this->cur_db_group_name.$this->logic_group, $this->is_write);
            //$this->log->error('MYMODEL', $map_group_name.' | '.$this->cur_db_group_name);
            $CI->{$this->ci_conn_name} = $this->{$this->model_conn_name} = $this->db = $CI->load->database($map_group_name, TRUE, TRUE, $this->ci_conn_name);
        }
    }

    /**
     * 是否分库分表
     * @return bool
     */
    private function is_multi() {
        return ($this->db_num > 1 || $this->table_num > 1) ? TRUE : FALSE;
    }

    /**
     * 是否分库
     * @return bool
     */
    private function is_multi_db() {
        return ($this->db_num > 1) ? TRUE : FALSE;
    }

    /**
     * 是否分表
     * @return bool
     */
    private function is_multi_table() {
        return ($this->table_num > 1) ? TRUE : FALSE;
    }

    /**
     * 设置分库数
     * @param $db_num
     */
    private function set_cur_db($db_num) {
        if($db_num >=0 && $db_num < $this->db_num) {
            $this->cur_db_num = intval($db_num);
            $this->cur_db_name = $this->db_name.$this->cur_db_num;
            $this->cur_db_group_name = $this->db_group_name.$this->cur_db_num;
        }
    }

    /**
     * 设置分库数
     * @param $db_num
     */
    private function set_cur_table($table_num) {
        if($table_num >= 0 && $table_num < $this->table_num) {
            $this->cur_table_num = intval($table_num);
            $this->cur_table_name = $this->table_name.$this->cur_table_num;
        }
    }

    /**
     * 根据key确定是几库几表  原则上整十倍数比较好
     * @param $key
     * @return array(db_num, table_num)
     * 1111111XY X:库名  Y:表名  eg:   11111123  对应2库3表
     */
    public function map($key = '') {
        if(is_numeric($key)) {
            $key = substr($key, -6);
        } elseif (is_string($key)) {
            $key = intval($this->get_uin_suffix($key));
        } else {
            return $this;
        }

        if ($this->is_multi_table()) {
            $this->set_cur_table($key%$this->table_num);
        }

        if ($this->is_multi_db()) {
            $this->set_cur_db(($key/$this->table_num)%$this->db_num);
        }

        return $this;
    }

    public function get_cur_table()
    {
        return $this->cur_table_name;
    }

    public function get_cur_database()
    {
        return $this->cur_db_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    private function set_db_map($params) {
        if ($this->is_multi()) {
            if(!$params) {
                throw new Exception('multi db have not set map key! '.$this->table_name, Lib_Errors::DB_NOT_MAP);
            } else if (is_string($params)) {
                $this->map($params);
            } else if (is_array($params)){
                if (is_array($this->db_map_column)) {
                    $flag = false;
                    foreach ($this->db_map_column as $map) {
                        if (!empty($params[$map])) {
                            $this->map($params[$map]);
                            $flag = true;
                            break;
                        }
                    }
                    if (!$flag) {
                        throw new Exception('multi db have not set map key! '.$this->table_name, Lib_Errors::DB_NOT_MAP);
                    }
                } else {
                    if (!empty($params[$this->db_map_column])) {
                        $this->map($params[$this->db_map_column]);
                    } else {
                        throw new Exception('multi db have not set map key! '.$this->table_name, Lib_Errors::DB_NOT_MAP);
                    }
                }
            } else {
                throw new Exception('multi db have not set map key! '.$this->table_name, Lib_Errors::DB_NOT_MAP);
            }
        }
    }

    /**
     * 插入数据 成功记入缓存
     * @param array $data
     * @param $map_key
     */
    public function add_row($data = array()) {
        $this->set_db_map($data);
        if ($this->auto_update_time) {
            $time = time();
            $data = array_merge($data, array('iCreateTime'=>$time,'iUpdateTime'=>$time));
        }
        if ($this->conn(true)->insert($this->cur_table_name, $data)) {
            $insert_id = $this->conn(true)->insert_id();
            if ($insert_id && $this->need_cache_row && $row = $this->get_row($insert_id)) {
                $this->cache_row($row);
            }

            if ($insert_id) {
                $data[$this->table_primary] = $insert_id;
            }

            //更新缓存
            if ($this->need_cache_row) {
                $this->update_cache_row($data);
            }

            return $insert_id ? $insert_id :true;
        } else {
            return false;
        }
    }

    /**
     * 根据主键取单条记录
     * @param $primary 表主键
     * @param $from_write 是否从主库读取
     */
    public function get_row($params, $from_write = false, $from_cache=true)
    {
        if(!$params) {
            return array();
        }

        $this->set_db_map($params);
        if (!is_array($params)) {
            $params = array($this->table_primary=>$params);
        }

        if ($this->need_cache_row && $from_cache) {
            $key_arr = $this->get_cache_key($params);
            if ($key_arr) {
                $ret = $this->get_row_from_cache($key_arr);
                if (!$ret) {
                    $ret = $this->conn($from_write)->from($this->cur_table_name)->where($params)->limit(1)->get()->row_array();
                    if ($ret) { //更新缓存
                        $this->update_cache_row($ret, true);
                    }
                }
            } else {
                $ret = $this->conn($from_write)->from($this->cur_table_name)->where($params)->limit(1)->get()->row_array();
                if ($ret) { //更新缓存
                    $this->update_cache_row($ret, true);
                }
            }
        } else {
            $ret = $this->conn($from_write)->from($this->cur_table_name)->where($params)->limit(1)->get()->row_array();
        }

        return $ret;
    }

    /**
     * 根据条件获取多条记录
     * @param $primary 表主键
     * @param $from_write 是否从主库读取
     */
    public function get_rows($params, $from_write = false)
    {
        if(!$params) {
            return array();
        }

        $this->set_db_map($params);
        if (!is_array($params)) {
            $params = array($this->table_primary=>$params);
        }

        $return = $this->conn($from_write)->from($this->cur_table_name)->where($params)->get();
        if(empty($return)){
            return array();
        }else{
            return $return->result_array();
        }
    }

    /**
     * @param $sql
     * @param bool $from_write
     */
    public function query($sql,$from_write = false)
    {
        if(empty($sql)){
            return array();
        }

        $ret = $this->conn($from_write)->query($sql);
        if(!$ret){
            return false;
        }

        return is_object($ret) ? $ret->result_array() : $ret;
    }

    /**
     * 按主键删除单条记录 [真删],假删除调用更新方法
     * @param $primary
     */
    public function delete_rows($params) {
        if ($this->can_real_delete) {
            $this->set_db_map($params);
            if (!is_array($params)) {
                $params = array($this->table_primary=>$params);
            }

            if ($this->need_cache_row) {
                $row_list  = $this->get_rows($params, true);
            }

            $ret = $this->conn(true)->delete($this->cur_table_name, $params);
            $affect_row = $this->conn(true)->affected_rows();
            //删除缓存
            if ($ret && $this->need_cache_row && !empty($row_list)) {
                foreach ($row_list as $row) {
                    $this->delete_cache_row($row, true);
                }
            }
            return $ret ? $affect_row : false;
        } else {
            return false;
        }
    }

    /**
     * 按主键删除单条记录 [真删],假删除调用更新方法
     * @param $primary
     */
    public function delete_row($params) {
        $this->set_db_map($params);
        if (!is_array($params)) {
            $params = array($this->table_primary=>$params);
        }

        if ($this->need_cache_row) {
            $row = $this->get_row($params, true, false);
        }
        $ret = $this->conn(true)->limit(1)->delete($this->cur_table_name, $params);
        //删除缓存
        if ($ret && $this->need_cache_row && !empty($row)) {
                $this->delete_cache_row($row, true);
        }
        return $this->conn(true)->limit(1)->delete($this->cur_table_name, $params) ? $this->conn(true)->affected_rows() : false;
    }


    /**
     * 更新单条或多条记录
     * 设计缓存更新 不推荐使用此方法
     * 尽量调用主键更新
     * @param array $data
     * @param array $params
     * @return mixed
     */
    public function update_rows($data, $params) {
        $this->set_db_map($params);
        if ($this->auto_update_time) {
            $data = array_merge($data, array('iUpdateTime'=>time()));
        }
        if (!is_array($params)) {
            $params = array($this->table_primary=>$params);
        }
        $ret = $this->conn(true)->update($this->cur_table_name, $data, $params);

        //更新缓存
        if ($this->need_cache_row && $ret) {
            $row_list = $this->get_rows($params, true);
            if ($row_list) {
                foreach ($row_list as $row) {
                    $this->update_cache_row($row, true);
                }
            }
        }
        return $this->conn(true)->update($this->cur_table_name, $data, $params) ? $this->conn(true)->affected_rows() : false;
    }

    /**
     * 更新单条或多条记录
     * 设计缓存更新 不推荐使用此方法
     * 尽量调用主键更新
     * @param array $data
     * @param array $params
     * @return mixed
     */
    public function update_row($data, $params) {
        $this->set_db_map($params);
        if ($this->auto_update_time) {
            $data = array_merge($data, array('iUpdateTime'=>time()));
        }
        if (!is_array($params)) {
            $params = array($this->table_primary=>$params);
        }

        //更新缓存
        $ret = $this->conn(true)->limit(1)->update($this->cur_table_name, $data, $params);
        if ($ret && $this->need_cache_row) {
            $row = $this->get_row($params, true, false);
            $this->update_cache_row($row, true);
        }

        return $ret;
    }

    /**
     * 取单条记录缓存
     * @param $key
     * @return bool|mixed
     */
    public function get_row_from_cache($key) {
        $cache_key = $this->get_row_cache_key($key);
        $this->load->driver('cache');
        $row = $this->cache->memcached->get($cache_key);
        return $row ? json_decode($row, TRUE) : FALSE;
    }

    /**
     * 缓存单条记录
     * @param array $data
     */
    protected function cache_row($data=array()) {
        $key_arr = $this->get_cache_key($data);
        if (!empty($key_arr)) {
            $cache_key = $this->get_row_cache_key($key_arr);
            $this->load->driver('cache');
            $this->cache->memcached->save($cache_key, json_encode($data), $this->cache_row_expired);
        }
    }

    public function row_count($where, $group_by = array(), $from_write = false) {
        $this->set_db_map($where);
        $db = $this->conn($from_write);

        if (is_string($where)) {
            $db->where($where);
        } else {
            //此处为了支持where_in和where_not_in查询
            $where_in = $where_not_in = array();
            if(isset($where['where_in'])){
                $where_in = $where['where_in'];
                $db->where_in($where_in[0],$where_in[1]);
                unset($where['where_in']);
            }
            if(isset($where['where_not_in'])){
                $where_not_in = $where['where_not_in'];
                $db->where_not_in($where_not_in[0],$where_not_in[1]);
                unset($where['where_not_in']);
            }

            if(isset($where['where_join'])){ //跨库联表慎用~~~
                $where_join = $where['where_join'];
                $db->join($where_join[0],$where_not_in[1]);
                unset($where['where_join']);
            }

            //此处为了支持like模糊查询,这里暂只支持数组
            $like = array();
            if(isset($where['like'])){
                $like = $where['like'];
                $db->like($like);
                unset($where['like']);
            }

            foreach ($where as $key=>$val) {
                $db->where($key,$val);
            }

            if(is_array($group_by)){
                foreach($group_by as $key=>$val){
                    $db->group_by($val);
                }
            }

        }

        $count = $db->select('count(*) as total')->from($this->cur_table_name)->get()->row_array();
        return $count ? intval($count['total']) : 0;
    }

    /**
     * @param string $fields
     * @param string $where
     * @param array $order_by
     * @param int $page_index
     * @param int $page_size
     * @param string $type
     * @return array
     */
    public function row_list($fields='*', $where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE , $group_by = array(),$from_write = false) {
        $this->set_db_map($where);

        $ret = array(
            'count' => 0,
            'list' => array(),
            'page_count' => 0,
            'page_size' => $page_size,
            'page_index' => $page_index,
        );

        $db = $this->conn($from_write);

        if (is_string($where)) {
            $db->where($where);
        } else {
            //此处为了支持where_in和where_not_in查询
            $where_in = $where_not_in = array();
            if(isset($where['where_in'])){
                $where_in = $where['where_in'];
                $db->where_in($where_in[0],$where_in[1]);
                unset($where['where_in']);
            }
            if(isset($where['where_not_in'])){
                $where_not_in = $where['where_not_in'];
                $db->where_not_in($where_not_in[0],$where_not_in[1]);
                unset($where['where_not_in']);
            }

            if(isset($where['where_join'])){ //跨库联表慎用~~~
                $where_join = $where['where_join'];
                $db->join($where_join[0],$where_not_in[1]);
                unset($where['where_join']);
            }

            //此处为了支持like模糊查询,这里暂只支持数组
            $like = array();
            if(isset($where['like'])){
                $like = $where['like'];
                $db->like($like);
                unset($where['like']);
            }

            foreach ($where as $key=>$val) {
                $db->where($key,$val);
            }
        }

        if(empty($group_by)){
            $count = $db->select('count(*) as total')->from($this->cur_table_name)->get()->row_array();
        }else{
            if(is_array($group_by)){
                $group_key = implode(',',$group_by);
            }else{
                $group_key = $group_by;
            }
            $count = $db->select('COUNT(DISTINCT '.$group_key.') AS total')->from($this->cur_table_name)->get()->row_array();
        }

        if ($count) {
            $count = $count['total'];
            $ret['page_count'] = ceil($count/$page_size);
            $list = array();
            if ($page_index <= $ret['page_count']) {
                if (is_array($fields)) {
                    $fields = implode(',', $fields);
                }
                if (is_string($fields) && $fields != '*' && $fields) {
                    $db->select($fields);
                }

                if(is_array($group_by)){
                    foreach($group_by as $key=>$val){
                        $db->group_by($val);
                    }
                }

                if (is_array($order_by)) {
                    foreach($order_by as $key=>$val) {
                        $db->order_by($key,$val);
                    }
                }

                $offset = ($page_index-1)*$page_size;
                if(!empty($where_not_in) && !empty($where_in)){
                    $list = $db->from($this->cur_table_name)->where($where)->where_in($where_in[0],$where_in[1])->where_not_in($where_not_in[0],$where_not_in[1])->offset($offset)->limit($page_size)->get()->result_array();
                }elseif(!empty($where_in)){
                    $list = $db->from($this->cur_table_name)->where($where)->where_in($where_in[0],$where_in[1])->offset($offset)->limit($page_size)->get()->result_array();
                }elseif(!empty($where_not_in)){
                    $list = $db->from($this->cur_table_name)->where($where)->where_not_in($where_not_in[0],$where_not_in[1])->offset($offset)->limit($page_size)->get()->result_array();
                }else{
                    $list = $db->from($this->cur_table_name)->where($where)->like($like)->offset($offset)->limit($page_size)->get()->result_array();
                }
            }

            $ret['list'] = $list;
        } else {
            $count = 0;
        }
        $ret['count'] = $count;
        return $ret;
    }

    /**
     * 更新记录缓存
     * @param $data
     * @param $is_db_row
     */
    protected function update_cache_row($data, $is_db_row=false)
    {
        $key_arr = $this->get_cache_key($data);
        if (!$key_arr) {
            $this->log->error('MY_Model', 'Update cache row | get cache key failed | '.json_encode($data).' | '.__METHOD__);
        } else {
            if (!$is_db_row) {
                $data = $this->get_row($key_arr, true, false);
            }

            $cache_key = $this->get_row_cache_key($key_arr);
            $this->load->driver('cache');

            if (!$this->cache->memcached->save($cache_key, json_encode($data), $this->cache_row_expired)) {
                $this->log->notice('MY_Model', 'Update cache row | failed | key[ '.$cache_key.'] | '.__METHOD__);
            }
        }
    }

    /**
     * 批量更新缓存
     * @param $data
     * @param bool $from_write
     * @return bool
     */
    public function update_cache_rows($data,$from_write = false){
        if(!is_array($data) || !$this->need_cache_row){
            return false;
        }

        $list = $this->get_rows($data,$from_write);
        if(empty($list)) return false;

        $this->load->driver('cache');
        foreach($list as $li){
            $key_arr = $this->get_cache_key($li);
            $cache_key = $this->get_row_cache_key($key_arr);

            if (!$this->cache->memcached->save($cache_key, json_encode($li), $this->cache_row_expired)) {
                $this->log->notice('MY_Model', 'Update cache rows | failed | key[ '.$cache_key.'] | '.__METHOD__);
                return false;
            }
        }

        return true;
    }

    /**
     * 更新记录缓存
     * @param $data
     * @param $is_db_row
     */
    protected function delete_cache_row($data, $is_db_row=false)
    {
        $key_arr = $this->get_cache_key($data);
        if (!$key_arr) {
            if (!$is_db_row) {
                $data = $this->get_row($data, true, false);
                if ($data) {
                    $key_arr = $this->get_cache_key($data);
                }
            }
        }

        if (!$key_arr) {
            $this->log->error('MY_Model', 'Update cache row | get cache key failed | '.json_encode($data).' | '.__METHOD__);
            return false;
        }
        $cache_key = $this->get_row_cache_key($key_arr);
        $this->load->driver('cache');
        $this->cache->memcached->delete($cache_key);
    }

    /**
     * 获取数据库单条记录缓存key
     * @param array $key
     * @return string
     */
    private function get_row_cache_key($key) {
        if(is_array($key)) {
            $key = implode('_', $key);
        }
        $key = trim($key, '_');
        if ($this->cache_key_prefix) {
            return $this->cache_key_prefix.$key;
        } else {
            return $this->cur_db_name.'_'.$this->cur_table_name.'_'.$key;
        }

    }

    /**
     * 取缓存key
     * @param $data
     * @return string
     */
    protected function get_cache_key($data)
    {
        $key = array();
        if (is_array($data)) {
            if (is_string($this->cache_row_key_column)) {
                if (isset($data[$this->cache_row_key_column])) {
                    $key[$this->cache_row_key_column] = $data[$this->cache_row_key_column];
                }
            } elseif (is_array($this->cache_row_key_column)) {
                foreach ($this->cache_row_key_column as $column) {
                    if (!isset($data[$column])) {
                        $key = array();
                        break;
                    }
                    $key[$column] = $data[$column];
                }
            }
        } else {
            if ($this->cache_row_key_column == $this->table_primary) {
                $key = $data;
            }
        }
        return $key;
    }

    public function get_table_primary()
    {
        return $this->table_primary;
    }

    /**
     * 更新发布字段状态
     * @param $primary
     */
    public function update_state($primary, $state)
    {
        $ori_row = $this->get_row($primary);
        if (!$ori_row) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (!$valid = Lib_Constants::valid_publish_state($ori_row['iState'], $state)) {
            return Lib_Errors::PARAMETER_ERR;
        }
        return $this->update_row(array('iState'=>$state), $primary) ? Lib_Errors::SUCC : Lib_Errors::SVR_ERR;
    }

    /**
     * 用户uin后两位
     * @param $str
     * @return string
     */
    protected  function get_uin_suffix($str)
    {
        if ($str) {
            $num = ord(substr($str,-1)) + ord(substr($str,-2, 1));
            return str_pad($num % 100, 2, 0, STR_PAD_LEFT);
        }
        return str_pad(rand(0,10), 2, 0, STR_PAD_LEFT);//2位
    }

    /**
     * 累加器
     *
     * @param     $params
     * @param     $field
     * @param     $limitField
     * @param int $count
     *
     * @return array|bool
     */
    public function increase($params, $field, $limitField=null, $count=1)
    {
        if (empty($this->increase_fields) || ! in_array($field, $this->increase_fields)) {
            return false;
        }

        $this->set_db_map($params);

        if (! is_array($params)) {
            $params = array($this->table_primary => $params);
        }

        $db = $this->conn(true);
        $where_str = '1=1';
        foreach ($params as $key => $value) {
            $where_str .= " AND `$key`=" . $db->escape($value);
        }
        $where_str = str_replace('1=1 AND ', '', $where_str);
        if ($limitField) {
            $where_str .= " AND `{$limitField}`>={$field}+{$count}";
        }
        $sql = "UPDATE {$this->cur_table_name} SET {$field}={$field}+{$count} WHERE $where_str LIMIT 1";
        $ret = $this->query($sql, true);
        if ($ret && $this->need_cache_row) {
            $row = $this->get_row($params, true, false);
            $this->update_cache_row($row, true);
        }

        return $ret;
    }
}