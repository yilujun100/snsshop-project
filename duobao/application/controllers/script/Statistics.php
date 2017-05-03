<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每天凌晨3点跑一次
 * 统计前一天的数据
 * @date 2016-05-23
 * @autor alanwang
 */

class Statistics extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的数量
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'Statistics';

    public function __construct()
    {
        parent::__construct();
    }


    public function run()
    {
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        set_time_limit(0);

        $this->load->model('flux_user_model');
        $this->load->model('statistics_user_model');
        $this->load->model('statistics_order_model');
        $this->load->model('active_merage_order_model');
        $this->load->model('active_order_model');
        $this->load->model('coupon_order_model');
        $this->load->model('bag_order_model');
        $this->load->model('user_model');


        $toDayTime =  strtotime('today');   //当天凌晨时间
        $yesterDayTime =  $toDayTime - 60*60*24;   //前一天凌晨时间
        $createTime = $yesterDayTime + 60*60;
        $toDayTimeSort = date('Y-m-d',$toDayTime);
        $yesterDayTimeSort = date('Y-m-d',$yesterDayTime);

        $activeUser = 0;    //活跃用户数量

        $where = ' WHERE iCreateTime < '.$toDayTime .' AND iCreateTime > '.$yesterDayTime;

        $payOrderCount = 0;    //已支付订单数
        $notPayOrderCount = 0;  //未支付订单数
        $useCoupon = 0; //已使用券
        $payMoney = 0;    //已支付现金金额
        $notPayMoney = 0; //未支付现金金额
        $payArray = array();    //已支付人数数组
        $notPayArray = array(); //未支付人数数组

        $this->log("====================================查询大订单并且循环=============================================");
        //先查询是否已经有这天的统计
        $check_active_order_where = ' AND iType = 2 ';
        $check_active_order_sql = 'SELECT iType,iCreateTime FROM t_order_detail '.$where.$check_active_order_where.'  ;';
        $check_active_order_result_list = $this->statistics_order_model->query($check_active_order_sql, true);

        if(!empty($check_active_order_result_list)) //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where = ' WHERE iType = 2 AND iCreateTime = '.$check_active_order_result_list[0]['iCreateTime'];
            $delete_active_order_sql = 'DELETE FROM t_order_detail '.$delete_active_order_where.' ;';
            $this->statistics_order_model->query($delete_active_order_sql, true);
        }

        for($db_index = 0; $db_index < 10; $db_index ++)
        {
            $db_name = ' t_active_merage_order'.$db_index;
            $active_merage_order_sql = 'SELECT sMergeOrderId,iUin,iTotalPrice,iCoupon,iAmount,iPayAgentType,iPayTime,iPayStatus,sTransId,iStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iSrc,iPlatformId,iCreateTime,iLastModTime FROM '.$db_name.$where.'  ;';
            $active_merage_order_result_list = $this->active_merage_order_model->query($active_merage_order_sql, true);
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

        $this->log("====================================查询小订单并且循环=============================================");
        for($order_index = 0; $order_index < 10; $order_index ++)
        {
            $order_db_name = ' t_active_order'.$order_index;
            $active_order_sql = 'SELECT sMergeOrderId,iUin,iGoodsId,iActId,iPeroid,iActType,iBuyType,iCount,iUnitPrice,iTotalPrice,iAmount,iPayAgentType,iPayTime,sTransId,iPayStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iLuckyCodeNum,iLuckyCodeState,iCreateTime,iLastModTime FROM '.$order_db_name.$where.'  ;';
            $active_order_result_list = $this->active_order_model->query($active_order_sql, true);
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
                'iCreateTime'   =>  $createTime
            );
            if ($this->statistics_order_model->add_row($data))
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


        $this->log("====================================充值流水明细=============================================");

        $couponPayCount = array();  //已支付人数
        $couponOrderCount = 0;  //已支付订单数
        $couponPayMoney = 0;    //已支付现金金额
        $couponUseCoupon = 0;   //已使用券
        $couponNotPayCount = array();   //未支付人数
        $couponNotOrderCount = 0;   //未支付订单数
        $counponNotPayMoney = 0;    //未支付金额

        $check_active_order_where_type_one = ' AND iType = 1 ';
        $check_active_order_sql_type_one = 'SELECT iType,iCreateTime FROM t_order_detail '.$where.$check_active_order_where_type_one.'  ;';
        $check_active_order_result_list_type_one = $this->statistics_order_model->query($check_active_order_sql_type_one, true);

        if(!empty($check_active_order_result_list_type_one))    //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where_type_one = ' WHERE iType = 1 AND iCreateTime = '.$check_active_order_result_list_type_one[0]['iCreateTime'];
            $delete_active_order_sql_type_one = 'DELETE FROM t_order_detail '.$delete_active_order_where_type_one.' ;';
            $this->statistics_order_model->query($delete_active_order_sql_type_one, true);
        }

        for($coupon_index = 0; $coupon_index < 10; $coupon_index ++)
        {
            $coupon_db_name = ' t_coupon_order'.$coupon_index;
            $coupon_order_sql = 'SELECT sOrderId,iUin,iCount,iPresentCount,iUnitPrice,iTotalPrice,iPayAgentType,iPayTime,iStatus,sTransId,iPayStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iSrc,iPlatformId,iCreateTime,iLastModTime FROM '.$coupon_db_name.$where.'  ;';
            $coupon_order_result_list = $this->coupon_order_model->query($coupon_order_sql, true);
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
                'iCreateTime'   =>  $createTime
            );
            if ($this->statistics_order_model->add_row($data))
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

        $this->log("====================================福袋流水明细=============================================");
        $luckyBagPayCount = array();  //已支付人数
        $luckyBagOrderCount = 0;  //已支付订单数
        $luckyBagPayMoney = 0;    //已支付现金金额
        $luckyBagUseCoupon = 0;   //已使用券
        $luckyBagNotPayCount = array();   //未支付人数
        $luckyBagNotOrderCount = 0;   //未支付订单数
        $luckyBagNotPayMoney = 0;    //未支付金额

        $check_active_order_where_type_four = ' AND iType = 4 ';
        $check_active_order_sql_type_four = 'SELECT iType,iCreateTime FROM t_order_detail '.$where.$check_active_order_where_type_four.'  ;';
        $check_active_order_result_list_type_four = $this->statistics_order_model->query($check_active_order_sql_type_four, true);

        if(!empty($check_active_order_result_list_type_four))   //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where_type_four = ' WHERE iType = 4 AND iCreateTime = '.$check_active_order_result_list_type_four[0]['iCreateTime'];
            $delete_active_order_sql_type_four = 'DELETE FROM t_order_detail '.$delete_active_order_where_type_four.' ;';
            $this->statistics_order_model->query($delete_active_order_sql_type_four, true);
        }

        for($bag_index = 0; $bag_index < 10; $bag_index ++)
        {
            $bag_db_name = ' t_bag_order'.$bag_index;
            $bag_sql = 'SELECT sOrderId,iBagId,iUin,iPayAmount,iPayCoupon,iTotalAmount,iCreateTime,iPayTime,iStatus FROM '.$bag_db_name.$where.'  ;';
            $bag_result_list = $this->bag_order_model->query($bag_sql, true);
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
                    $activeUser++;
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
                'iCreateTime'   =>  $createTime
            );
            if ($this->statistics_order_model->add_row($data))
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

        $this->log("====================================兑换流水明细=============================================");
        $exchangePayCount = array();  //已支付人数
        $exchangeOrderCount = array();  //已支付订单数
        $exchangePayMoney = 0;    //已支付现金金额
        $exchangeUseCoupon = 0;   //已使用券
        $exchangeNotPayCount = array();   //未支付人数
        $exchangeNotOrderCount = array();   //未支付订单数
        $exchangeNotPayMoney = 0;    //未支付金额

        $check_active_order_where_type_three = ' AND iType = 3 ';
        $check_active_order_sql_type_three = 'SELECT iType,iCreateTime FROM t_order_detail '.$where.$check_active_order_where_type_three.'  ;';
        $check_active_order_result_list_type_three = $this->statistics_order_model->query($check_active_order_sql_type_three, true);

        if(!empty($check_active_order_result_list_type_three))  //如果已经有昨天的数据,进行删除
        {
            $delete_active_order_where_type_three = ' WHERE iType = 3 AND iCreateTime = '.$check_active_order_result_list_type_three[0]['iCreateTime'];
            $delete_active_order_sql_type_three = 'DELETE FROM t_order_detail '.$delete_active_order_where_type_three.' ;';
            $this->statistics_order_model->query($delete_active_order_sql_type_three, true);
        }

        for($exchange_index = 0; $exchange_index < 10; $exchange_index ++)
        {
            $exchange_db_name = ' t_active_order'.$exchange_index;
            $exchange_order_sql = 'SELECT sMergeOrderId,iUin,iGoodsId,iActId,iPeroid,iActType,iBuyType,iCount,iUnitPrice,iTotalPrice,iAmount,iPayAgentType,iPayTime,sTransId,iPayStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iLuckyCodeNum,iLuckyCodeState,iCreateTime,iLastModTime FROM '.$exchange_db_name.$where.'  ;';
            $exchange_order_result_list = $this->active_order_model->query($exchange_order_sql, true);
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

        for($exchange_merage_index = 0; $exchange_merage_index < 10; $exchange_merage_index ++)
        {
            $exchange_merage_db_name = ' t_active_merage_order'.$exchange_merage_index;
            $exchange_merage_order_sql = 'SELECT sMergeOrderId,iUin,iTotalPrice,iCoupon,iAmount,iPayAgentType,iPayTime,iPayStatus,sTransId,iStatus,iRefundedCoupon,iRefundingCoupon,iRefundedAmount,iRefundingAmount,iRefundStatus,iSrc,iPlatformId,iCreateTime,iLastModTime FROM '.$exchange_merage_db_name.$where.'  ;';
            $exchange_merage_order_result_list = $this->active_merage_order_model->query($exchange_merage_order_sql, true);
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
                'iCreateTime'   =>  $createTime
            );
            if ($this->statistics_order_model->add_row($data))
            {
                $exchangeCount = 4;
                $this->log("====================================添加兑换数据成功=============================================");
            }
            else
            {
                $exchangeCount++;
                $this->log("====================================第".$luckyBagCount."次添加兑换数据失败=============================================");
            }
        }while($exchangeCount < 3);

        $this->log("====================================用户活跃,累积,新增数量=============================================");

        $check_user_sql = 'SELECT iActivityUser,iCreateTime FROM t_statistics_user '.$where.'  ;';
        $check_user_result_list = $this->statistics_user_model->query($check_user_sql, true);

        if(!empty($check_user_result_list)) //如果已经有昨天的数据,进行删除
        {
            $delete_user_where = ' WHERE iCreateTime = '.$check_user_result_list[0]['iCreateTime'];
            $delete_user_sql = 'DELETE FROM t_statistics_user '.$delete_user_where.' ;';
            $this->statistics_user_model->query($delete_user_sql, true);
        }

        $userCount = 0; //总用户数量
        for($user_index = 0; $user_index < 10; $user_index ++)
        {
            $user_db_name = ' t_user'.$user_index;
            $user_sql = 'SELECT count(iUin) cTotal FROM '.$user_db_name.' WHERE iLoginTime > 1461081600  ;';
            $user_result_list = $this->user_model->query($user_sql, true);
            $userCount = empty($user_result_list)?$userCount:$userCount+$user_result_list[0]['cTotal'];
        }

        $userCreateCount = 0; //新关注用户数量
        for($user_create_index = 0; $user_create_index < 10; $user_create_index ++)
        {
            $user_create_db_name = ' t_user'.$user_create_index;
            $user_create_where = ' WHERE iRegTime < '.$toDayTime .' AND iRegTime > '.$yesterDayTime;
            $user_create_sql = 'SELECT count(iUin) cTotal FROM '.$user_create_db_name.$user_create_where.'   ;';
            $user_create_result_list = $this->user_model->query($user_create_sql, true);
            $userCreateCount = empty($user_result_list)?$userCreateCount:$userCreateCount+$user_create_result_list[0]['cTotal'];
        }

        $insertUserCount = 0;
        do
        {
            $data = array(
                'iActivityUser' => $activeUser,
                'iNewUser' => $userCreateCount,
                'iAccumulationUser' => $userCount,
                'iCancelUser' => 0,
                'iCreateTime'   =>  $createTime
            );
            if ($this->statistics_user_model->add_row($data))
            {
                $insertUserCount = 4;
                $this->log("====================================添加用户表成功=============================================");
            }
            else
            {
                $this->log("====================================第".$insertUserCount."次添加用户表失败=============================================");
                $insertUserCount++;
            }
        }while($insertUserCount < 3);


        $PV = 0;
        $UV = 0;
        $IPTotal = 0;
        $token = '';
        $appid = '100003';  //TA系统提供
        $appsecret = '3dc81e3f2c523fb5955761bbe2d150f2';    //TA系统提供
        $site_id = '683';   //TA系统提供
        $user_id = '3'; //TA系统提供
        $token_url = 'http://ta.nexto2o.com/ta-token/get?appid='.$appid.'&appsecret='.$appsecret;
        //$this->log("====================================用户活跃,累积,新增数量=============================================");

        $check_flux_user_sql = 'SELECT iUserPV,iCreateTime FROM t_flux_user '.$where.'  ;';
        $check_flux_user_result_list = $this->flux_user_model->query($check_flux_user_sql, true);

        if(!empty($check_flux_user_result_list))    //如果已经有昨天的数据,进行删除
        {
            $delete_flux_user_where = ' WHERE iCreateTime = '.$check_flux_user_result_list[0]['iCreateTime'];
            $delete_flux_user_sql = 'DELETE FROM t_flux_user '.$delete_flux_user_where.' ;';
            $this->flux_user_model->query($delete_flux_user_sql, true);
        }

        //获取TOKEN
        $token_ch = curl_init();
        if ($token_ch === false)
        {
            $this->log("====================================初始化curl_init()失败=============================================");
        }
        else
        {
            curl_setopt($token_ch, CURLOPT_URL, $token_url);
            curl_setopt($token_ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($token_ch, CURLOPT_CONNECTTIMEOUT, 30);
            $token_res = curl_exec($token_ch);
            if ($token_res === false)
            {
                $this->log("====================================获取API失败=============================================");
            }
            else
            {
                $token_array = json_decode($token_res,true);
                $token = $token_array['access_token'];
            }
        }
        curl_close($token_ch);

        //获取TA系统中的PV和UV
        //$key = md5($_SERVER['SCRIPT_NAME']);
        $statistics_ch = curl_init();
        $statistics_url = 'http://ta.nexto2o.com/getdata/url?starttime='.$yesterDayTimeSort.'&endtime='.$toDayTimeSort.'&site_id='.$site_id.'&url=all&token='.$token.'&user_id='.$user_id;

        curl_setopt($statistics_ch, CURLOPT_URL, $statistics_url);
        curl_setopt($statistics_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($statistics_ch, CURLOPT_CONNECTTIMEOUT, 30);
        $statistics_result = curl_exec($statistics_ch);
        $statistics_api_result_list = json_decode($statistics_result,true);
        if($statistics_api_result_list['errmsg'] == 'ok')
        {
            $statistics_api_list = $statistics_api_result_list['data'];
            if(!empty($statistics_api_list))
            {
                $PV = $statistics_api_list[0]['pv'];
                $UV = $statistics_api_list[0]['uv'];
                $IPTotal = $statistics_api_list[0]['ip'];
            }
        }
        curl_close($statistics_ch);

        $insertFluxUserCount = 0;
        do
        {
            $data = array(
                'iUserPV' => $PV,
                'iUserUV' => $UV,
                'iIpTotal'  =>  $IPTotal,
                'iCreateTime'   =>  $createTime
            );
            if ($this->flux_user_model->add_row($data))
            {
                $insertFluxUserCount = 4;
                $this->log("====================================添加用户浏览表成功=============================================");
            }
            else
            {
                $this->log("====================================第".$insertFluxUserCount."次添加用户浏览表失败=============================================");
                $insertFluxUserCount++;
            }
        }while($insertFluxUserCount < 3);

        sleep(10);
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
        //}
    }


}