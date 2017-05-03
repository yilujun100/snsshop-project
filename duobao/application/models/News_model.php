<?php defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends MY_Model
{
    /**
     * 新闻公告
     *
     * @var int
     */
    private $news_id;

    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_news';   //表名
    protected $cache_list_key = 'iNewsId_List' ;
    protected $table_primary = 'iNewsId';   //表名主键名称
    protected $cache_row_key_column = 'iNewsId'; //单条表记录缓存字段

    /**
     * Active_item_model constructor
     *
     * @param null $news_id
     */
    public function __construct($news_id = null)
    {
        parent::__construct();
        if ($news_id > 0) {
            $this->news_id = intval($news_id);
        }
    }

    /**
     * 插入数据 成功记入缓存
     * @param array $data
     * @param $map_key
     */
    public function add_row($data = array()) {
        $result = parent::add_row($data) ;
        $this->clear_list_cache() ;
        return $result ;
    }

    /**
     * 按主键删除单条记录 [真删],假删除调用更新方法
     * @param $primary
     */
    public function delete_row($params) {
        $result = parent::delete_row($params) ;
        $this->clear_list_cache() ;
        return $result ;
    }

    /**
     * 更新单条记录
     * 尽量调用主键更新
     * @param array $data
     * @param array $params
     * @return mixed
     */
    public function update_row($data, $params) {
        $result = parent::update_row($data, $params);
        $this->clear_list_cache() ;
        return $result ;
    }
    /**
     * @param string field
     * @param array $where
     * @param array $order_by
     * @param int $page_index
     * @param int $page_size
     * @return array|bool
     */
    public function get_news($field = '*',$where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {

        $this->load->driver('cache');
        $this->cache->memcached->is_supported();
        $cache_name_pre = $this->table_name."_".$this->cache_list_key ;
        $cache_name = $cache_name_pre.'-'.$field ;
        if(count($where)>0){
            $where_str = '';
            foreach($where as $key => $val){
                $where_str .= '-||-'.$key.'('.$val.')' ;
            }
            $cache_name = $cache_name.'_'.$where_str ;
        }
        if(count($order_by)>0){
            $order_by_str = '';
            foreach($order_by as $key => $val ){
                $order_by_str .= '-||-'.$key .'('.$val.')' ;
            }
            $cache_name = $cache_name.'_'.$order_by_str ;
        }
        $cache_name = $cache_name."-".$page_size."-".$page_index ;
        $cache_name = md5($cache_name);
        //先看有没缓存，
        $list_mem = $this->cache->memcached->get($cache_name);
        if ($list_mem !=false && !empty($list_mem)){
            $list = json_decode($list_mem,true) ;
        }else{
            $list = parent::row_list($field,$where, $order_by, $page_index, $page_size);
            $list_save = json_encode($list);
            $this->cache->memcached->save($cache_name,$list_save,600); //把查询结果缓存
            //keyname 缓存到 ”缓存名称列表“
            $cache_keynamelist = $this->table_name.'_keynamelist';
            $keynamelist_mem = $this->cache->memcached->get($cache_keynamelist);
            if(!empty($keynamelist_mem) && $keynamelist_mem!=false){
                $keynamelist_mem = json_decode($keynamelist_mem) ;
                if(!in_array($cache_name,$keynamelist_mem)){
                    $keynamelist_mem[] = $cache_name ;
                    $this->cache->memcached->save($cache_keynamelist,json_encode($keynamelist_mem)); //把keylist name缓存
                }
            }else{
                $keynamelist_mem = array($cache_name);
                $this->cache->memcached->save($cache_keynamelist,json_encode($keynamelist_mem),600000); //把keylist name缓存
            }

        }

        return $list ;
    }

    /**
     * 把单个表的所有查询列表的缓存清除掉
     */
    public function clear_list_cache(){
        $cache_keynamelist = $this->table_name.'_keynamelist';
        $keynamelist_mem = $this->cache->memcached->get($cache_keynamelist);
        if(!empty($keynamelist_mem) && $keynamelist_mem!=false){
            $keynamelist_mem = json_decode($keynamelist_mem);
            foreach($keynamelist_mem as $val ){
                $this->cache->memcached->delete($val);
            }
        }
    }
}