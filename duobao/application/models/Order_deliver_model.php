<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_deliver_model extends MY_Model {

    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_order_deliver';         //表名
    protected $table_primary = 'iAutoId';              //主键
    protected $auto_update_time = true;

    protected $need_cache_row = false;                  //缓存key字段  可自定义

    protected $table_name_goods = 't_goods_item'; // 商品表

    public function __construct()
    {
        parent::__construct();
    }

	//添加发货记录
    public function add_deliver_row($uin,$goods_id,$order_id,$type,$remark = '')
    {
        if(empty($uin) || empty($goods_id) || empty($order_id) || empty($type)){
            return false;
        }

        $data = array(
            'iGoodsId' => $goods_id,
            'sOrderId' => $order_id,
            'iUin' => $uin,
            'iType' => $type,
            'sExpressId' => '',
            'sExpressName' => '',
            'sAddress' => '',
            'sName' => '',
            'sMobile' => '',
            'sRemark' => $remark,
            'sExtField' => json_encode(array('1'=>time())),
            'iCreateTime' => time(),
            'iUpdateTime' => time()
        );

        return $return = $this->add_row($data);
    }

    public function update_deliver_row($uin,$order_id,$name,$mobile,$address,$ext){
        if(empty($uin) || empty($order_id) || empty($name) || empty($address) || empty($mobile)){
            return false;
        }

        return $this->update_row(array('sAddress'=>$address,'sName'=>$name,'sMobile'=>$mobile,'sExtField'=>$ext),array('sOrderId'=>$order_id,'iUin'=>$uin));
    }

    /**
     * @param $deliver_id
     */
    public function get_deliver($deliver_id)
    {
        $db = $this->conn();
        return $db->select("{$this->table_name}.*, {$this->table_name_goods}.sImg as sGoodsImg, {$this->table_name_goods}.sName as sGoodsName")
            ->from($this->table_name)
            ->join($this->table_name_goods, "{$this->table_name}.iGoodsId={$this->table_name_goods}.iGoodsId", 'left')
            ->where($this->table_primary, $deliver_id)
            ->limit(1)
            ->get()
            ->row_array();
    }

	public function fetch_list($where = array(), $order_by = array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {
        $ret = array(
            'count' => 0,
            'list' => array(),
            'page_count' => 0,
            'page_size' => $page_size,
            'page_index' => $page_index,
        );
        $db = $this->conn();
        foreach ($where as $key => $val) {
            $db->where($this->table_name . '.' . $key, $val);
        }
        $count = $db->select('count(*) as total')->from($this->table_name)->get()->row_array();
        if ($count) {
            $count = $count['total'];
            $ret['page_count'] = ceil($count/$page_size);
            $list = array();
            if ($page_index <= $ret['page_count']) {
                if (is_array($order_by)) {
                    foreach($order_by as $key=>$val) {
                        $db->order_by($this->table_name . '.' . $key, $val);
                    }
                }
                $offset = ($page_index-1)*$page_size;
                foreach ($where as $key => $val) {
                    $db->where($this->table_name . '.' . $key, $val);
                }
                $list = $db->select("{$this->table_name}.*, {$this->table_name_goods}.sImg as sGoodsImg, {$this->table_name_goods}.sName as sGoodsName")
                    ->from($this->table_name)
                    ->join($this->table_name_goods, "{$this->table_name}.iGoodsId={$this->table_name_goods}.iGoodsId", 'left')
                    ->offset($offset)->limit($page_size)
                    ->get()
                    ->result_array();
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
}