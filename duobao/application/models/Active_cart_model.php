<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 购物车MODEL
 */
class Active_cart_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_active_cart';                //表名
    protected $table_primary = 'iCartId';                   //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num= 10;
    protected $db_map_column = 'iUin';  //分表字段
    protected $need_cache_row = false;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 增加购物车
     * @param $uin
     * @param $data
     * @return bool
     */
    public function add_cart($uin,$data)
    {
        if(empty($uin) || empty($data) || !isset($data['goods_id']) || !isset($data['act_id'])) return false;

        $arr = array(
            'iGoodsId' => $data['goods_id'],
            'iUin' => $data['uin'],
            'iBuyCount' => $data['count'],
            'iActId' => $data['act_id'],
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );
        if($result = $this->add_row($arr)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 获取用户购物车列表
     * @param $uin
     * @param array $where
     * @param array $order_by
     * @param int $page_index
     * @param int $page_size
     * @return bool
     */
    public function get_user_carts($uin,$where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {
        if(empty($uin)) return false;

        $where = array_merge($where,array('iUin' => $uin));
        return $list = $this->row_list('*',$where, $order_by, $page_index, $page_size);
    }


    /**
     * 删除购物车
     * @param $uin
     * @param $cart_id
     * @return bool
     */
    public function del_user_cart($uin,$cart_id)
    {
        if(empty($uin) || empty($cart_id)) return false;

        if($result = $this->delete_row(array('iUin'=>$uin,'iCartId'=>$cart_id))){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 更新购物车
     * @param $uin
     * @param $cart_id
     * @param $count
     * @return bool
     */
    public function update_user_cart($uin,$cart_id,$count)
    {
        if(empty($uin) || empty($cart_id)) return false;

        if($result = $this->update_row(array('iBuyCount'=>$count),array('iUin'=>$uin,'iCartId'=>$cart_id))){
            return true;
        }else{
            return false;
        }
    }
}