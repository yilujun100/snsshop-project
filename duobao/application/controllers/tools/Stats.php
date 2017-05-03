<?php


class Stats extends MY_Controller
{
    /**
     * 构造函数
     *
     * Active_order constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function run()
    {
        date_default_timezone_set('Asia/Shanghai');

        $con = mysql_connect("10.104.177.226","oruser","inksgotDKEk");
        //$con = mysql_connect("10.100.200.26","root","");

        //echo "==================Start create database or tables====================== <br/>\n";
        $active = $coupon = $luckyBag = array('paid'=>array(),'unpaid'=>array(),'users'=>0,'couponUser'=>0,'bagUser'=>0);

        for($i=0;$i<1;$i++){
            $db_name = 'yydb_user';
            //echo "Create database ".$db_name." <br/>\n";
            //mysql_query("CREATE DATABASE IF NOT EXISTS `".$db_name."`",$con);
            mysql_select_db($db_name, $con);

            //创建表
            for($j=0;$j<10;$j++){
                $table_name1 = 't_active_merage_order'.$j;
                $table_name2 = 't_coupon_order'.$j;
                //$table_name3 = 't_active_action_log'.$j;
                //$table_name4 = 't_lucky_bag'.$j;
                $table_name5 = 't_user_info'.$j;
                $table_name6 = 't_bag_order'.$j;

                $startDate = $this->get_post('start','2016-05-01');
                $endDate = $this->get_post('end','2016-06-01');
                $startTime = $timer = strtotime($startDate);
                $endTime = strtotime($endDate);

                while($endTime>$timer){
                    //=======参数活动的金额与人数===============//
                    //已支付
                    $date = date('Y-m-d H:i:s',$timer);
                    $activeOrderQuery = mysql_query("SELECT count(*) AS num,SUM(iCoupon) AS coupon,SUM(iTotalPrice) AS total FROM ".$table_name1." WHERE iPayTime >= '".$timer."' AND iPayTime < '".($timer+3600*24)."' and iPayStatus = 1",$con);
                    $activeOrder = mysql_fetch_assoc($activeOrderQuery);
                    if(!isset($active['paid'][$date])) $active['paid'][$date] = array('num'=>0,'coupon'=>0,'total'=>0);
                    $active['paid'][$date] = array(
                        'num' => intval($active['paid'][$date]['num'])+intval($activeOrder['num']),
                        'coupon' => intval($active['paid'][$date]['coupon']) + intval($activeOrder['coupon']),
                        'total' => intval($active['paid'][$date]['total']) + intval($activeOrder['total'])
                    );
                    //$this->debug('已支付数据统计-下单数['.$activeOrder['num'].']-使用券数量['.(empty($activeOrder['coupon'])?0:$activeOrder['coupon']).']-总金额['.(empty($activeOrder['total'])?0:$activeOrder['total']).']');

                    //未支付
                    $activeUnpaidQuery = mysql_query("SELECT count(*) AS num,SUM(iCoupon) AS coupon,SUM(iTotalPrice) AS total FROM ".$table_name1." WHERE iCreateTime >= '".$timer."' AND iCreateTime < '".($timer+3600*24)."' and iPayStatus = 0",$con);
                    $activeUnpaid = mysql_fetch_assoc($activeUnpaidQuery);
                    if(!isset($active['unpaid'][$date])) $active['unpaid'][$date] = array('num'=>0,'coupon'=>0,'total'=>0);
                    $active['unpaid'][$date] = array(
                        'num' => intval($active['unpaid'][$date]['num'])+intval($activeUnpaid['num']),
                        'coupon' => intval($active['unpaid'][$date]['coupon']) + intval($activeUnpaid['coupon']),
                        'total' => intval($active['unpaid'][$date]['total']) + intval($activeUnpaid['total'])
                    );


                    //================购买夺宝券====================//
                    //echo "SELECT count(*) AS num,SUM(iTotalPrice) AS total FROM ".$table_name2." WHERE iPayTime >= '".$timer."' AND iPayTime < '".($timer+3600*24)."' and iPayStatus = 1";
                    $couponQuery = mysql_query("SELECT count(*) AS num,SUM(iTotalPrice) AS total,SUM(`iPresentCount`) AS present FROM ".$table_name2." WHERE iPayTime >= '".$timer."' AND iPayTime < '".($timer+3600*24)."' and iPayStatus = 1",$con);
                    $couponOrder = mysql_fetch_assoc($couponQuery);
                    if(!isset($coupon['paid'][$date])) $coupon['paid'][$date] = array('num'=>0,'coupon'=>0,'present'=>0,'total'=>0);
                    $coupon['paid'][$date] = array(
                        'num' => intval($coupon['paid'][$date]['num'])+intval($couponOrder['num']),
                        'total' => intval($coupon['paid'][$date]['total']) + intval($couponOrder['total']),
                        'present' => intval($coupon['paid'][$date]['present']) + intval($couponOrder['present']),
                    );

                    $couponQuery = mysql_query("SELECT count(*) AS num,SUM(iTotalPrice) AS total,SUM(`iPresentCount`) AS present FROM ".$table_name2." WHERE iCreateTime >= '".$timer."' AND iCreateTime < '".($timer+3600*24)."' and iPayStatus = 0",$con);
                    $couponOrder = mysql_fetch_assoc($couponQuery);
                    if(!isset($coupon['unpaid'][$date])) $coupon['unpaid'][$date] = array('num'=>0,'coupon'=>0,'present' => 0,'total'=>0);
                    $coupon['unpaid'][$date] = array(
                        'num' => intval($coupon['unpaid'][$date]['num'])+intval($couponOrder['num']),
                        'total' => intval($coupon['unpaid'][$date]['total']) + intval($couponOrder['total']),
                        'present' => intval($coupon['paid'][$date]['present']) + intval($couponOrder['present']),
                    );

                    //=================福袋活动=====================//
                    $bagQuery = mysql_query("SELECT count(*) AS num,SUM(iPayAmount) AS amount,SUM(iPayCoupon) AS coupon FROM ".$table_name6." WHERE iPayTime >= '".$timer."' AND iPayTime < '".($timer+3600*24)."' and iStatus = 1",$con);
                    $bagOrder = mysql_fetch_assoc($bagQuery);
                    if(!isset($luckyBag['paid'][$date])) $luckyBag['paid'][$date] = array('num'=>0,'coupon'=>0,'amount'=>0);
                    $luckyBag['paid'][$date] = array(
                        'num' => intval($luckyBag['paid'][$date]['num'])+intval($bagOrder['num']),
                        'coupon' => intval($luckyBag['paid'][$date]['coupon']) + intval($bagOrder['coupon']),
                        'amount' => intval($luckyBag['paid'][$date]['amount']) + intval($bagOrder['amount'])
                    );

                    $bagQuery = mysql_query("SELECT count(*) AS num,SUM(iPayAmount) AS amount,SUM(iPayCoupon) AS coupon FROM ".$table_name6." WHERE iCreateTime >= '".$timer."' AND iCreateTime < '".($timer+3600*24)."' and iStatus = 0",$con);
                    $bagOrder = mysql_fetch_assoc($bagQuery);
                    if(!isset($luckyBag['unpaid'][$date])) $luckyBag['unpaid'][$date] = array('num'=>0,'coupon'=>0,'amount'=>0);
                    $luckyBag['unpaid'][$date] = array(
                        'num' => intval($luckyBag['unpaid'][$date]['num'])+intval($bagOrder['num']),
                        'coupon' => intval($luckyBag['unpaid'][$date]['coupon']) + intval($bagOrder['coupon']),
                        'amount' => intval($luckyBag['unpaid'][$date]['amount']) + intval($bagOrder['amount'])
                    );

                    $timer = $timer+3600*24;
                }

                //参与活动人数
                $activeUserQuery = mysql_query("SELECT COUNT(*) AS num FROM (SELECT iUin FROM ".$table_name1." WHERE iPayStatus = 1 and iPayTime > ".$startTime." and iPayTime < ".$endTime."  GROUP BY iUin) AS t_order",$con);
                $activeUser = mysql_fetch_assoc($activeUserQuery);
                $active['users'] = $active['users'] + $activeUser['num'];

                //购买夺宝券人数
                $activeUserQuery = mysql_query("SELECT COUNT(*) AS num FROM (SELECT iUin FROM ".$table_name2." WHERE iPayStatus = 1 and iPayTime > ".$startTime." and iPayTime < ".$endTime."    GROUP BY iUin) AS t_order",$con);
                $activeUser = mysql_fetch_assoc($activeUserQuery);
                $active['couponUser'] = $active['couponUser'] + $activeUser['num'];

                //购买福袋人数
                $activeUserQuery = mysql_query("SELECT COUNT(*) AS num FROM (SELECT iUin FROM ".$table_name6." WHERE iStatus = 1 and iPayTime > ".$startTime." and iPayTime < ".$endTime."    GROUP BY iUin) AS t_order",$con);
                $activeUser = mysql_fetch_assoc($activeUserQuery);
                $active['bagUser'] = $active['bagUser'] + $activeUser['num'];
            }
            //var_dump($active);die;

        }

        $this->debug('=============活动参与数据统计===========');
        foreach($active['paid'] as $date=>$activeOrder){
            $this->debug($date.'-paid-orders['.$activeOrder['num'].']-coupon['.(empty($activeOrder['coupon'])?0:$activeOrder['coupon']).']-total['.(empty($activeOrder['total'])?0:$activeOrder['total']).']');
        }
        $this->debug('');
        foreach($active['unpaid'] as $date=>$activeOrder){
            $this->debug($date.'-unpaid-orders['.$activeOrder['num'].']-coupon['.(empty($activeOrder['coupon'])?0:$activeOrder['coupon']).']-total['.(empty($activeOrder['total'])?0:$activeOrder['total']).']');
        }
        $this->debug('总参数人数：'.$active['users']."\n\n");


        $this->debug('=============夺宝券购买数据统计===========');
        foreach($coupon['paid'] as $date=>$activeOrder){
            $this->debug($date.'-paid-orders['.$activeOrder['num'].']-total['.(empty($activeOrder['total'])?0:$activeOrder['total']).']');
        }
        $this->debug('');
        foreach($coupon['unpaid'] as $date=>$activeOrder){
            $this->debug($date.'-unpaid-orders['.$activeOrder['num'].']-total['.(empty($activeOrder['total'])?0:$activeOrder['total']).']');
        }
        $this->debug('购买夺宝券人数:'.$active['couponUser']."\n\n");

        $this->debug('=============福袋购买数据统计===========');
        foreach($luckyBag['paid'] as $date=>$activeOrder){
            $this->debug($date.'-paid-orders['.$activeOrder['num'].']-coupon['.(empty($activeOrder['coupon'])?0:$activeOrder['coupon']).']-amount['.(empty($activeOrder['amount'])?0:$activeOrder['amount']).']');
        }
        $this->debug('');
        foreach($luckyBag['unpaid'] as $date=>$activeOrder){
            $this->debug($date.'-unpaid-orders['.$activeOrder['num'].']-coupon['.(empty($activeOrder['coupon'])?0:$activeOrder['coupon']).']-amount['.(empty($activeOrder['amount'])?0:$activeOrder['amount']).']');
        }
        $this->debug('购买福袋人数:'.$active['bagUser']."\n\n");

        //echo "==================Success create || Done====================== <br/>\n";
        mysql_close($con);

        header("Content-type:text/html;charset=utf-8");
        $file = $this->get_file_name();
        $this->download($file,'activeOrder');
        unlink($file);
    }


    public function debug($logs, $showTime = true)
    {
        $filename = $this->get_file_name();
        @file_put_contents($filename, ($showTime ? '['.date('Y-m-d H:i:s').'] ' : '').$logs."\n", FILE_APPEND);
    }

    public function get_file_name($type = "activeOrder"){
        return $filename = dirname(dirname(dirname(__FILE__))).'/logs/'.$type.'_'.date('Y-m-d').'.log';
    }

    public function download($file, $down_name){
        $suffix = substr($file,strrpos($file,'.')); //获取文件后缀
        $down_name = $down_name.$suffix; //新文件名，就是下载后的名字
        //判断给定的文件存在与否
        if(!file_exists($file)){
            die("您要下载的文件已不存在，可能是被删除");
        }
        $fp = fopen($file,"r");
        $file_size = filesize($file);
        //下载文件需要用到的头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:".$file_size);
        header("Content-Disposition: attachment; filename=".$down_name);
        $buffer = 1024;
        $file_count = 0;
        //向浏览器返回数据
        while(!feof($fp) && $file_count < $file_size){
            $file_con = fread($fp,$buffer);
            $file_count += $buffer;
            echo $file_con;
        }
        fclose($fp);
    }
}