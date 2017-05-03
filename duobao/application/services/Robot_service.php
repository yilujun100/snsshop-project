<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  机器人service
 * Class Score_service
 */
class Robot_service extends  MY_Service
{

    /**
     * 添加机器人临时参与记录,还并没有分配夺宝码
     * @param $uin 用户
     * @param $goods_id 商品ID
     * @param $goods_name 商品名称
     * @param $act_id   活动ID
     * @param $peroid   期数
     * @param $count    参与次数
     * @param $ip
     * @param $location
     * @return int
     */
    public function add_robot($uin,$goods_id,$goods_name,$act_id,$peroid,$count,$ip = '',$location = '')
    {
        if(empty($uin) || empty($goods_id) || empty($act_id) || empty($peroid) || empty($count)){
            return Lib_Errors::PARAMETER_ERR;
        }

        //检查夺宝活动状态
        $this->load->model('active_peroid_model');
        $active = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iPeroid'=>$peroid,'iIsCurrent'=>1,'iProcess<'=>100));
        if(empty($active)){
            return Lib_Errors::ACTIVE_ENDED;
        }
        $this->load->model('robot_temporary_model');
        $this->load->service('order_service');
        $data = array(
            'sOrderId' => $this->order_service->setOrderId(9,1,$uin),
            'iUin' => $uin,
            'iGoodsId' => $goods_id,
            'iGoodsName' => $goods_name,
            'iActId' => $act_id,
            'iPeroid' => $peroid,
            'iCount' => $count,
            'iUnitPrice' => Lib_Constants::COUPON_UNIT_PRICE,
            'iTotalPrice' => $count * Lib_Constants::COUPON_UNIT_PRICE,
            'sIP' => $ip,
            'sLocation' => $location,
            'iCreateTime' => time(),
            'iLastModTime' => time()
        );

        if($rs = $this->robot_temporary_model->add_row($data)){
            return $rs;
        }else{
            return Lib_Errors::SVR_ERR;
        }
    }


    /**
     * 机器人随机任务
     * @param $count    总参与次数
     * @param array $goods  商品信息
     * @param int $heat 商品热度
     * @param int $peroidcode 期号
     * @return array
     */
    public function lottery_rand_task($count,$goods,$heat = 60,$peroidcode = 0)
    {
        if(empty($count) || !is_array($goods)){
            return Lib_Errors::PARAMETER_ERR;
        }

        //产生随机相关数据
        $this->log->error('Task','=============$peroidcode['.$peroidcode.']-data['.date('Y-m-d H:i:s').']==================');
        $time = lottery_datetime($heat,get_variable('robot_win_rate',1),$this->date_rate());$this->log->error('Task','=============$time['.$time.']-data['.date('Y-m-d H:i:s').']==================');//随机分配开奖时间
        $task = lottery_rand_task($count,get_variable('robot_buy_weight',array('1'=>40,'2'=>5,'5'=>10,'10'=>5,'20'=>5,'100'=>1)));
        $this->log->error('Task','=============$task['.json_encode($task).']-data['.date('Y-m-d H:i:s').']==================');//随机分配任务队列
        $data = $this->lottery_rand_datetime($time,count($task));$this->log->error('Task','=============$data['.json_encode($data).']-data['.date('Y-m-d H:i:s').']==================');//根据任务数，随机分配在固定的时间内,结果是每分钟多少个task

        $task_data = array();
        $key = 0;
        $this->load->model('robot_model');
        foreach($data as $k=>$v){
            if(empty($v)){ //没有分配到任务
                continue;
            }
            $user = $this->robot_model->random($v);
            if(!is_array($user) && count($user) != $v){
                $this->log->error('Task','get rand user fail');
                continue;
            }
            while($v > 0){
                $user_info = isset($user[$v-1]) ? $user[$v-1] : array();
                $peroid_code = period_code_encode($goods['iActId'],$goods['iPeroid']);
                $task_data[] = array(
                    'sKey'=>$peroid_code.str_pad($key,4,'0',STR_PAD_LEFT).rand(100,999).time(),
                    'iGoodsId'=>$goods['iGoodsId'],
                    'sGoodsName' => $goods['sGoodsName'],
                    'iPeroidCode'=> $peroid_code,
                    'iLotCount'=>$task[$key],
                    'iUin'=>$user_info['iUin'],
                    'sNickName'=>$user_info['sNickName'],
                    'sHeadImg' => $user_info['sHeadImg'],
                    'sIP'=>$user_info['sIp'],
                    'sLocation'=>$user_info['sAddressIp'],
                    'iRunTime'=>($k-1)*60+rand(0,60)
                );
                $v--;
                $key++;
            }
        }

        //重新排序
        $volume = array();
        foreach($task_data as $k=>$val){
            $volume[$k] = $val['iRunTime'];
        }
        array_multisort($volume, SORT_ASC,$task_data);
        unset($volume,$data,$k,$v,$user_info,$user);

        //插入表中,这里不是批量插入，后期可优化
        $this->load->model('active_task_model');
        $rs = true;
        foreach($task_data as $task){
            $rs = $this->active_task_model->add_row($task);
            if(!$rs){
                $rs = false;
                break;
            }
        }
        $this->log->error('Task',"============================END==============================\n");

        return $rs ? $time : $rs;
    }




    /**
     * 分配某个时间段中每分钟的任务数
     * @param $time 多少分钟
     * @param $task 需要分配的任务数
     * @param float $cardinal   基数，影响随机数的浮动幅度
     * @return array
     */
    protected function lottery_rand_datetime($time,$task,$cardinal = 0.2){
        $return_data = array();
        $num = $task;
        $average = ceil($task/$time);
        $double_average = $average * 2;
        $i = 1;
        while($i <= $time){//循环分配直到分配$time次数完成
            //$mode = $num * $cardinal - $average > 0 ? 1 : 0;
            if($num <= $average){
                $return_data[$i] = $num;
            }else{
                if($task/$time < 1){ //当任务少，时间多的时候，则分配0概率要增大
                    $rand = rand(0,100);
                    $return_data[$i] = $rand > (1-$task/$time)*100 ? null : 0;
                }
                if($task/$time >=1 || $return_data[$i] === null){
                    $return_data[$i] = rand(0,$average - $num * $cardinal > 0 ? $num * $cardinal : $double_average);
                }
            }
            $num = $num - $return_data[$i];
            $i++;
        }
        $this->log->error('Task','=============lottery_rand_datetime['.$double_average.']-num['.$num.']-data['.date('Y-m-d H:i:s').']==================');

        //pr(array_sum($return_data).'_'.$num);
        //初次分配有可能$num没有分配完成，则把剩余的task继续分配在已经分配的数据中.
        $double_average = $double_average < 5 ? 5 : $double_average; //可能会这个值比较小
        $i = 1; //防止分配不了，出现死循环
        while($num > 0){
            $rand = $num>$double_average ? rand(1,$double_average) : $num;
            $index = rand(1,$time-1);
            if($return_data[$index]+$rand < $double_average){
                $i = 1;
                $return_data[$index]+= $rand;
                $num = $num - $rand;
            }elseif($i > 20){
                $return_data[$index]+= $rand;
                $num = $num - $rand;
            }
            $i++;
        }
        //pr(array_sum($return_data).'_'.$num);
        return $return_data;
    }


    /**
     * 时间段基准率
     * 不是的时间段，有不同的基准率
     * @return int
     */
    protected function date_rate(){
        $date_rate = get_variable('robot_time_win_rate',array());
        $time = time();
        foreach($date_rate as $date){
            if($time >= strtotime(date('Y-m-d').' '.$date['begin']) && $time <= strtotime(date('Y-m-d').' '.$date['end']) ){
                return $date['rate'];
            }
        }
        return 1;
    }
}