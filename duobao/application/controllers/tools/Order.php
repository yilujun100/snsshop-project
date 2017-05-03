<?php


class Order extends MY_Controller
{
    /**
     * 构造函数
     *
     * Active_order constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('user_ext_model');
        $this->load->model('weixin_user_model');
        $this->load->model('coupon_order_model');
        $this->load->service('order_service');
    }



    public function run()
    {
        $con = mysql_connect("localhost","root","") or die('not connect db');

        for($i=0;$i<10;$i++){
            $db_name = 'tuan_wtg_active';
            mysql_select_db($db_name.$i, $con);

            for($j=0;$j<10;$j++){
                $table_name = 't_coupon_order'.$j;

                $query = mysql_query("select * from ".$table_name." where iPayTime != -1 and iStatus = 1");
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
                    $data['t_coupon_order'.$table_ext][] = array(
                        'sOrderId' => $this->setOrderId(Lib_Constants::PLATFORM_WTG,Lib_Constants::ORDER_TYPE_COUPON,$user['iUin']),
                        'iUin' => '"'.$user['iUin'].'"',
                        'iCount' => $row['iCount'],
                        'iPresentCount' => 0,
                        'iUnitPrice' => Lib_Constants::COUPON_UNIT_PRICE,
                        'iTotalPrice' => Lib_Constants::COUPON_UNIT_PRICE*$row['iCount'],
                        'iPayAgentType' => Lib_Constants::ORDER_PAY_TYPE_WX,
                        'iPayTime' => $row['iPayTime'],
                        'sTransId' => '"'.$row['sTransId'].'"',
                        'iPayStatus' => $row['iStatus'],
                        'iPlatformId' => Lib_Constants::PLATFORM_WTG,
                        'iIP' => '"'.'127.0.0.1'.'"',
                        'iLocation' => '""',
                        'iCreateTime' => $row['iCreateTime'],
                        'iLastModTime' => time()
                    );
                }

                //插入新数据
                if(!$this->add_coupon_order($data)){
                    die('========================DONE==============================');
                }
            }
        }
    }



    public function add_coupon_order($data)
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
            $sql = "insert into `".$table."` (`sOrderId`,`iUin`,`iCount`,`iPresentCount`,`iUnitPrice`,`iTotalPrice`,`iPayAgentType`,`iPayTime`,`sTransId`,`iPayStatus`,`iPlatformId`,`iIP`,`iLocation`,`iCreateTime`,`iLastModTime`) values ".$inert_str;
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