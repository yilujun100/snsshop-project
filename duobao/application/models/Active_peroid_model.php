<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Active_peroid_model extends MY_Model  {
    protected $db_group_name = DATABASE_YYDB;          //分组名
    protected $table_name = 't_active_peroid';          //表名
    protected $table_primary = 'iActId';                //主键
    protected $cache_row_key_column = array('iActId', 'iPeroid');         //缓存key字段  可自定义
    protected $cache_key_prefix = 'active_peroid_';         //缓存key字段  可自定义
    protected $table_num= 1;
    protected $auto_update_time = true; //自动更新createtime 或updatetime
    protected $need_cache_row = true;

    /**
     * Active_peroid_model constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 正在进行中的活动
     */
    public function get_active_ongoing()
    {
        $sql = 'select iActId,iPeroid from '.$this->get_cur_table().' where iLotState ='.Lib_Constants::ACTIVE_LOT_STATE_DEFAULT.' and iGoodsId != 163 and iIsCurrent =1 and iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' order by iLotTime desc;';
        $list = $this->query($sql, true);

        if ($list) {
            Lib_CacheUtils::update_cache_data('active_ongoing', $list);
        }
        return $list;
    }

    /**
     * 已开奖的最新的1000活动
     */
    public function get_active_1000_opened()
    {
        $sql = 'select iActId,iPeroid from '.$this->get_cur_table().' where iLotState ='.Lib_Constants::ACTIVE_LOT_STATE_OPENED.' and iGoodsId != 163 and iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' order by iLotTime desc limit 100;';
        $list = $this->query($sql, true);
        $this->log->error('get_active_1000_opened', $sql);
        if ($list) {
            Lib_CacheUtils::update_cache_data('active_1000_opened', $list);
        }
        return $list;
    }

    /**
     * 即将开奖的活动
     */
    public function get_active_opening()
    {
        $sql = 'select iActId,iPeroid from '.$this->get_cur_table().' where iLotState ='.Lib_Constants::ACTIVE_LOT_STATE_GOING.' and iGoodsId != 163 and iActType='.Lib_Constants::ACTIVE_TYPE_SYS.' order by iLotTime desc;';
        $list = $this->query($sql, true);

        if ($list) {
            Lib_CacheUtils::update_cache_data('active_opening', $list);
        }
        return $list;
    }

    /**
     * @param string field
     * @param array $where
     * @param array $order_by
     * @param int $page_index
     * @param int $page_size
     * @return array|bool
     */
    public function get_active_peroids($field = '*',$where=array(), $order_by=array(), $page_index = 1, $page_size = self::PAGE_SIZE)
    {
        $ret =  $list = $this->row_list($field,$where, $order_by, $page_index, $page_size);
        //$this->log->error('active_peroid', $this->db->last_query());
        return $ret;
    }

    /**
     * @param $limit
     * @return array|bool
     */
    public function get_history_peroids($limit =  5)
    {
        return $this->query('(SELECT * FROM t_active_peroid WHERE iGoodsId !=163 and iLotState = 1 and iActType = '.Lib_Constants::ACTIVE_TYPE_SYS.' ORDER BY iLotTime DESC LIMIT '.$limit.' ) UNION (SELECT * FROM t_active_peroid WHERE iGoodsId !=163 and iLotState = 2 and iActType = '.Lib_Constants::ACTIVE_TYPE_SYS.' ORDER BY iLotTime DESC  LIMIT '.$limit.')');
    }


    /**
     * 新开一期夺宝活动
     * @param $act_id
     * @param array $active_config
     * @return bool
     */
    public function add_new_active_peroid($act_id,$active_config = array(),$peroid = 1){
        $active_config = empty($active_config) ? $this->active_config_model->get_row(array('iActId'=>$act_id)) : $active_config;
        $active_peroid = $this->active_peroid_model->get_row(array('iActId'=>$active_config['iActId'],'iGoodsId'=>$active_config['iGoodsId']));

        $time = time();
        if($active_config['iEndTime'] > $time && $active_config['iBeginTime'] <= $time && (empty($active_peroid) || ($active_peroid['iPeroid'] < $active_config['iPeroidCount'] && $active_config['iState'] == Lib_Constants::ACTIVE_STATE_ONLINE))){
            if(!empty($active_peroid)){
                //$this->update_row(array('iIsCurrent'=>0),array('iActId'=>$active_peroid['iActId'],'iPeroid'=>$active_peroid['iPeroid'],'iGoodsId'=>$active_peroid['iGoodsId']));
            }

            $data = array(
                'iActId' => $active_config['iActId'],
                'iGoodsId' => $active_config['iGoodsId'],
                'iCateId' => $active_config['iCateId'],
                'iCateId_1' => $active_config['iCateId_1'],
                'iCateId_2' => $active_config['iCateId_2'],
                'iCateId_3' => $active_config['iCateId_3'],
                'sGoodsName' => $active_config['sGoodsName'],
                'iGoodsType' => $active_config['iGoodsType'],
                'iCostPrice' => $active_config['iCostPrice'],
                'iLowestPrice' => $active_config['iLowestPrice'],
                'sImg' => $active_config['sImg'],
                'sImgExt' => $active_config['sImgExt'],
                'sSearchKey' => $active_config['sSearchKey'],
                'iActType' => $active_config['iActType'],
                'iInitiator' => $active_config['iInitiator'],
                'sActName' => $active_config['sActName'],
                'iHeat' => $active_config['iHeat'],
                'iCodePrice' => $active_config['iCodePrice'],
                'iLotCount' => $active_config['iLotCount'],
                'iTotalPrice' => $active_config['iTotalPrice'],
                'iBuyCount' => $active_config['iBuyCount'],
                'iPeroidBuyCount' => $active_config['iPeroidBuyCount'],
                'iPeroidCount' => $active_config['iPeroidCount'],
                'iCornerMark' => $active_config['iCornerMark'],
                'iRecommend' => $active_config['iRecommend'],
                'iRecWeight' => $active_config['iRecWeight'],
                'iBeginTime' => $active_config['iBeginTime'],
                'iEndTime' => $active_config['iEndTime'],
                'iTotalSoldCount' => empty($active_peroid['iTotalSoldCount'])  ? 0 : $active_peroid['iTotalSoldCount'], //保留上一期总销量
                'iPeroid' => $peroid,
                'iPeroidCode' => period_code_encode($active_config['iActId'],$peroid),
                'iIsCurrent' => 1,
                'iSoldCount' => 0,
                'iProcess' => 0,
                'iCreateTime' => time(),
                'iUpdateTime' => time(),
            );

            return $this->add_active_period($data,'index');
        }

        return false;
    }


    public function currect_active_list($in_arr)
    {
        if(empty($in_arr)){
            return array();
        }
        return $this->query("SELECT iActId,iGoodsId,sGoodsName,iGoodsType,sImg,iPeroid,iSoldCount,iProcess,iLotState,iLotCount,iCodePrice,iBuyCount from t_active_peroid where iIsCurrent = 1 and iActId in(".$in_arr.")");
    }

    //获取某个活某一期详情
    public function get_active_row($act_id,$peroid)
    {
        return $this->get_row(array('iActId'=>$act_id,'iPeroid'=>$peroid));
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
        $sql .= ' AND iLotState<'.Lib_Constants::ACTIVE_LOT_DONE;
        return $this->query($sql, true);
    }

    /**
     * 生成新一期夺宝活动
     *
     * @param $act_config
     * @param $period
     * @param $total_sold
     *
     * @return bool
     */
    public function generate_active_period($act_config, $period = 1, $total_sold = 0)
    {
        $time = time();
        $data = array(
            'iActId' => $act_config['iActId'],
            'iGoodsId' => $act_config['iGoodsId'],
            'iCateId' => $act_config['iCateId'],
            'iCateId_1' => $act_config['iCateId_1'],
            'iCateId_2' => $act_config['iCateId_2'],
            'iCateId_3' => $act_config['iCateId_3'],
            'sGoodsName' => $act_config['sGoodsName'],
            'iGoodsType' => $act_config['iGoodsType'],
            'iCostPrice' => $act_config['iCostPrice'],
            'iLowestPrice' => $act_config['iLowestPrice'],
            'sImg' => $act_config['sImg'],
            'sImgExt' => $act_config['sImgExt'],
            'sSearchKey' => $act_config['sSearchKey'],
            'iActType' => $act_config['iActType'],
            'iInitiator' => $act_config['iInitiator'],
            'sActName' => $act_config['sActName'],
            'iHeat' => $act_config['iHeat'],
            'iCodePrice' => $act_config['iCodePrice'],
            'iLotCount' => $act_config['iLotCount'],
            'iTotalPrice' => $act_config['iTotalPrice'],
            'iBuyCount' => $act_config['iBuyCount'],
            'iPeroidBuyCount' => $act_config['iPeroidBuyCount'],
            'iPeroidCount' => $act_config['iPeroidCount'],
            'iCornerMark' => $act_config['iCornerMark'],
            'iRecommend' => $act_config['iRecommend'],
            'iRecWeight' => $act_config['iRecWeight'],
            'iBeginTime' => $act_config['iBeginTime'],
            'iEndTime' => $act_config['iEndTime'],
            'iTotalSoldCount' => $total_sold,
            'iPeroid' => $period,
            'iPeroidCode' => period_code_encode($act_config['iActId'],$period),
            'iIsCurrent' => 1,
            'iSoldCount' => 0,
            'iProcess' => 0,
            'iCreateTime' => $time,
            'iUpdateTime' => $time,
        );
        return $this->add_active_period($data,'admin');
    }


    protected function add_active_period($data,$model)
    {
        //产生机器人数据
        $robot_stop_list = get_variable('robot_stop_list',array());
        $robot_stop_list = is_array($robot_stop_list) ? $robot_stop_list : array(); //确保是数组，以免报错
        if($data['iActType'] == Lib_Constants::ACTIVE_TYPE_SYS && !in_array($data['iPeroidCode'],$robot_stop_list)){ //系统发起夺宝活动
            $this->load->service('robot_service');
            $robot_service = $this->robot_service->lottery_rand_task(
                $data['iLotCount'],
                array('iActId'=>$data['iActId'],'iPeroid'=>$data['iPeroid'],'iGoodsId'=>$data['iGoodsId'],'sGoodsName'=>$data['sGoodsName']),
                isset($data['iHeat']) && !empty($data['iHeat']) && $data['iHeat']>= 10 ? $data['iHeat'] : 60, //商品热度不能少10分钟
                period_code_encode($data['iActId'],$data['iPeroid'])
            );
            if(!$robot_service || (!is_bool($robot_service) && $robot_service <= 0)){
                return false;
            }

            //更新机器人执行时间
            $this->load->model('active_task_model');

            if(!$this->active_task_model->query("UPDATE t_active_task SET iRunTime=iRunTime+".(time()+30).",iState=1 WHERE iPeroidCode = ".period_code_encode($data['iActId'],$data['iPeroid']),true)){
                return false;
            }
            $this->active_task_model->update_cache_rows(array('iPeroidCode'=>period_code_encode($data['iActId'],$data['iPeroid'])),true);//更新缓存

            //把预计最大开奖时间更新到DB中
            $data['iPredictMinute'] = $robot_service;
        }

        return $this->add_row($data);
    }
}