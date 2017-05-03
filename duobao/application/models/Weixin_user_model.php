<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * mptools微信用户-model
 */
class Weixin_user_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_MPTOOLS;               //分组名
    protected $table_name               =       'weixin_user';                   //表名
    protected $table_primary            =       'id';                           //主键
    protected $cache_row_key_column     =       'openid';                     //缓存key字段  可自定义
    protected $need_cache_row           =       false;


    /**
     * 根据微信openid取用户信息
     * @param $openid
     * @return array
     */
    public function get_wx_user_by_openid($openid)
    {
        return $this->get_row(array('openid'=>$openid));
    }

    /**
     * 更新微信用户
     * @param $wx_user
     */
    public function update_wtg_wx_user($openid, $wx_user)
    {
        $data = array();
        if (isset($wx_user['nickname'])) {
            $data['nickname'] = $wx_user['nickname'];
        }
        if (isset($wx_user['city'])) {
            $data['city'] = $wx_user['city'];
        }
        if (isset($wx_user['headimgurl'])) {
            $data['headImg'] = $wx_user['headimgurl'];
        }
        if (isset($wx_user['subscribe'])) {
            $data['contactState'] = $wx_user['sex'];
        }
        if (isset($wx_user['subscribe_time'])) {
            $data['subscribeTime'] = $wx_user['subscribe_time'];
        }
        if (isset($wx_user['unsubscribeTime'])) {
            $data['unsubscribeTime'] = $wx_user['unsubscribeTime'];
        }
        if (empty($data)) {
            return true;
        }
        $now = time();
        $data['updateTime'] = $now;

        return $this->update_row($data, array('openid'=>$openid));
    }

    /**
     * 添加微信用户
     * @param $wx_user
     * @return mixed
     */
    public function add_wtg_wx_user($wx_user)
    {
        if(!$wx_user['subscribe']) {//未关注 不添加
            return false;
        }
        $time = time();
        $data = array(
            'uin' => '',
            'wxid' => empty($wx_user['wxid']) ? $wx_user['openid'] : $wx_user['wxid'],
            'source' =>empty($S) ? 0 : intval($S),
            'ticket' => '1111',
            'openid' => $wx_user['openid'],
            'nickname' => $wx_user['nickname'],
            'headImg' => $wx_user['headimgurl'],
            'sex' => $wx_user['sex'],//1男 2女
            'city' => $wx_user['city'],
            'contactState' => $wx_user['subscribe'],
            'userIp' => get_ip(),
            'createTime' => date('Y-m-d H:i:s', $time),
            'subscribeTime' => $wx_user['subscribe_time'],
            'unsubscribeTime' => isset($wx_user['unsubscribe_time']) ? $wx_user['unsubscribe_time'] : '00-00-00 00:00:00',
            'loginTime' => date('Y-m-d H:i:s', $time),
            'updateTime' => date('Y-m-d H:i:s', $time)
        );
        return $this->add_row($data);
    }
}