<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class Fix_share
 *
 */
class Fix_share extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'Fix_share';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('active_peroid_model');
        $this->load->model('share_model');
    }

    public function run(){
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        set_time_limit(0);
        $count = $this->share_model->query("select count(*) iTotal from ".$this->share_model->get_cur_table(), true);
        if (!$count || empty($count[0]) || empty($count[0]['iTotal'])) {
            $this->log("no records | [".$this->share_model->db->last_query()."]");
            $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
            exit;
        }

        $count = intval($count[0]['iTotal']);
        $this->log("total count :".$count);
        $p_count = ceil($count/self::LIMIT);

        for ($p_index=1; $p_index<=$p_count; $p_index++) {
            if ($list = $this->share_model->query("select * from ".$this->share_model->get_cur_table().' limit '.($p_index-1)*self::LIMIT.','.self::LIMIT.';', TRUE)) {
                if (empty($list)) {
                    $this->log("records list is empty | [".$this->share_model->db->last_query()."]");
                    continue;
                }

                foreach($list as $row) {
                    $peroid_code = period_code_encode($row['iActId'], $row['iPeriod']);
                    $peroid_info = $this->active_peroid_model->get_row(array('iPeroidCode' => $peroid_code), true, false);
                    if (empty($peroid_info)) {
                        $this->log("active peroid detail is empty | [".$this->active_peroid_model->db->last_query()."]");
                        continue;
                    }
                    if (!$this->share_model->update_row(array('iWinnerCount'=>$peroid_info['iWinnerCount']), array('iShareId' => $row['iShareId']))) {
                        $this->log("update share winner count failed | [".$this->share_model->db->last_query()."]");
                        continue;
                    }
                }
            } else {
                $this->log("get records failed | [".$this->share_model->db->last_query()."]");
                continue;
            }
        }
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    /**
     * 记录日志
     */
    public function log( $logs, $showTime = true)
    {
        $type = $this->log_type;
        $filename = $filename = APPPATH.'logs/'.$type.'_'.date('Y-m-d').'.log';
        $msg = ($showTime ? '['.date('Y-m-d H:i:s').'] ' : '').$logs."\n";
        @file_put_contents($filename, $msg, FILE_APPEND);
        echo $msg;
    }
}