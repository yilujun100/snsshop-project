<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户扩展表-model
 */
class User_ext_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB_USER;          //分组名
    protected $table_name = 't_user_ext';                   //表名
    protected $table_primary = 'iUin';                      //主键
    protected $cache_row_key_column = 'iUin';               //缓存key字段  可自定义
    protected $table_num                =       10;
    protected $logic_group              =       LOGIC_GROUP_USER;
    protected $db_map_column = 'iUin';  //分表字段
    protected $need_cache_row = true;
    protected $cache_key_prefix = 'ext_info_';

    /**
     * 取用户信息
     * @param $platform
     */
    public function  get_user_by_uin($uin, $form_write=false)
    {
        return  $this->get_row($uin, $form_write);
    }

    /**
     * @param $uin
     */
    public function get_user_ext_info($uin, $form_write=false)
    {
        $user_info = $this->get_user_by_uin($uin, $form_write);
        if (empty($user_info)) {
            return false;
        }

        return $this->format_user_ext($user_info);
    }

    /**
     * 用户扩展信息格式化
     * @param $data
     * @return array
     */
    public function format_user_ext($data)
    {
        return array(
            'uin' => $data['iUin'],
            'coupon' => $data['iCoupon'],
            'his_gift_coupon' => $data['iHisGiftCoupon'],
            'his_coupon' => $data['iHisCoupon'],
            'lucky_bag' => $data['iLuckyBag'],
            'his_lucky_bag' => $data['iHisLuckyBag'],
            'score' => $data['iScore'],
            'free_coupon' => $data['iFreeCoupon'],
            'free_time' => $data['iGetFreeTime'],
            'his_used_score' => $data['iHisUsedScore'],
            'sign_time' => $data['iSignTime']
        );
    }

    public function update_count($params, $uin)
    {
        $table_name = $this->map($uin)->get_cur_table();
        $fileds = '';
        $fileds_arr = array('iCoupon','iHisGiftCoupon','iSign','iHisCoupon','iLuckyBag','iHisLuckyBag','iHisScore','iScore','iHisUsedScore','iFreeCoupon');
        foreach ($fileds_arr as $val) {
            if(isset($params[$val])) {
                if($params[$val]>0) {
                    $fileds .= $val.'='.$val.'+'.$params[$val].',';
                } else {
                    $fileds .= $val.'='.$val.'-'.abs($params[$val]).',';
                }
            }
        }

        if(isset($params['iSignTime'])) {
            $fileds .= 'iSignTime='.$params['iSignTime'].',';
        }
        if(isset($params['iGetFreeTime'])) {
            $fileds .= 'iGetFreeTime='.$params['iGetFreeTime'].',';
        }
        if(isset($params['iUpdateTime'])) {
            $fileds .= 'iUpdateTime='.$params['iUpdateTime'].',';
        } else {
            $fileds .= 'iUpdateTime='.time().',';
        }

        $fileds = trim($fileds, ',');
        if ($fileds) {
            $sql = 'update '.$table_name.' set '.$fileds.' where iUin='.$uin.' limit 1';
            $ret = $this->query($sql, true);

            if($this->need_cache_row && $ret) {
                $this->update_cache_row($uin);
            }

			return $ret;
        }
        return true;
    }
}