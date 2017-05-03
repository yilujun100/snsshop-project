<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每天凌晨3点跑一次
 * 统计前一天的数据
 * @date 2016-05-23
 * @autor alanwang
 */

class RealTimeStatistics extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的数量
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'RealTimeStatistics';

    public function __construct()
    {
        parent::__construct();
    }


    public function run()
    {
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        set_time_limit(0);


        date_default_timezone_set('PRC');   //中国时间区
        $now_time = time(); //当前时间戳
        $now_time_date = date('Y-m-d H',$now_time); //得到当前小时整点时间
        $now_time_date = $now_time_date.':00:00';
        $now_time_strto = strtotime($now_time_date);    //得到当前整点的时间戳
        $now_min_time = date('i',$now_time);    //得到当前时间的分钟数
        if($now_min_time >= 30)  //如果当前时间的分钟数大于30
        {
            $now_time_next = $now_time_strto;   //当前时间下一小时的整点时间戳
            $now_time_half = $now_time_strto + 60*30;   //当前时间30分时的时间戳
            $time_result['begin_time'] = $now_time_next;
            $time_result['end_time'] = $now_time_half;
        }
        else
        {
            $now_time_half = $now_time_strto - 60*30;
            $now_time_next = $now_time_strto;
            $time_result['begin_time'] = $now_time_half;
            $time_result['end_time'] = $now_time_next;
        }

        $createTime = $time_result['begin_time']+1;
        $delete_where = ' WHERE iCreateTime = '.$createTime;
        $where = ' WHERE iCreateTime >  '.$time_result['begin_time'].' AND iCreateTime < '.$time_result['end_time'];


        $yydb_user_statistics = $this->load->database('yydb_user_s2',TRUE); //开启从库数据库链接


        $userCreateCount = 0; //新关注用户数量
        for($user_create_index = 0; $user_create_index < 10; $user_create_index ++)
        {
            $user_create_db_name = ' t_user'.$user_create_index;
            $user_create_where = ' WHERE iRegTime > '.$time_result['begin_time'] .' AND iRegTime < '.$time_result['end_time'];
            $user_create_sql = 'SELECT count(iUin) cTotal FROM '.$user_create_db_name.$user_create_where.'   ;';
            $user_create_result_list = $yydb_user_statistics->query($user_create_sql)->result_array();
            //$user_create_result_list = $this->user_model->query($user_create_sql, true);
            $userCreateCount = empty($user_create_result_list[0]['cTotal'])?$userCreateCount:$userCreateCount+$user_create_result_list[0]['cTotal'];
        }

        $payOrderCount = 0;    //已支付订单数
        $notPayOrderCount = 0;  //未支付订单数
        $useCoupon = 0; //已使用券
        $payMoney = 0;    //已支付现金金额
        $notPayMoney = 0; //未支付现金金额
        $payArray = array();    //已支付人数数组
        $notPayArray = array(); //未支付人数数组
        $activeUser = 0;    //活跃用户数

        $this->log("====================================查询大订单并且循环=============================================");

        for($active_order_db_index = 0; $active_order_db_index < 10; $active_order_db_index ++)
        {
            $active_order_db_index_db_name = ' t_active_merage_order'.$active_order_db_index;
            $active_merage_order_sql = 'SELECT sMergeOrderId,iUin,iTotalPrice,iCoupon,iAmount,iPayAgentType,iPayTime,iPayStatus,sTransId,iStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iSrc,iPlatformId,iCreateTime,iLastModTime FROM '.$active_order_db_index_db_name.$where.'  ;';
            $active_merage_order_result_list = $yydb_user_statistics->query($active_merage_order_sql)->result_array();
            //$active_merage_order_result_list = $this->active_merage_order_model->query($active_merage_order_sql, true);

            if(!empty($active_merage_order_result_list))
            {
                foreach($active_merage_order_result_list as $active_merage_order)
                {
                    if($active_merage_order['iPayStatus'] == 1 && $active_merage_order['iStatus'] == 1)
                    {
                        $payOrderCount++;  //订单已支付并且是正常的状态
                        $useCoupon = $useCoupon + $active_merage_order['iCoupon'];
                        $payMoney = $payMoney + $active_merage_order['iAmount'];
                        $activeUser++;
                    }
                    if($active_merage_order['iPayStatus'] == 0)
                    {
                        $notPayOrderCount ++;   //订单未支付
                        $notPayMoney = $notPayMoney + $active_merage_order['iAmount'];
                    }
                }
            }

        }

        $this->log("====================================充值流水明细=============================================");

        $couponPayCount = array();  //已支付人数
        $couponOrderCount = 0;  //已支付订单数
        $couponPayMoney = 0;    //已支付现金金额
        $couponUseCoupon = 0;   //已使用券
        $couponNotPayCount = array();   //未支付人数
        $couponNotOrderCount = 0;   //未支付订单数
        $counponNotPayMoney = 0;    //未支付金额

        for($coupon_index = 0; $coupon_index < 10; $coupon_index ++)
        {
            $coupon_db_name = ' t_coupon_order'.$coupon_index;
            $coupon_order_sql = 'SELECT sOrderId,iUin,iCount,iPresentCount,iUnitPrice,iTotalPrice,iPayAgentType,iPayTime,iStatus,sTransId,iPayStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iSrc,iPlatformId,iCreateTime,iLastModTime FROM '.$coupon_db_name.$where.'  ;';
            $coupon_order_result_list = $yydb_user_statistics->query($coupon_order_sql)->result_array();
            //$coupon_order_result_list = $this->coupon_order_model->query($coupon_order_sql, true);
            if(!empty($coupon_order_result_list))
            {
                foreach($coupon_order_result_list as $coupon_order)
                {
                    if($coupon_order['iPayStatus'] == 1 && $coupon_order['iStatus'] == 1)
                    {
                        if(!in_array($coupon_order['iUin'],$couponPayCount))
                        {
                            array_push($couponPayCount,$coupon_order['iUin']);
                        }
                        $couponOrderCount++;
                        $couponPayMoney = $couponPayMoney + $coupon_order['iTotalPrice'];
                    }
                    if($coupon_order['iPayStatus'] == 0)
                    {
                        if(!in_array($coupon_order['iUin'],$couponNotPayCount))
                        {
                            array_push($couponNotPayCount,$coupon_order['iUin']);
                        }
                        $couponNotOrderCount++;
                        $counponNotPayMoney = $counponNotPayMoney + $coupon_order['iTotalPrice'];
                    }
                }
            }
        }

        $this->log("====================================查询小订单并且循环=============================================");
        for($order_index = 0; $order_index < 10; $order_index ++)
        {
            $order_db_name = ' t_active_order'.$order_index;
            $active_order_sql = 'SELECT sMergeOrderId,iUin,iGoodsId,iActId,iPeroid,iActType,iBuyType,iCount,iUnitPrice,iTotalPrice,iAmount,iPayAgentType,iPayTime,sTransId,iPayStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iLuckyCodeNum,iLuckyCodeState,iCreateTime,iLastModTime FROM '.$order_db_name.$where.'  ;';
            $active_order_result_list = $yydb_user_statistics->query($active_order_sql)->result_array();
            //$active_order_result_list = $this->active_order_model->query($active_order_sql, true);
            if(!empty($active_order_result_list))
            {
                foreach($active_order_result_list as $active_order)
                {
                    if($active_order['iPayStatus'] == 1)
                    {
                        if(!in_array($active_order['iUin'],$payArray))
                        {
                            array_push($payArray,$active_order['iUin']);
                        }
                    }
                    if($active_order['iPayStatus'] == 0)
                    {
                        if(!in_array($active_order['iUin'],$notPayArray))
                        {
                            array_push($notPayArray,$active_order['iUin']);
                        }
                    }
                }
            }

        }

        $this->log("====================================兑换流水明细=============================================");
        $exchangePayCount = array();  //已支付人数
        $exchangeOrderCount = array();  //已支付订单数
        $exchangePayMoney = 0;    //已支付现金金额
        $exchangeUseCoupon = 0;   //已使用券
        $exchangeNotPayCount = array();   //未支付人数
        $exchangeNotOrderCount = array();   //未支付订单数
        $exchangeNotPayMoney = 0;    //未支付金额

        for($exchange_index = 0; $exchange_index < 10; $exchange_index ++)
        {
            $exchange_db_name = ' t_active_order'.$exchange_index;
            $exchange_order_sql = 'SELECT sMergeOrderId,iUin,iGoodsId,iActId,iPeroid,iActType,iBuyType,iCount,iUnitPrice,iTotalPrice,iAmount,iPayAgentType,iPayTime,sTransId,iPayStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iLuckyCodeNum,iLuckyCodeState,iCreateTime,iLastModTime FROM '.$exchange_db_name.$where.'  ;';
            $exchange_order_result_list = $yydb_user_statistics->query($exchange_order_sql)->result_array();
            //$exchange_order_result_list = $this->active_order_model->query($exchange_order_sql, true);
            if(!empty($exchange_order_result_list))
            {
                foreach($exchange_order_result_list as $exchange_order)
                {
                    if($exchange_order['iPayStatus'] == 1 && $exchange_order['iBuyType'] == 2)
                    {
                        if(!in_array($exchange_order['iUin'],$exchangePayCount))
                        {
                            array_push($exchangePayCount,$exchange_order['iUin']);
                        }
                        if(!in_array($exchange_order['sMergeOrderId'],$exchangeOrderCount))
                        {
                            array_push($exchangeOrderCount,$exchange_order['sMergeOrderId']);
                        }

                    }
                    if($exchange_order['iPayStatus'] == 0 && $exchange_order['iBuyType'] == 2)
                    {
                        if(!in_array($exchange_order['iUin'],$exchangeNotPayCount))
                        {
                            array_push($exchangeNotPayCount,$exchange_order['iUin']);
                        }
                        if(!in_array($exchange_order['sMergeOrderId'],$exchangeNotOrderCount))
                        {
                            array_push($exchangeNotOrderCount,$exchange_order['sMergeOrderId']);
                        }
                    }
                }
            }

        }

        for($exchange_merage_index = 0; $exchange_merage_index < 10; $exchange_merage_index ++)
        {
            $exchange_merage_db_name = ' t_active_merage_order'.$exchange_merage_index;
            $exchange_merage_order_sql = 'SELECT sMergeOrderId,iUin,iTotalPrice,iCoupon,iAmount,iPayAgentType,iPayTime,iPayStatus,sTransId,iStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iSrc,iPlatformId,iCreateTime,iLastModTime FROM '.$exchange_merage_db_name.$where.'  ;';
            $exchange_merage_order_result_list = $yydb_user_statistics->query($exchange_merage_order_sql)->result_array();
            //$exchange_merage_order_result_list = $this->active_merage_order_model->query($exchange_merage_order_sql, true);
            if(!empty($exchange_merage_order_result_list))
            {
                foreach($exchange_merage_order_result_list as $exchange_merage_order)
                {
                    if($exchange_merage_order['iPayStatus'] == 1 && $exchange_merage_order['iStatus'] == 1)
                    {
                        if(in_array($exchange_merage_order['sMergeOrderId'],$exchangeOrderCount))
                        {
                            $exchangePayMoney = $exchangePayMoney + $exchange_merage_order['iAmount'];
                            $exchangeUseCoupon = $exchangeUseCoupon + $exchange_merage_order['iCoupon'];
                        }
                    }
                    if($exchange_merage_order['iPayStatus'] == 0)
                    {
                        if(in_array($exchange_merage_order['sMergeOrderId'],$exchangeNotOrderCount))
                        {
                            $exchangeNotPayMoney = $exchangeNotPayMoney + $exchange_merage_order['iAmount'];
                        }
                    }
                }
            }
        }

        $this->log("====================================福袋流水明细=============================================");
        $luckyBagPayCount = array();  //已支付人数
        $luckyBagOrderCount = 0;  //已支付订单数
        $luckyBagPayMoney = 0;    //已支付现金金额
        $luckyBagUseCoupon = 0;   //已使用券
        $luckyBagNotPayCount = array();   //未支付人数
        $luckyBagNotOrderCount = 0;   //未支付订单数
        $luckyBagNotPayMoney = 0;    //未支付金额
        $luckyBagActiveUser = 0;



        for($bag_index = 0; $bag_index < 10; $bag_index ++)
        {
            $bag_db_name = ' t_bag_order'.$bag_index;
            $bag_sql = 'SELECT sOrderId,iBagId,iUin,iPayAmount,iPayCoupon,iTotalAmount,iCreateTime,iPayTime,iStatus FROM '.$bag_db_name.$where.'  ;';
            $bag_result_list = $yydb_user_statistics->query($bag_sql)->result_array();
            //$bag_result_list = $this->bag_order_model->query($bag_sql, true);
            if(!empty($bag_result_list))
            {
                foreach($bag_result_list as $bag)
                {
                    if($bag['iStatus'] == 1)
                    {
                        if(!in_array($bag['iUin'],$luckyBagPayCount))
                        {
                            array_push($luckyBagPayCount,$bag['iUin']);
                        }
                        $luckyBagOrderCount++;
                        $luckyBagPayMoney = $luckyBagPayMoney + $bag['iPayAmount'];
                        $luckyBagUseCoupon = $luckyBagUseCoupon + $bag['iPayCoupon'];
                        $luckyBagActiveUser++;
                    }
                    if($bag['iStatus'] == 0)
                    {
                        if(!in_array($bag['iUin'],$luckyBagNotPayCount))
                        {
                            array_push($luckyBagNotPayCount,$bag['iUin']);
                        }
                        $luckyBagNotOrderCount++;
                        $luckyBagNotPayMoney = $luckyBagNotPayMoney + $bag['iPayAmount'];
                    }
                }
            }
        }

//        $yydb_user_statistics->close(); //关闭从库数据库链接

        $this->load->model('real_time_order_detail');
        /** 查询当前时间段是否已经记录过 Begin */
        $check_active_order_where = ' AND iType = 2 ';
        $check_active_order_sql = 'SELECT iType,iCreateTime FROM t_real_time_order_detail '.$delete_where.$check_active_order_where.'  ;';
        $check_active_order_result_list = $this->real_time_order_detail->query($check_active_order_sql, true);

        if(!empty($check_active_order_result_list)) //如果有当前数据,则进行删除
        {
            $delete_active_order_where = ' WHERE iType = 2 AND iCreateTime = '.$check_active_order_result_list[0]['iCreateTime'];
            $delete_active_order_sql = 'DELETE FROM t_real_time_order_detail '.$delete_active_order_where.' ;';
            $this->real_time_order_detail->query($delete_active_order_sql, true);
        }


        $check_active_order_where_type_one = ' AND iType = 1 ';
        $check_active_order_sql_type_one = 'SELECT iType,iCreateTime FROM t_real_time_order_detail '.$delete_where.$check_active_order_where_type_one.'  ;';
        $check_active_order_result_list_type_one = $this->real_time_order_detail->query($check_active_order_sql_type_one, true);

        if(!empty($check_active_order_result_list_type_one))    //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where_type_one = ' WHERE iType = 1 AND iCreateTime = '.$check_active_order_result_list_type_one[0]['iCreateTime'];
            $delete_active_order_sql_type_one = 'DELETE FROM t_real_time_order_detail '.$delete_active_order_where_type_one.' ;';
            $this->real_time_order_detail->query($delete_active_order_sql_type_one, true);
        }


        $check_active_order_where_type_three = ' AND iType = 3 ';
        $check_active_order_sql_type_three = 'SELECT iType,iCreateTime FROM t_real_time_order_detail '.$delete_where.$check_active_order_where_type_three.'  ;';
        $check_active_order_result_list_type_three = $this->real_time_order_detail->query($check_active_order_sql_type_three, true);

        if(!empty($check_active_order_result_list_type_three))  //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where_type_three = ' WHERE iType = 3 AND iCreateTime = '.$check_active_order_result_list_type_three[0]['iCreateTime'];
            $delete_active_order_sql_type_three = 'DELETE FROM t_real_time_order_detail '.$delete_active_order_where_type_three.' ;';
            $this->real_time_order_detail->query($delete_active_order_sql_type_three, true);
        }


        $check_active_order_where_type_four = ' AND iType = 4 ';
        $check_active_order_sql_type_four = 'SELECT iType,iCreateTime FROM t_real_time_order_detail '.$delete_where.$check_active_order_where_type_four.'  ;';
        $check_active_order_result_list_type_four = $this->real_time_order_detail->query($check_active_order_sql_type_four, true);

        if(!empty($check_active_order_result_list_type_four))   //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where_type_four = ' WHERE iType = 4 AND iCreateTime = '.$check_active_order_result_list_type_four[0]['iCreateTime'];
            $delete_active_order_sql_type_four = 'DELETE FROM t_real_time_order_detail '.$delete_active_order_where_type_four.' ;';
            $this->real_time_order_detail->query($delete_active_order_sql_type_four, true);
        }

        /** 查询当前时间段是否已经记录过 End */


        $couponCount = 0;
        do
        {
            $data = array(
                'iType' => 1,
                'iPayUserCount' => count($couponPayCount),
                'iPayOrderCount' => $couponOrderCount,
                'iPayMoney' => $couponPayMoney,
                'iUseCoupon' => $couponUseCoupon,
                'iOrderARPU' => count($couponPayCount)==0?0:($couponPayMoney/100+$couponUseCoupon)/count($couponPayCount),
                'iNotPayUserCount' => count($couponNotPayCount),
                'iNotPayOrderCount' => $couponNotOrderCount,
                'iNotPayMoney' => $counponNotPayMoney,
                'iRefundMoney' => 0,
                'iMarchMoney' => 0,
                'iMarchCoupon' => 0,
                'iActivityUser' =>  0,
                'iNewUser'  =>  $userCreateCount,
                'iCreateTime'   =>  $createTime
            );
            if ($this->real_time_order_detail->add_row($data))
            {
                $couponCount = 4;
                $this->log("====================================添加充值数据成功=============================================");
            }
            else
            {
                $couponCount++;
                $this->log("====================================第".$couponCount."次添加充值数据失败=============================================");
            }
        }while($couponCount < 3);

        $duobaoCount = 0;
        do
        {
            $data = array(
                'iType' => 2,
                'iPayUserCount' => count($payArray),
                'iPayOrderCount' => $payOrderCount,
                'iPayMoney' => $payMoney,
                'iUseCoupon' => $useCoupon,
                'iOrderARPU' => count($payArray)==0?0:($payMoney/100+$useCoupon)/count($payArray),
                'iNotPayUserCount' => count($notPayArray),
                'iNotPayOrderCount' => $notPayOrderCount,
                'iNotPayMoney' => $notPayMoney,
                'iRefundMoney' => 0,
                'iMarchMoney' => 0,
                'iMarchCoupon' => 0,
                'iActivityUser' =>  $activeUser,
                'iNewUser'  =>  $userCreateCount,
                'iCreateTime'   =>  $createTime
            );
            if ($this->real_time_order_detail->add_row($data))
            {
                $duobaoCount = 4;
                $this->log("====================================添加夺宝数据成功=============================================");
            }
            else
            {
                $duobaoCount++;
                $this->log("====================================第".$duobaoCount."次添加夺宝数据失败=============================================");
            }
        }while($duobaoCount < 3);

        $exchangeCount = 0;
        do
        {
            $data = array(
                'iType' => 3,
                'iPayUserCount' => count($exchangePayCount),
                'iPayOrderCount' => count($exchangeOrderCount),
                'iPayMoney' => $exchangePayMoney,
                'iUseCoupon' => $exchangeUseCoupon,
                'iOrderARPU' => count($exchangePayCount)==0?0:($exchangePayMoney/100+$exchangeUseCoupon)/count($exchangePayCount),
                'iNotPayUserCount' => count($exchangeNotPayCount),
                'iNotPayOrderCount' => count($exchangeNotOrderCount),
                'iNotPayMoney' => $exchangeNotPayMoney,
                'iRefundMoney' => 0,
                'iMarchMoney' => 0,
                'iMarchCoupon' => 0,
                'iActivityUser' =>  0,
                'iNewUser'  =>  $userCreateCount,
                'iCreateTime'   =>  $createTime
            );
            if ($this->real_time_order_detail->add_row($data))
            {
                $exchangeCount = 4;
                $this->log("====================================添加兑换数据成功=============================================");
            }
            else
            {
                $exchangeCount++;
                $this->log("====================================第".$exchangeCount."次添加兑换数据失败=============================================");
            }
        }while($exchangeCount < 3);

        $luckyBagCount = 0;
        do
        {
            $data = array(
                'iType' => 4,
                'iPayUserCount' => count($luckyBagPayCount),
                'iPayOrderCount' => $luckyBagOrderCount,
                'iPayMoney' => $luckyBagPayMoney,
                'iUseCoupon' => $luckyBagUseCoupon,
                'iOrderARPU' => count($luckyBagPayCount)==0?0:($luckyBagPayMoney/100+$luckyBagUseCoupon)/count($luckyBagPayCount),
                'iNotPayUserCount' => count($luckyBagNotPayCount),
                'iNotPayOrderCount' => $luckyBagNotOrderCount,
                'iNotPayMoney' => $luckyBagNotPayMoney,
                'iRefundMoney' => 0,
                'iMarchMoney' => 0,
                'iMarchCoupon' => 0,
                'iActivityUser' =>  $luckyBagActiveUser,
                'iNewUser'  =>  $userCreateCount,
                'iCreateTime'   =>  $createTime
            );
            if ($this->real_time_order_detail->add_row($data))
            {
                $luckyBagCount = 4;
                $this->log("====================================添加福袋数据成功=============================================");
            }
            else
            {
                $luckyBagCount++;
                $this->log("====================================第".$luckyBagCount."次添加福袋数据失败=============================================");
            }
        }while($luckyBagCount < 3);

        //重新切回主库,并且关闭链接
//        $yydb_user_m1 = $this->load->database('yydb_user_m1',TRUE);
//        $yydb_user_m1->close();

        sleep(10);
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
        //}
    }


}