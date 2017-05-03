<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每30秒跑一次
 * 自动推送消息，默认为微信消息
 * @date 2016-03-22
 * @autor leo.zou
 */

class Notify extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'Notify';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('active_peroid_model');
        $this->load->model('luckycode_summary_model');
        $this->load->model('user_model');
        $this->load->model('active_order_model');
    }


    public function run(){
        //while(true){
            $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
            set_time_limit(0);

            $user_list = array();
            for($i=0;$i<1;$i++){
                for($j=0;$j<10;$j++){
                    $list = $this->luckycode_summary_model->query("SELECT * FROM `t_luckycode_summary".$j."` WHERE iNotifyStatus = 0 LIMIT ".self::LIMIT);

                    //开始循环推送消息
                    $list = empty($list) ? array() : $list;
                    foreach($list as $li){
                        if($li['iNotifyStatus'] == 0){ //发送夺宝码
                            $this->log("STEP1: =============batchSendNotifyInfo  start=====================");
                            if(!isset($user_list[$li['iUin']])){
                                $user = $this->user_model->get_row(array('iUin'=>$li['iUin']));
                                if(!empty($user)){
                                    $user_list[$li['iUin']] = $user;
                                }
                            }else{
                                $user = $user_list[$li['iUin']];
                            }
                            $this->log("STEP2: =============get user info=====================\n");
                            if(empty($user['sOpenId'])){
                                if(isset($user_list[$li['iUin']])) unset($user_list[$li['iUin']]);
                                $this->log('batchSendReadyInfo fail | user['.json_encode($user).'] | uin['.$li['iUin'].']');
                                $this->luckycode_summary_model->update_rows(array('iNotifyStatus'=>2,'sNotifyException'=>'user info exception'),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']));
                                continue;
                            }

                            //查询订单
                            $order = $this->active_order_model->get_row(array('sOrderId'=>$li['sOrderId']));
                            $luckyCodeStr = implode(',',json_decode($li['sLuckyCodes']));
                            $params = array(
                                'actId' =>  $li['iActId'],
                                'priceString'  =>  empty($order['iTotalPrice']) ? '0元' : ($li['iLotCount']*$order['iUnitPrice']/100).'元',
                                'luckyCodeStr' =>  $luckyCodeStr,
                                'grouponId' =>  $li['iGoodsId'],
                                'grouponName'  =>  $li['sGoodsName'],
                                'openid'  =>  $user['sOpenId'],
                                'peroid'    => $li['iPeroid']
                            );
                            $this->log("STEP3: =============Lib_WeixinNotify::batchSendNotifyInfo=====================");
                            $this->log('batchSendNotifyInfo | params['.json_encode($params).']');
                            $rs = Lib_WeixinNotify::batchSendNotifyInfo($params);
                            if($rs === true){
                                $repeat = 1;        //如果失败，则重复提交
                                do {
                                    $return = true;
                                    if(!$this->luckycode_summary_model->update_rows(array('iNotifyStatus'=>1),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']))){
                                        $this->log('update lucky_summary fail | params['.json_encode($params).'] | repeat['.$repeat.'] | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                        $return = false;
                                    }
                                    $repeat++;
                                }while($repeat<self::REPEAT && $return == false );
                            }else{
                                if(!empty($rs) &&  !$this->luckycode_summary_model->update_rows(array('iNotifyStatus'=>2,'sNotifyException'=>$rs),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']))){
                                    $this->log('update lucky_summary fail | params['.json_encode($params).'] | res['.$rs.'] | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                }
                                $this->log('send ready message fail | params['.json_encode($params).']');
                            }
                            $this->log("STEP4: =============batchSendNotifyInfo  end=====================\n");
                        }
                    }


                    $user_list = $ready_list = $result_list = array();
                    $list = $this->luckycode_summary_model->query("SELECT * FROM `t_luckycode_summary".$j."` WHERE (iResultStatus = 0 OR iSoonStatus = 0) AND iLotState != 0 GROUP BY iActId,iPeroid,iUin LIMIT ".self::LIMIT);

                    //开始循环推送消息
                    $list = empty($list) ? array() : $list;
                    foreach($list as $li){
                        switch($li['iLotState']){
                            case Lib_Constants::ACTIVE_LOT_STATE_GOING:
                                if($li['iSoonStatus'] == 0 && !in_array($li['iUin'],$ready_list)){ //没有推送即将开奖通知
                                    $this->log("STEP1: =============batchSendReadyInfo start=====================\n");
                                    if(!isset($user_list[$li['iUin']])){
                                        $user = $this->user_model->get_row(array('iUin'=>$li['iUin']));
                                        if(!empty($user)){
                                            $user_list[$li['iUin']] = $user;
                                        }
                                    }else{
                                        $user = $user_list[$li['iUin']];
                                    }
                                    $this->log("STEP2: =============get user info=====================");
                                    if(empty($user['sOpenId'])){
                                        if(isset($user_list[$li['iUin']])) unset($user_list[$li['iUin']]);
                                        $this->luckycode_summary_model->update_rows(array('iSoonStatus'=>2,'sNotifyException'=>'user info exception'),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']));
                                        $this->log('batchSendReadyInfo fail | user['.json_encode($user).'] | uin['.$li['iUin'].']');
                                        continue;
                                    }
                                    $params = array(
                                        'openid' => $user['sOpenId'],
                                        'lotTime' => $li['iLotTime'],
                                        'peroid' => $li['iPeroid'],
                                        'goodsName' => $li['sGoodsName'],
                                        'actId' =>  $li['iActId']
                                    );
                                    $this->log("STEP3: =============Lib_WeixinNotify::batchSendReadyInfo=====================");
                                    $this->log('batchSendReadyInfo | params['.json_encode($params).']');
                                    $rs = Lib_WeixinNotify::batchSendReadyInfo($params);
                                    if($rs === true){
                                        //$ready_list[] = $li['iUin'];
                                        $repeat = 1;        //如果失败，则重复提交
                                        do {
                                            $return = true;
                                            if(!$this->luckycode_summary_model->update_rows(array('iSoonStatus'=>1),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']))){
                                                $this->log('update lucky_summary fail | params['.json_encode($params).'] | repeat['.$repeat.'] | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                                $return = false;
                                            }
                                            $repeat++;
                                        }while($repeat<self::REPEAT && $return == false );
                                    }else{
                                        if(!empty($rs) && !$this->luckycode_summary_model->update_rows(array('iSoonStatus'=>2,'sNotifyException'=>$rs),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']))){
                                            $this->log('update lucky_summary fail | params['.json_encode($params).'] | repeat['.$repeat.'] | res['.$rs.'] | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                        }
                                        $this->log('send ready message fail | params['.json_encode($params).']');
                                    }
                                    $this->log("STEP4: =============batchSendReadyInfo end=====================\n");
                                }
                                break;

                            case Lib_Constants::ACTIVE_LOT_STATE_OPENED://没有推送开奖结果通知
                                $time = time();
                                if($li['iResultStatus'] == 0 && $time>=$li['iLotTime']  && !in_array($li['iUin'],$result_list)){
                                    $this->log("STEP1: =============batchSendResultInfo start=====================\n");
                                    if(!isset($user_list[$li['iUin']])){
                                        $user = $this->user_model->get_row(array('iUin'=>$li['iUin']));
                                        if(!empty($user)){
                                            $user_list[$li['iUin']] = $user;
                                        }
                                    }else{
                                        $user = $user_list[$li['iUin']];
                                    }
                                    $this->log("STEP2: =============get user info=====================");
                                    if(empty($user['sOpenId'])){
                                        if(isset($user_list[$li['iUin']])) unset($user_list[$li['iUin']]);
                                        $this->luckycode_summary_model->update_rows(array('iResultStatus'=>2,'sNotifyException'=>'user info exception'),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']));
                                        $this->log('batchSendResultInfo fail | user['.json_encode($user).'] | uin['.$li['iUin'].']');
                                        continue;
                                    }
                                    $peroid = $this->active_peroid_model->get_row(array('iActId'=>$li['iActId'],'iPeroid'=>$li['iPeroid']));
                                    $params = array(
                                        'actId' =>  $li['iActId'],
                                        'openid' => $user['sOpenId'],
                                        'lotTime' => $li['iLotTime'],
                                        'peroid' => $li['iPeroid'],
                                        'goodsName' => $li['sGoodsName'],
                                        'luckyCode' => $peroid['sWinnerCode'],
                                        'isWinner' => $peroid['iWinnerUin'] == $li['iUin'] ? 1 : 0,
                                        'sWinnerOrder' => $peroid['sWinnerOrder'],
                                    );
                                    $this->log('batchSendResultInfo | params['.json_encode($params).']');
                                    $rs = Lib_WeixinNotify::batchSendResultInfo($params);
                                    if($rs === true){
                                        //$result_list[] = $li['iUin'];
                                        $repeat = 1;        //如果失败，则重复提交
                                        do {
                                            $return = true;
                                            if(!$this->luckycode_summary_model->update_rows(array('iResultStatus'=>1),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']))){
                                                $this->log('update lucky_summary fail | params['.json_encode($params).'] | repeat['.$repeat.'] | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                                $return = false;
                                            }
                                            $repeat++;
                                        }while($repeat<self::REPEAT && $return == false );
                                    }else{
                                        if(!empty($rs) && !$this->luckycode_summary_model->update_rows(array('iResultStatus'=>2,'sNotifyException'=>$rs),array('iUin'=>$li['iUin'],'iPeroid'=>$li['iPeroid'],'iActId'=>$li['iActId']))){
                                            $this->log('update lucky_summary fail | params['.json_encode($params).'] | repeat['.$repeat.'] | res['.$rs.'] | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                        }
                                        $this->log('send result message fail | params['.json_encode($params).']');
                                    }
                                    $this->log("STEP4: =============batchSendResultInfo end=====================\n");
                                }
                                break;
                        }
                    }
                }
            }

            sleep(10);
            $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
        //}
    }


}