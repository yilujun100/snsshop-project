<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 0元夺宝假数据自动跑脚本
 * 自动开奖及新开活动
 * @date 2016-05-18
 * @autor leo.zou
 */

class Fake extends Script_Base{
    protected $log_type = 'Fake';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('active_peroid_model');
        $this->load->driver('cache');
    }

    public function run(){
        $set_num = $this->get_post('t',0);
        $peroid_str = $this->get_post('k','');

        if(empty($set_num) || empty($peroid_str) || !is_numeric($set_num)){
            $active_peroid_list = $this->active_peroid_model->row_list('*',array('iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,'iProcess <'=>100,'iCodePrice'=>0),$order_by=array(), $page_index = 1, $page_size = 1000);
            foreach($active_peroid_list['list'] as $item){
                $peroid_str = period_code_encode($item['iActId'],$item['iPeroid']);
                $cache_key = 'ajax_active_num_'.$peroid_str;
                $cache_data = $this->cache->memcached->get($cache_key);
                $cache_data = empty($cache_data) ? 110 : intval($cache_data)+rand(0,10);

                $rs = $this->cache->memcached->save($cache_key, $cache_data, 86400);
            }
        }else{
            $cache_key = 'ajax_active_num_'.$peroid_str;
            $rs = $this->cache->memcached->save($cache_key, $set_num, 86400);
        }

        sleep(30);
    }
}