<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Luckybag extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理的订单数

    protected $log_type = 'Luckybag';
    private $now_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('lucky_bag_model');
        $this->load->model('bag_action_log_model');
        $this->load->model('coupon_action_log_model');
        $this->load->model('user_model');
        $this->load->model('user_ext_model');
    }


    public function run()
    {
        //while(true){
            $this->now_time = time();
            $__startTime = microtime(true);
            $this->log("+---------------------------START---------------------------");
            $this->log("| script start. nowTime[".$this->now_time."]; nowDate[".date('Y-m-d H:i:s',$this->now_time)."]; microtime[".$__startTime."]");

            $iDbNum = $iTableNum = 10;
            /*遍历福袋十库十表，逐个更新ttc*/
            for ($i=0; $i<$iDbNum; $i++)
            {
                for ($j = 0; $j < $iTableNum; $j++)
                {
                    $iCount = self::LIMIT;
                    while ($iCount > 0)
                    {
                        $countSql = 'select count(*) iCount from `'.$this->lucky_bag_model->get_table_name().$j.'` where iIsTimeOut = 0 and iEndTime <= '.$this->now_time;
                        $aCount = $this->lucky_bag_model->query($countSql, true);
                        $iCount = isset($aCount[0]['iCount']) ? intval($aCount[0]['iCount']) : 0;
                        $this->log('| get expired records count sql : '.$countSql.' | count:'.$iCount);
                        if ($iCount) {
                            //取ttckey[uin]、主键[iBagId]列表
                            $sql = 'select iUin,iBagId,iCoupon,iPayAmount,iUsed,iIsPaid,iType from `'.$this->lucky_bag_model->get_table_name().$j.'` where iIsTimeOut = 0 and iEndTime <= '.$this->now_time.' limit 0,'.self::LIMIT;
                            $ret = $this->lucky_bag_model->query($sql, true);
                            $this->log('| get expired records sql : '.$sql.' | row:'.json_encode($ret));
                            if (empty($ret)) {
                                $this->log("| get expired records failed. sql[$sql]");
                            } else {
                                foreach ($ret as $row)
                                {
                                    //更新福袋表过期状态
                                    if ($this->updateBagInfo($row['iUin'], $row))
                                    {
                                        if ($row['iIsPaid'] == 1) //已支付的福袋，退回券数量 = iAmout - iUsed
                                        {
                                            $iNeedRefundCoupon = $row['iCoupon'] - $row['iUsed'];
                                            $this->refundCoupon($row['iUin'], $row['iBagId'], $iNeedRefundCoupon, 1, '', $row['iType']);//返还券并且福袋数减一
                                        }
                                        else //未支付 需要查福袋订单表退夺宝券
                                        {
                                            $aLuckyBagOrder = $this->getLuckyBagOrder($row['iUin'], $row['iBagId']);
                                            if ($aLuckyBagOrder) //退夺宝券
                                            {
                                                $this->refundCoupon($row['iUin'], $row['iBagId'], $aLuckyBagOrder['iPayCoupon'], 0, '', $row['iType']);//返还券
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $__endTime = microtime(true);
            $this->log("| script end [".date('Y-m-d H:i:s')."];microtime[".$__endTime."];costTime[".($__endTime-$__startTime)."]");
            $this->log("+----------------------------END---------------------------");
            //sleep(10);
        //}
    }

    /**
     * @param $iUin
     * @param $iBagId
     * 取福袋订单信息
     */
    private function getLuckyBagOrder($iUin, $iBagId)
    {
        if ($row = $this->lucky_bag_model->get_row(array('iUin'=>$iUin, 'iBagId'=>$iBagId))) {
            return $row;
        } else {
            return array();
        }
    }

    /**
     * @param $iKey
     * 根据key取对应表和库的num
     */
    private function getDbAndTable($iKey)
    {
        $iDbNum = intval(substr($iKey, -1,1));
        $iTableNum = intval(substr($iKey, -2,1));
        return array($iDbNum, $iTableNum);
    }

    /**
     * @param $iUin
     * @param $aInfo
     * 更新福袋表
     */
    private function updateBagInfo($iUin, $aInfo)
    {
        //更新福袋表过期状态
        $ret = $this->lucky_bag_model->update_row(array('iIsTimeOut'=>1, 'iUpdateTime'=>$this->now_time), array('iUin'=> $iUin, 'iBagId'=>$aInfo['iBagId']));
        if (empty($ret)) {
            $this->log("| DB update lucky bag failed; sql[".$this->lucky_bag_model->db->last_query()."]");
            return false;
        } else {
            $this->log("| DB update lucky bag timeout | sql: ".$this->lucky_bag_model->db->last_query()." | affected rows[".($ret)."]");
            return true;
        }
    }

    /**
     * @param $iUin
     * @param $iCouponNum
     * @param $iBagNum
     * 福袋失效后返回券
     */
    private function refundCoupon($iUin, $iBagId, $iCouponNum, $iBagNum, $iBagType)
    {
        $user_ext_info = $this->user_ext_model->get_row($iUin);
        if (empty($user_ext_info)) {
            $this->log( '| No user info. sql['.$this->user_ext_model->db->last_query().']');
            return false;
        }

        $data = array();
        if ($iBagNum && ($user_ext_info['iLuckyBag'] >= 1)) {
            $data['iLuckyBag'] = -1;
        }
        $data['iCoupon'] = $iCouponNum;
        if($ret = $this->user_ext_model->update_count($data, $iUin)) {
            $this->log('| DB |  update user luckybag and coupon count | success | affected rows: '.intval($ret).' | sql : '.$this->user_ext_model->db->last_query().'');
            if($ret = $this->addCouponActionLog($iUin, $iBagId, Lib_Constants::ACTION_GET_TIME_OUT, $iCouponNum, '', $iBagType)) {
                return true;
            }
        } else {
            $this->log('| DB |  update user luckybag and coupon count | failed | sql : '.$this->user_ext_model->db->last_query().'');
            return false;
        }
    }

    /**
     * @param $iUin
     * @param $iBagId
     * @param $iAction 操作：1领取福袋，2购买夺宝券,3福袋超时券退回,4夺宝券使用，5兑换商品，6发福袋',
     * @param $iNum
     * @param $sExtend
     * 增加操作日志记录
     */
    private function addOptionLog($iUin, $iBagId, $iAction, $iNum, $sExtend, $iBagType)
    {
        $aInsert = array(
            'iUin' => $iUin,
            'iBagId' => intval($iBagId),
            'iAction' => $iAction,
            'iNum' => $iNum,
            'sExtend' => $sExtend,
            'iAddTime' => $this->now_time,
            'iType' => $iBagType,
        );
        $ret = $this->bag_action_log_model->add_row($aInsert);
        if ($ret)
        {
            $this->log("| Add active Log Error!!! logInfo[".json_encode($aInsert)."]");
        }
        else
        {
            $this->log("| Add active Log Success!!! logid[".$ret."]");
        }
    }

    /**
     * @param $iUin
     * @param $iBagId
     * @param $iAction 操作：1领取福袋，2购买夺宝券,3福袋超时券退回,4夺宝券使用，5兑换商品，6发福袋',
     * @param $iNum
     * @param $sExtend
     * 增加操作日志记录
     */
    private function addCouponActionLog($iUin, $iBagId, $iAction, $iNum, $sExtend, $iBagType)
    {
        $aInsert = array(
            'iUin' => $iUin,
            'iAction' => $iAction,
            'iNum' => $iNum,
            'sExt' => intval($iBagId),
            'iAddTime' => $this->now_time,
            'iType' => Lib_Constants::ACTION_INCOME,
        );
        $ret = $this->coupon_action_log_model->add_row($aInsert);

        if ($ret) {
            $this->log('| DB |  add user coupon action log | success | logid: '.$ret.' | data:  '.json_encode($aInsert).' | sql : '.$this->coupon_action_log_model->db->last_query().'');
        } else {
            $this->log('| DB |  add user coupon action log | failed | data:  '.json_encode($aInsert).' | sql : '.$this->coupon_action_log_model->db->last_query().'');
        }
    }
}