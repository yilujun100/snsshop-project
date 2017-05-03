<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Active_tag_map_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_active_tag_map';   //表名
    protected $can_real_delete = true;            //允许真删除

    /**
     * 添加
     *
     * @param $actId
     * @param $tagIds
     *
     * @return mixed
     */
    public function add_maps($actId, $tagIds)
    {
        $db = $this->conn(true);

        $data = array();
        foreach ($tagIds as $tagId) {
            $data[] = array(
                'iTagId' => $tagId,
                'iActId' => $actId,
            );
        }
        return $db->insert_batch($this->table_name, $data);
    }

    /**
     * 更新
     *
     * @param $actId
     * @param $tagIds
     *
     * @return mixed
     */
    public function update_maps($actId, $tagIds)
    {
        $db = $this->conn(true);

        $db->delete($this->table_name, array('iActId' => $actId));

        return $this->add_maps($actId, $tagIds);
    }
}