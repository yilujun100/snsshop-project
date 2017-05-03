<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/***
 * 自动发码脚本，每10秒跑一次
 * @autor leo.zou
 * @date 2016-03-18
 */


class Luckycode extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数
    const BASE_CODE = Lib_Constants::LUCKY_CODE_BASE;  //夺宝码的基数
    const REPEAT = 1; //如果数据库操作失败，将会尝试几次重新执行操作

    public $yydb_db = 'yydb';
    public $yydb_user_db = 'yydb_user';
    protected $log_type = 'Luckycode';

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('active_config_model');
        $this->load->model('active_peroid_model');
        $this->load->model('order_refund_model');
        $this->load->model('active_order_model');
        $this->load->model('active_merage_order_model');
        $this->load->model('luckycode_record_model');
        $this->load->model('luckycode_summary_model');
        //$this->load->model('order_summary_model');
        $this->load->model('active_summary_model');
        $this->load->model('user_summary_model');
        $this->load->model('active_temporary_model');
        $this->load->model('user_model');
    }

    public function run($tab_index)
    {
        $this->log_type = $this->log_type.$tab_index;
        if($tab_index == null) die('config error');
        $this->log("====================================BEING(".date('Y-m-d H:i:s').") | INDEX(".$tab_index.")=============================================");
        $this->active_run($tab_index);
        $this->robot_run($tab_index);
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n");
    }

    //真实用户发码逻辑
    public function active_run($tab_index)
    {
        $this->log("===============BEING ACTIVE RUN(".date('Y-m-d H:i:s').")=========================");
        //while(true){
            set_time_limit(0);
            $order = $refund = array();
            //$active_config = $this->get_active_item();
            for($i=0;$i<1;$i++){
                for($j=0;$j<10;$j++){
                    $table_name = 't_active_order'.$j;
                    $merage_order_table = 't_active_merage_order'.$j;

                    $result = $this->active_order_model->query("SELECT active_order.*,merage_order.iIP,merage_order.iLocation,merage_order.iCoupon FROM `".$table_name."` AS active_order LEFT JOIN `".$merage_order_table."` AS merage_order ON merage_order.sMergeOrderId = active_order.sMergeOrderId WHERE substring(active_order.iActId,-1) = ".$tab_index." AND active_order.iBuyType = 1 AND active_order.`iPayStatus` = 1 AND active_order.`iPayTime` != -1 AND active_order.`sTransId` != '' AND active_order.iLuckyCodeState = 0 LIMIT ".self::LIMIT);
                    foreach($result as $row){
                        //检查购买的夺宝活动是否还在线，如果不在或不等于当前期数，则全额退款
                        $this->log("STEP1: =============check active=============");
                        $item = $this->active_peroid_model->get_row(array('iActId'=>$row['iActId'],'iPeroid'=>$row['iPeroid'],'iIsCurrent'=>1));
                        if(empty($item) || $item['iPeroid'] != $row['iPeroid']){
                            if($query = $this->active_order_model->query("UPDATE `".$table_name."` SET `iLuckyCodeState` = 1 WHERE `sOrderId`='".$row['sOrderId']."' AND `iUin`='".$row['iUin']."'", true)){
                                $refund[$row['iActId']][] = array_merge($row,array('uin'=>$row['iUin'],'order_id'=>$row['sOrderId'],'merage_order_id'=>$row['sMergeOrderId'],'refund'=>$row['iTotalPrice'],'coupon'=>$row['iCoupon'],'pay_type'=>$row['iPayAgentType'],'trans_id'=>$row['sTransId'],'goods_name'=>$item['sGoodsName'],'table'=>$j));
                                $this->log('active item not found | params['.json_encode($row).']');
                            }
                            continue;
                        }

                        //先设置订单表为iLuckyCodeState=1
                        $query = $this->active_order_model->query("UPDATE `".$table_name."` SET `iLuckyCodeState` = 1 WHERE `sOrderId`='".$row['sOrderId']."' AND `iUin`='".$row['iUin']."'", true);
                        $this->log("STEP1: =============update iLuckycode status = 1==================");
                        if(!$query){
                            $this->log('update active order fail | sOrderId['.$row['sOrderId'].']');
                            $this->log($this->active_order_model->db->last_query());
                            continue;
                        }

                        //检查用户信息
                        $this->log("STEP3: =============check user=============");
                        $user = $this->user_model->get_user_by_uin($row['iUin']);
                        if(!$user){
                            $this->log('user not found | iUin['.$row['iUin'].']');
                            continue;
                        }

                        //检查是否发过码
                        $this->log("STEP4: =============check send repeat code=============");
                        $log = $this->luckycode_record_model->get_row(array('sOrderId'=>$row['sOrderId'],'iUin'=>$row['iUin'],'iActId'=>$row['iActId']));
                        if(!empty($log)){
                            $this->log('order already processed | sOrderId['.$row['sOrderId'].']');
                            continue;
                        }


                        $item_code_num = $item['iSoldCount'] > $item['iLotCount'] || $item['iProcess'] >= 100 ? 0 : ($item['iLotCount'] - $item['iSoldCount']);//当期可以购买的码数
                        $code_num = $item_code_num > $row['iCount'] ? $row['iCount'] : $item_code_num;  //需要的发码数
                        $refund_code_num = $item_code_num > $row['iCount'] ? 0 : $row['iCount'] - $item_code_num; //需要退款的码数
                        //检查当期夺宝活动还需要多少码，如果不够，则多出来的退款
                        $this->log("STEP5: =============luckycode & refund==SoldCount[".$item['iSoldCount']."] | iLotCount[".$item['iLotCount']."] | iCodeNum[".$code_num."]===========");
                        if($refund_code_num > 0){
                            $refund[$row['iActId']][] = array_merge($row,array('uin'=>$row['iUin'],'order_id'=>$row['sOrderId'],'merage_order_id'=>$row['sMergeOrderId'],'code_num'=>$refund_code_num,'refund'=>$refund_code_num*$item['iCodePrice'],'pay_type'=>$row['iPayAgentType'],'trans_id'=>$row['sTransId'],'coupon'=>$row['iCoupon'],'goods_name'=>$item['sGoodsName'],'table'=>$j));
                            $this->log("refund code num[".$refund_code_num."]");
                        }
                        if($code_num <= 0){
                            continue;
                        }


                        $this->log("STEP6: =============t_active_order=============");
                        $lucky_code = array();
                        for($m=0;$m<=$code_num-1;$m++){
                            $lucky_code[] = self::BASE_CODE+$m+$item['iSoldCount'];
                        }
                        $json_lucky_code = json_encode($lucky_code);
                        $query = $this->active_order_model->query("UPDATE `".$table_name."` SET `iLuckyCodeState` = 1,`iLuckyCodeNum` = ".$code_num.",`sLuckyCodeJson` = '".$json_lucky_code."' WHERE `sOrderId`='".$row['sOrderId']."' AND `iUin`='".$row['iUin']."'", true);
                        $this->log($this->active_order_model->db->last_query());
                        if(!$query){
                            $this->log('update active order fail | sOrderId['.$row['sOrderId'].']');
                        }else{
                            //更新活动表中减去夺宝码数量
                            $time = time();
                            $msct_time = round(microtime(1)*1000);//这里是毫秒级别
                            $this->log("STEP7: =============t_active_peroid=============");
                            $process = (int)(($item['iSoldCount']+$code_num)/$item['iLotCount']*100);
                            $process = empty($process) ? 1 : $process;
                            $query = $this->active_peroid_model->query("UPDATE `t_active_peroid` SET `iSoldCount` = `iSoldCount`+".$code_num.",`iSoldOutTime`=".$time.",`iUpdateTime`=".time().",`iTotalSoldCount`=`iTotalSoldCount`+".$code_num.",`iProcess`=".$process." WHERE iActId=".$item['iActId']." AND iPeroid=".$item['iPeroid']." LIMIT 1", true);
                            $this->log($this->active_peroid_model->db->last_query());
                            $this->active_peroid_model->update_cache_rows(array('iPeroidCode'=>period_code_encode($item['iActId'],$item['iPeroid'])));//更新缓存
                            if(!$query){
                                $this->log('update active_peroid_model fail');
                                continue;
                            }

                            //这里将为用户分配夺宝码并且插入记录表中
                            $this->log("STEP8: =============t_luckycode_record=============");
                            $lucky_code_sql = "INSERT INTO t_luckycode_record".$tab_index." (iGoodsId,iActId,iPeroid,sOrderId,iUin,sNickName,sLuckyCode,iCreateTime,iMsecTime) VALUES ";
                            $lucky_code_temp = "";
                            foreach($lucky_code as $k=>$code){
                                $lucky_code_temp .= "(".$item['iGoodsId'].",".$item['iActId'].",".$row['iPeroid'].",'".$row['sOrderId']."','".$row['iUin']."','".addslashes($user['sNickName'])."',".$code.",".$time.",".$msct_time."),";
                            }
                            $lucky_code_sql .= trim($lucky_code_temp,',');
                            $repeat = self::REPEAT;
                            //$this->log($lucky_code_sql);
                            do{
                                $return = true;
                                if(!$this->luckycode_record_model->query($lucky_code_sql)){
                                    $return = false;
                                    $this->log('insert t_luckycode_record fail | repeat['.$repeat.']');
                                }
                                $repeat--;

                            }while($repeat > 0 && $return == false);
                            unset($lucky_code_sql,$lucky_code_temp,$lucky_code);


                            //上面的操作执行成功，在夺宝码汇总表插入数据，是推送发码消息的直接数据来源
                            $this->log("STEP9: =============t_luckycode_summary=============");
                            if($return){
                                $add = array(
                                    'iActId' => $item['iActId'],
                                    'iGoodsId' => $item['iGoodsId'],
                                    'sGoodsName' => $item['sGoodsName'],
                                    'iPeroid' => $row['iPeroid'],
                                    'sOrderId' => $row['sOrderId'],
                                    'iUin' => $row['iUin'],
                                    'sNickName' => addslashes($user['sNickName']),
                                    'sHeadImg' => $user['sHeadImg'],
                                    'iLotCount' => $code_num,
                                    'sLuckyCodes' => $json_lucky_code,
                                    'iIP' => $row['iIP'],
                                    'iLocation' => $row['iLocation'],
                                    'iLotState' => Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,
                                    'iCreateTime' => time(),
                                    'iLastModTime' => time(),
                                );
                                if(!$this->add_luckycode_summary($add)){
                                    $this->log('insert t_luckycode_summary fail | sOrderId['.$row['sOrderId'].']');
                                }
                            }

                            //上面的操作执行成功，在夺宝码汇总表插入数据，是推送发码消息的直接数据来源
                            $this->log("STEP10: =============t_active_summary=============");
                            if($return){
                                $now = time();
                                $add = array(
                                    'iPeroidCode' => period_code_encode($item['iActId'], $row['iPeroid']),
                                    'sOrderId' => $row['sOrderId'],
                                    'iCreateTime' => date('Y-m-d H:i:s', $now),
                                    'iActId' => $item['iActId'],
                                    'iPeroid' => $row['iPeroid'],
                                    'iGoodsId' => $item['iGoodsId'],
                                    'sGoodsName' => $item['sGoodsName'],
                                    'iUin' => $row['iUin'],
                                    'sNickName' => addslashes($user['sNickName']),
                                    'sHeadImg' => $user['sHeadImg'],
                                    'iLotCount' => $code_num,
                                    'sLuckyCodes' => $json_lucky_code,
                                    'iIP' => $row['iIP'],
                                    'iLocation' => $row['iLocation'],
                                    'iLastModTime' => date('Y-m-d H:i:s', $now),
                                    'iMsecTime' => round(microtime(1)*1000),//这里是毫秒级别
                                );
                                if(!$this->add_user_summary($add)){
                                    $this->log('insert t_user_summary fail | sOrderId['.$row['sOrderId'].']');
                                }
                                if(!$this->add_active_summary($add)){
                                    $this->log('insert t_active_summary fail | sOrderId['.$row['sOrderId'].']');
                                }
                                if(!$this->add_active_temporary($add)){
                                    $this->log('insert t_active_temporary fail | sOrderId['.$row['sOrderId'].']');
                                }
                            }
                        }
                    }
                }
            }

            //退款处理
            if(!empty($refund)){
                $this->log("STEP9  : =============refund orders=============");
                $this->log('refund orders | data['.json_encode($refund).']');
                foreach($refund as $act_id=>$user){   //不同活动
                    foreach($user as $val){  //不同订单
                        $repeat = self::REPEAT;
                        do{
                            //$val['refund'需要退的金额
                            $return = true;
                            $coupon_amount = $val['coupon']*Lib_Constants::COUPON_UNIT_PRICE;
                            $coupon = $val['refund'] >= $coupon_amount ? $coupon_amount/Lib_Constants::COUPON_UNIT_PRICE : ($val['refund'])/Lib_Constants::COUPON_UNIT_PRICE;///此处如果单价不是整数，可能会涉及到退小数的券
                            $coupon = intval($coupon);
                            $amount = $val['refund'] >  $coupon_amount ? ($val['refund'] - $coupon_amount) : 0;
                            $query = $this->active_merage_order_model->query("UPDATE `t_active_merage_order".$val['table']."` SET `iRefundingCoupon` = `iRefundingCoupon` + ".$coupon.",`iRefundingAmount` = `iRefundingAmount` + ".$amount.",`iRefundStatus`=1 WHERE `sMergeOrderId`=".$val['merage_order_id']." AND `iUin`=".$val['uin'], true);
                            if(!$query){
                                $this->log($this->active_merage_order_model->db->last_query());
                                $return = false;
                            }else{
                                $query = $this->active_order_model->query("UPDATE `t_active_order".$val['table']."` SET `iRefundingCoupon` = `iRefundingCoupon` + ".$coupon.",`iRefundingAmount` = `iRefundingAmount` + ".$amount.",`iRefundStatus`=1 WHERE `sOrderId`=".$val['order_id']." AND `iUin`=".$val['uin'], true);

                                $this->log('STEP10  : =============insert refund orders | coupon['.$coupon.'] | amount['.$amount.']=============');
                                ///+++++++++++++由于退款与退夺宝券分开，所以涉及到同时退款退券的订单需要分两个退款单
                                if(!empty($amount)){
                                    $add = array(
                                        'sOrderId' => $val['order_id'],
                                        'iUin' => $val['uin'],
                                        'sToken' => '',
                                        'iBuyType' => Lib_Constants::ORDER_TYPE_ACTIVE,
                                        'iPayAgentType' => $val['pay_type'],
                                        'iRefundPrice' => $amount,
                                        'iRefundCoupon' => 0,
                                        'sRefundKey' => $this->serial(),//内部退款单号
                                        'sTransId' => $val['trans_id'], //支付订单流水
                                        'iCreateTime' => time()
                                    );
                                    if(!($insert = $this->add_refund($add))){
                                        $this->log('insert t_order_refund fail | repeat['.$repeat.']');
                                    }
                                }

                                if(!empty($coupon)){
                                    $add = array(
                                        'sOrderId' => $val['order_id'],
                                        'iUin' => $val['uin'],
                                        'sToken' => '',
                                        'iBuyType' => Lib_Constants::ORDER_TYPE_ACTIVE,
                                        'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_COUPON,
                                        'iRefundPrice' => 0,
                                        'iRefundCoupon' => $coupon,
                                        'sRefundKey' => $this->serial(),//内部退款单号
                                        'sTransId' => $val['trans_id'], //支付订单流水
                                        'iCreateTime' => time()
                                    );
                                    if(!$insert = $this->add_refund($add)){
                                        $this->log('insert t_order_refund fail | repeat['.$repeat.']');
                                    }

                                    //发送推送通知
                                    $data = array(
                                        'url' => gen_uri('/luckybag/coupon'),
                                        'uin' => $val['uin'],
                                        'nick_name' => $val['nick_name'],
                                        'goods_name' => $val['goods_name'],
                                        'peroid' => period_code_encode($val['iActId'],$val['iPeroid']),
                                        'count' => $val['iCount'],//总参与人次
                                        'amount' => price_format($val['iAmount']),//总支付金额,
                                        'payagent' => '返回您的账户劵余额',
                                        'refund_id' => $val['order_id'],
                                        'refund_coupon' => $coupon,
                                        'send_code' => $val['iCount']-($coupon*Lib_Constants::COUPON_UNIT_PRICE/$val['iUnitPrice'])
                                    );
                                    $this->load->service('push_service');
                                    $rs = $this->push_service->add_task(Lib_Constants::$msg_business_type[Lib_Constants::MSG_TEM_ORDER_REFUND],$val['order_id'],$val['uin'],$data);
                                    if(empty($rs) || $rs < 0){
                                        $this->log('add push task fail | rs['.$rs.'] | data['.json_encode($data).']');
                                    }
                                }
                            }
                            $repeat--;

                        }while($repeat > 0 && $return == false);
                    }
                }
            }

            $this->log("===============END ACTIVE RUN(".date('Y-m-d H:i:s').")=========================\n");
            //echo "DONE!!!!";
        //}
    }

    //机器人发码逻辑
    public function robot_run($tab_index)
    {
        $this->log("===============BEGIN ROBOT RUN(".date('Y-m-d H:i:s').")=========================");
        $this->load->model('robot_temporary_model');
        //$list = $this->robot_temporary_model->get_rows(array('iLuckyCodeState'=>0));
        $list = $this->robot_temporary_model->query("SELECT * FROM t_robot_temporary".$tab_index." WHERE iLuckyCodeState = 0 order by iCreateTime asc limit 10"); ///由于要减小用户等待发码时间，所以这里只能把limit值设小减少相关时间
        //$this->log('STEP1  : =============get list=============');
        foreach($list as $row){
            //检查购买的夺宝活动是否还在线，如果不在或不等于当前期数，则全额退款
            $this->log("STEP1: =============check active[".period_code_encode($row['iActId'],$row['iPeroid'])."]=============");
            $item = $this->active_peroid_model->get_row(array('iActId'=>$row['iActId'],'iPeroid'=>$row['iPeroid'],'iIsCurrent'=>1));
            if(empty($item) || $item['iPeroid'] != $row['iPeroid']){
                if($query = $this->robot_temporary_model->update_row(array('iLuckyCodeState'=>-1),array('iActId'=>$row['iActId'],'iPeroid'=>$row['iPeroid'],'sOrderId'=>$row['sOrderId']))){
                    $this->log('active item not found | params['.json_encode($row).']');
                }
                continue;
            }

            //先设置订单表为iLuckyCodeState=1
            $query = $this->robot_temporary_model->update_row(array('iLuckyCodeState'=>1),array('iActId'=>$row['iActId'],'iPeroid'=>$row['iPeroid'],'sOrderId'=>$row['sOrderId']));
            $this->log("STEP2: =============update iLuckycode status = 1==================");
            if(!$query){
                $this->log('update active order fail | sOrderId['.$row['sOrderId'].']');
                $this->log($this->robot_temporary_model->db->last_query());
                continue;
            }

            //检查用户信息
            $this->log("STEP3: =============check user=============");
            $user = $this->user_model->get_user_by_uin($row['iUin']);
            if(!$user){
                $this->log('user not found | iUin['.$row['iUin'].']');
                continue;
            }

            //检查是否发过码
            $this->log("STEP4: =============check send repeat code=============");
            $log = $this->luckycode_record_model->get_row(array('sOrderId'=>$row['sOrderId'],'iUin'=>$row['iUin'],'iActId'=>$row['iActId']));
            if(!empty($log)){
                $this->log('order already processed | sOrderId['.$row['sOrderId'].']');
                continue;
            }


            $item_code_num = $item['iSoldCount'] > $item['iLotCount'] || $item['iProcess'] >= 100 ? 0 : ($item['iLotCount'] - $item['iSoldCount']);//当期可以购买的码数
            $code_num = $item_code_num > $row['iCount'] ? $row['iCount'] : $item_code_num;  //需要的发码数
            $refund_code_num = $item_code_num > $row['iCount'] ? 0 : $row['iCount'] - $item_code_num; //需要退款的码数
            //检查当期夺宝活动还需要多少码，如果不够，则多出来的退款
            $this->log("STEP5: =============luckycode & refund==SoldCount[".$item['iSoldCount']."] | iLotCount[".$item['iLotCount']."] | iCodeNum[".$code_num."]===========");
            if($refund_code_num > 0){
                //$refund[$row['iActId']][] = array('uin'=>$row['iUin'],'order_id'=>$row['sOrderId'],'merage_order_id'=>$row['sMergeOrderId'],'code_num'=>$refund_code_num,'refund'=>$refund_code_num*$item['iCodePrice'],'pay_type'=>$row['iPayAgentType'],'trans_id'=>$row['sTransId'],'coupon'=>$row['iCoupon'],'table'=>$j);
                $this->log("refund code num[".$refund_code_num."]");
            }
            if($code_num <= 0){
                continue;
            }


            $this->log("STEP6: =============t_active_order=============");
            $lucky_code = array();
            for($m=0;$m<=$code_num-1;$m++){
                $lucky_code[] = self::BASE_CODE+$m+$item['iSoldCount'];
            }
            $json_lucky_code = json_encode($lucky_code);
            $query = $this->robot_temporary_model->update_row(array('sLuckyCodeJson'=>$json_lucky_code,'iLuckyCodeNum'=>count($lucky_code)),array('iActId'=>$row['iActId'],'iPeroid'=>$row['iPeroid'],'sOrderId'=>$row['sOrderId']));
            if(!$query){
                $this->log('update active order fail | sOrderId['.$row['sOrderId'].']');
                $this->log($this->robot_temporary_model->db->last_query());
            }else{
                //更新活动表中减去夺宝码数量
                $time = time();
                $msct_time = round(microtime(1)*1000);//这里是毫秒级别
                $this->log("STEP7: =============t_active_peroid=============");
                $process = (int)(($item['iSoldCount']+$code_num)/$item['iLotCount']*100);
                $process = empty($process) ? 1 : $process;
                $query = $this->active_peroid_model->query("UPDATE `t_active_peroid` SET `iSoldCount` = `iSoldCount`+".$code_num.",`iSoldOutTime`=".$time.",`iUpdateTime`=".time().",`iTotalSoldCount`=`iTotalSoldCount`+".$code_num.",`iProcess`=".$process." WHERE iActId=".$item['iActId']." AND iPeroid=".$item['iPeroid']." LIMIT 1", true);
                //$this->log($this->active_peroid_model->db->last_query());
                if(!$query){
                    $this->log('update active_peroid_model fail');
                    continue;
                }
                //更新缓存
                if (!$this->active_peroid_model->update_cache_rows(array('iPeroidCode'=>period_code_encode($item['iActId'],$item['iPeroid']),'iActId'=>$item['iActId'], 'iPeroid'=>$item['iPeroid']))) {//更新缓存
                    $this->log('update active_peroid_model row cache fail | row:'.json_encode(array('iActId'=>$item['iActId'], 'iPeroid'=>$item['iPeroid'])));
                }

                //这里将为用户分配夺宝码并且插入记录表中
                $this->log("STEP8: =============t_luckycode_record=============");
                $lucky_code_sql = "INSERT INTO t_luckycode_record".$tab_index." (iGoodsId,iActId,iPeroid,sOrderId,iUin,sNickName,sLuckyCode,iCreateTime,iMsecTime) VALUES ";
                $lucky_code_temp = "";
                foreach($lucky_code as $k=>$code){
                    $lucky_code_temp .= "(".$item['iGoodsId'].",".$item['iActId'].",".$row['iPeroid'].",'".$row['sOrderId']."','".$row['iUin']."','".addslashes($user['sNickName'])."',".$code.",".$time.",".$msct_time."),";
                }
                $lucky_code_sql .= trim($lucky_code_temp,',');
                $repeat = self::REPEAT;
                //$this->log($lucky_code_sql);
                do{
                    $return = true;
                    if(!$this->luckycode_record_model->query($lucky_code_sql)){
                        $return = false;
                        $this->log('insert t_luckycode_record fail | repeat['.$repeat.']');
                    }
                    $repeat--;

                }while($repeat > 0 && $return == false);
                unset($lucky_code_sql,$lucky_code_temp,$lucky_code);

                //上面的操作执行成功，在夺宝码汇总表插入数据，是推送发码消息的直接数据来源
                $this->log("STEP10: =============t_active_summary=============");
                if($return){
                    $now = time();
                    $add = array(
                        'iPeroidCode' => period_code_encode($item['iActId'], $row['iPeroid']),
                        'sOrderId' => $row['sOrderId'],
                        'iCreateTime' => date('Y-m-d H:i:s', $now),
                        'iActId' => $item['iActId'],
                        'iPeroid' => $row['iPeroid'],
                        'iGoodsId' => $item['iGoodsId'],
                        'sGoodsName' => $item['sGoodsName'],
                        'iUin' => $row['iUin'],
                        'sNickName' => addslashes($user['sNickName']),
                        'sHeadImg' => $user['sHeadImg'],
                        'iLotCount' => $code_num,
                        'sLuckyCodes' => $json_lucky_code,
                        'iIsRobot' => is_robot($row['iUin']) ? 1 : 0,
                        'iIP' => empty($row['sIP']) ? '' : $row['sIP'],
                        'iLocation' => empty($row['sLocation']) ? '' : $row['sLocation'],
                        'iLastModTime' => date('Y-m-d H:i:s', $now),
                        'iMsecTime' => round(microtime(1)*1000),//这里是毫秒级别
                    );
                    if(!$this->add_user_summary($add)){
                        $this->log('insert t_user_summary fail | sOrderId['.$row['sOrderId'].']');
                    }
                    if(!$this->add_active_summary($add)){
                        $this->log('insert t_active_summary fail | sOrderId['.$row['sOrderId'].']');
                    }
                    if(!$this->add_active_temporary($add)){
                        $this->log('insert t_active_temporary fail | sOrderId['.$row['sOrderId'].']');
                    }
                }
            }
            $this->log("STEP11: =============item end=============\n");
        }
        $this->log("===============END ROBOT RUN(".date('Y-m-d H:i:s').")=========================");
    }


    /**
     * 获取所有在线活动,并且可以购买的
     */
    public function get_active_item(){
        $list = array();
        $time = time();
        $table_name = 't_active_peroid';
        $result = $this->active_config_model->query("SELECT * FROM `".$table_name."` WHERE iIsCurrent = 1 AND iLotState = ".Lib_Constants::ACTIVE_LOT_STATE_DEFAULT);
        foreach($result as $row){
            $list[$row['iActId']] = $row;
        }

        return $list;
    }

    /**
     * 增加退款记录
     * @param $data
     * @return bool
     */
    public function add_refund($data)
    {
        $inser = $this->order_refund_model->add_row($data);
        $this->log($this->order_refund_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }

    /**
     * 夺宝记录表
     * @param $data
     * @return bool
     */
    public function add_record($data)
    {
        $inser = $this->luckycode_record_model->add_row($data);
        //$this->log($this->luckycode_record_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }

    /**
     * 夺宝记录汇总表
     * @param $data
     * @return bool
     */
    public function add_user_summary($data){
        $inser = $this->user_summary_model->add_row($data);
        //$this->log($this->user_summary_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }


    public function add_active_summary($data)
    {
        $inser = $this->active_summary_model->add_row($data);
        //$this->log($this->active_summary_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }

    public function add_active_temporary($data)
    {
        $inser = $this->active_temporary_model->add_row($data);
        //$this->log($this->active_temporary_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }


    /**
     * 夺宝记录汇总表
     * @param $data
     * @return bool
     */
    public function add_summary($data){
        $inser = $this->order_summary_model->add_row($data);
        $this->log($this->order_summary_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }


    public function add_luckycode_summary($data)
    {
        $inser = $this->luckycode_summary_model->add_row($data);
        $this->log($this->luckycode_summary_model->db->last_query());
        if(!$inser){
            return false;
        }else{
            return $inser;
        }
    }

    protected function serial()
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr($usec,2,6);

        return date('YmdHis').$usec.rand(10,99);
    }


}