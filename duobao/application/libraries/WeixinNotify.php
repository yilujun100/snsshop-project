<?php


class Lib_WeixinNotify
{
    /**
     * 发送消息模板
     **/
    public static function sendNotify($params){
        get_instance()->config->load('pay');//加载微信配置文件
        $config = config_item('weixinConfig');
        $wx = new Lib_Weixin($config);
        $retry = false;
        $times = 1;         //如果失败，则重复提交，最多3次
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
                Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','notify_weixin exception | params['.json_encode($params).'] | config['.json_encode($config).'] | error['.$e->getMessage().'] | times['.$times.'] | '.__METHOD__);
                $rs = $e->getMessage();
                $retry = true;
            }
            $times++;
        } while ($retry and $times > 3);

        return $rs;
    }


    public static function batchSendNotifyInfo($params){
        if(empty($params['openid'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendNotifyInfo openid is empty |  params['.json_encode($params).'] | '.__METHOD__);
            return false;
        }

        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['batchSendNotifyInfo'];
        $params = array(
            'openId'=>$params['openid'],
            'template_id' => $config['template_id'],
            'TEMP_ID'=> $config['TEMP_ID'],
            'url'=>  intval($params['priceString'])== 0 ? gen_uri('/free/index',array('peroid_str'=>period_code_encode($params['actId'],$params['peroid']))) : gen_uri('/active/detail',array('id'=>period_code_encode($params['actId'],$params['peroid']))),                                      //消息跳转链接
            'data' => array(
                'first' => array('value'=>'感谢您参加微团购百分好礼活动！','color'=>''),
                'orderMoneySum' => array('value'=>$params['priceString'],"color"=>""),
                'orderProductName' =>  array('value'=>'您已成功参与【'.$params['grouponName'].'】 （第'.period_code_encode($params['actId'],$params['peroid']).'期）， 您的参与码是'.$params['luckyCodeStr'].'。',"color"=>"#ff0000"),
                'Remark' => array('value'=>'请耐心等待人数集齐即可揭晓啦！如有任何问题请咨询平台客服：0755-22677020 /0755-86534792','color'=>'')
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


    public static function buyCouponSucc($params){
        if(empty($params['openid'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','buyCouponSucc openid is empty |  params['.json_encode($params).'] | '.__METHOD__);
            return false;
        }

        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['buyCouponSucc'];
        $params = array(
            'openId'=>$params['openid'],
            'template_id' => $config['template_id'],
            'TEMP_ID'=> $config['TEMP_ID'],
            'url'=>  $config['url'],                                      //消息跳转链接
            'data' => array(
                'first' => array('value'=>'百分好礼充值成功！','color'=>''),
                'orderMoneySum' => array('value'=>$params['priceString'],"color"=>""),
                'orderProductName' =>  array('value'=>empty($params['present']) ? '您已成功购买'.$params['count'].'张券!' : '您已成功购买'.$params['count'].'张券，活动赠送：'.$params['present'].'张券，速速参与活动吧~','color'=>'')
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


    /**
     *  夺宝奇兵即将开奖通知
     */
    public static function batchSendReadyInfo($params){
        if(empty($params['openid'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendReadyInfo openid is empty |  params['.json_encode($params).'] | '.__METHOD__);
            return false;
        }
        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['batchSendReadyInfo'];
        $notifyParams = array(
            'TEMP_ID'=> $config['TEMP_ID'],
            'template_id' => $config['template_id'],
            'url'=> gen_uri('/active/detail',array('id'=>period_code_encode($params['actId'],$params['peroid']))),
            'data' => array(
                'first' => array('value'=>'要！揭！晓！啦！~',"color"=>"#000000"),
                'keyword1' => array('value'=>'百分好礼',"color"=>"#000000"),
                'keyword2' =>  array('value'=>date('Y-m-d  H:i:s',$params['lotTime']),"color"=>"#000000"),
                'remark' => array('value'=>'您心爱的【'.$params['goodsName'].'】  (第 '.period_code_encode($params['actId'],$params['peroid']).' 期) 马上要揭晓啦！好！鸡！冻！速速进入百分好礼活动页面等待揭晓！ >>',"color"=>"#000000")
            ),
            'openId' => $params['openid']
        );

        $rs = self::sendNotify($notifyParams);//发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendReadyInfo  sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        }else{
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendReadyInfo  sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }


    /**
     *  夺宝奇兵开奖通知
     */
    public static function batchSendResultInfo($params){
        if(empty($params['openid'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendReadyInfo openid is empty |  params['.json_encode($params).'] | '.__METHOD__);
            return false;
        }
        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['batchSendResultInfo'];

        switch($params['isWinner']){
            case true:
                $notifyParams = array(
                    'TEMP_ID'=> $config['TEMP_ID'],
                    'template_id' => $config['template_id'],
//                    'url'=> $config['url'],
                    'url'=> gen_uri('/my/active_win_order', array('order_id'=>$params['sWinnerOrder'], 'peroid_str'=>period_code_encode($params['actId'],$params['peroid']))),
                    'data' => array(
                        'first' => array('value'=>'您中奖啦！快来填收货地址吧！',"color"=>"#000000"),
                        'keyword1' => array('value'=>'百分好礼',"color"=>"#000000"),
                        'keyword2' =>  array('value'=>date('Y-m-d  H:i:s',$params['lotTime']),"color"=>"#000000"),
                        'remark' => array('value'=>'中了中了！您心爱的【 '.$params['goodsName'].'】 (第 '.period_code_encode($params['actId'],$params['peroid']).' 期) ,是您的了！幸运码'.$params['luckyCode'].'，速速点击进入页面填写您的收货地址，小宝会尽快将商品送达至您手中！>>',"color"=>"#000000")
                    ),
                    'openId' => $params['openid']
                );
                break;

            default:
                $notifyParams = array(
                    'TEMP_ID'=> $config['TEMP_ID'],
                    'template_id' => $config['template_id'],
                    'url'=>  gen_uri('/active/detail', array('id'=>period_code_encode($params['actId'],$params['peroid']))),
                    'data' => array(
                        'first' => array('value'=>'T^T差一点就是你的了！',"color"=>"#000000"),
                        'keyword1' => array('value'=>'百分好礼',"color"=>"#000000"),
                        'keyword2' =>  array('value'=>date('Y-m-d  H:i:s',$params['lotTime']),"color"=>"#000000"),
                        'remark' => array('value'=>'您心爱的【'.$params['goodsName'].' 商品】 (第 '.period_code_encode($params['actId'],$params['peroid']).' 期)与您擦肩而过，得不到的就特别爱，不过小宝相信有志者，事竟成，追爱脚步永不停! >>~',"color"=>"#000000")
                    ),
                    'openId' => $params['openid']
                );
        }

        $rs = self::sendNotify($notifyParams);//发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendReadyInfo  sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        }else{
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','batchSendReadyInfo  sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }

    /**
     * 延迟发货通知
     *
     * @param $params
     *
     * @return bool|string
     */
    public static function dailyDeliverNotify($params)
    {
        if(empty($params['openId'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify', 'dailyDeliverNotify openId is empty', array('params'=>$params));
            return false;
        }
        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['dailyDeliverNotify'];
        $notifyParams = array(
            'TEMP_ID' => $config['TEMP_ID'],
            'template_id' => $config['template_id'],
            'url'=> $params['url'],
            'data' => array(
                'first' => array('value'=>$params['subject'], "color"=>"#000000"),
                'keyword1' => array('value'=>$params['goods_name'], "color"=>"#000000"),
                'keyword2' => array('value'=>$params['order_id'], "color"=>"#000000"),
                'keyword3' => array('value'=>date('Y-m-d H:i:s', $params['order_time']), "color"=>"#000000"),
                'keyword4' => array('value'=>$params['reason_for_delay'], "color"=>"#000000"),
                'remark' => array('value'=>$params['remark'], "color"=>"#000000"),
            ),
            'openId' => $params['openId']
        );

        $rs = self::sendNotify($notifyParams); //发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','dailyDeliverNotify sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        } else {
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','dailyDeliverNotify sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }

    /**
     * 取消订单通知
     *
     * @param $params
     *
     * @return bool|string
     */
    public static function cancelOrderNotify($params)
    {
        if(empty($params['openId'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify', 'dailyDeliverNotify openId is empty', array('params'=>$params));
            return false;
        }
        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['cancelOrderNotify'];
        $notifyParams = array(
            'TEMP_ID' => $config['TEMP_ID'],
            'template_id' => $config['template_id'],
            'url'=> $params['url'],
            'data' => array(
                'first' => array('value'=>$params['subject'], "color"=>"#000000"),
                'keynote1' => array('value'=>$params['pay_amount'], "color"=>"#000000"),
                'keynote2' => array('value'=>$params['refund_type'], "color"=>"#000000"),
                'keynote3' => array('value'=>$params['refund_time'], "color"=>"#000000"),
                'keynote4' => array('value'=>$params['goods_name'], "color"=>"#000000"),
                'keynote5' => array('value'=>$params['order_id'], "color"=>"#000000"),
                'keynote6' => array('value'=>$params['refund_reason'], "color"=>"#000000"),
                'remark' => array('value'=>$params['remark'], "color"=>"#000000"),
            ),
            'openId' => $params['openId']
        );

        $rs = self::sendNotify($notifyParams); //发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','dailyDeliverNotify sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        } else {
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','dailyDeliverNotify sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }

    /**
     * 发货通知
     *
     * @param $params
     *
     * @return bool|string
     */
    public static function deliverNotify($params)
    {
        if(empty($params['openId'])){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify', 'dailyDeliverNotify openId is empty', array('params'=>$params));
            return false;
        }
        if (empty($params['first'])) {
            $params['first'] = '您购买的订单发货啦！';
        }
        if (empty($params['order_desc'])) {
            $params['order_desc'] = '【超惠拼】恋红妆•烟台大樱桃3人成团，3斤仅需99元';
        }
        if (empty($params['express'])) {
            $params['express'] = '顺丰快递';
        }
        if (empty($params['remark'])) {
            $params['remark'] = '包裹正向您飞奔而来，请留意手机或短信，感谢您的耐心等候~';
        }
        get_instance()->config->load('pay');
        $config = config_item('weixinNotify');
        $config = $config['deliverNotify'];
        $notifyParams = array(
            'TEMP_ID' => $config['TEMP_ID'],
            'template_id' => $config['template_id'],
            'url'=> $params['url'],
            'data' => array(
                'first' => array('value'=>$params['first'], "color"=>"#000000"),
                'keyword1' => array('value'=>$params['order_desc'], "color"=>"#000000"),
                'keyword2' => array('value'=>$params['express'], "color"=>"#000000"),
                'keyword3' => array('value'=>$params['express_id'], "color"=>"#000000"),
                'keyword4' => array('value'=>$params['deliver_user'], "color"=>"#000000"),
                'remark' => array('value'=>$params['remark'], "color"=>"#000000"),
            ),
            'openId' => $params['openId']
        );

        $rs = self::sendNotify($notifyParams); //发送消息

        if($rs === true){
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','dailyDeliverNotify sendNotify  success['.$rs.']  | '.__METHOD__);
            return true;
        } else {
            Lib_WeixinUserOauth2::getCI()->log->notice('WeixinNotify','dailyDeliverNotify sendNotify  error['.$rs.']  | '.__METHOD__);
            return $rs;
        }
    }
}