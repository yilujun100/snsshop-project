<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Goods_category_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_goods_category';   // 表名
    protected $table_primary = 'iCateId';   // 表名主键名称
    protected $cache_row_key_column = 'iCateId'; // 单条表记录缓存字段
    protected $auto_update_time = true; // 自动更新createTime updateTime

    /**
     * 获取的最多分类数目
     */
    const MAX_CATE_COUNT = 600;

    /**
     * 类目ID
     *
     * @var int
     */
    private $cate_id;

    /**
     * Goods_category_model constructor
     *
     * @param null $cate_id
     */
    public function __construct($cate_id = null)
    {
        parent::__construct();
        if ($cate_id > 0) {
            $this->cate_id = intval($cate_id);
        }
    }

    /**
     * @param $cate_id
     */
    public function get_cate($cate_id)
    {
        $db = $this->conn();
        $cate = $db->where('iCateId', $cate_id)->limit(1)->get($this->table_name)->row_array();
        if (! $cate) {
            return;
        }
        $cate['parents'] = $this->fetch_parents($cate);
        return $cate;
    }

    /**
     * 获取类目列表
     *
     * @return mixed
     */
    public function fetch_list()
    {
        $db = $this->conn();
        return $db->order_by('iLft', 'ASC')->limit(self::MAX_CATE_COUNT)->get($this->table_name)->result_array();
    }

    /**
     * 获取一级类目
     *
     * @param $filterHidden
     */
    public function fetch_top($filterHidden = false)
    {
        $db = $this->conn()->where('iLvl', 1)->order_by('iSort', 'desc');
        if ($filterHidden) {
            $db->where('iIsShow', 1);
        }
        return $db->get($this->table_name)->result_array();
    }

    /**
     * 获取某个类目下一级子类目
     *
     * @param $cate
     */
    public function fetch_children($cate)
    {
        $db = $this->conn();
        if (! is_array($cate)) {
            $cate = $db->where('iCateId', $cate)->limit(1)->get($this->table_name)->row_array();
            if (! $cate) {
                return;
            }
        }
        return $db->where('iLvl', $cate['iLvl'] + 1)
            ->where('iLft >', $cate['iLft'])
            ->where('iRgt <', $cate['iRgt'])
            ->order_by('iSort', 'DESC')
            ->get($this->table_name)
            ->result_array();
    }

    /**
     * 获取父级类目树
     *
     * @param $cate
     */
    public function fetch_parents($cate)
    {
        $db = $this->conn();
        if (! is_array($cate)) {
            $cate = $db->where('iCateId', $cate)->limit(1)->get($this->table_name)->row_array();
            if (! $cate) {
                return;
            }
        }
        return $db->where('iLvl <', $cate['iLvl'])
            ->where('iLft <', $cate['iLft'])
            ->where('iRgt >', $cate['iRgt'])
            ->order_by('iLft', 'ASC')
            ->get($this->table_name)
            ->result_array();
    }

    /**
     * 显示类目
     *
     * @param $cate_id
     *
     * @return bool
     */
    public function show_cate($cate_id)
    {
        $db = $this->conn(true);

        $data = array(
            'iIsShow' => 1,
            'iUpdateTime' => time(),
        );

        return $db->where('iCateId', $cate_id)->limit(1)->update($this->table_name, $data);
    }

    /**
     * 隐藏类目
     *
     * @param $cate_id
     *
     * @return bool
     */
    public function hide_cate($cate_id)
    {
        $db = $this->conn(true);

        $data = array(
            'iIsShow' => 0,
            'iUpdateTime' => time(),
        );
        
        if (! $db->where('iCateId', $cate_id)->limit(1)->update($this->table_name, $data)) {
            
        }
        
        return $res;
    }

    /**
     * 更新类目
     *
     * @param $cate_id
     * @param $data
     *
     * @return bool
     */
    public function update_cate($cate_id, $data)
    {
        $db = $this->conn(true);

        $updData = array(
            'iUpdateTime' => time(),
        );
        if (isset($data['sName'])) {
            $updData['sName'] = $data['sName'];
        }
        if (isset($data['iIsShow'])) {
            $updData['iIsShow'] = $data['iIsShow'];
        }
        if (isset($data['iSort'])) {
            $updData['iSort'] = $data['iSort'];
        }
        if (isset($data['sRemark'])) {
            $updData['sRemark'] = $data['sRemark'];
        }
        return $db->where('iCateId', $cate_id)->limit(1)->update($this->table_name, $updData);
    }

    /**
     * 新增类目
     *
     * @param        $parentId
     * @param        $name
     * @param        $isShow
     * @param        $sort
     * @param string $remark
     * @return bool
     */
    public function add_cate($parentId, $name, $isShow, $sort = 0, $remark = '')
    {
        $db = $this->conn(true);
        $db->trans_start();

        if ($db->count_all() >= self::MAX_CATE_COUNT) { // 达到系统允许的最大类目数
            $db->trans_rollback();
            return;
        }

        if (0 === $parentId) { // 增加顶级类目
            $prev = $db->order_by('iRgt', 'DESC')->limit(1)->get($this->table_name)->row_array();
            if (! $prev) { // 第一个类目
                $lft = 0;
                $lvl = 1;
            } else {
                $lft = $prev['iRgt'];
                $lvl = 1;
            }
        } else {
            $parent = $db->where('iCateId', $parentId)->limit(1)->get($this->table_name)->row_array();
            if (! $parent) {
                $db->trans_rollback();
                return;
            }
            $lft = $parent['iLft'];
            $lvl = $parent['iLvl'] + 1;

            $sql = 'UPDATE ' . $this->table_name . ' SET iLft=iLft+2, iRgt=iRgt+2 WHERE `iLft`>' . $lft;
            if (! $db->query($sql, true)) {
                $db->trans_rollback();
                return;
            }

            $sql = 'UPDATE ' . $this->table_name . ' SET iRgt=iRgt+2 WHERE iLft<=' . $lft . ' AND iRgt>' . $lft;
            if (! $db->query($sql, true)) {
                $db->trans_rollback();
                return;
            }
        }

        $time = time();
        $data = array(
            'sName' => $name,
            'iIsShow' => $isShow,
            'iSort' => $sort,
            'iLft' => $lft + 1,
            'iRgt' => $lft + 2,
            'iLvl' => $lvl,
            'iCreateTime' => $time,
            'iUpdateTime' => $time,
        );
        if ($remark) {
            $data['sRemark'] = $remark;
        }
        if (! $db->insert($this->table_name, $data)) {
            $db->trans_rollback();
            return;
        }

        if ($db->trans_status() === FALSE) {
            $db->trans_rollback();
        } else {
            $db->trans_commit();
            return true;
        }
    }

    /**
     * 删除类目
     *
     * @param $cate_id
     * @return bool
     */
    public function delete_cate($cate_id)
    {
        $db = $this->conn(true);
        $db->trans_start();

        $cate = $db->where('iCateId', $cate_id)->limit(1)->get($this->table_name)->row_array();
        if (! $cate) {
            $db->trans_rollback();
            return;
        }

        $lft = $cate['iLft'];
        $rgt = $cate['iRgt'];
        $dValue = $rgt - $lft + 1;

        $sql = 'DELETE FROM ' . $this->table_name . ' WHERE iLft>= ' . $lft . ' AND `iRgt`<=' . $rgt;
        if (! $db->query($sql, true)) {
            $db->trans_rollback();
            return;
        }

        $sql = 'UPDATE ' . $this->table_name . ' SET iLft=iLft-'.$dValue.', iRgt=iRgt-' . $dValue . ' WHERE `iLft`>' . $lft;
        if (! $db->query($sql, true)) {
            $db->trans_rollback();
            return;
        }

        $sql = 'UPDATE ' . $this->table_name . ' SET iRgt=iRgt-' . $dValue . ' WHERE iLft<' . $lft . ' AND iRgt>' . $lft;
        if (! $db->query($sql, true)) {
            $db->trans_rollback();
            return;
        }

        if ($db->trans_status() === FALSE) {
            $db->trans_rollback();
        } else {
            $db->trans_commit();
            return true;
        }
    }
}