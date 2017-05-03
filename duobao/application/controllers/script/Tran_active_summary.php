<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Tran_active_summary
 * 迁移夺宝活动summary记录
 */
class Tran_active_summary extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'Tran_active_summary';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('luckycode_summary_model');
        $this->load->model('active_summary_model');
    }

    public function run(){
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        set_time_limit(0);

        for($i=0;$i<1;$i++){
            for($j=0;$j<10;$j++){

                $count = $this->luckycode_summary_model->query("select count(*) iTotal from ".$this->luckycode_summary_model->get_cur_table().$j, true);
                if (!$count || empty($count[0]) || empty($count[0]['iTotal'])) {
                    echo "no records | [".$this->luckycode_summary_model->db->last_query()."]\n";
                    continue;
                }
                $count = intval($count[0]['iTotal']);
                echo "total count :".$count."\n";
                $p_count = ceil($count/self::LIMIT);

                for ($p_index=1; $p_index<=$p_count; $p_index++) {
                    if ($list = $this->luckycode_summary_model->query("select * from ".$this->luckycode_summary_model->get_cur_table().$j.' order by iCreateTime asc limit '.($p_index-1)*self::LIMIT.','.self::LIMIT.';', TRUE)) {
                        if (empty($list)) {
                            echo "records list is empty | [".$this->luckycode_summary_model->db->last_query()."]\n";
                            continue;
                        }
                        $insert_arr = array();
                        foreach($list as $row) {
                            $insert_arr[$row['iPeroid']][] = array(
                                'iActId' => $row['iActId'],
                                'iPeroid' => $row['iPeroid'],
                                'iPeroidCode' => period_code_encode($row['iActId'], $row['iPeroid']),
                                'iGoodsId' => $row['iGoodsId'],
                                'sGoodsName' => $row['sGoodsName'],
                                'iUin' => $row['iUin'],
                                'sOrderId' => $row['sOrderId'],
                                'sNickName' => $row['sNickName'],
                                'sHeadImg' => $row['sHeadImg'],
                                'iLotCount' => $row['iLotCount'],
                                'sLuckyCodes' => $row['sLuckyCodes'],
                                'iLotState' => $row['iLotState'],
                                'iIP' => $row['iIP'],
                                'iLocation' => $row['iLocation'],
                                'iCreateTime' => date('Y-m-d H:i:s',$row['iCreateTime']),
                                'iLastModTime' => date('Y-m-d H:i:s',$row['iLastModTime'])
                            );
                        }
                        $this->batch_inset_summary($insert_arr);
                    } else {
                        echo "get records failed | [".$this->luckycode_summary_model->db->last_query()."]\n";
                        continue;
                    }

                }
            }
        }
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }


    private function batch_inset_summary($insert_arr)
    {
        $fileds = 'iActId,iPeroid,iPeroidCode,iGoodsId,sGoodsName,iUin,sOrderId,sNickName,sHeadImg,iLotCount,sLuckyCodes,iLotState,iIP,iLocation,iCreateTime,iLastModTime';
        if ($insert_arr) {
            foreach ($insert_arr as $key =>  $list) {
                if (empty($list)) {
                    continue;
                }
                $table_name = $this->active_summary_model->map($key)->get_cur_table();
                $db_name = $this->active_summary_model->map($key)->get_cur_database();
                $sql = 'INSERT INTO '.$db_name.'.'.$table_name.' ('.$fileds.') VALUES';
                foreach ($list as $arr) {
                    $sql .= ' (\''.implode("','", $arr).'\'),';
                }
                $sql = trim($sql, ',');
                echo $sql."\n";
                if (!$this->active_summary_model->query($sql, true)) {
                    echo "insert records failed | sql".$sql."\n";
                }
            }
        }
    }
}