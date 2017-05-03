<?php
/*header("Content-type: text/html; charset=utf-8");
define('BASEPATH',__FILE__);
define('PROJECT_PATH',dirname(dirname(dirname(__FILE__))).'/application/');
require_once(PROJECT_PATH.'helpers/common_helper.php');
require_once('common/Common.php');
require_once('common/Base.php');
require_once('common/Log.php');
require_once('common/AES.php');*/

/*$user = WTG_BModel_Base::getDataByMapi('http://dev.mapi.gaopeng.com/user/address/list',array('uin'=>'2141318264268524745','type'=>'dev'));
if(is_array($user) && $user['retData']){
	$user = $user['retData'][0];
}else{
	WTG_Lib_Log::debug('User', "用户收货地址没有找到");
	//echo '没有查询到用户信息';exit;
}*/

class User extends MY_Controller
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
    }


    public function run()
    {
        set_time_limit(0);
        $start_time = time();
        $page_index = $this->get_post('page_index',1);
        $list = $this->weixin_user_model->row_list('wxid,openid,uin,nickname,headImg,sex,city,contactState,subscribeTime,userIp',$where=array('where_in'=>array('uin',array('2141085774268233613','2141531994268233817','2141071174268234057'))), $order_by=array(), $page_index, $page_size  =  1000);
        echo "===".(time()-$start_time)."====</br>";

        $insert = array();
        echo ("===============row[".count($list)."]-index[".$page_index."]-size[".$page_size."]==================</br>");
        foreach($list['list'] as $key=>$row){
            //生成新的UIN
            if(empty($row['uin']) || empty($row['openid']) || strstr($row['uin'],'Exception') !== false) continue;
            $repeat = 1;
            do{
                $uin = $this->user_model->generate_uin(4,$row['openid']);
                $table = $this->user_model->map($uin)->get_cur_table();
                $repeat++;
            }while(isset($insert[$table][$uin]) && $repeat <= 3);


            $data = array(
                'uin' => $uin,
                'openid' => '"'.$row['openid'].'"',
                'wxxid' => '"'.$row['wxid'].'"',
                'wuin' => $row['uin'],
                'eamil' => '"'.$row['openid'].'@wtg.com'.'"',
                'nickname' => empty($row['nickname']) ? '""': '"'.addslashes($row['nickname']).'"',
                'headimgurl' => empty($row['headImg']) ? '""' : '"'.$row['headImg'].'"',
                'sex' => $row['sex'],
                'city' => empty($row['city']) ? '""' : '"'.$row['city'].'"',
                'subscribe' => empty($row['contactState']) ? 0 : $row['contactState'],
                'subscribe_time' => empty($row['subscribeTime']) || $row['subscribeTime'] == '0000-00-00 00:00:00' ? 0 : strtotime($row['subscribeTime']),
                'platform' => 4,
                'regsrc' => 0,
                'regtime' => time(),
                'ip' => empty($row['userIp']) ? '""' : ip2long($row['userIp']),
                'logtime' => time()
            );
            //pr($row);pr($this->user_model->generate_uin(4,$row['uin']));pr($table);die;

            $insert[$table][$uin] = $data;
        }

        if(!$this->add_user($insert)){
            pr($page_index);die;
        }

        if($list['count'] != 0){
            //echo 'http://wtgdev.vikduo.com/tools/user/run?page_index='.$page_index+1;
            //redirect('http://wtgdev.vikduo.com/tools/user/run?page_index='.($page_index+1));
            echo $url = 'http://wtgdev.vikduo.com/tools/user/run?page_index='.($page_index+1);
            echo "<script>location.href='".$url."'</script>";
        }else{
            die('========================DONE==============================');
        }
        echo "===".(time()-$start_time)."====</br>";
    }





    protected function add_user($data)
    {
        foreach($data as $table =>$val){
            $count = count($val)-1;
            $inert_str = $ext_str = "";
            foreach($val as $k => $v){
                $inert = implode(',',$v);
                $inert = '('.$inert.')';
                $inert_str .= $k == $count ? $inert : ($inert.',');
                $ext_str .= '('.$v['uin'].'),';
            }
            $inert_str = trim($inert_str,',');
            $sql = "insert into `".$table."` (`iUin`,`sOpenId`,`sWxid`,`iWuin`,`sEmail`,`sNickName`,`sHeadImg`,`iGender`,`sCity`,`iContactState`,`iSubscribeTime`,`iRegPlatform`,`iRegSrc`,`iRegTime`,`iRegUserIp`,`iLoginTime`) values ".$inert_str;
            if(!$this->user_model->query($sql, true)){
                pr($this->user_model->db->lasst_query());
                return false;
            }

            $ext_str = trim($ext_str,',');
            $user_ext_table = 't_user_ext'.substr($table,-1);
            $this->user_ext_model->query("insert into `".$user_ext_table."` (`iUin`) values ".$ext_str, true);
        }

        return true;
    }
}