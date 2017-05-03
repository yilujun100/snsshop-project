<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 收藏
 */
class Active_collect_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_active_collect';                //表名
    protected $table_primary = 'iCollectId';                   //主键
    protected $table_num= 10;
    protected $cache_row_key_column = 'iCollectId';               //缓存key字段  可自定义
    protected $db_map_column = 'iUin';  //分表字段
    protected $need_cache_row = false;

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 增加收藏
     * @param $uin
     * @param $data
     * @return bool
     */
    public function add_collect($uin,$data)
    {
        if(empty($uin) || empty($data) || !isset($data['goods_id'])) return false;

        $arr = array(
            'iActId' => $data['act_id'],
            'iGoodsId' => $data['goods_id'],
            'sGoodsName' => $data['goods_name'],
            'sImg' => $data['img'],
            'iCodePrice' => $data['price'],
            'iUin' => $uin,
            'iCount' => $data['count'],
            'iCreateTime' => time()
        );
        if($result = $this->add_row($arr)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 更新收藏
     * @param $uin
     * @param $id
     * @return bool
     */
    public function update_collect($uin,$id,$count)
    {
        if(empty($uin) || empty($id)) return false;

        if($result = $this->update_rows(array('iCount'=>(int)$count),array('iUin'=>$uin,'iActId'=>$id))){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 删除收藏
     * @param $uin
     * @param $id
     * @return bool
     */
    public function del_collect($uin,$id)
    {
        if(empty($uin) || empty($id)) return false;

        if($result = $this->delete_row(array('iUin'=>$uin,'iActId'=>$id))){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 返回收藏列表
     * @param $uin
     * @return array|bool
     */
    public function get_collect_list($uin,$where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {
        if(empty($uin)) return false;

        $where = array_merge($where,array('iUin'=>$uin));
        $list = $this->row_list('*',$where,$order_by,$page_index,$page_size);
        if(!$list){
            return false;
        }
        return $list;
    }

    /**
     * @param $uin
     * @param $act_id
     * @return array|bool|mixed
     */
    public function get_collect($uin,$act_id)
    {
        if(empty($uin) || empty($act_id)) return false;

        $row = $this->get_row(array('iActId'=>$act_id,'iUin'=>$uin));
        if(!$row){
            return false;
        }

        return $row;
    }
}