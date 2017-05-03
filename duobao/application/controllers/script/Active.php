<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每30秒跑一次
 * 自动开奖及新开活动
 * @date 2016-03-21
 * @autor leo.zou
 */

class Active extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数

    protected $log_type = 'Active';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_deliver_model');
        $this->load->model('active_config_model');
        $this->load->model('active_peroid_model');
        $this->load->model('luckycode_record_model');
        $this->load->model('luckycode_summary_model');
        $this->load->model('active_summary_model');
        //$this->load->model('order_summary_model');
        $this->load->model('user_summary_model');
        $this->load->model('active_temporary_model');
        $this->load->model('user_model');
        $this->load->model('ssc_model');
    }


    public function run()
    {
        //while(true){
            set_time_limit(0);
            $time = time();
            $active_peroid_list = $this->active_peroid_model->row_list('iActId,iPeroid,iPeroidCode,iIsCurrent,iGoodsId,iCateId,sGoodsName,iGoodsType,iCostPrice,
            sImg,iActType,iHeat,iCodePrice,iLotCount,iTotalPrice,iPeroidCount,iSoldCount,iProcess,iSoldOutTime,iBeginTime,iEndTime,iTotalSoldCount,iLotState,iLotTime,sWinnerCode,
            sWinnerNickname,sWinnerHeadImg,iWinnerCount,iIssue,iLotNumA,iLotNumB,iIsRobot,iCreateTime,iUpdateTime',array('iLotState !='=>Lib_Constants::ACTIVE_LOT_STATE_OPENED,'iProcess >='=>100),$order_by=array(), $page_index = 1, $page_size = self::LIMIT);
            //pr($this->active_peroid_model->db->last_query());pr($active_peroid_list);die;
            $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
            foreach($active_peroid_list['list'] as $item){
                $peroid_code = period_code_encode($item['iActId'],$item['iPeroid']);
                $this->log('#############item : '.json_encode($item));
                $lot_basis = array();
                switch ($item['iLotState']){
                    case Lib_Constants::ACTIVE_LOT_STATE_DEFAULT://未开奖
                        //检查是符合开奖条件
                        if($item['iProcess'] >= 100 && $item['iLotCount'] <= $item['iSoldCount'] && empty($item['iLotTime'])){
                            $this->log("STEP1: =============active status[0]=============");

                            //保存A值
                            $numberA = 0;
                            $record = $this->active_temporary_model->row_list('iPeroidCode,sOrderId,iCreateTime,iMsecTime,iGoodsId,sGoodsName,iUin,sNickName,sHeadImg,iLotCount,sLuckyCodes,iIP,iLocation',array('iMsecTime <='=>$item['iSoldOutTime'].'000'),$order_by=array('iMsecTime'=>'desc','iCreateTime'=>'desc'), $page_index = 1, $page_size = 50);
                            foreach($record['list'] as $li){
                                $lot_basis[] = $li;
                                $numberA += $li['iMsecTime'];
                            }

                            //如果为私人定制，则不需要等时时彩结果,1分钟就开奖
                            if($item['iActType'] != Lib_Constants::ACTIVE_TYPE_CUSTOM){
                                $ssc = $this->ssc_model->get_next_ssc($item['iSoldOutTime']);
                                $this->log("STEP2: =============get ssc data[".$item['iIssue']."] | ssc[".json_encode($ssc)."]=============");
                                if(empty($ssc) || !is_array($ssc)){
                                    $this->log("STEP2-1: =============get ssc data fail=============");
                                    break;
                                }else{
                                    $this->log("STEP2-2: =============get ssc data succ=============");
                                }
                                $lot_time = $ssc['min']+100;//开奖时间,由于API可能会有延时，所以稍延长100秒
                            }else{
                                $lot_time = time()+60; //私人夺宝不需要太长等待时间
                            }

                            $data = array(
                                'iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_GOING,
                                'iLotTime'=>$lot_time,
                                'iIsCurrent' => 0,
                                'iIssue' => empty($ssc['issue']) ? 0 : $ssc['issue'],
                                'iLotNumA' => $numberA,
                                'sLotBasis' => json_encode($lot_basis)
                            );
                            unset($record,$lot_basis);

                            //判断是否能开启新期
                            $active_config = $this->active_config_model->get_row(array('iActId'=>$item['iActId']));
                            $active_peroid = $this->active_peroid_model->get_row(array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId'],'iIsCurrent'=>1));
                            $this->active_peroid_model->update_row($data,array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']));
                            //$this->active_config_model->update_row(array('iLotState'=>2,'iLotTime'=>$lot_time),array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']));
                            if($active_config['iEndTime'] > $time && $active_config['iBeginTime'] <= $time && $active_peroid['iPeroid'] < $active_config['iPeroidCount'] && $active_config['iState'] == Lib_Constants::ACTIVE_STATE_ONLINE){
                                $insert = $this->active_peroid_model->add_new_active_peroid($item['iActId'],$active_config,$active_peroid['iPeroid']+1);
                                if(!$insert){
                                    $this->log('create new active period fail | sql['.$this->active_peroid_model->db->last_query().')]');
                                }
                            }elseif($active_config['iState'] == Lib_Constants::ACTIVE_STATE_ONLINE){
                                $rs = $this->active_config_model->update_row(array('iState'=>Lib_Constants::ACTIVE_STATE_DONE),array('iActId'=>$item['iActId'],'iGoodsId'=>$item['iGoodsId']));
                                if(!$rs){
                                    $this->log('update active_config_model fail | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                }
                            }

                            //需要开奖通知的所有用户,这里只更新相关数据，单独由消息脚本处理通知
                            if(!$this->luckycode_summary_model->update_rows(array('iLotTime'=>$lot_time,'iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_GOING),array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']))){
                                $this->log('update luckycode_summary fail | sql['.$this->luckycode_summary_model->db->last_query().')]');
                            }

                            //更新订单汇总表活动状态——即将开奖
                            $this->update_order_summary(array(
                                'iLotTime'=>$lot_time,
                                'iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_GOING,
                                'iLastModTime' => time()
                            ),array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']));
                        }
                        break;

                    case Lib_Constants::ACTIVE_LOT_STATE_GOING://即奖开奖
                        $this->log("STEP1: =============active status[1] peroidcode[".$peroid_code."]=============");

                        $time = time();
                        if($item['iLotTime'] <= $time){
                            $active_peroid = $this->active_peroid_model->get_row(array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']));
                            if($active_peroid['iProcess'] >= 100 && $active_peroid['iLotCount'] <= $active_peroid['iSoldCount']){
                                $this->log("STEP2: =============active lottery start[".$peroid_code."]=============");//开奖逻辑判断

                                //获取时时彩数据
                                if($item['iActType'] != Lib_Constants::ACTIVE_TYPE_CUSTOM){ //如果为私人定制，则不走时时彩规则
                                    $ssc = $this->ssc_model->get_win($item['iIssue']);
                                    $this->log("STEP3: =============get ssc data[".$item['iIssue']."] | ssc[".json_encode($ssc)."]=============");
                                    if(empty($ssc) || !is_array($ssc)){//如果未获取到，则先不开奖,
                                        $this->log("STEP3-1: =============get ssc data fail[".json_encode($ssc)."]=============");
                                        break;
                                    }
                                }
                                $numberA = $item['iLotNumA']; //参与记录最后50条总和
                                $numberB = empty($ssc['iWinNum']) ? 0 : intval($ssc['iWinNum']);//时时返回5位中奖数
                                $numberC = $item['iLotCount']; //商品总需参与人次

                                $this->log("STEP4: =============calculate luckycode | numberB[".$numberB."] | numberA[".$numberA."] | numberC[".$numberC."]=============");
                                //开奖:幸运号码 = （数字A + 数字B）% 数字C + Lib_Constants::LUCKY_CODE_BASE
                                $lot_basis = json_decode($active_peroid['sLotBasis'],true);
                                $custom = get_variable('custom_lucky_'.$peroid_code);
                                if(!empty($custom) && is_numeric($custom)){ //定制,影响单价5000以内
                                    $winner_code = $custom_code = $custom;//10003900
                                    $reste1 = $custom_code - Lib_Constants::LUCKY_CODE_BASE;
                                    $reste2 = ($numberA+$numberB) - intval(($numberA+$numberB)/$numberC)*$numberC;
                                    if($reste1 > $reste2){
                                        $msec = $reste1 - $reste2;
                                    }else{
                                        $msec = $numberC-$reste2+$reste1;
                                    }
                                    $msec = $msec < 100 ? $msec+$numberC : $msec;
                                    $this->log('custom luckycode | $msec['.$msec.']');

                                    foreach($lot_basis as $k => &$lot){
                                        $rank = $this->rankNum($msec,50-($k+1));
                                        $create_time = strtotime($lot['iCreateTime']);
                                        if($k == 49){
                                            $lot['iMsecTime'] = $rank+intval($create_time.'000');
                                        }else{
                                            $lot['iMsecTime'] = $rank+intval($create_time.'000');
                                        }
                                        $msec = $msec-$rank;
                                    }

                                    $volume = array();
                                    foreach($lot_basis as $k => $val){
                                        $volume[$k] = $val['iMsecTime'];
                                    }
                                    array_multisort($volume, SORT_DESC,$lot_basis);
                                    unset($volume,$custom,$custom_code,$reste1,$reste2,$rank);
                                }else{
                                    $winner_code = ($numberA+$numberB) - intval(($numberA+$numberB)/$numberC)*$numberC + Lib_Constants::LUCKY_CODE_BASE;
                                }
                                $this->log("STEP5: =============active winner code[".$winner_code."]=============");

                                $this->log("STEP6: =============get active winner user=============");
                                $winner = $this->luckycode_record_model->get_row(array('sLuckyCode'=>$winner_code,'iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']));
                                if(empty($winner) || !isset($winner['iUin'])){
                                    $this->log('STEP6-1:=======get winner user fail | $winner['.json_encode($winner).')] | winner_code['.$winner_code.']==========');
                                    continue;
                                }
                                $user = $this->user_model->get_user_by_uin($winner['iUin']);
                                if(empty($user)){
                                    $this->log('STEP6-2:=======get winner user info fail | $winner['.json_encode($user).')] | winner_code['.$winner_code.'] | uin['.$winner['iUin'].']==========');
                                    continue;
                                }
                                $winner = array_merge($winner,$user);
                                $is_robot = is_robot($winner['iUin']) ? 1 : 0;

                                //设置中奖人,并且设置状态为已揭晓
                                $this->log("STEP7: =============set active winner user=============");
                                $total_count = 0;
                                $summary = $this->active_summary_model->row_list('iLotCount',array('iPeroidCode'=>$peroid_code,'iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId'],'iUin'=>$winner['iUin']),array(),1,self::LIMIT);
                                foreach($summary['list'] as $val){
                                    $total_count += $val['iLotCount'];
                                }
                                $data = array(
                                    'iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_OPENED,
                                    'sWinnerCode'=>$winner_code,
                                    'iWinnerUin'=>$winner['iUin'],
                                    'sWinnerNickname'=>$winner['sNickName'],
                                    'sWinnerHeadImg'=>$winner['sHeadImg'],
                                    'iWinnerCount' => $total_count,
                                    'sWinnerOrder' => $winner['sOrderId'],
                                    'iLotNumA' => $numberA,
                                    'iLotNumB' => $numberB,
                                    'iIsCurrent' => 0,
                                    'iIsRobot' => $is_robot,
                                    'sLotBasis' => json_encode($lot_basis)
                                );
                                if($this->active_peroid_model->update_row($data,array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']))){
                                    $this->log("STEP7-1: =============active update data succ=============");
                                }else{
                                    $this->log("STEP7-2: =============active update data  fail:sql[".json_encode($data)."]=============");
                                    continue;
                                }

                                //更新开奖结果通知,此表数据仅适用真实用户推送消息之用
                                $this->log("STEP8: =============update luckycode summary active state=============");
                                if(!$this->luckycode_summary_model->update_rows(array('iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_OPENED),array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']))){
                                    $this->log('update luckycode_summary fail | sql['.$this->luckycode_summary_model->db->last_query().')]');
                                }

                                //添加发货数据
                                if(empty($is_robot)){
                                    $this->log("STEP9: =============add deliver data=============");
                                    if(!$this->order_deliver_model->add_deliver_row($winner['iUin'],$active_peroid['iGoodsId'],$winner['sOrderId'],Lib_Constants::ORDER_TYPE_ACTIVE,'中奖信息')){
                                        $this->log('STEP9-1: ===============add  order_deliver_model fail | sql['.$this->order_deliver_model->db->last_query().')]===============');
                                    }
                                }

                                //更新订单汇总表活动状态——已开奖
                                $this->log("STEP9: =============update user summary data=============");
                                $this->update_order_summary(array(
                                    'iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_OPENED,
                                    'iLastModTime' => time()
                                ),array('iActId'=>$item['iActId'],'iPeroid'=>$item['iPeroid'],'iGoodsId'=>$item['iGoodsId']));

                                //更新中奖订单
                                if(!$this->user_summary_model->update_row(array('iIsWin' => 1,'iLastModTime'=>time()),array('iUin'=>$winner['iUin'],'sOrderId' => $winner['sOrderId']))){
                                    $this->log('update  user_summary_model fail | sql['.$this->user_summary_model->db->last_query().')]');
                                }
                            }
                        }
                        break;
                }

            }

            //sleep(10);
            $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
        //}
    }


    /**
     * 随机数
     * @param $total
     * @param $num
     * @param float $cardinal
     * @return int
     */
    protected function rankNum($total,$num,$cardinal = 0.2)
    {
        if($total<= 0 || $num<= 0) return 0;

        $couponNum = 0;
        if($num == 1){
            $couponNum = $total;
        }else{
            $threshold = intval(($total-$num)*(1/$num+$cardinal));
            if(!empty($threshold)){
                $couponNum = 1+rand(1,$threshold);
            }else{
                $couponNum = 1;
            }

        }

        return $couponNum;
    }

    /**
     * 获取当前期的汇总记录
     * @param $item
     * @return mixed
     */
    public function notify_summary($item)
    {
        $cur_table = $this->luckycode_summary_model->map($item['iActId'])->get_cur_table();
        $summary = $this->luckycode_summary_model->query('SELECT * FROM `'.$cur_table.'` WHERE iActId='.$item['iActId']." AND iPeroid=".$item['iPeroid']." AND iGoodsId=".$item['iGoodsId']." AND iNotifyStatus = 0");

        return $summary;
    }


    /**
     * 获取当前期的汇总记录
     * @param $item
     * @return mixed
     */
    public function result_summary($item)
    {
        $cur_table = $this->luckycode_summary_model->map($item['iActId'])->get_cur_table();
        $summary = $this->luckycode_summary_model->query('SELECT * FROM `'.$cur_table.'` WHERE iActId='.$item['iActId']." AND iPeroid=".$item['iPeroid']." AND iGoodsId=".$item['iGoodsId']." AND iResultStatus = 0");

        return $summary;
    }


    /**
     * 更新用户订单汇总表
     * @param $data
     * @param $condition
     */
    public function update_order_summary($data,$condition)
    {
        for($i=0;$i<10;$i++){
            $this->user_summary_model->map($i.'1');
            for($j=0;$j<10;$j++){
                $table_name = 't_user_summary'.$j;
                $insert = $where = "";
                foreach($data as $key=>$val){
                    $insert .= $key."=".$val.",";
                }
                foreach($condition as $key=>$val){
                    $where .= $key."=".$val." AND ";
                }

                if(!$this->user_summary_model->query('UPDATE `'.$table_name.'` SET '.trim($insert,',').'  WHERE '.$where.' 1=1', true)){
                    $this->log('add  user_summary_model fail | sql['.$this->user_summary_model->db->last_query().')]');
                }
            }
        }
    }
}