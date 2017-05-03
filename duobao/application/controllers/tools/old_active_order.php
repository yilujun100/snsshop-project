<?php


class Old_active_order extends MY_Controller
{
    /**
     * 构造函数
     *
     * Active_order constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('luckycode_record_model');

        //$this->load->model('goods_item_model');
        //$this->load->model('active_config_model');
        $this->load->model('active_peroid_model');
        $this->load->model('active_merage_order_model');
        $this->load->model('active_order_model');
        $this->load->model('order_summary_model');
        $this->load->model('luckycode_summary_model');
        //$this->load->model('weixin_user_model');
        $this->load->model('user_model');
    }



    public function run()
    {
       //$con = mysql_connect("localhost","root","") or die('not connect db');

        //for($i=0;$i<1;$i++){
            //$db_name = 'tuan_wtg_active';
            //mysql_select_db($db_name.$i, $con);

            //for($j=0;$j<1;$j++){
                $index = $this->get_post('index',0);
                $table_name = 't_luckycode_record'.$index;
                $query = $this->luckycode_record_model->query("select count(*) as iCount,sOrderId,iCreateTime,iUin,iGoodsId,iActId,iPeroid from ".$table_name." where sOrderId REGEXP '^(139)[0-9]{25}$' group by sOrderId");
                pr($this->luckycode_record_model->db->last_query());
                /*pr($query);die;*/

                $merage = $active = $order_summary = $luckycode_summary = array();
                foreach($query as $row){
                    $row['iType'] = isset( $row['iType']) ?  $row['iType'] : 1;
                    /*$user = $this->weixin_user_model->get_row(array('uin'=>$row['iUin']));
                    if(empty($user['openid'])){
                        echo '================not found user=============</br>';
                        pr($user);pr($row);continue;
                    }*/
                    $user = $this->user_model->get_row(array('iUin'=>$row['iUin']));
                    $table = $this->user_model->map($user['iUin'])->get_cur_table();
                    $table_ext = substr($table,-1);
                    $merageOrderId = $this->setOrderId(Lib_Constants::PLATFORM_WTG,1,$user['iUin']);
                    $merage['t_active_merage_order'.$table_ext][] = array(
                        'sMergeOrderId' => '"'.$merageOrderId.'"',
                        'iUin' => '"'.$user['iUin'].'"',
                        'iTotalPrice' => Lib_Constants::COUPON_UNIT_PRICE*$row['iCount'],
                        'iCoupon' => 0,
                        'iAmount' => $row['iCount'],
                        'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
                        'iPayTime' => $row['iCreateTime'],
                        'iPayStatus' => 1,
                        'sTransId' => '"'.$row['sOrderId'].'"',
                        'iPlatformId' => Lib_Constants::PLATFORM_WTG,
                        'iIP' => '"'.'127.0.0.1'.'"',
                        'iLocation' => '""',
                        'iCreateTime' => $row['iCreateTime'],
                        'iLastModTime' => time()
                    );


                    //查询订单
                    /*$active_config = $this->active_config_model->get_row(array('iGrouponId'=>$row['iGoodsId']));
                    if(empty($active_config)){
                        echo '================not found active config=============</br>';
                        pr($row);continue;
                    }*/
                    $summary = $this->luckycode_summary_model->get_row(array('sOrderId'=>$row['sOrderId'],'iActId'=>$row['iActId']));
                    if(empty($summary)){
                        echo '================not found summary order=============</br>';
                        pr($row);continue;
                    }
                    $active_order = $this->setOrderId(Lib_Constants::PLATFORM_WTG,$row['iType'],$user['iUin']);
                    $active['t_active_order'.$table_ext][] = array(
                        'sOrderId' => '"'.$active_order.'"',
                        'sMergeOrderId' =>'"'.$merageOrderId.'"',
                        'iUin' => '"'.$user['iUin'].'"',
                        'iGoodsId' => $summary['iGoodsId'],
                        'iActId' => $summary['iActId'],
                        'iPeroid' => $summary['iPeroid'],
                        'iBuyType' => $row['iType'],
                        'iCount' => $row['iCount'],
                        'iUnitPrice' => Lib_Constants::COUPON_UNIT_PRICE,
                        'iTotalPrice' => $row['iCount']*Lib_Constants::COUPON_UNIT_PRICE,
                        'iAmount' => $row['iCount']*Lib_Constants::COUPON_UNIT_PRICE,
                        'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
                        'iPayTime' => $row['iCreateTime'],
                        'sTransId' => '"'.$row['sOrderId'].'"',
                        'iPayStatus' => 1,
                        'iCreateTime' => $row['iCreateTime'],
                        'iLastModTime' => time()
                    );

                    $peroid = $this->active_peroid_model->get_row(array('sWinnerOrder'=>$row['sOrderId']));
                    if(!empty($peroid)){
                        $is_win = 1;
                    }else{
                        $is_win = 0;
                    }
                    $table = $this->user_model->map($user['iUin'])->get_cur_table();
                    $table_ext = substr($table,-1);
                    $order_summary['t_order_summary'.$table_ext][] = array(
                        'iActId' => $summary['iActId'],
                        'iPeroid' => $summary['iPeroid'],
                        'iGoodsId' => $summary['iGoodsId'],
                        'sGoodsName' => '"'.$summary['sGoodsName'].'"',
                        'sOrderId' => '"'.$active_order.'"',
                        'iUin' => $user['iUin'],
                        'sNickName' => '"'.addslashes($user['sNickName']).'"',
                        'sHeadImg' => '"'.$user['sHeadImg'].'"',
                        'iLotCount' => $row['iCount'],
                        'iLotTime' => $summary['iLotTime'],
                        'iLotState' => $summary['iLotState'],
                        'iIsWin' => $is_win,
                        'sLuckyCodes' => "'".$summary['sLuckyCodes']."'",
                        'iIP' => '"'.'127.0.0.1'.'"',
                        'iLocation' => '""',
                        'iCreateTime' => $row['iCreateTime'],
                        'iLastModTime' => time(),
                    );

                    //更新订单号
                    $this->active_peroid_model->update_row(array('sWinnerOrder'=>$active_order),array('sWinnerOrder'=>$row['sOrderId']));
                    $this->luckycode_record_model->update_rows(array('sOrderId'=>$active_order),array('sOrderId'=>$row['sOrderId'],'iActId'=>$summary['iActId']));
                    $this->luckycode_summary_model->update_rows(array('sOrderId'=>$active_order),array('sOrderId'=>$row['sOrderId'],'iActId'=>$summary['iActId']));
                }

                $this->add_merage_order($merage);
                $this->add_active_order($active);
                $this->add_order_summary($order_summary);
                //$this->add_luckycode_summary($luckycode_summary);
            //}
        //}

        echo "===========================SUCCESS=======================================";
    }

    public function add_merage_order($data){
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
                //$ext_str .= '('.$v['uin'].'),';
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`sMergeOrderId`,`iUin`,`iTotalPrice`,`iCoupon`,`iAmount`,`iPayAgentType`,`iPayTime`,`iPayStatus`,`sTransId`,`iPlatformId`,`iIP`,`iLocation`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->active_merage_order_model->query($sql, true)){
                pr($this->active_merage_order_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }

    public function add_active_order($data){
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
                //$ext_str .= '('.$v['uin'].'),';
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`sOrderId`,`sMergeOrderId`,`iUin`,`iGoodsId`,`iActId`,`iPeroid`,`iBuyType`,`iCount`,`iUnitPrice`,`iTotalPrice`,`iAmount`,`iPayAgentType`,`iPayTime`,`sTransId`,`iPayStatus`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->active_order_model->query($sql, true)){
                pr($this->active_order_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }

    public function add_luckycode_summary($data)
    {
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
                //$ext_str .= '('.$v['uin'].'),';
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`iActId`,`iPeroid`,`iGoodsId`,`sGoodsName`,`sOrderId`,`iUin`,`sNickName`,`sHeadImg`,`iLotCount`,`sLuckyCodes`,`iLotTime`,`iLotState`,`iNotifyStatus`,`iSoonStatus`,`iResultStatus`,`iIP`,`iLocation`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->luckycode_summary_model->query($sql, true)){
                pr($this->luckycode_summary_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }


    public function add_order_summary($data)
    {
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
                //$ext_str .= '('.$v['uin'].'),';
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`iActId`,`iPeroid`,`iGoodsId`,`sGoodsName`,`sOrderId`,`iUin`,`sNickName`,`sHeadImg`,`iLotCount`,`iLotTime`,`iLotState`,`iIsWin`,`sLuckyCodes`,`iIP`,`iLocation`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
            if(!$this->order_summary_model->query($sql, true)){
                pr($this->order_summary_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }



    /**
     * 统一返回指定格式的订单号
     * 格式：平台类型+订单类型+年月日时分秒+微秒+随机数+uin后两位
     * @param $plat_from
     * @param $uin
     * @param $type
     * @return string
     */
    private function setOrderId($plat_from,$type,$uin = null)
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = substr($usec,2,4);
        if($uin == null){
            return $plat_from.$type.date('YmdHis').$usec.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 4);
        }else{
            $suffix = substr($uin,-2);
            return $plat_from.$type.date('YmdHis').$usec.substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 2).$suffix;
        }
    }
}