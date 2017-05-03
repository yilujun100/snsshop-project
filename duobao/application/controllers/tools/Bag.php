<?php


class Bag extends MY_Controller
{
    /**
     * 构造函数
     *
     * Active_order constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('bag_order_model');
        $this->load->model('lucky_bag_model');
        $this->load->model('weixin_user_model');
        $this->load->model('user_model');
    }


    public function run()
    {
        $con = mysql_connect("localhost","root","") or die('not connect db');

        for($i=0;$i<10;$i++){
            $db_name = 'tuan_wtg_active';
            mysql_select_db($db_name.$i, $con);

            for($j=0;$j<10;$j++){
                $table_name = 't_bag_order'.$j;
                $query = mysql_query("select * from ".$table_name." where iPayTime != -1 and iStatus = 1");
                $data = array();
                /*while($row = mysql_fetch_array($query)){
                    $user = $this->weixin_user_model->get_row(array('uin'=>$row['iUin']));
                    $openid = $user['openid'];
                    if(empty($openid)){
                        pr($user);pr($row['iUin']);continue;
                    }
                    $user = $this->user_model->get_row(array('sOpenId'=>$openid));
                    $table = $this->user_model->map($user['iUin'])->get_cur_table();
                    $table_ext = substr($table,-1);
                    $data['t_bag_order'.$table_ext][] = array(
                        'sOrderId' => '"'.$this->setOrderId(Lib_Constants::PLATFORM_WTG,Lib_Constants::ORDER_TYPE_BAG,$user['iUin']).'"',
                        'iUin' => '"'.$user['iUin'].'"',
                        'iBagId' => $row['iBagId'],
                        'iPayAmount' => $row['iPayAmount']*100,
                        'iPayCoupon' => $row['iPayCoupon'],
                        'iTotalAmount' => $row['iTotalPrice']*100,
                        'iCreateTime' => $row['iCreateTime'],
                        'iPayTime' => $row['iPayTime'],
                        'iStatus' => $row['iStatus'],
                        'iUpdateTime' => time(),
                        'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
                        'iPlatForm' => Lib_Constants::PLATFORM_WX,
                        'sTransId' => '"'.$row['sTransId'].'"'
                    );
                }

                //插入新数据
                if(!$this->add_bag_order($data)){
                    die('========================DONE==============================');
                }*/


                $table_name = 't_lucky_bag'.$j;
                $query = mysql_query("select * from ".$table_name);
                $data = array();
                while($row = mysql_fetch_array($query)){
                    $user = $this->weixin_user_model->get_row(array('uin'=>$row['iUin']));
                    $openid = $user['openid'];
                    if(empty($openid)){
                        pr($user);pr($row['iUin']);continue;
                    }
                    $user = $this->user_model->get_row(array('sOpenId'=>$openid));
                    $table = $this->user_model->map($user['iUin'])->get_cur_table();
                    $table_ext = substr($table,-1);

                    $data = array(
                        'iUin' => $user['iUin'],
                        'iType' => $row['iType'],
                        'sWish' => $row['sWish'],
                        'iCoupon' => $row['iNum'],
                        'iPayAmount' => $row['iAmount']*100,
                        'iPerson' => $row['iPeople'],
                        'iPerCoupon' => $row['iPeopleNum'],
                        'iUsed' => $row['iUsed'],
                        'iUsedPerson' => $row['iUsedPeople'],
                        'iIsPaid' => $row['iIsPaid'],
                        'iIsDone' => $row['iIsDone'],
                        'iIsTimeOut' => $row['iIsTimeOut'],
                        'iStatus' => $row['iStatus'],
                        'iStartTime' => $row['iStartTime'],
                        'iEndTime' => $row['iEndTime'],
                        'iUpdateTime' => time(),
                        'bid' => $row['iBagId']
                    );

                    if($row['iUin'] == '2141660864270060810'){
                        pr($data);die;
                    }


                    if($inert_id = $this->lucky_bag_model->add_row($data)){
                        //$this->bag_order_model->update_row(array('iBagId'=>$inert_id),array('iUin'=>$user['iUin'],'iBagId'=>$row['iBagId']));
                    }else{
                        pr($row);continue;
                    }
                }

                pr('==========================DONE==================================');
            }
        }
    }


    public function add_bag_order($data){
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
            $sql = "insert into `".$table."` (`sOrderId`,`iUin`,`iBagId`,`iPayAmount`,`iPayCoupon`,`iTotalAmount`,`iCreateTime`,`iPayTime`,`iStatus`,`iUpdateTime`,`iPayAgentType`,`iPlatForm`,`sTransId`) values ".$inert_str;
            if(!$this->bag_order_model->query($sql, true)){
                pr($this->bag_order_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }

    public function add_bag_info($data){
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
            $sql = "insert into `".$table."` (`iUin`,`iAction`,`iNum`,`sExt`,`iAddTime`,`iPlatForm`,`iType`) values ".$inert_str;
            if(!$this->coupon_order_model->query($sql, true)){
                pr($this->coupon_order_model->db->lasst_query());
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