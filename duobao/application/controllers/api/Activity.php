<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity extends API_Base
{
    /**
     * 百分好礼活动- 分享有礼 - 邀请成功获得夺宝券列表
     */
    public function share_invite_succ_list(){
        extract($this->cdata);

        if(empty($act_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $act_id = intval($act_id);

        $p_index = isset($p_index) ? intval($p_index) : 1;
        $p_size = isset($p_size) ? intval($p_size) : 10;

        $this->load->model('share_invite_succ_model');
        $list = $this->share_invite_succ_model->row_list('*', array('iActId'=>$act_id), array('iCreateTime'=>'desc'), $p_index ,$p_size);

        $this->render_result(Lib_Errors::SUCC,$list);
    }

    /**
     * 百分好礼活动 - 分享有礼 - 添加邀请成功记录
     */
    public function add_share_invite_succ()
    {
        extract($this->cdata);
        if (empty($uin) || empty($to_uin) || empty($act_id) || $uin == $to_uin) {
            $this->log->error('Activity_Share', 'add share invite succ | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        //校验用户
        $this->load->model('user_model');
        $to_user = $this->user_model->get_row($to_uin, true, false);
        if (empty($to_user)) {
            $this->log->error('Activity_Share', 'to user not found | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        //校验是否为新用户
        $this->load->model('wx_new_user_model');
        if (!$this->wx_new_user_model->get_row(array('iUin'=>$to_user['iUin'],'iStatus'=>Lib_Constants::STATUS_0), true, false)) {
            $this->log->error('Activity_Share', 'to user is not new user | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $user = $this->user_model->get_user_by_uin($uin);
        if (empty($user)) {
            $this->log->error('Activity_Share', 'user not found | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('share_invite_succ_model');
        //检查是否已添加
        if ($this->share_invite_succ_model->get_row(array('iToUin'=>$to_user['iUin']), true, false)){
            $this->log->error('Activity_Share', 'already added | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SHARE_INVITE_ALREADY_GET);
        }

        $data = array(
            'iActId' => $act_id,
            'iUin' => $user['iUin'],
            'sNickName' => $user['sNickName'],
            'iToUin' => $to_user['iUin'],
            'sToNickName' => $to_user['sNickName'],
            'iStatus' => Lib_Constants::STATUS_0,
            'iToStatus' => Lib_Constants::STATUS_0,
            'sExt' => '',
            'iCreateTime' => time()
        );
        if ($this->share_invite_succ_model->add_row($data)) {
            $this->render_result(Lib_Errors::SUCC);
        } else {
            $this->log->error('Activity_Share', 'add_share_invite_succ failed  | params:'.json_encode($this->cdata). ' | data:'.json_encode($data).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }

    /**
     * 百分好礼活动 - 分享有礼 - 领取夺宝券
     */
    public function get_share_invite_awards()
    {
        extract($this->cdata);

        if (empty($to_uin) || empty($act_id) || empty($sign)) {
            $this->log->error('Activity_Share', 'get share invite rewards | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        //校验数据
        $this->load->model('share_invite_succ_model');
        if (!$invite = $this->share_invite_succ_model->get_row(array( 'iToUin'=>$to_uin, 'iActId'=>$act_id), true, false)){
            $this->log->error('Activity_Share', 'no invite succ records found | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        if (gen_sign($invite['iUin'], $act_id) != $sign) {
            $this->log->error('Activity_Share', 'sign error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        if ($invite['iToStatus'] == Lib_Constants::STATUS_1) { //已处理 直接返回
            $this->log->error('Activity_Share', 'to user has already get invite rewards | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SHARE_INVITE_ALREADY_GET);
        }

        $this->load->model('user_model');
        $to_user = $this->user_model->get_row($to_uin, true, false);
        if (empty($to_user['iContactState'])) { //未关注公众号
            $this->render_result(Lib_Errors::NOT_SUBSCRIBE);
        }

        //领券
        $this->load->service('awards_service');
        $ret = $this->awards_service->grant_awards($to_uin, Lib_Constants::AWARDS_TYPE_TAG_SHARE_GIFT, $this->client_id, array('uin'=>$invite['iUin'], 'act_id'=>$act_id,'key'=>$to_uin.'_'.$invite['iUin'].'_'.$act_id), array('key'=>md5($to_uin.'_'.$invite['iUin'].'_'.$act_id), 'uin'=>$to_uin, 'data'=>array('awards_time'=>date('Y年m月d日H点i分'), 'create_time'=>date('Y年m月d日H点i分',$invite['iCreateTime']))));
        if (!is_array($ret) && $ret != Lib_Errors::SUCC) {
            $this->log->error('Activity_Share', 'grant share invite awards failed | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SVR_ERR);
        }

        if (is_array($ret) ||  $ret == Lib_Errors::SUCC) { //更新状态
            if (!$this->share_invite_succ_model->update_row(array('iToStatus'=>Lib_Constants::STATUS_1, 'sToNickName'=>$to_user['sNickName'], 'iUpdateTime'=>time()), array('iAutoId'=>$invite['iAutoId']))) {
                $this->log->error('Activity_Share', 'update invite succ status failed | '.json_encode($this->cdata).' | '.__METHOD__);
            }
        }
        $this->render_result(Lib_Errors::SUCC);
    }

    /**
     * 邀请成功表记录
     */
    public function get_share_invite_succ()
    {
        extract($this->cdata);
        if (empty($to_uin)) {
            $this->log->error('Activity_Share', 'add share invite succ | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('share_invite_succ_model');
        if ($ret = $this->share_invite_succ_model->get_row(array('iToUin'=>$to_uin), true, false)) {
            $this->render_result(Lib_Errors::SUCC, $ret);
        } else {
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
    }

    /**
     * 邀请人获得券总数
     */
    public function get_invite_coupon_count()
    {
        extract($this->cdata);

        if (empty($uin) || empty($act_id)) {
            $this->log->error('Activity_Share', 'get share invite rewards | params error | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        //校验数据
        $this->load->model('share_invite_succ_model');
        if (!$invite = $this->share_invite_succ_model->get_row(array( 'iUin'=>$uin, 'iActId'=>$act_id))){
            $this->log->error('Activity_Share', 'no invite succ records found | '.json_encode($this->cdata).' | '.__METHOD__);
            $this->render_result(Lib_Errors::SUCC, 0);
        } else {
            $this->load->model('coupon_action_log_model');
            $count = $this->coupon_action_log_model->row_count(array('iUin'=>$uin,'iAction'=>Lib_Constants::SHARE_INVITE));
            $this->render_result(Lib_Errors::SUCC, intval($count));
        }
    }


    /**
     * 充值排行列表
     */
    public function get_rank_list()
    {
        $cache_data_key = 'activity_ranklist_data';
        $cache_robot_key = 'activity_ranklist_robot';
        $cache_data_state = 'activity_ranklist_state';
        $date = get_variable('activity_ranklist_config',array());
        $state = get_variable($cache_data_state,null);

        //重置活动缓存数据
        $this->load->model('variable_model');
        if(empty($date)  || !is_array($date) || strtotime($date['start_date']) > time()){
            $this->cache->memcached->delete($cache_data_key);
            $this->cache->memcached->delete($cache_robot_key);
            $this->variable_model->update_row(array('sValue'=>0),array('sKey'=>$cache_data_state)); //活动状态初始化
            $this->render_result(Lib_Errors::SUCC);
        }

        //初始化数据
        $start_time = strtotime($date['start_date']);
        $end_time = strtotime($date['end_date']);
        $max_amount = 50;//最大值，如果没有用户排名，则robot参与，但充值不能超过50

        $rank_data = $this->cache->memcached->get($cache_data_key);
        if((time() >= $start_time && time() <= $end_time) || empty($rank_data)){
            //获取真实用户充值排名
            $this->load->model('coupon_order_model');
            $list = array();
            for($i=0;$i<10;$i++){
                $table = 't_coupon_order'.$i;
                $sql = "SELECT iUin,SUM(iTotalPrice) as iTotalPrice,iPresentCount FROM ".$table." WHERE iPayTime >= ".$start_time." AND iPayTime <= ".$end_time." AND iPayStatus = ".Lib_Constants::PAY_STATUS_PAID." GROUP BY iUin ORDER BY iTotalPrice DESC LIMIT 1000";
                $temp = $this->coupon_order_model->query($sql,true);
                if(!empty($temp) && is_array($temp)){
                    $max_amount = $temp[0]['iTotalPrice'] > $max_amount ? $temp[0]['iTotalPrice'] : $max_amount;
                    $list = array_merge($list,$temp);
                }
            }

            //获取robot参与记录排名，并保存缓存
            $cache_data =  $this->cache->memcached->get($cache_robot_key);
            if(empty($cache_data)){
                $max_amount = $max_amount > 50 ? $max_amount * 0.2 + $max_amount : $max_amount;//允许超过用户最大值的20%
                $max_amount = intval($max_amount*100);  //先转成分计算
                $this->load->model('robot_temporary_model');
                $cache_data = array();
                for($i=0;$i<10;$i++){
                    $table = 't_robot_temporary'.$i;
                    $sql = "SELECT iUin,SUM(iTotalPrice) as iTotalPrice FROM ".$table." WHERE iCreateTime >= ".$start_time." AND iCreateTime <= ".$end_time." GROUP BY iUin ORDER BY iTotalPrice DESC LIMIT 1000";
                    $temp = $this->robot_temporary_model->query($sql,true);
                    if(!empty($temp) && is_array($temp)){
                        foreach($temp as $val){
                            if($val['iTotalPrice'] <= $max_amount){
                                $val['iTotalPrice'] = $this->simulate_recharge($val['iTotalPrice']);
                                $cache_data[] = $val;
                            }
                        }
                    }
                }
                $this->cache->memcached->save($cache_robot_key,json_encode($cache_data),time()+rand(10*60,30*60)); //通过缓存时间来达到不间断控制robot冲击真实用户的排行
                $list = array_merge($list,$cache_data);
            }else{
                $cache_data = json_decode($cache_data,true);
                if(!is_array($cache_data)) $cache_data = array();
                $list = array_merge($list,$cache_data);
            }

            //重新排序
            $volume = array();
            foreach($list as $k => $val){
                $volume[$k] = $val['iTotalPrice'];
            }
            array_multisort($volume, SORT_DESC,$list);
            $list = array_slice($list,0,1000); //仅保留1000排行
            unset($volume,$temp,$table,$cache_data);


            //人工干预
            if(!empty($date['rank_list'])){
                foreach($date['rank_list'] as $key => $val){
                    $list[] = array(
                        'iUin' => $key,
                        'iTotalPrice' => $val,
                    );
                }
            }

            //获取用户信息，并且整理数据
            $rank = array();
            $this->load->model('user_model');
            foreach($list as $li){
                if(strlen($li['iUin']) < 18) continue;
                $user = $this->user_model->get_user_by_uin($li['iUin']);
                if(empty($user)) continue;
                $rank[$li['iUin']] = array(
                    'uin' => $li['iUin'],
                    'total_price' => $li['iTotalPrice'],
                    'nick_name' => $user['sNickName'],
                    'head_img' => $user['sHeadImg']
                );
            }
            unset($list,$li);

            //是否为截止时间，再一次进行排名判断，确保前三是robot
            //是否为截止时间，再一次进行排名判断，确保前三是robot
            if(strtotime($date['end_date']) < time()){
                $user_max = 0;
                $i = 1;
                foreach($rank as $val){
                    if(!is_robot($val['uin'])){
                        $user_max = $user_max > $val['total_price'] ? $user_max : $val['total_price'];
                    }
                    if($i >= 3) break;
                    $i++;
                }

                //随机robot排前三
                if($user_max > 0){
                    $robot = array();
                    foreach($rank as $val){
                        if(is_robot($val['uin'])){
                            $robot[] = $val;
                        }
                    }

                    $i = 3;
                    $rand_arr = array(5,10,30,50,100,200);
                    do{
                        $rand_index = rand(0,10);
                        $rand_type = rand(0,5);
                        $rand = $robot[$rand_index];
                        $rank[$rand['uin']]['total_price'] = $user_max + $rand_arr[$rand_type];
                        $i--;
                    }while($i > 0);
                }

                //缓存排行榜
                $this->cache->memcached->save($cache_data_key,json_encode($rank),time()+24*3600*90);
                $this->variable_model->update_row(array('sValue'=>1),array('sKey'=>$cache_data_state)); //活动已结束状态
            }
        }else{
            $rank = json_decode($rank_data,true);
        }

        $volume = array();
        foreach($rank as $k => $val){
            $volume[$k] = $val['total_price'];
        }
        array_multisort($volume, SORT_DESC,$rank);
        unset($volume,$rand_index,$rand_arr,$rand_type,$robot,$user_max);

        //推送消息,发货表,并且锁定
        $lock = $this->cache->memcached->get($cache_data_state);
        $push_list = array_slice($rank,0,10);//前10名
        if(strtotime($date['end_date']) < time() && $state == 1 && empty($lock)){
            $this->cache->memcached->save($cache_data_state,1,time()+1*60);//锁定1分钟,防止并发
            $this->variable_model->update_row(array('sValue'=>2),array('sKey'=>$cache_data_state)); //活动已推送通知
            $this->load->model('order_deliver_model');
            $this->load->service('push_service');
            $prize_list = get_variable('activity_ranklist_prize',array());
            foreach($push_list as $k => $val){
                if(!is_robot($val['uin'])){
                    $order_id = 'R-l'.date('YmdHis').'0'.($k+1);
                    if(!$this->order_deliver_model->add_deliver_row($val['uin'],$prize_list[$k]['id'],$order_id,Lib_Constants::ORDER_TYPE_ACTIVITY,'充值排行榜活动')){
                        $this->log->error('Activity','add deliver order fail | sql['.$this->order_deliver_model->db->last_query().')]');
                    }
                    $data = array(
                        'url' => gen_uri('/my/order_add_address',array('order_id'=>$order_id)),
                        'uin' => $val['uin'],
                        'nick_name' => $val['nick_name'],
                        'goods_name' => $prize_list[$k]['title']
                    );
                    $rs = $this->push_service->add_task(Lib_Constants::$msg_business_type[Lib_Constants::MSG_TEM_ORDER_RANK_AWARDS],$order_id,$val['uin'],$data);
                    if(empty($rs) || $rs < 0){
                        $this->log->error('Activity','add push task fail | rs['.$rs.')] | data['.json_encode($data).']');
                    }
                }
            }
            $this->cache->memcached->save($cache_data_state,0,time()-10);
        }

        $this->render_result(Lib_Errors::SUCC,$rank);
    }

    /**
     * 获取用户充值记录
     * @return array
     */
    public function get_my_pay()
    {
        extract($this->cdata);
        if(empty($uin)) return array();

        $date = get_variable('activity_ranklist_config',array());
        $start_time = strtotime($date['start_date']);
        $end_time = strtotime($date['end_date']);

        $this->load->model('coupon_order_model');
        $this->load->model('user_ext_model');
        $this->coupon_order_model->map($uin);
        $table = $this->coupon_order_model->get_cur_table();
        $sql = "SELECT iUin,SUM(iTotalPrice) as total_price FROM ".$table." WHERE iPayTime >= ".$start_time." AND iPayTime <= ".$end_time." AND iPayStatus = ".Lib_Constants::PAY_STATUS_PAID." AND iUin='".$uin."' GROUP BY iUin ORDER BY iTotalPrice DESC LIMIT 1000";
        $temp = $this->coupon_order_model->query($sql,true);
        $user_ext = $this->user_ext_model->get_row(array('iUin'=>$uin));

        if(empty($temp)){
            $return = array();
        }else{
            $return = $temp[0];
        }

        $return['total_price'] = isset($return['total_price']) ? $return['total_price'] : 0;
        $return['coupon'] = !empty($user_ext['iCoupon']) ? $user_ext['iCoupon'] : 0;
        $this->render_result(Lib_Errors::SUCC,$return);
    }


    /**
     * 充值弹幕
     */
    public function get_order_bullet_screen()
    {
        $date = get_variable('activity_ranklist_config',array());
        $start_time = strtotime($date['start_date']);
        $end_time = strtotime($date['end_date']);

        $this->load->model('coupon_order_model');
        $list = array();
        for($i=0;$i<10;$i++){
            $table = 't_coupon_order'.$i;
            $sql = "SELECT iUin,iTotalPrice,iPresentCount FROM ".$table." WHERE iPayTime >= ".$start_time." AND iPayTime <= ".$end_time." AND iPayStatus = ".Lib_Constants::PAY_STATUS_PAID." LIMIT 100";
            $temp = $this->coupon_order_model->query($sql);
            if(!empty($temp) && is_array($temp)){
                $list = array_merge($list,$temp);
            }
        }


        //获取用户信息，并且整理数据
        $rank = array();
        $this->load->model('user_model');
        foreach($list as $li){
            if(strlen($li['iUin']) < 18) continue;
            $user = $this->user_model->get_user_by_uin($li['iUin']);
            if(empty($user)) continue;
            $rank[] = array(
                'uin' => $li['iUin'],
                'total_price' => $li['iTotalPrice']/100,
                'present_count' => $li['iPresentCount'],
                'nick_name' => $user['sNickName'],
                'head_img' => $user['sHeadImg']
            );
        }
        unset($list,$li);

        $this->render_result(Lib_Errors::SUCC,$rank);
    }


    /**
     * 将一个数值模拟充值，返回一个接近充值的值
     * @param $money
     * @return float
     */
    protected function simulate_recharge($money)
    {
        $remainder = ($money/100) % 10;//由于是分，so要先转成元
        if($remainder == 0) return $money;
        return (($money/100) - $remainder)*100;
    }
}