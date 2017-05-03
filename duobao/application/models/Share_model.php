<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 奖励活动-model
 */
class Share_model extends MY_Model {
    protected $db_group_name = DATABASE_YYDB;               //分组名
    protected $table_name = 't_share';                      //表名
    protected $table_primary = 'iShareId';                  //主键
    protected $need_cache_row = TRUE;                       //缓存key字段  可自定义
    protected $auto_update_time = TRUE;    //添加或修改记录时自动更新createtime 或updatetime

    public function get_share_list($platform, $params, $p_cur=1, $p_size=10)
    {
        $params['iPlatForm'] = $platform;
        $params['iOnlineTime <'] = time();

        $fields = 'iShareId,iUin,sContent,sNickName,sHeadImg,iActId,iPeriod,iLikeCount,iViewCount,sGoodsName,iLuckyCode,iLotTime,iLotCount,iWinnerCount,iIp,iCreateTime,sImg,sGoodsImg,sArea,iOnlineTime';
        $row_list = $this->row_list($fields, $params, array('iShareId' => 'desc'), $p_cur, $p_size);

        $row_list['list'] = $this->format_share($row_list['list']);
        return $row_list;
    }

    public function detail($share_id) {
        $params = array(
            'iShareId' => $share_id,
        );

        if (!$row = $this->get_row($params)) {
            return array();
        }
        return $this->format_share_detail($row);
    }

    private function format_share($list)
    {
        if($list) {
            $ret = array();
            foreach($list as $item) {
                $ret[] = $this->format_share_detail($item);
            }
            return $ret;
        }
        return array();
    }


    public function format_share_detail($item)
    {
        return array(
            'share_id' => $item['iShareId'],
            'uin' => $item['iUin'],
            'content' => $item['sContent'],
            'nickname' => $item['sNickName'],
            'headimg' => $item['sHeadImg'],
            'act_id' => $item['iActId'],
            'period' => $item['iPeriod'],
            'like_count' => $item['iLikeCount'],
            'view_count' => $item['iViewCount'],
            'goods_name' => $item['sGoodsName'],
            'goods_img' => $item['sGoodsImg'],
            'lucky_code' => $item['iLuckyCode'],
            'lot_time' => $item['iLotTime'],
            'share_time' => $item['iOnlineTime']<1?$item['iCreateTime']:$item['iOnlineTime'],
            'lot_count' => $item['iLotCount'],
            'winner_count' => $item['iWinnerCount'],
            'imgs' => json_decode($item['sImg'], true),
            'ip' => long2ip($item['iIp']),
            'area' => $item['sArea']
        );
    }

    public function update_count($share_id, $params)
    {
        $fileds = '';
        if(isset($params['iLikeCount'])) {
            $fileds .= 'iLikeCount=iLikeCount+'.$params['iLikeCount'].',';
        }
        if(isset($params['iViewCount'])) {
            $fileds .= 'iViewCount=iViewCount+'.$params['iViewCount'].',';
        }
        $fileds = trim($fileds, ',');
        $table_name = $this->get_cur_table();
        if ($fileds) {
            $sql = 'update '.$table_name.' set '.$fileds.' where iShareId='.$share_id.' limit 1';
            $ret = $this->query($sql, true);

            if($this->need_cache_row && $ret) {
                $this->update_cache_row($share_id);
            }

            return $ret;
        }
        return true;
    }
}