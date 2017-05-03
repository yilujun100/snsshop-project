<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 参团-user model
 *
 * Class Groupon_join_user_model
 */
class Groupon_join_user_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB_USER; // 分组名
    protected $table_name  = 't_groupon_join'; // 表名
    protected $table_primary = 'iJoinId'; // 主键
    protected $cache_row_key_column  = 'iJoinId'; // 缓存key字段
    protected $table_num = 10;
    protected $db_map_column = array('iUin', 'sOrderId'); //分库分表字段
    protected $auto_update_time = true; // 自动更新 createtime 或 updatetime

    /**
     * 我的团列表
     * @param $params
     * @param $order_by
     * @param int $p_index
     * @param int $p_size
     */
    public function get_groupon_list($uin, $state, $p_index=1, $p_size=10)
    {
        $ret = array(
            'count' => 0,
            'list' => array(),
            'page_count' => 0,
            'page_size' => $p_size,
            'page_index' => $p_index,
        );

        $this->load->model('groupon_diy_model');
        $this->load->model('groupon_active_model');
        $diy_table = $this->groupon_diy_model->get_cur_database().'.'.$this->groupon_diy_model->get_cur_table();
        $join_user_table = $this->map($uin)->get_cur_database().'.'.$this->map($uin)->get_cur_table();
        $active_table = $this->groupon_active_model->get_cur_database().'.'.$this->groupon_active_model->get_cur_table();

        $join = ' from '.$join_user_table.' a left join '.$diy_table.' b on a.iDiyId = b.iDiyId';
        $where = ' where a.iUin='.$uin;
        if (array_key_exists($state, Lib_Constants::$groupon_diy_states)) {
            $where .= ' and b.iState='.$state;
        }

        $sql = 'select count(a.iJoinId) iTotal '.$join.$where;
        $count = $this->query($sql);
        if (empty($count)) {
            return $ret;
        }

        $count = $count[0]['iTotal'];
        if ($count <= 0) {
            return $ret;
        }

        $ret['page_count'] = ceil($count/$p_size);

        if ($p_index <= $ret['page_count']) {
            $fields = '';
            $join_user_columns = array(
                'iJoinId',
                array('sOrderId', 'sJoinOrderId'),
                array('iUin', 'iJoinUin'),
                'iGrouponId',
                'iSpecId',
                array('sNickName', 'sJoinNickName'),
                array('sHeadImg', 'sHeadImg'),
                'iIsColonel',
                'iCreateTime'
            );
            $fields .= $this->gen_sql_fields($join_user_columns, 'a');

            $diy_columns = array(
                'iDiyId',
                array('sOrderId', 'sDiyOrderId'),
                array('iUin', 'iDiyUin'),
                'iGrouponType',
                array('sNickName',  'sDiyNickName'),
                array('sHeadImg', 'sDiyHeadImg'),
                'iGrouponPrice',
                'iPeopleNum',
                'iFree',
                'iBuyNum',
                'iOpenCount',
                'iState',
                'iSuccTime',
                'iStartTime',
                'iEndTime',
            );
            $fields .= $this->gen_sql_fields($diy_columns, 'b');

            $active_columns = array(
                'sGoodsName',
                'sSpec',
                'sKeyword',
                'sImg',
                'sImgExt',
                'iPrice',
                array('iState', 'iActiveState'),
                array('iStartTime', 'iActiveStartTime'),
                array('iEndTime', 'iActiveEndTime'),
                'iStock',
                'iSuccCount',
                'iSoldCount',
                'iSoldOutTime',
                'iJoinNum'
            );
            $fields .= $this->gen_sql_fields($active_columns, 'c');
            $fields = trim($fields, ',');

            $join = ' from '.$join_user_table.' a left join '.$diy_table.' b on a.iDiyId = b.iDiyId left join '.$active_table.' c on a.iGrouponId = c.iGrouponId';
            $order_by = ' order by b.iCreateTime desc';
            $limit = ' limit '.($p_index-1)*$p_size.','.$p_size.';';
            $sql = 'select '.$fields.$join.$where.$order_by.$limit;

            $list = $this->query($sql);
            if (!empty($list)) {
                $ret['list'] = $list;
            }
        }
        return $ret;
    }

    public function gen_sql_fields($columns, $name)
    {
        $fields = '';
        foreach ($columns as $column) {
            if (is_string($column)) {
                $fields .= $name.'.'.$column.' '.$column.',';
            } else {
                list($ori, $new) = $column;
                $fields .= $name.'.'.$ori.' '.$new.',';
            }
        }
        return $fields;
    }
}