<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Statistics extends Admin_Base
{

    /**
     * 构造函数
     *
     * Goods constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 数据统计中-流量统计
     */
    public function index()
    {

        date_default_timezone_set('PRC');
        $this->predefine_asset('validate');
        $this->predefine_asset('datetimepicker');
        $toDayTime =  strtotime('today');   //当天凌晨时间
        $sevenDayTime =  $toDayTime - 60*60*24*7;   //7天前凌晨时间
        $statistics_list = array();

        $this->load->model('flux_user_model');
        $this->load->model('statistics_user_model');
        $this->load->model('statistics_order_model');

        $beginTime = trim($this->get('beginTime', ''));
        $endTime = trim($this->get('endTime', ''));

        $where = ' WHERE 1=1 ';

        if(!empty($beginTime) && !empty($endTime))  //如果有选择时间,则按照时间来查询
        {
            $begin_time_date = date('Y-m-d',strtotime($beginTime)).' 00:00:00';
            $beginTime = strtotime($begin_time_date);
            $end_time_date = date('Y-m-d',strtotime($endTime)).' 23:59:59';
            $endTime = strtotime($end_time_date);
            $where .= ' AND iCreateTime < '.$endTime .' AND iCreateTime > '.$beginTime;
        }
        else    //如果没有选择时间,则查询一周的时间
        {
            $where .= ' AND iCreateTime < '.$toDayTime .' AND iCreateTime > '.$sevenDayTime;
        }

        /** 查询浏览表数据 */

        $flux_user_sql = 'SELECT iUserPV,iUserUV,iCreateTime FROM `t_flux_user` '.$where.' ORDER BY iCreateTime DESC;';
        $flux_user_result_list = $this->flux_user_model->query($flux_user_sql, true);

        $iUserPVCount = 0;
        $iUserUVCount = 0;
        foreach($flux_user_result_list as $v)
        {
            $iUserPVCount = $iUserPVCount+$v['iUserPV'];
            $iUserUVCount = $iUserUVCount+$v['iUserUV'];
        }

        /** 查询用户表数据 */
        $statistics_user_sql = 'SELECT iActivityUser,iNewUser,iAccumulationUser,iCancelUser,iCreateTime FROM `t_statistics_user` '.$where.' ORDER BY iCreateTime DESC;';
        $statistics_user_result_list = $this->statistics_user_model->query($statistics_user_sql, true);

        $iActivityUserCount = 0;
        $iNewUserCount = 0;
        $iAccumulationUserCount = 0;
        $iCancelUserCount = 0;
        foreach($statistics_user_result_list as $statistics_user)
        {
            $iActivityUserCount = $iActivityUserCount+$statistics_user['iActivityUser'];
            $iNewUserCount = $iNewUserCount+$statistics_user['iNewUser'];
            //$iAccumulationUserCount = $iAccumulationUserCount+$statistics_user['iAccumulationUser'];
            $iCancelUserCount = $iCancelUserCount+$statistics_user['iCancelUser'];
            if($statistics_user['iAccumulationUser'] > $iAccumulationUserCount)
            {
                $iAccumulationUserCount = $statistics_user['iAccumulationUser'];
            }
        }

        /** 查询订单流水明细表数据 */
        $statistics_order_sql = 'SELECT iType,iPayUserCount,iPayOrderCount,iPayMoney,iUseCoupon,iOrderARPU,iNotPayUserCount,iNotPayOrderCount,iNotPayMoney,iRefundMoney,iRefundCoupon,iMarchMoney,iMarchCoupon,iCreateTime FROM `t_order_detail` '.$where.' ORDER BY iCreateTime DESC;';
        $statistics_order_result_list = $this->statistics_order_model->query($statistics_order_sql, true);

        $money = 0;
        $couponCount = 0;
        $orderCount = 0;
        $orderMoney = 0;

        foreach($statistics_order_result_list as $statistics_order)
        {
            $money = $money+$statistics_order['iPayMoney'];
            $couponCount = $couponCount+$statistics_order['iUseCoupon']-$statistics_order['iRefundCoupon']-$statistics_order['iMarchCoupon'];
            $orderCount = $orderCount+$statistics_order['iPayOrderCount']+$statistics_order['iNotPayOrderCount'];
            $orderMoney = $orderMoney + ($statistics_order['iPayMoney']/100) + $statistics_order['iUseCoupon'] - ($statistics_order['iRefundMoney']/100) - $statistics_order['iRefundCoupon'];
        }

        $statistics_list['iUserPV'] = $iUserPVCount;    //浏览量(PV)
        $statistics_list['iUserUV'] = $iUserUVCount;    //用户量(UV)
        $statistics_list['money'] = $money/100;    //现金流水
        $statistics_list['couponCount'] = $couponCount;    //用券数量
        $statistics_list['orderCount'] = $orderCount;    //订单数量
        $statistics_list['orderMoney'] = $orderMoney;    //订单金额
        $statistics_list['iActivityUser'] = $iActivityUserCount;    //活跃用户
        $statistics_list['iNewUser'] = $iNewUserCount;    //新增关注用户
        $statistics_list['iAccumulationUser'] = $iAccumulationUserCount;    //累积关注用户
        $statistics_list['iCancelUser'] = $iCancelUserCount;    //取消关注用户
        $viewData = array(
            'statistics_list' => $statistics_list,
            'beginTime' => $beginTime,
            'endTime' => $endTime
        );

        $this->render($viewData);
    }

    /**
     * 数据统计中心-流水明细
     */
    public function detail()
    {
        date_default_timezone_set('PRC');
        $this->predefine_asset('validate');
        $this->predefine_asset('datetimepicker');

        $toDayTime =  strtotime('today');   //当天凌晨时间
        $sevenDayTime =  $toDayTime - 60*60*24*7;   //7天前凌晨时间
        $statistics_order_list = array();


        $iPayUserCount = 0;
        $iPayOrderCount = 0;
        $iPayMoney = 0;
        $iUseCoupon = 0;
        $iOrderARPU = 0;
        $iNotPayUserCount = 0;
        $iNotPayOrderCount = 0;
        $iNotPayMoney = 0;
        $where = ' WHERE 1=1 ';

        $this->load->model('flux_user_model');
        $this->load->model('statistics_user_model');
        $this->load->model('statistics_order_model');

        /** 判断用户是否选择了时间区间 */
        $beginTime = trim($this->get('beginTime', ''));
        $endTime = trim($this->get('endTime', ''));

        if(!empty($beginTime) && !empty($endTime))  //如果有选择时间,则按照时间来查询
        {
            $begin_time_date = date('Y-m-d',strtotime($beginTime)).' 00:00:00';
            $beginTime = strtotime($begin_time_date);
            $end_time_date = date('Y-m-d',strtotime($endTime)).' 23:59:59';
            $endTime = strtotime($end_time_date);
            $where .= ' AND iCreateTime < '.$endTime .' AND iCreateTime > '.$beginTime;
        }
        else    //如果没有选择时间,则查询一周的时间
        {
            $where .= ' AND iCreateTime < '.$toDayTime .' AND iCreateTime > '.$sevenDayTime;
        }


        /** 查询订单流水明细表数据 */
        $statistics_order_sql = 'SELECT iType,iPayUserCount,iPayOrderCount,iPayMoney,iUseCoupon,iOrderARPU,iNotPayUserCount,iNotPayOrderCount,iNotPayMoney,iRefundMoney,iRefundCoupon,iMarchMoney,iMarchCoupon FROM `t_order_detail` '.$where.' ORDER BY iCreateTime DESC;';
        $statistics_order_result_list = $this->statistics_order_model->query($statistics_order_sql, true);

        /** 开始循环得到的数据,并且进行整合 */
        foreach($statistics_order_result_list as $statistics_order)
        {
            if($statistics_order['iType'] == 1)
            {
                $statistics_order_list[$statistics_order['iType']]['type'] = '充值';
            }
            else if($statistics_order['iType'] == 2)
            {
                $statistics_order_list[$statistics_order['iType']]['type'] = '夺宝';
            }
            else if($statistics_order['iType'] == 3)
            {
                $statistics_order_list[$statistics_order['iType']]['type'] = '兑换';
            }
            else if($statistics_order['iType'] == 4)
            {
                $statistics_order_list[$statistics_order['iType']]['type'] = '福袋';
            }
            $statistics_order_list[$statistics_order['iType']]['iPayUserCount'] = empty($statistics_order_list[$statistics_order['iType']]['iPayUserCount'])?$statistics_order['iPayUserCount']:$statistics_order_list[$statistics_order['iType']]['iPayUserCount'] + $statistics_order['iPayUserCount'];
            $statistics_order_list[$statistics_order['iType']]['iPayOrderCount'] = empty($statistics_order_list[$statistics_order['iType']]['iPayOrderCount'])?$statistics_order['iPayOrderCount']:$statistics_order_list[$statistics_order['iType']]['iPayOrderCount'] + $statistics_order['iPayOrderCount'];
            $statistics_order_list[$statistics_order['iType']]['iPayMoney'] = empty($statistics_order_list[$statistics_order['iType']]['iPayMoney'])?$statistics_order['iPayMoney']/100:$statistics_order_list[$statistics_order['iType']]['iPayMoney'] + ($statistics_order['iPayMoney']/100);
            $statistics_order_list[$statistics_order['iType']]['iUseCoupon'] = empty($statistics_order_list[$statistics_order['iType']]['iUseCoupon'])?$statistics_order['iUseCoupon']:$statistics_order_list[$statistics_order['iType']]['iUseCoupon'] + $statistics_order['iUseCoupon'];
            $statistics_order_list[$statistics_order['iType']]['iOrderARPU'] = empty($statistics_order_list[$statistics_order['iType']]['iOrderARPU'])?$statistics_order['iOrderARPU']:$statistics_order_list[$statistics_order['iType']]['iOrderARPU'] + $statistics_order['iOrderARPU'];
            $statistics_order_list[$statistics_order['iType']]['iNotPayUserCount'] = empty($statistics_order_list[$statistics_order['iType']]['iNotPayUserCount'])?$statistics_order['iNotPayUserCount']:$statistics_order_list[$statistics_order['iType']]['iNotPayUserCount'] + $statistics_order['iNotPayUserCount'];
            $statistics_order_list[$statistics_order['iType']]['iNotPayOrderCount'] = empty($statistics_order_list[$statistics_order['iType']]['iNotPayOrderCount'])?$statistics_order['iNotPayOrderCount']:$statistics_order_list[$statistics_order['iType']]['iNotPayOrderCount'] + $statistics_order['iNotPayOrderCount'];
            $statistics_order_list[$statistics_order['iType']]['iNotPayMoney'] = empty($statistics_order_list[$statistics_order['iType']]['iNotPayMoney'])?$statistics_order['iNotPayMoney']/100:$statistics_order_list[$statistics_order['iType']]['iNotPayMoney'] + ($statistics_order['iNotPayMoney']/100);

            //为了显示总计的全部数据,将每行的数据进行相加
            $iPayUserCount = $iPayUserCount + $statistics_order['iPayUserCount'];
            $iPayOrderCount = $iPayOrderCount + $statistics_order['iPayOrderCount'];
            $iPayMoney = $iPayMoney + ($statistics_order['iPayMoney']/100);
            $iUseCoupon = $iUseCoupon + $statistics_order['iUseCoupon'];
            $iOrderARPU = $iOrderARPU + $statistics_order['iOrderARPU'];
            $iNotPayUserCount = $iNotPayUserCount + $statistics_order['iNotPayUserCount'];
            $iNotPayOrderCount = $iNotPayOrderCount + $statistics_order['iNotPayOrderCount'];
            $iNotPayMoney = $iNotPayMoney + ($statistics_order['iNotPayMoney']/100);

        }
        //为了显示总计的全部数据
        $result_count = count($statistics_order_list)+1;
        $statistics_order_list[$result_count]['type'] = '总计';
        $statistics_order_list[$result_count]['iPayUserCount'] = $iPayUserCount;
        $statistics_order_list[$result_count]['iPayOrderCount'] = $iPayOrderCount;
        $statistics_order_list[$result_count]['iPayMoney'] = $iPayMoney;
        $statistics_order_list[$result_count]['iUseCoupon'] = $iUseCoupon;
        $statistics_order_list[$result_count]['iOrderARPU'] = $iOrderARPU;
        $statistics_order_list[$result_count]['iNotPayUserCount'] = $iNotPayUserCount;
        $statistics_order_list[$result_count]['iNotPayOrderCount'] = $iNotPayOrderCount;
        $statistics_order_list[$result_count]['iNotPayMoney'] = $iNotPayMoney;

        //变量赋值
        $viewData = array(
            'statistics_order_list' => $statistics_order_list,
            'beginTime' => $beginTime,
            'endTime' => $endTime
        );
        //视图渲染
        $this->render($viewData);
    }

    /**
     * 根据参数查询数据并且整合返回给PHPExcel直接使用
     * @param $type array
     * @param $beginTime int
     * @param $endTime int
     * @return array('title','date','file_title')
     */
    private function load_excel_data($type,$beginTime,$endTime)
    {
        date_default_timezone_set('PRC');
        $type = trim($type);
        $toDayTime =  strtotime('today');   //当天凌晨时间
        $sevenDayTime =  $toDayTime - 60*60*24*7;   //7天前凌晨时间
        $where = ' WHERE 1=1 ';

        if(trim($type) == '' || empty($type))
        {
            return array();
        }


        if(!empty($beginTime) && !empty($endTime))  //如果有选择时间,则按照时间来查询
        {
            $begin_time_date = date('Y-m-d',strtotime($beginTime)).' 00:00:00';
            $beginTime = strtotime($begin_time_date);
            $end_time_date = date('Y-m-d',strtotime($endTime)).' 23:59:59';
            $endTime = strtotime($end_time_date);
            $where .= ' AND iCreateTime < '.$endTime .' AND iCreateTime > '.$beginTime;
        }
        else    //如果没有选择时间,则查询一周的时间
        {
            $where .= ' AND iCreateTime < '.$toDayTime .' AND iCreateTime > '.$sevenDayTime;
        }

        $this->load->model('flux_user_model');
        $this->load->model('statistics_user_model');
        $this->load->model('statistics_order_model');

        if($type == 1)
        {
            $this->log->error('statistics_excel', 'load_excel_data of type 1');
            $title = Lib_Constants::$statistics_excel_sheet_title;

            $statistics_list = array();
            /** 查询浏览表数据 */

            $flux_user_sql = 'SELECT iUserPV,iUserUV,iCreateTime FROM `t_flux_user` '.$where.' ORDER BY iCreateTime ASC;';
            $flux_user_result_list = $this->flux_user_model->query($flux_user_sql, true);
            $this->log->error('statistics_excel', 'load_excel_data search flux_user');
            /** 查询订单流水明细表数据 */
            $statistics_order_sql = 'SELECT iType,iPayUserCount,iPayOrderCount,iPayMoney,iUseCoupon,iOrderARPU,iNotPayUserCount,iNotPayOrderCount,iNotPayMoney,iRefundMoney,iRefundCoupon,iMarchMoney,iMarchCoupon,iCreateTime FROM `t_order_detail` '.$where.' ORDER BY iCreateTime ASC;';
            $statistics_order_result_list = $this->statistics_order_model->query($statistics_order_sql, true);
            $this->log->error('statistics_excel', 'load_excel_data search order_detail');
            /** 查询用户表数据 */
            $statistics_user_sql = 'SELECT iActivityUser,iNewUser,iAccumulationUser,iCancelUser,iCreateTime FROM `t_statistics_user` '.$where.' ORDER BY iCreateTime ASC;';
            $statistics_user_result_list = $this->statistics_user_model->query($statistics_user_sql, true);
            $this->log->error('statistics_excel', 'load_excel_data search statistics_user');
            $count = count($flux_user_result_list);
            for($i=0;$i<$count;$i++)
            {
                foreach($flux_user_result_list as $k => $flux_user)
                {
                    $flux_user_key = 0;
                    $statistics_list[$k][$flux_user_key] = date("Y-m-d", $flux_user['iCreateTime']);
                    $statistics_list[$k][$flux_user_key+1] = $flux_user['iUserPV'];
                    $statistics_list[$k][$flux_user_key+2] = $flux_user['iUserUV'];

                    $money = 0;
                    $couponCount = 0;
                    $orderCount = 0;
                    $orderMoney = 0;
                    foreach($statistics_order_result_list as $statistics_order)
                    {
                        if(date("Y-m-d", $flux_user['iCreateTime']) == date("Y-m-d", $statistics_order['iCreateTime']))
                        {
                            $money = $money + ($statistics_order['iPayMoney']/100);
                            $couponCount = $couponCount + $statistics_order['iUseCoupon'] - $statistics_order['iRefundCoupon'] - $statistics_order['iMarchCoupon'];
                            $orderCount = $orderCount + $statistics_order['iPayOrderCount'] + $statistics_order['iNotPayOrderCount'];
                            $orderMoney = $orderMoney + ($statistics_order['iPayMoney']/100) + $statistics_order['iUseCoupon'] - ($statistics_order['iRefundMoney']/100) - $statistics_order['iRefundCoupon'];
                        }
                    }

                    $statistics_order_key = 3;
                    $statistics_list[$k][$statistics_order_key] = $money;
                    $statistics_list[$k][$statistics_order_key+1] = $couponCount;
                    $statistics_list[$k][$statistics_order_key+2] = $orderCount;
                    $statistics_list[$k][$statistics_order_key+3] = $orderMoney;

                    foreach($statistics_user_result_list as  $statistics_user)
                    {
                        if(date("Y-m-d", $flux_user['iCreateTime']) == date("Y-m-d", $statistics_user['iCreateTime']))
                        {
                            $statistics_user_key = 7;
                            $statistics_list[$k][$statistics_user_key] = $statistics_user['iActivityUser'];
                            $statistics_list[$k][$statistics_user_key+1] = $statistics_user['iNewUser'];
                            $statistics_list[$k][$statistics_user_key+2] = $statistics_user['iAccumulationUser'];
                        }

                    }
                }
            }
            $this->log->error('statistics_excel', 'load_excel_data ready return');
            return array('title'=>$title,'date'=>$statistics_list,'file_title'=>'流量统计');
        }
        else if($type == 2)
        {
            $this->log->error('statistics_excel', 'load_excel_data of type 2');
            $title = Lib_Constants::$statistics_excel_sheet_detail_title;

            /** 查询订单流水明细表数据 */
            $statistics_order_sql = 'SELECT iType,iPayUserCount,iPayOrderCount,iPayMoney,iUseCoupon,iOrderARPU,iNotPayUserCount,iNotPayOrderCount,iNotPayMoney,iRefundMoney,iRefundCoupon,iMarchMoney,iMarchCoupon,iCreateTime FROM `t_order_detail` '.$where.' ORDER BY iCreateTime ASC;';
            $statistics_order_result_list = $this->statistics_order_model->query($statistics_order_sql, true);

            $statistics_order_list = array();

            foreach($statistics_order_result_list as $k => $statistics_order)
            {
                $statistics_order_list[$k][0] = date("Y-m-d", $statistics_order['iCreateTime']);
                if($statistics_order['iType'] == 1)
                {
                    $statistics_order_list[$k][1] = '充值';
                }
                else if($statistics_order['iType'] == 2)
                {
                    $statistics_order_list[$k][1] = '夺宝';
                }
                else if($statistics_order['iType'] == 3)
                {
                    $statistics_order_list[$k][1] = '兑换';
                }
                else if($statistics_order['iType'] == 4)
                {
                    $statistics_order_list[$k][1] = '福袋';
                }

                $statistics_order_list[$k][2] = $statistics_order['iPayUserCount'];
                $statistics_order_list[$k][3] = $statistics_order['iPayOrderCount'];
                $statistics_order_list[$k][4] = $statistics_order['iPayMoney']/100;
                $statistics_order_list[$k][5] = $statistics_order['iUseCoupon'];
                $statistics_order_list[$k][6] = $statistics_order['iOrderARPU'];
                $statistics_order_list[$k][7] = $statistics_order['iNotPayUserCount'];
                $statistics_order_list[$k][8] = $statistics_order['iNotPayOrderCount'];
                $statistics_order_list[$k][9] = $statistics_order['iNotPayMoney']/100;


            }

            return array('title'=>$title,'date'=>$statistics_order_list,'file_title'=>'流水明细');
        }
        else
        {
            $title = array();
            return array();
        }

        return array();
    }

    /**
     * 下载excel
     *
     * @param null $template_id
     */
    public function excel()
    {
        date_default_timezone_set('PRC');
        $type = intval($this->get('type', 0));
        $beginTime = trim($this->get('beginTime', ''));
        $endTime = trim($this->get('endTime', ''));
        $this->log->error('statistics_excel', 'return data begin');

        $result_list = Statistics::load_excel_data($type,$beginTime,$endTime);

        $this->log->error('statistics_excel', 'return data end');

        if(empty($result_list))
        {
            $this->log->error('statistics_excel', 'result is null');
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $this->log->error('statistics_excel', 'result is not null');
        $excel = new Lib_Excel($result_list['title'] , $result_list['date']);
        $this->log->error('statistics_excel', 'new Lib_Excel' . json_encode($result_list));
        $excel->download('百分好礼-' .$result_list['file_title'].date('Y-m-d',time()).'.xlsx');
        $this->log->error('statistics_excel', ' Lib_Excel download');
    }

    /**
     * 数据统计中心-实时数据
     */
    public function real_time_detail()
    {
        date_default_timezone_set('PRC');   //中国时间区
        $this->load->model('real_time_order_detail');

        $toDayTime =  strtotime('today');   //当天凌晨时间
        $now_time = time(); //当前时间戳
        $now_min_time = date('H',$now_time);    //得到当前时间的分钟数
        if($now_min_time == 00) //如果当前时间是凌晨00点
        {
            $diff_time = $now_time - $toDayTime;
            if($diff_time <= 1800)
            {
                $now_time = $toDayTime;
                $toDayTime = $toDayTime - 60*60*24;
            }
        }

        $where = ' WHERE 1 = 1 ';
        $indiana_active_where = ' AND iCreateTime > '.$toDayTime.' AND iCreateTime < '.$now_time.' ';
        $indiana_active_sql = 'SELECT iType,iPayUserCount,iPayOrderCount,iPayMoney,iUseCoupon,iOrderARPU,iNotPayUserCount,iNotPayOrderCount,iNotPayMoney,iRefundMoney,iRefundCoupon,iMarchMoney,iMarchCoupon,iActivityUser,iNewUser,iCreateTime  FROM t_real_time_order_detail '.$where.$indiana_active_where.'  ;';
        $indiana_active_result_list = $this->real_time_order_detail->query($indiana_active_sql, true);

        $indiana_list = array();    //夺宝
        $coupon_list = array(); //充值
        $exchange_list = array();   //兑换
        $lucky_bag_list = array();  //福袋
        $total_list = array();  //总计

        $indiana_detail_list = array(); //夺宝详细
        $coupon_detail_list = array();  //充值详细
        $exchange_detail_list = array();    //兑换详细
        $lucky_bag_detail_list = array();   //福袋详细
        if(!empty($indiana_active_result_list))
        {
            foreach($indiana_active_result_list as $indiana)
            {
                if($indiana['iType'] == 1)
                {
                    $coupon_list['type'] = '充值';
                    $coupon_list['pay_count']  =   empty($coupon_list['pay_count'])?$indiana['iPayUserCount']:$coupon_list['pay_count'] + $indiana['iPayUserCount'];
                    $coupon_list['pay_order']  =   empty($coupon_list['pay_order'])?$indiana['iPayOrderCount']:$coupon_list['pay_order'] + $indiana['iPayOrderCount'];
                    $coupon_list['pay_money']  =   empty($coupon_list['pay_money'])?$indiana['iPayMoney']/100:$coupon_list['pay_money'] + $indiana['iPayMoney']/100;
                    $coupon_list['use_coupon']  =   empty($coupon_list['use_coupon'])?$indiana['iUseCoupon']:$coupon_list['use_coupon'] + $indiana['iUseCoupon'];
                    $coupon_list['ARPU']  =   empty($coupon_list['ARPU'])?$indiana['iOrderARPU']:$coupon_list['ARPU'] + $indiana['iOrderARPU'];
                    $coupon_list['not_pay_count']  =   empty($coupon_list['not_pay_count'])?$indiana['iNotPayUserCount']:$coupon_list['not_pay_count'] + $indiana['iNotPayUserCount'];
                    $coupon_list['not_pay_order']  =   empty($coupon_list['not_pay_order'])?$indiana['iNotPayOrderCount']:$coupon_list['not_pay_order'] + $indiana['iNotPayOrderCount'];
                    $coupon_list['not_pay_money']  =   empty($coupon_list['not_pay_money'])?$indiana['iNotPayMoney']/100:$coupon_list['not_pay_money'] + $indiana['iNotPayMoney']/100;
                    $coupon_list['active_user']  =   empty($coupon_list['active_user'])?$indiana['iActivityUser']:$coupon_list['active_user'] + $indiana['iActivityUser'];
                    $coupon_list['new_user']  =   0;
                    $total_list['new_user'] = empty($total_list['new_user'])?$indiana['iNewUser']:$total_list['new_user'] + $indiana['iNewUser'];
                    array_push($coupon_detail_list,$indiana);
                }
                if($indiana['iType'] == 2)
                {
                    $indiana_list['type'] = '夺宝';
                    $indiana_list['pay_count']  =   empty($indiana_list['pay_count'])?$indiana['iPayUserCount']:$indiana_list['pay_count'] + $indiana['iPayUserCount'];
                    $indiana_list['pay_order']  =   empty($indiana_list['pay_order'])?$indiana['iPayOrderCount']:$indiana_list['pay_order'] + $indiana['iPayOrderCount'];
                    $indiana_list['pay_money']  =   empty($indiana_list['pay_money'])?$indiana['iPayMoney']/100:$indiana_list['pay_money'] + $indiana['iPayMoney']/100;
                    $indiana_list['use_coupon']  =   empty($indiana_list['use_coupon'])?$indiana['iUseCoupon']:$indiana_list['use_coupon'] + $indiana['iUseCoupon'];
                    $indiana_list['ARPU']  =   empty($indiana_list['ARPU'])?$indiana['iOrderARPU']:$indiana_list['ARPU'] + $indiana['iOrderARPU'];
                    $indiana_list['not_pay_count']  =   empty($indiana_list['not_pay_count'])?$indiana['iNotPayUserCount']:$indiana_list['not_pay_count'] + $indiana['iNotPayUserCount'];
                    $indiana_list['not_pay_order']  =   empty($indiana_list['not_pay_order'])?$indiana['iNotPayOrderCount']:$indiana_list['not_pay_order'] + $indiana['iNotPayOrderCount'];
                    $indiana_list['not_pay_money']  =   empty($indiana_list['not_pay_money'])?$indiana['iNotPayMoney']/100:$indiana_list['not_pay_money'] + $indiana['iNotPayMoney']/100;
                    $indiana_list['active_user']  =   empty($indiana_list['active_user'])?$indiana['iActivityUser']:$indiana_list['active_user'] + $indiana['iActivityUser'];
                    $indiana_list['new_user']  =   0;
                    array_push($indiana_detail_list,$indiana);
                }
                if($indiana['iType'] == 3)
                {
                    $exchange_list['type'] = '兑换';
                    $exchange_list['pay_count']  =   empty($exchange_list['pay_count'])?$indiana['iPayUserCount']:$exchange_list['pay_count'] + $indiana['iPayUserCount'];
                    $exchange_list['pay_order']  =   empty($exchange_list['pay_order'])?$indiana['iPayOrderCount']:$exchange_list['pay_order'] + $indiana['iPayOrderCount'];
                    $exchange_list['pay_money']  =   empty($exchange_list['pay_money'])?$indiana['iPayMoney']/100:$exchange_list['pay_money'] + $indiana['iPayMoney']/100;
                    $exchange_list['use_coupon']  =   empty($exchange_list['use_coupon'])?$indiana['iUseCoupon']:$exchange_list['use_coupon'] + $indiana['iUseCoupon'];
                    $exchange_list['ARPU']  =   empty($exchange_list['ARPU'])?$indiana['iOrderARPU']:$exchange_list['ARPU'] + $indiana['iOrderARPU'];
                    $exchange_list['not_pay_count']  =   empty($exchange_list['not_pay_count'])?$indiana['iNotPayUserCount']:$exchange_list['not_pay_count'] + $indiana['iNotPayUserCount'];
                    $exchange_list['not_pay_order']  =   empty($exchange_list['not_pay_order'])?$indiana['iNotPayOrderCount']:$exchange_list['not_pay_order'] + $indiana['iNotPayOrderCount'];
                    $exchange_list['not_pay_money']  =   empty($exchange_list['not_pay_money'])?$indiana['iNotPayMoney']/100:$exchange_list['not_pay_money'] + $indiana['iNotPayMoney']/100;
                    $exchange_list['active_user']  =   empty($exchange_list['active_user'])?$indiana['iActivityUser']:$exchange_list['active_user'] + $indiana['iActivityUser'];
                    $exchange_list['new_user']  =   0;
                    array_push($exchange_detail_list,$indiana);
                }
                if($indiana['iType'] == 4)
                {
                    $lucky_bag_list['type'] = '福袋';
                    $lucky_bag_list['pay_count']  =   empty($lucky_bag_list['pay_count'])?$indiana['iPayUserCount']:$lucky_bag_list['pay_count'] + $indiana['iPayUserCount'];
                    $lucky_bag_list['pay_order']  =   empty($lucky_bag_list['pay_order'])?$indiana['iPayOrderCount']:$lucky_bag_list['pay_order'] + $indiana['iPayOrderCount'];
                    $lucky_bag_list['pay_money']  =   empty($lucky_bag_list['pay_money'])?$indiana['iPayMoney']/100:$lucky_bag_list['pay_money'] + $indiana['iPayMoney']/100;
                    $lucky_bag_list['use_coupon']  =   empty($lucky_bag_list['use_coupon'])?$indiana['iUseCoupon']:$lucky_bag_list['use_coupon'] + $indiana['iUseCoupon'];
                    $lucky_bag_list['ARPU']  =   empty($lucky_bag_list['ARPU'])?$indiana['iOrderARPU']:$lucky_bag_list['ARPU'] + $indiana['iOrderARPU'];
                    $lucky_bag_list['not_pay_count']  =   empty($lucky_bag_list['not_pay_count'])?$indiana['iNotPayUserCount']:$lucky_bag_list['not_pay_count'] + $indiana['iNotPayUserCount'];
                    $lucky_bag_list['not_pay_order']  =   empty($lucky_bag_list['not_pay_order'])?$indiana['iNotPayOrderCount']:$lucky_bag_list['not_pay_order'] + $indiana['iNotPayOrderCount'];
                    $lucky_bag_list['not_pay_money']  =   empty($lucky_bag_list['not_pay_money'])?$indiana['iNotPayMoney']/100:$lucky_bag_list['not_pay_money'] + $indiana['iNotPayMoney']/100;
                    $lucky_bag_list['active_user']  =   empty($lucky_bag_list['active_user'])?$indiana['iActivityUser']:$lucky_bag_list['active_user'] + $indiana['iActivityUser'];
                    $lucky_bag_list['new_user']  =   0;
                    array_push($lucky_bag_detail_list,$indiana);
                }
                $total_list['type'] = '总计';
                $total_list['pay_count']  =   empty($total_list['pay_count'])?$indiana['iPayUserCount']:$total_list['pay_count'] + $indiana['iPayUserCount'];
                $total_list['pay_order']  =   empty($total_list['pay_order'])?$indiana['iPayOrderCount']:$total_list['pay_order'] + $indiana['iPayOrderCount'];
                $total_list['pay_money']  =   empty($total_list['pay_money'])?$indiana['iPayMoney']/100:$total_list['pay_money'] + $indiana['iPayMoney']/100;
                $total_list['use_coupon']  =   empty($total_list['use_coupon'])?$indiana['iUseCoupon']:$total_list['use_coupon'] + $indiana['iUseCoupon'];
                $total_list['ARPU']  =   empty($total_list['ARPU'])?$indiana['iOrderARPU']:$total_list['ARPU'] + $indiana['iOrderARPU'];
                $total_list['not_pay_count']  =   empty($total_list['not_pay_count'])?$indiana['iNotPayUserCount']:$total_list['not_pay_count'] + $indiana['iNotPayUserCount'];
                $total_list['not_pay_order']  =   empty($total_list['not_pay_order'])?$indiana['iNotPayOrderCount']:$total_list['not_pay_order'] + $indiana['iNotPayOrderCount'];
                $total_list['not_pay_money']  =   empty($total_list['not_pay_money'])?$indiana['iNotPayMoney']/100:$total_list['not_pay_money'] + $indiana['iNotPayMoney']/100;
                $total_list['active_user']  =   empty($total_list['active_user'])?$indiana['iActivityUser']:$total_list['active_user'] + $indiana['iActivityUser'];
                //$total_list['new_user']  =   $indiana['iNewUser'];
            }
            $real_list = array();
            array_push($real_list,$indiana_list);
            array_push($real_list,$coupon_list);
            array_push($real_list,$exchange_list);
            array_push($real_list,$lucky_bag_list);
            array_push($real_list,$total_list);
        }
        else
        {
            $real_list = array();
        }

        if(!empty($indiana_detail_list))
        {
            foreach($indiana_detail_list as $key => $v)
            {
                $create_time = $v['iCreateTime'];
                $end_time = $create_time + 60*30;
                $indiana_detail_list[$key]['endTime'] = $end_time;
            }
        }

        if(!empty($coupon_detail_list))
        {
            foreach($coupon_detail_list as $key => $v)
            {
                $create_time = $v['iCreateTime'];
                $end_time = $create_time + 60*30;
                $coupon_detail_list[$key]['endTime'] = $end_time;
            }
        }

        if(!empty($exchange_detail_list))
        {
            foreach($exchange_detail_list as $key => $v)
            {
                $create_time = $v['iCreateTime'];
                $end_time = $create_time + 60*30;
                $exchange_detail_list[$key]['endTime'] = $end_time;
            }
        }

        if(!empty($lucky_bag_detail_list))
        {
            foreach($lucky_bag_detail_list as $key => $v)
            {
                $create_time = $v['iCreateTime'];
                $end_time = $create_time + 60*30;
                $lucky_bag_detail_list[$key]['endTime'] = $end_time;
            }
        }

        $viewData = array(
            'real_list'  =>  $real_list,
            'indiana_detail_list'   =>  $indiana_detail_list,
            'coupon_detail_list'   =>  $coupon_detail_list,
            'exchange_detail_list'  =>  $exchange_detail_list,
            'lucky_bag_detail_list' =>  $lucky_bag_detail_list
        );
        $this->render($viewData);

    }


}
