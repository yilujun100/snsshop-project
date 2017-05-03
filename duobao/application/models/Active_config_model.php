<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Active_config_model extends MY_Model
{
    /**
     * 活动ID
     *
     * @var int
     */
    private $act_id;

    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_active_config';   //表名
    protected $table_name_period = 't_active_peroid';
    protected $table_primary = 'iActId';   //表名主键名称
    protected $cache_row_key_column = 'iActId'; //单条表记录缓存字段
    protected $auto_update_time = true; //自动更新createtime 或updatetime
    protected $can_real_delete = true;   //允许真删除
    protected $need_cache_row = false;

    /**
     * Active_config_model constructor
     *
     * @param null $act_id
     */
    public function __construct($act_id = null)
    {
        parent::__construct();
        if ($act_id > 0) {
            $this->act_id = intval($act_id);
        }
    }

    /**
     * 更新活动状态
     *
     * @param $act_id
     * @param $state
     *
     * @return int
     */
    public function update_state($act_id, $state)
    {
        $ori_row = $this->get_row($act_id);
        if (! $ori_row) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (! $valid = Lib_Constants::valid_publish_state($ori_row['iState'], $state)) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (Lib_Constants::PUBLISH_STATE_ONLINE == $state) { // 上线

            return $this->online($act_id, $ori_row);

        } else {
            if (! $this->update_row(array('iState'=>$state), $act_id)) {
                $this->log->error('active_config_model', 'update_state error', array('act_id'=>$act_id,'state'=>$state,'active'=>$ori_row));
                return Lib_Errors::ACTIVE_UPDATE_STATE_FAILED;
            }
        }

        return Lib_Errors::SUCC;
    }

    /**
     * 生成第一期活动
     *
     * @param $act_id
     *
     * @return mixed
     */
    public function generate_period($act_id)
    {
        $this->load->model('active_peroid_model');
        return $this->active_peroid_model->add_new_active_peroid($act_id);
    }

    /**
     * 删除活动
     *
     * @param $act_id
     *
     * @return mixed
     */
    public function delete_row($act_id)
    {
        parent::delete_row($act_id);
        $this->load->model('active_tag_map_model');
        return $this->active_tag_map_model->delete_rows(array('iActId' => $act_id));
    }

    /**
     * @param string field
     * @param array $where
     * @param array $order_by
     * @param int $page_index
     * @param int $page_size
     * @return array|bool
     */
    public function get_active_configs($field = '*',$where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {
        return $list = $this->row_list($field,$where, $order_by, $page_index, $page_size);
    }


    /**
     * 没有分页
     * @param $in_arr
     * @return array|bool
     */
    public function get_active_config($in_arr)
    {
        if(empty($in_arr)){
            return array();
        }
        return $this->query("SELECT iActId,iGoodsId,sGoodsName,iGoodsType,sImg,iLotCount,iCodePrice,iState,iBeginTime,iEndTime from t_active_config where iActId in(".$in_arr.")");
    }

    /**
     * 同步更新商品信息
     *
     * @param $goods_id
     * @param $data
     *
     * @return array|bool
     */
    public function sync_goods($goods_id, $data)
    {
        $goods_id = (int)$goods_id;
        if (! $goods_id) {
            return false;
        }
        $this->conn(true);
        $sql = 'UPDATE `'.$this->table_name.'` SET `sSearchKey`=REPLACE(`sSearchKey`,`sGoodsName`,'.$this->db->escape($data['sGoodsName']).')';
        $sql .= ',sGoodsName='.$this->db->escape($data['sGoodsName']).',`sImg`='.$this->db->escape($data['sImg']);
        $sql .= ',iCateId='.$this->db->escape($data['iCateId']).',`iCateId_1`='.$this->db->escape($data['iCateId_1']).',`iCateId_2`='.$this->db->escape($data['iCateId_2']).',`iCateId_3`='.$this->db->escape($data['iCateId_3']);
        if (isset($data['sImgExt'])) {
            $sql .= ',sImgExt='.$this->db->escape($data['sImgExt']).'';
        }
        $sql .= ' WHERE iGoodsId='.$goods_id;
        $sql .= ' AND iEndTime>='.time();
        $sql .= ' AND iState!='.Lib_Constants::PUBLISH_STATE_END;
        return $this->query($sql, true);
    }

    /**
     * 上线活动
     *
     * @param       $act_id
     * @param array $act_config
     *
     * @return bool
     */
    public function online($act_id, $act_config = array())
    {
        if (! $act_config) {
            $act_config = $this->get_row($act_id);
        }
        if (empty($act_config)) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (! in_array($act_config['iState'], array(Lib_Constants::PUBLISH_STATE_READY, Lib_Constants::PUBLISH_STATE_OFFLINE))) {
            $this->log->error('active_config_model', 'online state error', $act_config);
            return Lib_Errors::PARAMETER_ERR;
        }

        $now = time();

        if ($act_config['iBeginTime'] > $now) {
            $this->log->error('active_config_model', 'active not begin', $act_config);
            return Lib_Errors::ACTIVE_NOT_BEGIN;
        }
        if ($act_config['iEndTime'] && $act_config['iEndTime'] < $now) {
            /*if (! $this->update_row(array('iState' => Lib_Constants::PUBLISH_STATE_END), $act_id)) { // 更新活动为 已结束
                $this->log->error('active_config_model', 'active expire update state end error', array('act_id'=>$act_id));
                return Lib_Errors::ACTIVE_END_FAILED;
            }*/
            return Lib_Errors::ACTIVE_ENDED;
        }

        $this->load->model('active_peroid_model');

        if (Lib_Constants::PUBLISH_STATE_READY == $act_config['iState']) { // 确认上线
            if (! $this->active_peroid_model->generate_active_period($act_config)) {
                $this->log->error('active_config_model', 'generate first period error', array('act_config'=>$act_config));
                return Lib_Errors::ACTIVE_GENERATE_FAILED;
            }
        } else { // 重新上线
            $where = array(
                'iActId'=>$act_id,
                'iLotState'=>Lib_Constants::ACTIVE_LOT_NOT,
            );
            $act_period = $this->active_peroid_model->get_row($where);

            if (empty($act_period)) {

                $sql = "SELECT * FROM `{$this->table_name_period}` WHERE iActId={$act_id} ORDER BY iPeroid DESC LIMIT 1;";

                $last_period = $this->active_peroid_model->query($sql);
                $last_period = $last_period[0];

                if ($act_config['iPeroidCount'] && $last_period['iPeroid'] >= $act_config['iPeroidCount']) { //达到最大开奖期数

                    if (! $this->update_row(array('iState' => Lib_Constants::PUBLISH_STATE_END), $act_id)) { // 更新活动为 已结束
                        $this->log->error('active_config_model', 'period limit update state end error', array('act_id'=>$act_id));
                        return Lib_Errors::ACTIVE_END_FAILED;
                    }

                    return Lib_Errors::ACTIVE_PERIOD_LIMIT;
                }

                $period = $last_period['iPeroid'] + 1;
                $total_sold = $last_period['iTotalSoldCount'];

                if (! $this->active_peroid_model->generate_active_period($act_config, $period, $total_sold)) {
                    $this->log->error('active_config_model', 'generate new period error', array('act_config'=>$act_config,'period'=>$period,'total_sold'=>$total_sold));
                    return Lib_Errors::ACTIVE_GENERATE_FAILED;
                }
            }
        }

        if (! $this->update_row(array('iState' => Lib_Constants::PUBLISH_STATE_ONLINE), $act_id)) { // 更新活动为上线状态
            $this->log->error('active_config_model', 'update state online error', array('act_id'=>$act_id));
            return Lib_Errors::ACTIVE_ONLINE_FAILED;
        }

        return Lib_Errors::SUCC;
    }
}