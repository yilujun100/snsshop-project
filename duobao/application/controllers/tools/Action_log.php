<?php


class Action_log extends MY_Controller
{
    /**
     * 构造函数
     *
     * Active_order constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('coupon_action_log_model');
        $this->load->model('bag_action_log_model');
        $this->load->model('weixin_user_model');
        $this->load->model('user_model');
        $this->load->model('lucky_bag_model');
    }


    public function run()
    {
        $con = mysql_connect("localhost","root","") or die('not connect db');

        for($i=0;$i<10;$i++){
            $db_name = 'tuan_wtg_active';
            mysql_select_db($db_name.$i, $con);

            for($j=0;$j<10;$j++){
                $table_name = 't_active_action_log'.$j;

                $query = mysql_query("select * from ".$table_name);
                $data = $coupon_log = $bag_log = array();
                while($row = mysql_fetch_array($query)){
                    $user = $this->weixin_user_model->get_row(array('uin'=>$row['iUin']));
                    $openid = $user['openid'];
                    if(empty($openid)){
                        pr($user);pr($row['iUin']);continue;
                    }
                    $user = $this->user_model->get_row(array('sOpenId'=>$openid));
                    $table = $this->user_model->map($user['iUin'])->get_cur_table();
                    $table_ext = substr($table,-1);
                    if($row['iAction'] != 7){
                        if($row['iAction'] == 1){
                            if(!$ext = $this->get_user_info($row['sExtend'])){
                                pr($row);pr('=========user no find============');continue;
                            }

                            if(!$bag = $this->get_bag($ext['iUin'],$row['iBagId'])){
                                $row['new_uin'] = $user['iUin'];
                                pr($row);pr('=========bag no find============');pr($user);continue;
                            }

                            $json  = array('uin'=>$ext['iUin'],'bag_id'=>$bag['iBagId'],'nickname'=>$ext['sNickName'],'type'=>2);
                            $type = 1;
                        }elseif($row['iAction'] == 2){
                            $row['iAction'] = 20;
                            $type = 1;
                            $json  = array('bag_id'=>$row['iBagId'],'order_id'=>"");
                        }elseif($row['iAction'] == 3){
                            $type = 1;
                            $json  = array('bag_id'=>$row['iBagId'],'order_id'=>"");
                        }elseif($row['iAction'] == 6){
                            $type = 2;
                            $json  = array('bag_id'=>$row['iBagId'],'order_id'=>"");
                        }elseif($row['iAction'] == 4){
                            $type = 2;
                            $row['iAction'] = 16;
                            $json  = array('order_id'=>$row['sExtend']);
                        }elseif($row['iAction'] == 5){
                            $type = 2;
                            $row['iAction'] = 18;
                            $json  = array('bag_id'=>$row['iBagId'],'order_id'=>"");
                        }else{
                            $json = array();
                        }

                        $coupon_log['t_coupon_action_log'.$table_ext][] = array(
                            'iUin' => '"'.$user['iUin'].'"',
                            'iAction' => $row['iAction'],
                            'iNum' => $row['iNum'],
                            'sExt' => "'".addslashes(json_encode($json))."'",
                            'iAddTime' => $row['iAddTime'],
                            'iPlatForm' => Lib_Constants::PLATFORM_WX,
                            'iType' => $type,
                        );
                        //if($row['iAction'] == 1 ) {pr($coupon_log);die;}
                    }else{
                        if(!$ext = $this->get_user_info($row['sExtend'])){
                            pr($row);pr('=========user no find============');continue;
                        }
                        $bag_log['t_bag_action_log'.$table_ext][] = array(
                            'iUin' => '"'.$user['iUin'].'"',
                            'iBagId' => $row['iBagId'],
                            'iAction' => $row['iAction'],
                            'iNum' => $row['iNum'],
                            'sExtend' => $row['sExtend'],
                            'iAddTime' => $row['iAddTime'],
                            'sNickName' => '"'.$user['sNickName'].'"',
                            'sHeadImg' => '"'.$user['sHeadImg'].'"',
                            'iType' => 0,
                        );
                    }
                }

                //插入新数据
                if(!$this->add_coupon_action_log($coupon_log)){
                    die('========================DONE==============================');
                }

                if(!$this->add_bag_action_log($bag_log)){
                    die('========================DONE==============================');
                }
            }
        }
    }


    public function get_user_info($uin){
        $user = $this->weixin_user_model->get_row(array('uin'=>$uin));
        $openid = $user['openid'];
        if(empty($openid)){
            return false;
        }
        return $user = $this->user_model->get_row(array('sOpenId'=>$openid));
    }

    public function get_bag($uin,$bid)
    {
        $bag = $this->lucky_bag_model->get_row(array('iUin'=>$uin,'bid'=>$bid));

        return $bag;
    }

    public function add_coupon_action_log($data)
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
            $sql = "insert into `".$table."` (`iUin`,`iAction`,`iNum`,`sExt`,`iAddTime`,`iPlatForm`,`iType`) values ".$inert_str;//pr($sql);
            if(!$this->coupon_action_log_model->query($sql, true)){
                pr($this->coupon_action_log_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }


    public function add_bag_action_log($data){
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
            $sql = "insert into `".$table."` (`iUin`,`iBagId`,`iAction`,`iNum`,`sExtend`,`iAddTime`,`sNickName`,`sHeadImg`,`iType`) values ".$inert_str;
            if(!$this->bag_action_log_model->query($sql, true)){
                pr($this->bag_action_log_model->db->lasst_query());
                return false;
            }
        }

        return true;
    }

}