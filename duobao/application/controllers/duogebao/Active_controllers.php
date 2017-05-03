<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: alanwang
 * Date: 2016/5/31
 * Time: 16:45
 */
class Active_controllers extends Duogebao_Base
{

    public $layout_name = null;
    protected $need_login_methods = array('who_local_tyrant');


    public function __construct()
    {

        parent::__construct();
    }


    public function who_local_tyrant_result()
    {
        $this->set_wx_share('who_local_tyrant');
        $this->load->model('active_merage_order_model');
        $this->load->model('user_model');
        $this->load->service('user_service');

        $uin = $this->get_uin();    //获取用户的UIN
        $uin_str = strval($uin);    //将整型强制转换成字符串
        $last_number = substr($uin_str,-1); //获取字符串最后一位

        //根据uin获取用户基本信息
        $api_ret = $this->get_api('user_ext_info', array('uin' => $uin));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        //当前用户可使用券赋值
        if(empty($user_ext))
        {
            $coupon = 0;
            $self_name = '';
        }
        else
        {
            $coupon = $user_ext['coupon'];
            //$self_name = $user_ext[''];
        }

        $base_info_api_ret = $this->get_api('user_base_info', array('uin' => $uin));
        if ($base_info_api_ret['retCode'] == Lib_Errors::SUCC) {
            $base_info_user_ext = $base_info_api_ret['retData'];
        } else {
            $base_info_user_ext = array();
        }

        //当前用户可使用券赋值
        if(empty($base_info_user_ext))
        {

            $self_name = '';
            $self_head_img = '';
        }
        else
        {
            $self_name = $base_info_user_ext['nick_name'];
            $self_head_img = $base_info_user_ext['head_img'];
        }

        $order_list = array();
        $self_list = array();
        $self_list['self_coupon'] = $coupon;
        $self_list['self_name'] =   $self_name;
        $self_list['self_head_img'] =   $self_head_img;

        $begin_time = '1464917400'; //2016-06-03 09:30:00
        $end_time = '1465135200';   //2016-06-05 22:00:00
        $where = ' WHERE iPayStatus = 1 AND iStatus = 1 AND iPayTime > '.$begin_time.' AND iPayTime < '.$end_time.' ORDER BY iCreateTime ASC';

        //获取当前用户使用的券数量
        $self_where = ' WHERE iUin = '.$uin.' AND  iPayStatus = 1 AND iStatus = 1 AND iPayTime > '.$begin_time.' AND iPayTime < '.$end_time.' ORDER BY iCreateTime ASC';
        $self_order_db_name = 't_active_merage_order'.$last_number;
        $self_order_sql = 'SELECT iUin,iCoupon,iRefundedCoupon,iRefundingCoupon FROM '.$self_order_db_name.$self_where.' ;';
        $self_order_result_list = $this->active_merage_order_model->query($self_order_sql, true);
        $self_order_count = 0;
        foreach($self_order_result_list as $self_order)
        {
            $self_order_count = $self_order_count + ($self_order['iCoupon'] - $self_order['iRefundedCoupon'] - $self_order['iRefundedCoupon']);
        }
        $self_list['self_count'] = $self_order_count;

        $end_time_rank = strtotime('2016-06-05 22:00:00');  //结束时间
        $end_time_result = false;   //是否已经到了结束时间
        if(time() >= $end_time_rank)
        {
            $end_time_result = true;
        }
        $first_local_tyrant_cache_key = 'first_local_tyrant';
        $second_local_tyrant_cache_key = 'second_local_tyrant';
        $level_cache_key = 'level_local_tyrant';
        $first_local_tyrant_cache  = $this->cache->memcached->get($first_local_tyrant_cache_key);
        $second_local_tyrant_cache  = $this->cache->memcached->get($second_local_tyrant_cache_key);
        $level_local_tyrant_cache  = $this->cache->memcached->get($level_cache_key);


        $rank_first = rand(1,9);  //随机排名1-9
        if(!$first_local_tyrant_cache)
        {
            $rank_first = rand(5,9);
        }
        $rank_second = rand($rank_first,10);  //随机排名1-10
        $second_count = rand(1,10);
        $first_count = rand(1,10);
//        $rank_first = 2;
        //循环得到活动期间使用过券的用户
        for($order_index = 0; $order_index < 10; $order_index ++)
        {
            $order_db_name = ' t_active_merage_order'.$order_index;
            $active_merage_order_sql = 'SELECT iUin,iCoupon,iRefundedCoupon,iRefundingCoupon,iCreateTime FROM '.$order_db_name.$where.'  ;';
            $active_merage_order_result_list = $this->active_merage_order_model->query($active_merage_order_sql, true);
            foreach($active_merage_order_result_list as $active_merage_order)
            {
                $active_merage_uin = $active_merage_order['iUin'];
                if(!isset($order_list[$active_merage_uin]) && empty($order_list[$active_merage_uin]))
                {
                    $order_list[$active_merage_uin] = $active_merage_order['iCoupon'] - $active_merage_order['iRefundedCoupon'] - $active_merage_order['iRefundedCoupon'];
                }
                else
                {
                    $order_list[$active_merage_uin] = $order_list[$active_merage_uin] + $active_merage_order['iCoupon'] - $active_merage_order['iRefundedCoupon'] - $active_merage_order['iRefundedCoupon'];
                }
            }
        }

        arsort($order_list);    //排序,从高到低
        $now_time = time();
        $is_change = false;
        $rank_order_list = array();
        if($level_local_tyrant_cache)
        {
            if($now_time - $level_local_tyrant_cache['time'] >= 3600*12)
            {
                $is_change = true;
            }
        }
        else
        {
            $is_change = true;
        }
//        $is_change=  true;
//        $end_time_result = true;
        if($end_time_result)
        {
            $i_rank = 1;
            foreach($order_list as $forgery_key => $forgery_cache)
            {
                if($i_rank == 1)
                {
                    if($first_local_tyrant_cache['count'] < $forgery_cache)
                    {
                        array_push($rank_order_list,array('uin'=>'first','count'=>($forgery_cache+$first_count+$second_count)));
                        array_push($rank_order_list,array('uin'=>'second','count'=>($forgery_cache+$second_count)));
                        $first_local_tyrant_list = array('headImg'=>'','sNickName'=>'first','count'=>($forgery_cache+$first_count+$second_count),'rank'=>1);
                        $second_local_tyrant_list = array('headImg'=>'','sNickName'=>'second','count'=>($forgery_cache+$second_count),'rank'=>2);
                        $this->cache->memcached->save($first_local_tyrant_cache_key,$first_local_tyrant_list,172800);
                        $this->cache->memcached->save($second_local_tyrant_cache_key,$second_local_tyrant_list,172800);
                    }
                    else
                    {
                        array_push($rank_order_list,array('uin'=>'first','count'=>$first_local_tyrant_cache['count']));
                        array_push($rank_order_list,array('uin'=>'second','count'=>$second_local_tyrant_cache['count']));
                    }
                }
                array_push($rank_order_list,array('uin'=>$forgery_key,'count'=>$forgery_cache));
                $i_rank++;
            }
        }
        else
        {
            if($is_change)
            {
                $first_temp_count = empty($first_local_tyrant_cache['count'])?0:$first_local_tyrant_cache['count'];
                $first_result = $this->change_rank_count($rank_first,$first_temp_count,$order_list);
                if($first_result['result'])
                {
                    $i_rank = 1;
                    $first_local_tyrant_list = array();
                    foreach($order_list as $forgery_key => $forgery_cache)
                    {
                        if($rank_first == $i_rank)
                        {
                            $first_local_tyrant_list = array('headImg'=>'','sNickName'=>'first','count'=>$first_result['count'],'rank'=>$i_rank);
                            array_push($rank_order_list,array('uin'=>'first','count'=>$first_result['count']));
                            $this->cache->memcached->save($first_local_tyrant_cache_key,$first_local_tyrant_list,172800);
                        }
                        array_push($rank_order_list,array('uin'=>$forgery_key,'count'=>$forgery_cache));
                        $i_rank++;
                    }
                    $rank_order_list  = $this->restart_rank_list($first_local_tyrant_list,$order_list);
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }
                else
                {
                    $rank_order_list  = $this->restart_rank_list($first_local_tyrant_cache,$order_list);
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }

                $second_temp_count = empty($second_local_tyrant_cache['count'])?0:$second_local_tyrant_cache['count'];
                $second_result = $this->second_change_rank_count($rank_second,$second_temp_count,$rank_order_list);

                if($second_result['result'] && $rank_first != $rank_second)
                {

                    $i_rank = 1;
                    $second_temp_list = array();
                    foreach($rank_order_list as $v)
                    {
                        if($rank_second == $i_rank)
                        {
                            $second_local_tyrant_list = array('headImg'=>'','sNickName'=>'second','count'=>$second_result['count'],'rank'=>$i_rank);
                            array_push($second_temp_list,array('uin'=>'second','count'=>$second_result['count']));
                            $this->cache->memcached->save($second_local_tyrant_cache_key,$second_local_tyrant_list,172800);
                        }
                        array_push($second_temp_list,array('uin'=>$v['uin'],'count'=>$v['count']));
                        $i_rank++;
                    }
                    $rank_order_list = $second_temp_list;
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }
                else
                {
                    $rank_order_list = $this->second_restart_rank_list($second_local_tyrant_cache,$rank_order_list);
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }

            }
            else
            {
                $rank_order_list  = $this->restart_rank_list($first_local_tyrant_cache,$order_list);
                $rank_order_list = $this->second_restart_rank_list($second_local_tyrant_cache,$rank_order_list);
            }
        }

        $result = array();
        $forgery_result = array();

        $i = 1;
        foreach($rank_order_list as $key => $v)
        {

            if($v['uin'] == $uin)
            {
                $self_list['self_rank'] = $i;
            }
            $temp_arr = array();
            if($v['uin'] == 'first' || $v['uin'] == 'second')
            {
                $temp_arr['uin']    =   $v['uin'];
                $temp_arr['headImg']   =   $v['uin']=='first'?'http://imgcache.qq.com/vipstyle/tuan/duobao//indiana/images/local_tyrants/jimo.jpeg':'http://imgcache.qq.com/vipstyle/tuan/duobao//indiana/images/local_tyrants/huifei.jpg';
                $temp_arr['sNickName']  =   $v['uin']=='first'?'寂寞寻风':'会飞的冬瓜';
                $temp_arr['count']  =   $v['count'];
                array_push($forgery_result,array('sNickName'=>'','count'=>$v['count']));
            }
            else
            {
                $user = $this->user_model->get_user_by_uin(strval($v['uin']));

                $temp_arr['uin']    =   $v['uin'];
                $temp_arr['headImg']   =   $user['sHeadImg'];
                $temp_arr['sNickName']  =   $user['sNickName'];
                $temp_arr['count']  =   $v['count'];
                array_push($forgery_result,array('sNickName'=>$user['sNickName'],'count'=>$v['count']));
            }
            array_push($result,$temp_arr);
            $i++;
        }

        //如果不够10位用户,强制加入虚拟数据
        if(count($result) < 10)
        {
            $last_count = 10 - count($result);
            for($count_result = 0; $count_result < $last_count; $count_result++)
            {
                array_push($result,array('headImg'=>'http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/images/local_tyrants/default.jpg','sNickName'=>'虚位以待','count'=>0));
            }
        }

        //如果当前用户没有交易过订单,则设置为0
        if(!isset($self_list['self_rank']))
        {
            $self_list['self_rank'] = 0;
        }
        $forgery_time_rank = strtotime('2016-06-03 10:00:00');  //结束时间

        if(time() >= $forgery_time_rank)
        {
            $forgery_list = array(
                array('sNickName'=>'此夏l','count'=>10),
                array('sNickName'=>'新城邓宏','count'=>1),
                array('sNickName'=>'剑雨江湖','count'=>2),
                array('sNickName'=>'Wilsen文参','count'=>10),
                array('sNickName'=>'945725885','count'=>5),
                array('sNickName'=>'纯情小王爷','count'=>1),
                array('sNickName'=>'浅浅的丶吟唱','count'=>5),
                array('sNickName'=>'柳下惠的忧伤','count'=>1),
                array('sNickName'=>'任志久','count'=>20),
                array('sNickName'=>'草木深情','count'=>1),
                array('sNickName'=>'龚小包','count'=>1),
                array('sNickName'=>'狮子','count'=>2),
                array('sNickName'=>'sunboys','count'=>5),
                array('sNickName'=>'浅笑云灬','count'=>5),
                array('sNickName'=>'未见绉颜','count'=>1),
                array('sNickName'=>'Story故事','count'=>1),
                array('sNickName'=>'睿了睿','count'=>1),
                array('sNickName'=>'此人未被包养','count'=>1),
                array('sNickName'=>'never','count'=>2),
                array('sNickName'=>'还没睡的。吼起','count'=>1),
                array('sNickName'=>'不份手dě恋爱','count'=>1),
                array('sNickName'=>'丢三落四ぅ','count'=>3),
                array('sNickName'=>'表白.','count'=>5),
                array('sNickName'=>'难瘦°','count'=>20),
                array('sNickName'=>'你看起来很下饭','count'=>15),
                array('sNickName'=>'人生多忐忑','count'=>1),
                array('sNickName'=>'姐特坏','count'=>1),
                array('sNickName'=>'心不动，则不痛','count'=>1),
                array('sNickName'=>'咆哮。','count'=>2),
                array('sNickName'=>'厌学的小骚年','count'=>5),
                array('sNickName'=>'女汉子，怕谁','count'=>3),
                array('sNickName'=>'怀念。','count'=>4),
                array('sNickName'=>'我喜欢帅哥','count'=>10),
                array('sNickName'=>'冷暖自知','count'=>2),
                array('sNickName'=>'小温馨','count'=>11),
                array('sNickName'=>'叫我糊涂','count'=>9),
                array('sNickName'=>'哇咔咔','count'=>1),
                array('sNickName'=>'你好逗!','count'=>2),

                array('sNickName'=>'牛逼人物','count'=>10),
                array('sNickName'=>'粑粑去哪儿','count'=>1),
                array('sNickName'=>'我还好！','count'=>50),
                array('sNickName'=>'超人丶','count'=>20),
                array('sNickName'=>'当垃圾丢','count'=>10),
                array('sNickName'=>'百年茶业','count'=>1),
                array('sNickName'=>'Married ?','count'=>5),
                array('sNickName'=>'wolf','count'=>1),
                array('sNickName'=>'Growl@','count'=>20),
                array('sNickName'=>'旧同桌的你','count'=>1),
                array('sNickName'=>'逗比女王','count'=>1),
                array('sNickName'=>'小钢铁','count'=>2),
                array('sNickName'=>'鲜花配绿叶','count'=>5),
                array('sNickName'=>'zero','count'=>5),
                array('sNickName'=>'six','count'=>1),
                array('sNickName'=>'seven','count'=>1),
                array('sNickName'=>'Alice','count'=>1),
                array('sNickName'=>'土豆小姐','count'=>1),
                array('sNickName'=>'爱吃麻辣烫','count'=>2),
                array('sNickName'=>'不吃香菜的猴子','count'=>8),
                array('sNickName'=>'不吃兔兔','count'=>12),
                array('sNickName'=>'猫爷','count'=>3),
                array('sNickName'=>'悉数沉淀.','count'=>5),
                array('sNickName'=>'暖寄归人','count'=>20),
                array('sNickName'=>'瞎闹腾i','count'=>15),
                array('sNickName'=>'独美i ','count'=>1),
                array('sNickName'=>'厌世症i','count'=>1),
                array('sNickName'=>'人心可畏','count'=>1),
                array('sNickName'=>'你真逗比','count'=>2),
                array('sNickName'=>'前凸后翘','count'=>5),
                array('sNickName'=>'可喜可乐','count'=>3),
                array('sNickName'=>'以心换心。','count'=>4),
                array('sNickName'=>'或许','count'=>10),
                array('sNickName'=>'渣中王','count'=>2),
                array('sNickName'=>'一干为尽','count'=>11),
                array('sNickName'=>'你的愚忠','count'=>9),
                array('sNickName'=>'就是任性','count'=>1),
                array('sNickName'=>'缺氧患人！!','count'=>2),


                array('sNickName'=>'住进时光里','count'=>10),
                array('sNickName'=>'难免心酸°','count'=>1),
                array('sNickName'=>'只为你生！','count'=>50),
                array('sNickName'=>'前后都是你','count'=>20),
                array('sNickName'=>'陌离女王','count'=>10),
                array('sNickName'=>'缺我也没差','count'=>1),
                array('sNickName'=>'十年温如初','count'=>5),
                array('sNickName'=>'闹够了就滚','count'=>1),
                array('sNickName'=>'单身女王','count'=>20),
                array('sNickName'=>'我心透心凉','count'=>1),
                array('sNickName'=>'有钱就是任性','count'=>1),
                array('sNickName'=>'爱情就是难题','count'=>2),
                array('sNickName'=>'沁月沫璇55','count'=>5),
                array('sNickName'=>'谢希比','count'=>5),
                array('sNickName'=>'给个响亮名字','count'=>1),
                array('sNickName'=>'欢呼声天枰','count'=>1),
                array('sNickName'=>'小悠悠','count'=>1),
                array('sNickName'=>'贫道随云子','count'=>1),
                array('sNickName'=>'huangiroi','count'=>2),
                array('sNickName'=>'1罗船长1','count'=>8),
                array('sNickName'=>'a939824956','count'=>12),
                array('sNickName'=>'夜小贱520','count'=>3),
                array('sNickName'=>'我最love萝莉.','count'=>5),
                array('sNickName'=>'简爱依楠参','count'=>20),
                array('sNickName'=>'mask0809','count'=>15),
                array('sNickName'=>'卡牌大湿','count'=>1),
                array('sNickName'=>'捂住灵魂傲娇','count'=>1),
                array('sNickName'=>'失心的坏小孩','count'=>1),
                array('sNickName'=>'wing孤独的总和','count'=>2),
                array('sNickName'=>'苦涩虾米','count'=>5),
                array('sNickName'=>'水瓶奋斗的前程','count'=>3),
                array('sNickName'=>'合衬欧尼','count'=>4),
                array('sNickName'=>'习惯沉默65','count'=>10),
                array('sNickName'=>'星际GM','count'=>2),
                array('sNickName'=>'you诚','count'=>11),
                array('sNickName'=>'velin2013','count'=>9),
                array('sNickName'=>'美美名品店','count'=>1),
                array('sNickName'=>'鱼粑粑打鱼','count'=>2),

                array('sNickName'=>'孙氏家族','count'=>10),
                array('sNickName'=>'不ai笑的鱼','count'=>1),
                array('sNickName'=>'萝卜辉辉','count'=>50),
                array('sNickName'=>'我是大馒头c','count'=>20),
                array('sNickName'=>'小草帽咯咯','count'=>10),
                array('sNickName'=>'漠河以北','count'=>1),
                array('sNickName'=>'为你折的纸玫瑰','count'=>5),
                array('sNickName'=>'宫野沫欣','count'=>1),
                array('sNickName'=>'火柴214','count'=>20),
                array('sNickName'=>'付忻人','count'=>1),
                array('sNickName'=>'夕微娅','count'=>50),
                array('sNickName'=>'旧梦乱人心','count'=>2),
                array('sNickName'=>'反正你那么爱她','count'=>5),
                array('sNickName'=>'大皇帝','count'=>5),
                array('sNickName'=>'高美麒','count'=>30),
                array('sNickName'=>'陌怨人殇','count'=>1),
                array('sNickName'=>'暗殘殤','count'=>1),
                array('sNickName'=>'爸','count'=>1),
                array('sNickName'=>'Gambler鈩','count'=>2),
                array('sNickName'=>'莫若玥肆','count'=>8),
                array('sNickName'=>'1罗船长1','count'=>12),
                array('sNickName'=>'程翰文','count'=>3),
                array('sNickName'=>'亿年梦回眸.','count'=>5),
                array('sNickName'=>'薇尔利特','count'=>20),
                array('sNickName'=>'左耳边de轻语','count'=>15),
                array('sNickName'=>'猩猩嘿嘿嘿','count'=>1),
                array('sNickName'=>'寻路e_','count'=>1),
                array('sNickName'=>'彼岸花殇恋','count'=>1),
                array('sNickName'=>'稚琦1997','count'=>2),
                array('sNickName'=>'木叶清岩','count'=>5),
                array('sNickName'=>'雨后的彩虹之恋','count'=>3),
                array('sNickName'=>'华衣姑娘','count'=>4),
                array('sNickName'=>'飘渺繁寂','count'=>10),
                array('sNickName'=>'零落刹那芳华','count'=>2),
                array('sNickName'=>'馨宠儿yoyo','count'=>11),
                array('sNickName'=>'夕夏ST','count'=>9),
                array('sNickName'=>'阿漆柒','count'=>1),
                array('sNickName'=>'暗香疏影xxy','count'=>2),

                array('sNickName'=>'君影芦铃','count'=>10),
                array('sNickName'=>'福城小猫','count'=>1),
                array('sNickName'=>'歆天然纯','count'=>50),
                array('sNickName'=>'彧以长安','count'=>20),
                array('sNickName'=>'解冻的心','count'=>10),
                array('sNickName'=>'兢瀞的等待','count'=>1),
                array('sNickName'=>'橘子罐头','count'=>5),
                array('sNickName'=>'唯念倾颜','count'=>1),
                array('sNickName'=>'天羚燕','count'=>20),
                array('sNickName'=>'尸屿i','count'=>1),
                array('sNickName'=>'小诺夕柔','count'=>1),
                array('sNickName'=>'飞雪绝代','count'=>2),
                array('sNickName'=>'荏斐','count'=>5),
                array('sNickName'=>'叶鸣乌晗','count'=>5),
                array('sNickName'=>'梅长歌','count'=>1),
                array('sNickName'=>'妍卿Lolita','count'=>1),
                array('sNickName'=>'镜栀雪I','count'=>1),
                array('sNickName'=>'七七小梧桐:','count'=>1),
                array('sNickName'=>'荒城旧日','count'=>2),
                array('sNickName'=>'安笙凉城','count'=>8),
                array('sNickName'=>'离人心上秋','count'=>12),
                array('sNickName'=>'何如旧颜','count'=>3),
                array('sNickName'=>'闹剧。','count'=>5),
                array('sNickName'=>'国名小逗比','count'=>20),
                array('sNickName'=>'乐意','count'=>15),
                array('sNickName'=>'你会腻我何必','count'=>1),
                array('sNickName'=>'钻石女王心','count'=>1),
                array('sNickName'=>'枪蹦','count'=>1),
                array('sNickName'=>'霸花','count'=>2),
                array('sNickName'=>'适合','count'=>5),
                array('sNickName'=>'王学长','count'=>3),
                array('sNickName'=>'我带我飞','count'=>4),
                array('sNickName'=>'刘海参','count'=>10),
                array('sNickName'=>'深爱','count'=>2),
                array('sNickName'=>'笑i','count'=>11),
                array('sNickName'=>'久伴','count'=>9),
                array('sNickName'=>'怎样自在怎样活','count'=>1),
                array('sNickName'=>'为梦喧闹','count'=>2),

                array('sNickName'=>'纯','count'=>10),
                array('sNickName'=>'你不懂','count'=>1),
                array('sNickName'=>'辈子','count'=>50),
                array('sNickName'=>'灵魂深处','count'=>20),
                array('sNickName'=>'众人皆醉','count'=>10),
                array('sNickName'=>'谋杀','count'=>1),
                array('sNickName'=>'妲己再美终是妃','count'=>5),
                array('sNickName'=>'不再眠心','count'=>1),
                array('sNickName'=>'女人无情','count'=>20),
                array('sNickName'=>'腐朽年華','count'=>1),
                array('sNickName'=>'你是我的幸运儿2','count'=>50),
                array('sNickName'=>'花花花、小伙','count'=>2),
                array('sNickName'=>'Au revoir','count'=>5),
                array('sNickName'=>'墨尔本','count'=>5),
                array('sNickName'=>'玩的是命','count'=>30),
                array('sNickName'=>'笑','count'=>1),
                array('sNickName'=>'一半眼线','count'=>1),
                array('sNickName'=>'这样就好','count'=>1),
                array('sNickName'=>'豆芽菜','count'=>2),
                array('sNickName'=>'過客','count'=>8),
                array('sNickName'=>'無盡透明的思念','count'=>12),
                array('sNickName'=>'solo','count'=>3),
                array('sNickName'=>'忘了他.','count'=>5),
                array('sNickName'=>'粉红。顽皮豹','count'=>20),
                array('sNickName'=>'小心翼翼','count'=>15),
                array('sNickName'=>'別敷衍','count'=>1),
                array('sNickName'=>'恋人爱成路人','count'=>1),
                array('sNickName'=>'昔日餘光。','count'=>1),
                array('sNickName'=>'放肆','count'=>2),
                array('sNickName'=>'今非昔比','count'=>5),
                array('sNickName'=>'无名指','count'=>3),
                array('sNickName'=>'莫名的青春','count'=>4),
                array('sNickName'=>'一抹丶苍白','count'=>10),
                array('sNickName'=>'笑叹尘世美','count'=>2),
                array('sNickName'=>'爱你心口难开','count'=>11),
                array('sNickName'=>'那傷。眞美','count'=>9),
                array('sNickName'=>'命運不堪浮華','count'=>1),
                array('sNickName'=>'爱被冰凝固','count'=>2),
                array('sNickName'=>'一生承诺','count'=>1),
                array('sNickName'=>'行尸走肉','count'=>2),
            );
        }
        else
        {
            $forgery_list = array();
        }
        //伪造弹幕数据

        $forgery = array_merge($forgery_result,$forgery_list);  //合并虚拟数据和真实数据
        $forgery_count = count($forgery);
        shuffle($forgery);
        $end_count = ceil($forgery_count/2)-1;
        $begin_list = array_slice($forgery,0,$end_count);
        $end_list = array_slice($forgery,$end_count,$forgery_count);

        print_r($result[0]);
        echo '</br>';
        print_r($result[1]);
        echo '</br>';
        print_r($result[2]);
        echo '</br>';
        print_r($result[3]);
        echo '</br>';
        print_r($result[4]);
        echo '</br>';
        print_r($result[5]);
        echo '</br>';
        print_r($result[6]);
        echo '</br>';
        print_r($result[7]);
        echo '</br>';
        print_r($result[8]);
        echo '</br>';
        print_r($result[9]);
        echo '</br>';
        exit;
        $this->assign('self_list', $self_list);
        $this->assign('result_list', $result);
        $this->assign('begin_list', $begin_list);
        $this->assign('end_list', $end_list);
        $this->render(array(),'active_controllers/who_local_tyrant');
    }

    /**
     * 谁是土豪活动入口
     */
    public function who_local_tyrant()
    {
        $this->set_wx_share('who_local_tyrant');
        $this->load->model('active_merage_order_model');
        $this->load->model('user_model');
        $this->load->service('user_service');

        $uin = $this->get_uin();    //获取用户的UIN
        $uin_str = strval($uin);    //将整型强制转换成字符串
        $last_number = substr($uin_str,-1); //获取字符串最后一位

        //根据uin获取用户基本信息
        $api_ret = $this->get_api('user_ext_info', array('uin' => $uin));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        //当前用户可使用券赋值
        if(empty($user_ext))
        {
            $coupon = 0;
            $self_name = '';
        }
        else
        {
            $coupon = $user_ext['coupon'];
            //$self_name = $user_ext[''];
        }

        $base_info_api_ret = $this->get_api('user_base_info', array('uin' => $uin));
        if ($base_info_api_ret['retCode'] == Lib_Errors::SUCC) {
            $base_info_user_ext = $base_info_api_ret['retData'];
        } else {
            $base_info_user_ext = array();
        }

        //当前用户可使用券赋值
        if(empty($base_info_user_ext))
        {

            $self_name = '';
            $self_head_img = '';
        }
        else
        {
            $self_name = $base_info_user_ext['nick_name'];
            $self_head_img = $base_info_user_ext['head_img'];
        }

        $order_list = array();
        $self_list = array();
        $self_list['self_coupon'] = $coupon;
        $self_list['self_name'] =   $self_name;
        $self_list['self_head_img'] =   $self_head_img;

        $begin_time = '1464917400'; //2016-06-03 09:30:00
        $end_time = '1465135200';   //2016-06-05 22:00:00
        $where = ' WHERE iPayStatus = 1 AND iStatus = 1 AND iPayTime > '.$begin_time.' AND iPayTime < '.$end_time.' ORDER BY iCreateTime ASC';

        //获取当前用户使用的券数量
        $self_where = ' WHERE iUin = '.$uin.' AND  iPayStatus = 1 AND iStatus = 1 AND iPayTime > '.$begin_time.' AND iPayTime < '.$end_time.' ORDER BY iCreateTime ASC';
        $self_order_db_name = 't_active_merage_order'.$last_number;
        $self_order_sql = 'SELECT iUin,iCoupon,iRefundedCoupon,iRefundingCoupon FROM '.$self_order_db_name.$self_where.' ;';
        $self_order_result_list = $this->active_merage_order_model->query($self_order_sql, true);
        $self_order_count = 0;
        foreach($self_order_result_list as $self_order)
        {
            $self_order_count = $self_order_count + ($self_order['iCoupon'] - $self_order['iRefundedCoupon'] - $self_order['iRefundedCoupon']);
        }
        $self_list['self_count'] = $self_order_count;

        $end_time_rank = strtotime('2016-06-05 22:00:00');  //结束时间
        $end_time_result = false;   //是否已经到了结束时间
        if(time() >= $end_time_rank)
        {
            $end_time_result = true;
        }
        $first_local_tyrant_cache_key = 'first_local_tyrant';
        $second_local_tyrant_cache_key = 'second_local_tyrant';
        $level_cache_key = 'level_local_tyrant';
        $first_local_tyrant_cache  = $this->cache->memcached->get($first_local_tyrant_cache_key);
        $second_local_tyrant_cache  = $this->cache->memcached->get($second_local_tyrant_cache_key);
        $level_local_tyrant_cache  = $this->cache->memcached->get($level_cache_key);


        $rank_first = rand(1,9);  //随机排名1-9
        if(!$first_local_tyrant_cache)
        {
            $rank_first = rand(5,9);
        }
        $rank_second = rand($rank_first,10);  //随机排名1-10
        $second_count = rand(1,10);
        $first_count = rand(1,10);
//        $rank_first = 2;
        //循环得到活动期间使用过券的用户
        for($order_index = 0; $order_index < 10; $order_index ++)
        {
            $order_db_name = ' t_active_merage_order'.$order_index;
            $active_merage_order_sql = 'SELECT iUin,iCoupon,iRefundedCoupon,iRefundingCoupon,iCreateTime FROM '.$order_db_name.$where.'  ;';
            $active_merage_order_result_list = $this->active_merage_order_model->query($active_merage_order_sql, true);
            foreach($active_merage_order_result_list as $active_merage_order)
            {
                $active_merage_uin = $active_merage_order['iUin'];
                if(!isset($order_list[$active_merage_uin]) && empty($order_list[$active_merage_uin]))
                {
                    $order_list[$active_merage_uin] = $active_merage_order['iCoupon'] - $active_merage_order['iRefundedCoupon'] - $active_merage_order['iRefundedCoupon'];
                }
                else
                {
                    $order_list[$active_merage_uin] = $order_list[$active_merage_uin] + $active_merage_order['iCoupon'] - $active_merage_order['iRefundedCoupon'] - $active_merage_order['iRefundedCoupon'];
                }
            }
        }

        arsort($order_list);    //排序,从高到低
        $now_time = time();
        $is_change = false;
        $rank_order_list = array();
        if($level_local_tyrant_cache)
        {
            if($now_time - $level_local_tyrant_cache['time'] >= 3600*12)
            {
                $is_change = true;
            }
        }
        else
        {
            $is_change = true;
        }
//        $is_change=  true;
//        $end_time_result = true;
        if($end_time_result)
        {
            $i_rank = 1;
            foreach($order_list as $forgery_key => $forgery_cache)
            {
                if($i_rank == 1)
                {
                    if($first_local_tyrant_cache['count'] < $forgery_cache)
                    {
                        array_push($rank_order_list,array('uin'=>'first','count'=>($forgery_cache+$first_count+$second_count)));
                        array_push($rank_order_list,array('uin'=>'second','count'=>($forgery_cache+$second_count)));
                        $first_local_tyrant_list = array('headImg'=>'','sNickName'=>'first','count'=>($forgery_cache+$first_count+$second_count),'rank'=>1);
                        $second_local_tyrant_list = array('headImg'=>'','sNickName'=>'second','count'=>($forgery_cache+$second_count),'rank'=>2);
                        $this->cache->memcached->save($first_local_tyrant_cache_key,$first_local_tyrant_list,172800);
                        $this->cache->memcached->save($second_local_tyrant_cache_key,$second_local_tyrant_list,172800);
                    }
                    else
                    {
                        array_push($rank_order_list,array('uin'=>'first','count'=>$first_local_tyrant_cache['count']));
                        array_push($rank_order_list,array('uin'=>'second','count'=>$second_local_tyrant_cache['count']));
                    }
                }
                array_push($rank_order_list,array('uin'=>$forgery_key,'count'=>$forgery_cache));
                $i_rank++;
            }
        }
        else
        {
            if($is_change)
            {
                $first_temp_count = empty($first_local_tyrant_cache['count'])?0:$first_local_tyrant_cache['count'];
                $first_result = $this->change_rank_count($rank_first,$first_temp_count,$order_list);
                if($first_result['result'])
                {
                    $i_rank = 1;
                    $first_local_tyrant_list = array();
                    foreach($order_list as $forgery_key => $forgery_cache)
                    {
                        if($rank_first == $i_rank)
                        {
                            $first_local_tyrant_list = array('headImg'=>'','sNickName'=>'first','count'=>$first_result['count'],'rank'=>$i_rank);
                            array_push($rank_order_list,array('uin'=>'first','count'=>$first_result['count']));
                            $this->cache->memcached->save($first_local_tyrant_cache_key,$first_local_tyrant_list,172800);
                        }
                        array_push($rank_order_list,array('uin'=>$forgery_key,'count'=>$forgery_cache));
                        $i_rank++;
                    }
                    $rank_order_list  = $this->restart_rank_list($first_local_tyrant_list,$order_list);
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }
                else
                {
                    $rank_order_list  = $this->restart_rank_list($first_local_tyrant_cache,$order_list);
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }

                $second_temp_count = empty($second_local_tyrant_cache['count'])?0:$second_local_tyrant_cache['count'];
                $second_result = $this->second_change_rank_count($rank_second,$second_temp_count,$rank_order_list);

                if($second_result['result'] && $rank_first != $rank_second)
                {

                    $i_rank = 1;
                    $second_temp_list = array();
                    foreach($rank_order_list as $v)
                    {
                        if($rank_second == $i_rank)
                        {
                            $second_local_tyrant_list = array('headImg'=>'','sNickName'=>'second','count'=>$second_result['count'],'rank'=>$i_rank);
                            array_push($second_temp_list,array('uin'=>'second','count'=>$second_result['count']));
                            $this->cache->memcached->save($second_local_tyrant_cache_key,$second_local_tyrant_list,172800);
                        }
                        array_push($second_temp_list,array('uin'=>$v['uin'],'count'=>$v['count']));
                        $i_rank++;
                    }
                    $rank_order_list = $second_temp_list;
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }
                else
                {
                    $rank_order_list = $this->second_restart_rank_list($second_local_tyrant_cache,$rank_order_list);
                    $this->cache->memcached->save($level_cache_key,array('time'=>time()),172800);
                }

            }
            else
            {
                $rank_order_list  = $this->restart_rank_list($first_local_tyrant_cache,$order_list);
                $rank_order_list = $this->second_restart_rank_list($second_local_tyrant_cache,$rank_order_list);
            }
        }



        $result = array();
        $forgery_result = array();

        $i = 1;
        foreach($rank_order_list as $key => $v)
        {

            if($v['uin'] == $uin)
            {
                $self_list['self_rank'] = $i;
            }
            $temp_arr = array();
            if($v['uin'] == 'first' || $v['uin'] == 'second')
            {
                $temp_arr['uin']    =   $v['uin'];
                $temp_arr['headImg']   =   $v['uin']=='first'?'http://imgcache.qq.com/vipstyle/tuan/duobao//indiana/images/local_tyrants/jimo.jpeg':'http://imgcache.qq.com/vipstyle/tuan/duobao//indiana/images/local_tyrants/huifei.jpg';
                $temp_arr['sNickName']  =   $v['uin']=='first'?'寂寞寻风':'会飞的冬瓜';
                $temp_arr['count']  =   $v['count'];
                array_push($forgery_result,array('sNickName'=>'','count'=>$v['count']));
            }
            else
            {
                $user = $this->user_model->get_user_by_uin(strval($v['uin']));

                $temp_arr['uin']    =   $v['uin'];
                $temp_arr['headImg']   =   $user['sHeadImg'];
                $temp_arr['sNickName']  =   $user['sNickName'];
                $temp_arr['count']  =   $v['count'];
                array_push($forgery_result,array('sNickName'=>$user['sNickName'],'count'=>$v['count']));
            }
            array_push($result,$temp_arr);
            $i++;
        }

        //如果不够10位用户,强制加入虚拟数据
        if(count($result) < 10)
        {
            $last_count = 10 - count($result);
            for($count_result = 0; $count_result < $last_count; $count_result++)
            {
                array_push($result,array('headImg'=>'http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/images/local_tyrants/default.jpg','sNickName'=>'虚位以待','count'=>0));
            }
        }

        //如果当前用户没有交易过订单,则设置为0
        if(!isset($self_list['self_rank']))
        {
            $self_list['self_rank'] = 0;
        }
        $forgery_time_rank = strtotime('2016-06-03 10:00:00');  //结束时间

        if(time() >= $forgery_time_rank)
        {
            $forgery_list = array(
                array('sNickName'=>'此夏l','count'=>10),
                array('sNickName'=>'新城邓宏','count'=>1),
                array('sNickName'=>'剑雨江湖','count'=>2),
                array('sNickName'=>'Wilsen文参','count'=>10),
                array('sNickName'=>'945725885','count'=>5),
                array('sNickName'=>'纯情小王爷','count'=>1),
                array('sNickName'=>'浅浅的丶吟唱','count'=>5),
                array('sNickName'=>'柳下惠的忧伤','count'=>1),
                array('sNickName'=>'任志久','count'=>20),
                array('sNickName'=>'草木深情','count'=>1),
                array('sNickName'=>'龚小包','count'=>1),
                array('sNickName'=>'狮子','count'=>2),
                array('sNickName'=>'sunboys','count'=>5),
                array('sNickName'=>'浅笑云灬','count'=>5),
                array('sNickName'=>'未见绉颜','count'=>1),
                array('sNickName'=>'Story故事','count'=>1),
                array('sNickName'=>'睿了睿','count'=>1),
                array('sNickName'=>'此人未被包养','count'=>1),
                array('sNickName'=>'never','count'=>2),
                array('sNickName'=>'还没睡的。吼起','count'=>1),
                array('sNickName'=>'不份手dě恋爱','count'=>1),
                array('sNickName'=>'丢三落四ぅ','count'=>3),
                array('sNickName'=>'表白.','count'=>5),
                array('sNickName'=>'难瘦°','count'=>20),
                array('sNickName'=>'你看起来很下饭','count'=>15),
                array('sNickName'=>'人生多忐忑','count'=>1),
                array('sNickName'=>'姐特坏','count'=>1),
                array('sNickName'=>'心不动，则不痛','count'=>1),
                array('sNickName'=>'咆哮。','count'=>2),
                array('sNickName'=>'厌学的小骚年','count'=>5),
                array('sNickName'=>'女汉子，怕谁','count'=>3),
                array('sNickName'=>'怀念。','count'=>4),
                array('sNickName'=>'我喜欢帅哥','count'=>10),
                array('sNickName'=>'冷暖自知','count'=>2),
                array('sNickName'=>'小温馨','count'=>11),
                array('sNickName'=>'叫我糊涂','count'=>9),
                array('sNickName'=>'哇咔咔','count'=>1),
                array('sNickName'=>'你好逗!','count'=>2),

                array('sNickName'=>'牛逼人物','count'=>10),
                array('sNickName'=>'粑粑去哪儿','count'=>1),
                array('sNickName'=>'我还好！','count'=>50),
                array('sNickName'=>'超人丶','count'=>20),
                array('sNickName'=>'当垃圾丢','count'=>10),
                array('sNickName'=>'百年茶业','count'=>1),
                array('sNickName'=>'Married ?','count'=>5),
                array('sNickName'=>'wolf','count'=>1),
                array('sNickName'=>'Growl@','count'=>20),
                array('sNickName'=>'旧同桌的你','count'=>1),
                array('sNickName'=>'逗比女王','count'=>1),
                array('sNickName'=>'小钢铁','count'=>2),
                array('sNickName'=>'鲜花配绿叶','count'=>5),
                array('sNickName'=>'zero','count'=>5),
                array('sNickName'=>'six','count'=>1),
                array('sNickName'=>'seven','count'=>1),
                array('sNickName'=>'Alice','count'=>1),
                array('sNickName'=>'土豆小姐','count'=>1),
                array('sNickName'=>'爱吃麻辣烫','count'=>2),
                array('sNickName'=>'不吃香菜的猴子','count'=>8),
                array('sNickName'=>'不吃兔兔','count'=>12),
                array('sNickName'=>'猫爷','count'=>3),
                array('sNickName'=>'悉数沉淀.','count'=>5),
                array('sNickName'=>'暖寄归人','count'=>20),
                array('sNickName'=>'瞎闹腾i','count'=>15),
                array('sNickName'=>'独美i ','count'=>1),
                array('sNickName'=>'厌世症i','count'=>1),
                array('sNickName'=>'人心可畏','count'=>1),
                array('sNickName'=>'你真逗比','count'=>2),
                array('sNickName'=>'前凸后翘','count'=>5),
                array('sNickName'=>'可喜可乐','count'=>3),
                array('sNickName'=>'以心换心。','count'=>4),
                array('sNickName'=>'或许','count'=>10),
                array('sNickName'=>'渣中王','count'=>2),
                array('sNickName'=>'一干为尽','count'=>11),
                array('sNickName'=>'你的愚忠','count'=>9),
                array('sNickName'=>'就是任性','count'=>1),
                array('sNickName'=>'缺氧患人！!','count'=>2),


                array('sNickName'=>'住进时光里','count'=>10),
                array('sNickName'=>'难免心酸°','count'=>1),
                array('sNickName'=>'只为你生！','count'=>50),
                array('sNickName'=>'前后都是你','count'=>20),
                array('sNickName'=>'陌离女王','count'=>10),
                array('sNickName'=>'缺我也没差','count'=>1),
                array('sNickName'=>'十年温如初','count'=>5),
                array('sNickName'=>'闹够了就滚','count'=>1),
                array('sNickName'=>'单身女王','count'=>20),
                array('sNickName'=>'我心透心凉','count'=>1),
                array('sNickName'=>'有钱就是任性','count'=>1),
                array('sNickName'=>'爱情就是难题','count'=>2),
                array('sNickName'=>'沁月沫璇55','count'=>5),
                array('sNickName'=>'谢希比','count'=>5),
                array('sNickName'=>'给个响亮名字','count'=>1),
                array('sNickName'=>'欢呼声天枰','count'=>1),
                array('sNickName'=>'小悠悠','count'=>1),
                array('sNickName'=>'贫道随云子','count'=>1),
                array('sNickName'=>'huangiroi','count'=>2),
                array('sNickName'=>'1罗船长1','count'=>8),
                array('sNickName'=>'a939824956','count'=>12),
                array('sNickName'=>'夜小贱520','count'=>3),
                array('sNickName'=>'我最love萝莉.','count'=>5),
                array('sNickName'=>'简爱依楠参','count'=>20),
                array('sNickName'=>'mask0809','count'=>15),
                array('sNickName'=>'卡牌大湿','count'=>1),
                array('sNickName'=>'捂住灵魂傲娇','count'=>1),
                array('sNickName'=>'失心的坏小孩','count'=>1),
                array('sNickName'=>'wing孤独的总和','count'=>2),
                array('sNickName'=>'苦涩虾米','count'=>5),
                array('sNickName'=>'水瓶奋斗的前程','count'=>3),
                array('sNickName'=>'合衬欧尼','count'=>4),
                array('sNickName'=>'习惯沉默65','count'=>10),
                array('sNickName'=>'星际GM','count'=>2),
                array('sNickName'=>'you诚','count'=>11),
                array('sNickName'=>'velin2013','count'=>9),
                array('sNickName'=>'美美名品店','count'=>1),
                array('sNickName'=>'鱼粑粑打鱼','count'=>2),

                array('sNickName'=>'孙氏家族','count'=>10),
                array('sNickName'=>'不ai笑的鱼','count'=>1),
                array('sNickName'=>'萝卜辉辉','count'=>50),
                array('sNickName'=>'我是大馒头c','count'=>20),
                array('sNickName'=>'小草帽咯咯','count'=>10),
                array('sNickName'=>'漠河以北','count'=>1),
                array('sNickName'=>'为你折的纸玫瑰','count'=>5),
                array('sNickName'=>'宫野沫欣','count'=>1),
                array('sNickName'=>'火柴214','count'=>20),
                array('sNickName'=>'付忻人','count'=>1),
                array('sNickName'=>'夕微娅','count'=>50),
                array('sNickName'=>'旧梦乱人心','count'=>2),
                array('sNickName'=>'反正你那么爱她','count'=>5),
                array('sNickName'=>'大皇帝','count'=>5),
                array('sNickName'=>'高美麒','count'=>30),
                array('sNickName'=>'陌怨人殇','count'=>1),
                array('sNickName'=>'暗殘殤','count'=>1),
                array('sNickName'=>'爸','count'=>1),
                array('sNickName'=>'Gambler鈩','count'=>2),
                array('sNickName'=>'莫若玥肆','count'=>8),
                array('sNickName'=>'1罗船长1','count'=>12),
                array('sNickName'=>'程翰文','count'=>3),
                array('sNickName'=>'亿年梦回眸.','count'=>5),
                array('sNickName'=>'薇尔利特','count'=>20),
                array('sNickName'=>'左耳边de轻语','count'=>15),
                array('sNickName'=>'猩猩嘿嘿嘿','count'=>1),
                array('sNickName'=>'寻路e_','count'=>1),
                array('sNickName'=>'彼岸花殇恋','count'=>1),
                array('sNickName'=>'稚琦1997','count'=>2),
                array('sNickName'=>'木叶清岩','count'=>5),
                array('sNickName'=>'雨后的彩虹之恋','count'=>3),
                array('sNickName'=>'华衣姑娘','count'=>4),
                array('sNickName'=>'飘渺繁寂','count'=>10),
                array('sNickName'=>'零落刹那芳华','count'=>2),
                array('sNickName'=>'馨宠儿yoyo','count'=>11),
                array('sNickName'=>'夕夏ST','count'=>9),
                array('sNickName'=>'阿漆柒','count'=>1),
                array('sNickName'=>'暗香疏影xxy','count'=>2),

                array('sNickName'=>'君影芦铃','count'=>10),
                array('sNickName'=>'福城小猫','count'=>1),
                array('sNickName'=>'歆天然纯','count'=>50),
                array('sNickName'=>'彧以长安','count'=>20),
                array('sNickName'=>'解冻的心','count'=>10),
                array('sNickName'=>'兢瀞的等待','count'=>1),
                array('sNickName'=>'橘子罐头','count'=>5),
                array('sNickName'=>'唯念倾颜','count'=>1),
                array('sNickName'=>'天羚燕','count'=>20),
                array('sNickName'=>'尸屿i','count'=>1),
                array('sNickName'=>'小诺夕柔','count'=>1),
                array('sNickName'=>'飞雪绝代','count'=>2),
                array('sNickName'=>'荏斐','count'=>5),
                array('sNickName'=>'叶鸣乌晗','count'=>5),
                array('sNickName'=>'梅长歌','count'=>1),
                array('sNickName'=>'妍卿Lolita','count'=>1),
                array('sNickName'=>'镜栀雪I','count'=>1),
                array('sNickName'=>'七七小梧桐:','count'=>1),
                array('sNickName'=>'荒城旧日','count'=>2),
                array('sNickName'=>'安笙凉城','count'=>8),
                array('sNickName'=>'离人心上秋','count'=>12),
                array('sNickName'=>'何如旧颜','count'=>3),
                array('sNickName'=>'闹剧。','count'=>5),
                array('sNickName'=>'国名小逗比','count'=>20),
                array('sNickName'=>'乐意','count'=>15),
                array('sNickName'=>'你会腻我何必','count'=>1),
                array('sNickName'=>'钻石女王心','count'=>1),
                array('sNickName'=>'枪蹦','count'=>1),
                array('sNickName'=>'霸花','count'=>2),
                array('sNickName'=>'适合','count'=>5),
                array('sNickName'=>'王学长','count'=>3),
                array('sNickName'=>'我带我飞','count'=>4),
                array('sNickName'=>'刘海参','count'=>10),
                array('sNickName'=>'深爱','count'=>2),
                array('sNickName'=>'笑i','count'=>11),
                array('sNickName'=>'久伴','count'=>9),
                array('sNickName'=>'怎样自在怎样活','count'=>1),
                array('sNickName'=>'为梦喧闹','count'=>2),

                array('sNickName'=>'纯','count'=>10),
                array('sNickName'=>'你不懂','count'=>1),
                array('sNickName'=>'辈子','count'=>50),
                array('sNickName'=>'灵魂深处','count'=>20),
                array('sNickName'=>'众人皆醉','count'=>10),
                array('sNickName'=>'谋杀','count'=>1),
                array('sNickName'=>'妲己再美终是妃','count'=>5),
                array('sNickName'=>'不再眠心','count'=>1),
                array('sNickName'=>'女人无情','count'=>20),
                array('sNickName'=>'腐朽年華','count'=>1),
                array('sNickName'=>'你是我的幸运儿2','count'=>50),
                array('sNickName'=>'花花花、小伙','count'=>2),
                array('sNickName'=>'Au revoir','count'=>5),
                array('sNickName'=>'墨尔本','count'=>5),
                array('sNickName'=>'玩的是命','count'=>30),
                array('sNickName'=>'笑','count'=>1),
                array('sNickName'=>'一半眼线','count'=>1),
                array('sNickName'=>'这样就好','count'=>1),
                array('sNickName'=>'豆芽菜','count'=>2),
                array('sNickName'=>'過客','count'=>8),
                array('sNickName'=>'無盡透明的思念','count'=>12),
                array('sNickName'=>'solo','count'=>3),
                array('sNickName'=>'忘了他.','count'=>5),
                array('sNickName'=>'粉红。顽皮豹','count'=>20),
                array('sNickName'=>'小心翼翼','count'=>15),
                array('sNickName'=>'別敷衍','count'=>1),
                array('sNickName'=>'恋人爱成路人','count'=>1),
                array('sNickName'=>'昔日餘光。','count'=>1),
                array('sNickName'=>'放肆','count'=>2),
                array('sNickName'=>'今非昔比','count'=>5),
                array('sNickName'=>'无名指','count'=>3),
                array('sNickName'=>'莫名的青春','count'=>4),
                array('sNickName'=>'一抹丶苍白','count'=>10),
                array('sNickName'=>'笑叹尘世美','count'=>2),
                array('sNickName'=>'爱你心口难开','count'=>11),
                array('sNickName'=>'那傷。眞美','count'=>9),
                array('sNickName'=>'命運不堪浮華','count'=>1),
                array('sNickName'=>'爱被冰凝固','count'=>2),
                array('sNickName'=>'一生承诺','count'=>1),
                array('sNickName'=>'行尸走肉','count'=>2),
            );
        }
        else
        {
            $forgery_list = array();
        }
        //伪造弹幕数据

        $forgery = array_merge($forgery_result,$forgery_list);  //合并虚拟数据和真实数据
        $forgery_count = count($forgery);
        shuffle($forgery);
        $end_count = ceil($forgery_count/2)-1;
        $begin_list = array_slice($forgery,0,$end_count);
        $end_list = array_slice($forgery,$end_count,$forgery_count);


        $this->assign('self_list', $self_list);
        $this->assign('result_list', $result);
        $this->assign('begin_list', $begin_list);
        $this->assign('end_list', $end_list);
        $this->render(array(),'active_controllers/who_local_tyrant');
    }

    private function second_restart_rank_list($re_list,$list)
    {
        $rank_order_list = array();
        $have_in = false;
        foreach($list as $v)
        {
            if($re_list['count'] > $v['count'])
            {
                if(!$have_in)
                {
                    array_push($rank_order_list,array('uin'=>'second','count'=>$re_list['count']));
                    $have_in = true;
                }
            }
            array_push($rank_order_list,array('uin'=>$v['uin'],'count'=>$v['count']));
        }
        return $rank_order_list;
    }

    private function restart_rank_list($re_list,$list)
    {
        $i_rank = 1;
        $rank_order_list = array();
        $have_in = false;
        foreach($list as $forgery_key => $forgery_cache)
        {
            if($re_list['count'] > $forgery_cache)
            {
                if(!$have_in)
                {
                    array_push($rank_order_list,array('uin'=>'first','count'=>$re_list['count']));
                    $have_in = true;
                }
            }
            array_push($rank_order_list,array('uin'=>$forgery_key,'count'=>$forgery_cache));
            $i_rank++;
        }
        return $rank_order_list;
    }

    private function second_change_rank_count($rank,$count,$list)
    {
        $i = 1;
        $level_count = 0;
        $temp_count = 0;
        foreach($list as $v)
        {
            if($rank == 1)
            {
                if($i == 1)
                {
                    $level_count = $v['count']+10;
                    $temp_count = $v['count'];
                    break;
                }
            }
            else
            {
                if(($rank-1) == $i)
                {
                    $level_count = $v['count'];
                }
                if($rank == $i)
                {
                    $temp_count = $v['count'];

                }

            }
            $i++;
        }

        $differ_count = ($level_count - $temp_count);
        if($differ_count == 0)
        {
            $return_count = $count;
        }
        else
        {
            $temp_rank = rand(1,$differ_count);
            $return_count = (int)$temp_count + (int)$temp_rank;
        }

        if($return_count > $count)
        {
            return array('result'=>true,'count'=>$return_count);
        }
        else
        {
            return array('result'=>false);
        }


    }

    private function change_rank_count($rank,$count,$list)
    {
        $i = 1;
        $level_count = 0;
        $temp_count = 0;
        foreach($list as $v)
        {
            if($rank == 1)
            {
                if($i == 1)
                {
                    $level_count = $v+10;
                    $temp_count = $v;
                    break;
                }
            }
            else
            {
                if(($rank-1) == $i)
                {
                    $level_count = $v;
                }
                if($rank == $i)
                {
                    $temp_count = $v;

                }

            }
            $i++;
        }

        $differ_count = ($level_count - $temp_count);
        if($differ_count == 0)
        {
            $return_count = $count;
        }
        else
        {
            $temp_rank = rand(1,$differ_count);
            $return_count = (int)$temp_count + (int)$temp_rank;
        }

        if($return_count > $count)
        {
            return array('result'=>true,'count'=>$return_count);
        }
        else
        {
            return array('result'=>false);
        }


    }

    private function get_uin()
    {
        $this->load->service('user_service');
        //校验登陆
        if($uin = $this->user_service->valid_user_login()) {
            return $uin;
        }

        return false;
    }
}