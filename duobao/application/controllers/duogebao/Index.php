<?php


class Index extends Duogebao_Base
{
    protected $need_login = true;


    public function __construct(){
        parent::__construct();
    }


    public function home()
    {
        $cache_data_key = 'activity_ranklist_data';
        $this->cache->memcached->delete($cache_data_key);
    }




    /*public function test(){
        //pr($this->user);die;
        die('kkkk');

        $user_arr = array(
            array(
                '146461051944887090',
                '144461055895378600',
                '146461052216247660',
                '149461056997197760',
                '148461049923548210',
                '140461052700930470',
                '146461050102130020',
                '145461051103624780',
                '141461052674213980',
                '142461052915909970'
            ),
            array(
                '143461049826798451',
                '143461050344813291',
                '143461050056628011',
                '146461053861330051',
                '143461050486305561',
                '146461049718154291',
                '142461050061141131',
                '147461050940018891',
                '148461050263829111',
                '149461052188616001'
            ),
            array(
                '143461050212121722',
                '149461053466551152',
                '142461053897568072',
                '145461050613692942',
                '146461049969993082',
                '142461050168124702',
                '148461050352636602',
                '148461050352636602',
            ),
            array(
                '146461052909096743',
                '143461051344342863',
                '143461051422887913',
                '149461050469440573',
                '141461050616605603',
                '148461054120031273',
                '142461052240160733',
                '146461050610860823',
            ),
            array(
                '142461049957362174',
                '146461050505556454',
                '143461053892448534',
                '146461051293986254',
                '147461058378291784',
                '145461051677265324',
                '140461054905367274',
                '149461050145321694',
                '143461054726583634',
                '144461051545327324',
                '148461049889766454',
            ),
            array(
                '141461050284930785',
                '147461053289347875',
                '147461053897536455',
                '148461050003307265',
                '145461051340790375',
                '148461053106506385',
            ),
            array(
                '140461054660955506',
                '147461052172813866',
                '141461052224314846',
                '141461057052073616',
                '143461052275646386',
                '143461051293979676',
                '147461052731549886',
                '141461052541493656',
                '141461050166868176',
                '148461052184637526',
                '145461049862836796',
                '146461058718920426',
                '142461054427206326',
                '141461050220565646',
            ),
            array(
                '144461050366044187',
                '143461052228173987',
                '144461052462909717',
                '147461050616618757',
                '144461052462909717',
                '142461058815112607',
                '143461051433789067',
                '148461050117872437',
                '141461054099637887',
                '145461051031294627',
            ),
            array(
                '149461058312577168',
                '142461055086970258',
                '145461051068727298',
                '147461050026607308',
                '149461056088390868',
                '140461049800201628',
                '140461051755462528',
                '148461057903179698',
                '144461050141534918',
            ),
            array(
                '141461050447766879',
                '148461052240155889',
                '143461053615184029',
                '142461053304158969',
                '148461051355034629',
                '146461050223128559',
                '142461051172245949',
                '148461056347302409',
                '148461056347302409',
                '146461050350110219',
                '141461051828428969',
                '140461050292885279'
            )
        );

        $this->load->model('user_model');
        $user_data = array();
        foreach($user_arr as $arr){
            foreach($arr as $uin){
                $user = $this->user_model->get_row(array('iUin'=>$uin));
                //pr($user);
                if(!$user){
                    pr($uin);
                }
                $user_data[$uin] = $user;
            }
        }

        pr(count($user_data));
        pr($user_data);die;

        //开始发消息
        //$user_data = array(array('sOpenId'=>'ooNLujkN5UHU36pJCHMCIE-69BVA'));
        foreach($user_data as $user){
            //self::batchSendNotifyInfo($user);
        }
    }*/

    public static function batchSendNotifyInfo($params){
        if(empty($params['sOpenId'])){

            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendNotifyInfo openid is empty |  params['.json_encode($params).'] | '.__METHOD__);
            return false;
        }

        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['batchSendNotifyInfo'];
        $params = array(
            'openId'=>$params['sOpenId'],
            //'template_id' => 'VQAgyRO3Th1JaOEsiCuV7eZo5Pxe0a4lUdVpcS5JIl4',
            'template_id' => 'hsKA1nEKQNfnyGjZmu_yhQ8cNBsCo9U6SQS_3wfkzQc',
            'TEMP_ID'=> $config['TEMP_ID'],
            'url'=>  'http://duogebao.gaopeng.com/duogebao/ranklist/index',                                      //消息跳转链接
            'data' => array(
                'first' => array('value'=>'倒计时30分！冲榜大奖的命运就在你手里！',"color"=>"#ff0000"),
                'keyword1' => array('value'=>'[百分好礼]充值打榜送iPhone!最后冲刺半小时！',"color"=>""),
                'keyword2' =>  array('value'=>'2016年6月23日22点',"color"=>"#ff0000"),
                'remark' => array('value'=>'最后冲刺半小时！活动晚上10点准时截止，大奖就差一步，放手一搏！如有任何问题请咨询平台客服：0755-86721139 ，竭诚为您服务！',"color"=>"")
            )
        );

        $rs = self::sendNotify($params);//发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendNotifyInfo  sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        }else{
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendNotifyInfo  sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }




    public function share_order(){
        die('=========================================================');
        $new_win_list = array(
            array('iWinnerUin' => '144461054156502091'),
            array('iWinnerUin' => '144461057027398503'),
            array('iWinnerUin' => '146461050762760758'),
            array('iWinnerUin' => '143461056978868205'),
            array('iWinnerUin' => '142461049783407996'),
            array('iWinnerUin' => '140461059665616114'),
            array('iWinnerUin' => '143461057962971399'),
            array('iWinnerUin' => '142461058912571594'),
            array('iWinnerUin' => '146461059549344771'),
            array('iWinnerUin' => '141461056001283061'),
            array('iWinnerUin' => '144461058876889240'),
            array('iWinnerUin' => '149461058035344751')
        );

        $this->load->model('user_model');
        $this->load->model('active_peroid_model');
        $win_list = $this->active_peroid_model->get_rows(array('iLotTime !='=>0,'sWinnerCode !='=>'','iWinnerUin !='=>0,'iLotState'=>2));//pr($this->active_peroid_model->db->last_query());
        $win_list = array_merge($win_list,$new_win_list);
        $user_data =  array();
        foreach($win_list as $win){
            $user = $this->user_model->get_row(array('iUin'=>$win['iWinnerUin']));
            if(!$user){
                pr($win);
                continue;
            }
            $user_data[$user['sOpenId']] = $user;
        }
        pr($user_data);//die;
        //$user_data = array(array('sOpenId'=>'ooNLujkN5UHU36pJCHMCIE-69BVA'));
        foreach($user_data as $user){
            //self::shareOrderNotifyInfo($user);
        }
    }

    public static function shareOrderNotifyInfo($params){
        if(empty($params['sOpenId'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','shareOrderNotifyInfo openid is empty |  params['.json_encode($params).'] | '.__METHOD__);
            return false;
        }

        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['batchSendNotifyInfo'];
        $params = array(
            'openId'=>$params['sOpenId'],
            //'template_id' => 'LopV-_qHOK5T5wBhQ_attXUOnDIFYsn2PQd-jtnsJcA',//开发
            'template_id' => 'qXxV8PsttnT2NPrqyjrhUlTqzxhS78lpxdICrOka5Eg',//正式
            'TEMP_ID'=> $config['TEMP_ID'],
            'url'=>  'http://duogebao.gaopeng.com/duogebao/my/active?cls=winner',                                      //消息跳转链接
            'data' => array(
                'first' => array('value'=>'送你5个夺宝券！',"color"=>"#ff0000"),
                'keyword1' => array('value'=>'晒单免费领取夺宝券',"color"=>"#ff0000"),
                'keyword2' =>  array('value'=>'5个夺宝券',"color"=>""),
                'keyword3' => array('value'=>'2016-05-17','color'=>''),
                'keyword4' => array('value'=>'1','color'=>''),
                'keyword5' => array('value'=>'1.点击下方详情进入【已中奖】页面，选择对应商品【查询中奖详情】即可去晒单（未确认收货的需点击确认收货；2.晒单需不少于30字的内容（灌水无效），并上传三张真实高清商品图；3.加微信【564755416】领取奖励！！！ ','color'=>''),
                'remark' => array('value'=>'戳我，去晒单领券',"color"=>"#ff0000")
            )
        );

        $rs = self::sendNotify($params);//发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendNotifyInfo  sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        }else{
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendNotifyInfo  sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }


    public static function sendNotify($params){
        get_instance()->config->load('pay');//加载微信配置文件
        $config = config_item('weixinConfig');
        $wx = new Lib_Weixin($config);
        $retry = false;
        $times = 3;         //如果失败，则重复提交，最多3次
        do {
            try {
                $msg = array('touser' => $params['openId'], 'template_id' => $params['template_id'], 'url' => $params['url'], 'topcolor' => '');
                Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','notify_weixin params['.json_encode($params).'] | $msg['.json_encode($msg).'] | times['.$times.'] | '.__METHOD__);
                $msg['data'] = $params['data'];
                $rs = $wx->sendTemplateMsg($msg);
                if($rs){
                    $retry = false;
                }else{
                    $retry = true;
                    Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','notify_weixin fail['.json_encode($params).'] | $msg['.json_encode($rs).'] | times['.$times.'] | '.__METHOD__);
                }
            } catch (Exception $e) {
                Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','notify_weixin exception | params['.json_encode($params).'] | error['.$e->getMessage().'] | times['.$times.'] | '.__METHOD__);
                $rs = $e->getMessage();
                $retry = true;
            }
            $times--;
        } while ($retry and $times > 0);

        return $rs;
    }
}