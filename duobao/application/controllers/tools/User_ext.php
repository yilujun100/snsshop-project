<?php



class User_ext extends MY_Controller
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
        $this->load->model('user_ext_model');
    }



    public function run()
    {
        $con = mysql_connect("localhost","root","") or die('not connect db');

        for($i=0;$i<10;$i++){
            $db_name = 'tuan_wtg_active';
            mysql_select_db($db_name.$i, $con);

            for($j=0;$j<10;$j++){
                $table_name = 't_user_info'.$j;
                $query = mysql_query("select * from ".$table_name." where iLuckyBag >0 or  iCoupon > 0");
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

                    if(!$this->user_ext_model->update_row(array('iCoupon'=>$row['iCoupon'],'iLuckyBag'=>$row['iLuckyBag'],'iHisCoupon'=>$row['iCoupon'],'iHisLuckyBag'=>$row['iLuckyBag']),array('iUin'=>$user['iUin']))){
                        $row['new_uin'] = $user['iUin'];
                        pr($row);continue;
                    }
                }
            }
        }
    }
}