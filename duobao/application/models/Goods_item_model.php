<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_item_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_goods_item';   // 表名
    protected $table_primary = 'iGoodsId';   // 表名主键名称
    protected $cache_row_key_column = 'iGoodsId'; // 单条表记录缓存字段
    protected $auto_update_time = true; // 自动更新createTime updateTime
    protected $can_real_delete = true;     //表是否能真删数据
    protected $need_cache_row = false;

    protected $table_name_cate = 't_goods_category';   // 类目表

    /**
     * 商品ID
     *
     * @var int
     */
    private $goods_id;

    /**
     * Role_model constructor
     *
     * @param null $goods_id
     */
    public function __construct($goods_id = null)
    {
        parent::__construct();
        if ($goods_id > 0) {
            $this->goods_id = intval($goods_id);
        }
    }

    /**
     * 获取商品列表
     *
     * @param array $where
     * @param array $order_by
     * @param int   $page_index
     * @param int   $page_size
     *
     * @return array
     */
    public function fetch_list($where = array(), $order_by = array(), $page_index = 1, $page_size = parent::PAGE_SIZE)
    {
        $ret = array(
            'count' => 0,
            'list' => array(),
            'page_count' => 0,
            'page_size' => $page_size,
            'page_index' => $page_index,
        );

        $db = $this->conn()
            ->select("a.*, b.sName as sCateName")
            ->from($this->table_name . ' AS a')
            ->join($this->table_name_cate . ' AS b', "a.iCateId=b.iCateId", 'left');
        if (! empty($where['like'])) {
            $db->like($where['like']);
        }
        if (isset($where['like'])) {
            unset($where['like']);
        }
        if (! empty($where['group']) && is_array($where['group'])) {

        }
        if (isset($where['group'])) {
            unset($where['group']);
        }
        if (! empty($where)) {
            $db->where($where);
        }
        $count = $db->count_all_results('', FALSE);

        if ($count) {
            $ret['page_count'] = ceil($count / $page_size);
            $list = array();
            if ($page_index <= $ret['page_count']) {
                if (is_array($order_by)) {
                    foreach($order_by as $key=>$val) {
                        $db->order_by($key,$val);
                    }
                }
                $offset = ($page_index - 1) * $page_size;
                $list = $db->offset($offset)->limit($page_size)->get()->result_array();
            }
            if($list) {
                $ret['list'] = $list;
            }
        } else {
            $count = 0;
            $db->reset_query();
        }
        $ret['count'] = $count;
        return $ret;
    }

    /**
     * 上/下线
     *
     * @param $good_id
     * @param $state
     *
     * @return int
     */
    public function update_state($good_id, $state)
    {
        $ori_row = $this->get_row($good_id);
        if (! $ori_row) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (! $valid = Lib_Constants::valid_publish_state($ori_row['iState'], $state)) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (Lib_Constants::PUBLISH_STATE_OFFLINE == $state) { // 下线
            $time = time();
            $this->load->model('active_config_model');
            $undone_count = $this->active_config_model->row_count(array(
                'iGoodsId' => $good_id,
                'iState <' => Lib_Constants::PUBLISH_STATE_END,
                'iEndTime' >= $time
            ));
            if ($undone_count > 0) {
                return Lib_Errors::GOODS_UNDONE_ACTIVE;
            }
            $this->load->model('score_activity_model');
            $undone_count = $this->score_activity_model->row_count(array(
                'iGoodsId' => $good_id,
                'iState <' => Lib_Constants::PUBLISH_STATE_END,
                'iEndTime' >= $time
            ));
            if ($undone_count > 0) {
                return Lib_Errors::GOODS_UNDONE_ACTIVITY;
            }
        }

        if ($this->update_row(array('iState'=>$state), $good_id)) {
            return Lib_Errors::SUCC;
        }

        $this->log->error('admin goods', 'update_state error', array('goods_id'=>$good_id,'state'=>$state,'goods'=>$ori_row));

        return Lib_Errors::SVR_ERR;
    }
}