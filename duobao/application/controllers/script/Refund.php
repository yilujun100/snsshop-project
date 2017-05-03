<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每半小时跑一次
 * 自动退款处理脚本
 * @date 2016-03-22
 * @autor leo.zou
 */

require_once APPPATH.'third_party/payagent/TenpayFacade.php';
require_once APPPATH.'third_party/payagent/AlipayFacade.php';
require_once APPPATH.'third_party/payagent/WeiXinFacade.php';

class Refund extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理记录数
    const REPEAT = 3;//操作失败，则重复操作次数

    const REFUND_DELAY_TIME = 0;

    protected $log_type = 'Refund';
    protected $tenpayFacade;//财付通类库
    protected $alipayFacade;//支付宝类库
    protected $weixinFacade; //微信类库
    protected $qqFacade; //手Q类库

    private static $refund_delay_time;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_refund_model');
        $this->load->model('user_model');
        $this->load->model('user_ext_model');
        $this->load->model('coupon_action_log_model');
        $this->load->model('active_order_model');
        $this->load->model('active_merage_order_model');
        $this->load->model('bag_order_model');
        $this->load->model('coupon_order_model');
        $this->load->service('order_service');

        $this->weixinFacade = new WeiXinFacade();

        self::$refund_delay_time = get_variable('refund_delay_time');
    }


    public function run()
    {
        //while(true){
            $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
            $this->log("----------------------------------------------1");
            set_time_limit(0);
            $refund_list = $this->order_refund_model->get_rows(array('iRetStatus  <='=>Lib_Constants::REFUND_RET_STATUS_SUBMIT,'iLocked'=>0,'sTransId !=' => '0','iTryTimes <='=> Lib_Constants::SCRIPT_REFUND_TRY_TIMES));
            if(!empty($refund_list) && is_array($refund_list)){
                $this->log("----------------------------------------------2");
                foreach($refund_list as $list){
                    $this->log("----------------------------------------------3");
                    switch($list['iPayAgentType']){
                        case Lib_Constants::ORDER_PAY_TYPE_WX://微信支付
                            $this->log("----------------------------------------------3");
                            if(!empty($list['iRefundPrice'])){
                                $this->log("----------------------------------------------4");
                                if(isset($list['iRefundType']) && !empty(self::$refund_delay_time) && isset(self::$refund_delay_time[$list['iRefundType']])) {
                                    $delay_time = intval(self::$refund_delay_time[$list['iRefundType']]);
                                } else {
                                    $delay_time = self::REFUND_DELAY_TIME;
                                }
                                $now = time();
                                if (($list['iCreateTime']+$delay_time) > $now) { //未到退款时间
                                    $this->log('order refund delay | params['.json_encode($list).'] | now:'.$now);
                                    $this->log("----------------------------------------------5");
                                    continue;
                                }
                                //锁定订单并且处理次数+1
                                if(!$this->lock($list['iUin'],$list['sRefundKey'])){
                                    $this->log('order lock fail | params['.json_encode($list).']');
                                    $this->log("----------------------------------------------6");
                                    continue;
                                }

                                //查询订单信息
                                $arrTradeInfo = $this->weixinFacade->queryOrderPayInfo( $list['sOrderId'], $list['sTransId']);
                                $this->log("----------------------------------------------7");
                                if(empty($arrTradeInfo)){
                                    $this->log("----------------------------------------------8");
                                    $this->log('order is not found | params['.json_encode($list).'] | tradeinfo['.json_encode($arrTradeInfo).']');
                                    $data = array(
                                        'sRefundId'=> 0,
                                        'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_FAIL,
                                        'iRetCode' => -1,
                                        'sRetDetail' => 'query order pay fail!',
                                    );
                                    $refund_data = array_merge($data,array('iRefundPrice' => $list['iRefundPrice']));
                                    $this->unlock_save($list['iUin'],$list['sRefundKey'],$data);
                                    continue;
                                }elseif($arrTradeInfo['trade_state'] == 'REFUND'){
                                    $this->log("----------------------------------------------9");
                                    $refund_id = isset($retArr['transaction_id'])? $retArr['transaction_id'] : 0;
                                    $data = array(
                                        'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_SUBMIT,
                                        'sRefundId'=> $refund_id,
                                        'iRetCode' => $arrTradeInfo['result_code'],
                                        'sRetDetail' => json_encode($arrTradeInfo),
                                        'iLastModTime' => time()
                                    );
                                    $refund_data = array_merge($data,array('iRefundPrice' => $list['iRefundPrice']));
                                    $this->unlock_save($list['iUin'],$list['sRefundKey'],$data);
                                    continue;
                                }
                                $this->log("----------------------------------------------10");
                                //判断是否支付
                                $isPaid = WeiXinFacade::isTradePaid($arrTradeInfo);
                                if(!$isPaid){
                                    $this->log("----------------------------------------------11");
                                    $this->log('order is not pay | params['.json_encode($list).'] | tradeinfo['.json_encode($arrTradeInfo).']');
                                    $data = array(
                                        'sRefundId'=> 0,
                                        'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_FAIL,
                                        'iRetCode' => $arrTradeInfo['result_code'],
                                        'sRetDetail' => json_encode($arrTradeInfo),
                                    );
                                    $refund_data = array_merge($data,array('iRefundPrice' => $list['iRefundPrice']));
                                    $this->unlock_save($list['iUin'],$list['sRefundKey'],$data,1);
                                    continue;
                                }
                                $this->log("----------------------------------------------12");

                                //发起退款
                                $refund_id = 0;
                                $retArr = $this->weixinFacade->refundOrder($list['sOrderId'],$list['sTransId'],$list['sRefundKey'],$arrTradeInfo['total_fee'],$list['iRefundPrice']); //FIXMEtotalprice读db,[查接口],增加写入
                                $this->log("----------------------------------------------13");
                                if (isset($retArr['result_code']) && $retArr['result_code'] == 'SUCCESS'){
                                    $this->log("----------------------------------------------7");
                                    $this->log('order refund success | retArr['.json_encode($retArr).'] | tradeinfo['.json_encode($arrTradeInfo).']');
                                    $refund_id = isset($retArr['transaction_id'])? $retArr['transaction_id'] : 0;
                                    $data = array(
                                        'iLocked' => 0,
                                        'sRefundId'=> $refund_id,
                                        'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_SUBMIT,
                                        'iRetCode' => $retArr['result_code'],
                                        'sRetDetail' => json_encode($retArr),
                                    );
                                    $refund_data = array_merge($data,array('iRefundPrice' => $list['iRefundPrice']));
                                    $this->unlock_save($list['iUin'],$list['sRefundKey'],$data);
                                }else{//发起申请退款失败
                                    $this->log("----------------------------------------------14");
                                    $this->log('order refund failed | retArr['.json_encode($retArr).'] | tradeinfo['.json_encode($arrTradeInfo).']');
                                    $data = array(
                                        'sRefundId'=> 0,
                                        'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_FAIL,
                                        'iRetCode' => $retArr['return_code'],
                                        'sRetDetail' => json_encode($retArr),
                                    );
                                    $refund_data = array_merge($data,array('iRefundPrice' => $list['iRefundPrice']));
                                    $this->unlock_save($list['iUin'],$list['sRefundKey'],$data);
                                }
                            }
                            break;
                        case Lib_Constants::ORDER_PAY_TYPE_COUPON: //夺宝券支付
                            if(!empty($list['iRefundCoupon'])){
                                //锁定订单并且处理次数+1
                                if(!$this->lock($list['iUin'],$list['sRefundKey'])){
                                    $this->log('order lock fail | params['.json_encode($list).']');
                                }

                                //检查订单支付详情
                                $type = $this->order_service->check_order_type($list['sOrderId']);
                                switch($type){
                                    case Lib_Constants::ORDER_TYPE_ACTIVE:
                                    case Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE:
                                        $order_detail = $this->active_merage_order_model->get_merage_order_by_id($list['iUin'],$list['sOrderId']);
                                        $order_detail['iPayCoupon'] = $order_detail['iCoupon'];
                                        if(!$this->order_service->is_paid($order_detail)){
                                            $this->log('order is not paid | params['.json_encode($list).']'.' | sql['.$this->active_merage_order_model->db->last_query().')]');
                                            continue;
                                        }
                                        break;

                                    case Lib_Constants::ORDER_TYPE_BAG:
                                        $this->load->model('bag_order_model');
                                        $order_detail = $this->bag_order_model->get_bag_order_by_id($list['iUin'],$list['sOrderId']);
                                        if(is_array($order_detail)){
                                            $order_detail['iPayStatus'] = $order_detail['iStatus'];
                                            $order_detail['iPayCoupon'] = $order_detail['iPayCoupon'];
                                        }
                                        if(!$this->order_service->is_paid($order_detail)){
                                            $this->log('order is not paid | params['.json_encode($list).']');
                                            continue;
                                        }
                                        break;

                                    default :
                                        $this->log('order type error | params['.json_encode($list).']');
                                        continue;
                                }

                                //检查退券,是否超过订单券,防止订单重复退款
                                $query = $this->order_refund_model->get_row('SUM(iRefundPrice) AS iRefundPrice,SUM(iRefundCoupon) AS iRefundCoupon',array('sOrderId'=>$list['sOrderId'],'iPayAgentType'=>Lib_Constants::ORDER_PAY_TYPE_COUPON));
                                if(!empty($query) && $order_detail['iPayCoupon'] < $query['iRefundCoupon']){
                                    $this->log('order coupon less than refund coupon | params['.json_encode($list).']');
                                    continue;
                                }


                                $data = array(
                                    'sRefundId'=> 0,
                                    'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_SUCC,
                                    'iRetCode' => Lib_Errors::SUCC,
                                    'sRetDetail' => 'SUCCESS'
                                );
                                if(!$this->order_refund_model->update_row($data,array('sRefundKey' =>$list['sRefundKey'],'iUin'=>$list['iUin']))){
                                    $this->log('order refund status update fail | params['.json_encode($list).'] | sql['.$this->order_refund_model->db->last_query().')]');
                                    continue;
                                }else{
                                    if(!$this->refundCoupon($list['iUin'],$list['iRefundCoupon'],$list['sRefundKey'])){
                                        $this->log('refund refund fail | params['.json_encode($list));
                                        continue;
                                    }
                                }
                            }


                            break;
                        case Lib_Constants::ORDER_PAY_TYPE_QQ: //手Q支付
                            break;

                        case Lib_Constants::ORDER_PAY_TYPE_CFT: //财付通支付
                            break;

                        case Lib_Constants::ORDER_PAY_TYPE_ZFB:  //支付宝
                            break;

                        default:

                    }


                    //退款成功或者更新退款状态之后同步到订单中
                    if(isset($refund_data) && !empty($refund_data)){

                    }
                }
            }

            $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
            //echo "DONE!!!!";
        //}
    }


    /**
     * @param $data
     * 退款成功，则更新订单退款信息
     */
    private function refund_order_status($data)
    {

    }


    /**退款返夺宝券
     * @param $uin
     * @param $coupon
     * @param $refund_id
     * @return bool
     */
    private function refundCoupon($uin, $coupon, $refund_id)
    {
        $user_ext_info = $this->user_ext_model->get_row($uin);
        if (empty($user_ext_info)) {
            $this->log( '| No user info. sql['.$this->user_ext_model->db->last_query().']');
            return false;
        }

        $data = array(
            'iCoupon' => $coupon
        );
        if($ret = $this->user_ext_model->update_count($data, $uin)) {
            $this->log('| DB |  update user coupon count | success | affected rows: '.intval($ret).' | sql : '.$this->user_ext_model->db->last_query().'');
            if($ret = $this->addCouponActionLog($uin, $refund_id, Lib_Constants::REFUND_COUPON, $coupon)) {
                return true;
            }
        } else {
            $this->log('| DB |  update user coupon count | failed | sql : '.$this->user_ext_model->db->last_query().'');
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
    private function addCouponActionLog($uin, $refund_id, $action, $coupon)
    {
        $aInsert = array(
            'iUin' => $uin,
            'iAction' => $action,
            'iNum' => $coupon,
            'sExt' => $refund_id,
            'iAddTime' => time(),
            'iType' => Lib_Constants::ACTION_INCOME,
        );
        $ret = $this->coupon_action_log_model->add_row($aInsert);

        if ($ret) {
            $this->log('| DB |  add user coupon action log | success | logid: '.$ret.' | data:  '.json_encode($aInsert).' | sql : '.$this->coupon_action_log_model->db->last_query().'');
        } else {
            $this->log('| DB |  add user coupon action log | failed | data:  '.json_encode($aInsert).' | sql : '.$this->coupon_action_log_model->db->last_query().'');
        }
    }



    /**
     * 锁定任务，避免重复发起任务
     */
    protected function lock($uin,$RefundKey)
    {
        if($this->order_refund_model->query("UPDATE `t_order_refund` SET `iLocked`=1,`iTryTimes`=`iTryTimes`+1,`iLastModTime`=".time()." WHERE `sRefundKey`='".$RefundKey."' AND iUin='".$uin."'", true)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 解锁并且保存退款结果
     */
    protected function unlock_save($uin,$RefundKey,$data,$islock = 0)
    {
        $data = array(
            'iLocked' => $islock,
            'sRefundId'=>$data['sRefundId'],
            'iRetStatus' => $data['iRetStatus'],
            'iRetCode' => $data['iRetCode'],
            'sRetDetail' => $data['sRetDetail'],
            'iLastModTime' => time()
        );
        if($this->order_refund_model->update_row($data,array('sRefundKey'=>$RefundKey,'iUin'=>$uin))){
            return true;
        }else{
            return false;
        }
    }
}