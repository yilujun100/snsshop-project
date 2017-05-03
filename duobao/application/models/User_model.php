<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户-model
 */
class User_model extends MY_Model
{
    protected $db_group_name            =       DATABASE_YYDB_USER;         //分组名
    protected $table_name               =       't_user';                   //表名
    protected $table_primary            =       'iUin';                     //主键
    protected $cache_row_key_column     =       'iUin';                     //缓存key字段  可自定义
    protected $table_num                =       10;
    protected $logic_group              =       LOGIC_GROUP_USER;
    protected $db_map_column            =       array('iUin','sOpenId');  //分表字段
    protected $need_cache_row = true;
    protected $cache_row_expired = 691200;
    protected $cache_key_prefix = 'base_info_';

    /**
     * db中微信用户信息格式化
     * @param $data
     * @return array
     */
    public function format_wx_user($data)
    {
        return array(
            'uin' => $data['iUin'],
            'nick_name' => $data['sNickName'],
            'head_img' => $data['sHeadImg'],
            'contact_state' => $data['iContactState'],
            'openid' => $data['sOpenId'],
            'province' => $data['sProvince'],
            'city' => $data['sCity'],
            'country' => $data['sCountry'],
        );
    }

    /**
     * oauth2微信用户信息格式化
     * @param $data
     * @return array
     */
    public function format_wx_oauth2_user($data) {
        return array(
            'nick_name' => $data['nickname'],
            'province' => $data['province'],
            'city' => $data['city'],
            'country' => $data['country'],
            'head_img' => $data['headimgurl'],
            'contact_state' => $data['subscribe'],
            'openid' => $data['openid'],
        );
    }

    /**
     * @param $uin
     */
    public function get_user_base_info($uin, $platform)
    {
        $user_info = $this->get_user_by_uin($uin);
        if (empty($user_info)) {
            return false;
        }
        switch ($platform) {
            case Lib_Constants::PLATFORM_WX:
                return $this->format_wx_user($user_info);
            break;
            default:
                return $this->format_wx_user($user_info);
                break;
        }
    }

    /**
     * 取用户信息
     * @param $platform
     */
    public function get_user_by_uin($uin)
    {
        if (is_robot($uin)) {
            $this->load->model('robot_model');
            return $this->robot_model->get_row($uin);
        }
        return  $this->get_row($uin);
    }

    /**
     * 根据微信openid取用户信息
     * @param $openid
     * @return array
     */
    public function get_wx_user_by_openid($openid) {
        $map = $this->get_uin_suffix($openid);
        $table_name = $this->map($map)->get_cur_table();
        $sql = 'select iUin,sNickName,iContactState,sHeadImg,sCity,sProvince,sCountry,iLoginTime from '.$table_name.' where sOpenId=\''.$openid.'\' limit 1';
        $user_info = $this->query($sql);
        return isset($user_info[0]) ? $user_info[0] : array();
    }

    /**
     * 判断用户是否为新用户
     * @param $user
     */
    public function is_new_user($user)
    {
        return (empty($user['sNickName']) && empty($user['sHeadImg']) && empty($user['iContactState']) && empty($user['iSubscribeTime'])) ? true : false;
    }

    /**
     * 更新微信用户
     * @param $wx_user
     */
    public function update_wx_user($uin, $wx_user)
    {
        $data = array();
        if (isset($wx_user['nickname'])) {
            $data['sNickName'] = $wx_user['nickname'];
        }
        if (isset($wx_user['city'])) {
            $data['sCity'] = $wx_user['city'];
        }
        if (isset($wx_user['country'])) {
            $data['sCountry'] = $wx_user['country'];
        }
        if (isset($wx_user['province'])) {
            $data['sProvince'] = $wx_user['province'];
        }
        if (isset($wx_user['headimgurl'])) {
            $data['sHeadImg'] = $wx_user['headimgurl'];
        }
        if (isset($wx_user['subscribe'])) {
            $data['iContactState'] = $wx_user['subscribe'];
        }
        if (isset($wx_user['subscribe_time'])) {
            $data['iSubscribeTime'] = $wx_user['subscribe_time'];
        }
        if (isset($wx_user['unsubscribeTime'])) {
            $data['unsubscribeTime'] = $wx_user['unsubscribeTime'];
        }
        if (isset($wx_user['login_time'])) {
            $data['iLoginTime'] = $wx_user['login_time'];
        }

        if (empty($data)) {
            return true;
        }
        return $this->update_row($data, $uin);
    }

    /**
     * 添加微信用户
     * @param $wx_user
     * @return mixed
     */
    public function add_wx_user($wx_user)
    {
        $time = time();
        $i = 0;
        $flag = false;
        while ($i < 3 && $flag === false) {
            $i++;
            $uin = $this->generate_uin(Lib_Constants::PLATFORM_WX, $wx_user['openid']);
            $row = $this->get_row($uin);
            if (!$row) {
                $flag = true;
            } else {
                sleep(1);
            }
        }

        $data = array(
            'iUin' => $uin,
            'iWuin' => isset($wx_user['wuin']) ? $wx_user['wuin'] : '',
            'sEmail' => $wx_user['openid'].'@wtg.com',
            'sNickName' => isset($wx_user['nickname']) ? $wx_user['nickname'] : '',
            'iGender' =>  isset($wx_user['sex']) ? intval($wx_user['sex']) : 0, //1男 2女
            'sCity' => empty($wx_user['city']) ? '' : $wx_user['city'],
            'sProvince' => empty($wx_user['province']) ? '' : $wx_user['province'],
            'sCountry' => empty($wx_user['country']) ? '' : $wx_user['country'],
            'sHeadImg' => isset($wx_user['headimgurl']) ? $wx_user['headimgurl'] : '',
            'iContactState' => isset($wx_user['subscribe']) ? $wx_user['subscribe'] : 0,
            'iSubscribeTime' => isset($wx_user['subscribe_time']) ? $wx_user['subscribe_time'] : 0,
            'sOpenId' => $wx_user['openid'],
            'sWxid' => $wx_user['openid'],
            'iRegPlatform' => Lib_Constants::PLATFORM_WX,
            'iRegSrc' => 0,
            'iRegTime' => $time,
            'iRegUserIp' => ip2long(get_ip()),
            'iLoginTime' => $time,
        );
        return $this->add_row($data) ? $uin : false;
    }

    /**
     * 生成用户uin算法
     * @param $platform
     * @return bool|string
     */
    public  function generate_uin($platform, $str='')
    {
        $prefix = '1'.$platform; //2位
//        list($time, $micro) = explode('.', microtime(true)); //14位
        $micro_arr = explode('.', microtime(true)); //14位
        $time = $micro_arr[0];
        $micro = empty($micro_arr[1]) ? mt_rand(0, 9999) : $micro_arr[1];
        $micro = str_pad($micro, 4, 0, STR_PAD_RIGHT);//4位
        $time = substr($time, 1);
        $suffix = $this->get_uin_suffix($str);//2位
        //$uin1 = $prefix.'-'.rand(0,9).'-'.$time.'-'.$micro.'-'.$suffix;//18位
        $uin = $prefix.mt_rand(0,9).$time.$micro.$suffix;//18位
        return $uin;
    }

    /**
     * 检查用户信息是否需要更新
     *
     *  主要检查昵称 头像 关注状态
     * @param $wxuser 微信授权信息
     * @param $db_user 数据库用户信息
     */
    public function check_wxuser_need_update($wxuser, $db_user)
    {
        if(empty($wxuser) || empty($db_user)) {
            return false;
        }
        $now_time = time();
        $diff_time = $now_time - $db_user['iLoginTime'];
        $time_result = $diff_time>(60*60*24*3)?true:false;

        if ($wxuser['subscribe'] != $db_user['iContactState'] || //关注状态
            $wxuser['nickname'] != $db_user['sNickName'] ||  //昵称
            $wxuser['city'] != $db_user['sCity'] ||  //昵称
            $wxuser['province'] != $db_user['sProvince'] ||  //昵称
            $wxuser['country'] != $db_user['sCountry'] ||  //昵称
            $wxuser['headimgurl'] != $db_user['sHeadImg']  //头像
        ) {
            return true;
        } else {
            if($time_result)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}