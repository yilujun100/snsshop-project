<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * 计划每半小时跑一次
 * 更新退款状态
 * @date 2016-03-22
 * @autor leo.zou
 */

require_once APPPATH.'third_party/payagent/TenpayFacade.php';
require_once APPPATH.'third_party/payagent/AlipayFacade.php';
require_once APPPATH.'third_party/payagent/WeiXinFacade.php';

class Refund_fix extends Script_Base
{
    const LIMIT = 1000;  //每次脚本处理记录数
    const REPEAT = 3;//操作失败，则重复操作次数

    protected $log_type = 'Refund_fix';
    protected $tenpayFacade;//财付通类库
    protected $alipayFacade;//支付宝类库
    protected $weixinFacade; //微信类库
    protected $qqFacade; //手Q类库

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

        set_time_limit(0);
    }

    /**
     * 每天一次脚本
     */
    public function run_day() {
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        $refund_list = $this->order_refund_model->get_rows(array('iRetStatus  ='=>Lib_Constants::REFUND_RET_STATUS_SUBMIT,'iLocked'=>0,'sTransId !=' => '0'));
        $this->refund_fix($refund_list);
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    public function run() {
        $this->log("====================================BEING(".date('Y-m-d H:i:s').")=============================================");
        $refund_list = $this->order_refund_model->get_rows(array('iRetStatus  ='=>Lib_Constants::REFUND_RET_STATUS_SUBMIT,'iLocked'=>0,'sTransId !=' => '0','iFixTimes <='=> Lib_Constants::SCRIPT_REFUND_FIX_TIMES));
        $this->refund_fix($refund_list);
        $this->log("====================================END(".date('Y-m-d H:i:s').")=============================================\n\n");
    }

    private function refund_fix($refund_list)
    {
        if (!empty($refund_list) && is_array($refund_list)){
            foreach ($refund_list as $list) {
                switch ($list['iPayAgentType']) {
                    case Lib_Constants::ORDER_PAY_TYPE_WX://微信支付
                        if (!empty($list['iRefundPrice'])) {
                            //锁定订单并且处理次数+1
                            if(!$this->lock($list['iUin'],$list['sRefundKey'])){
                                $this->log('order lock fail | params['.json_encode($list).']');
                                continue;
                            }

                            //查询退款信息
                            $arrRefundInfo = $this->weixinFacade->queryRefund( $list['sRefundKey'], $list['sTransId']);
                            if(empty($arrRefundInfo)){
                                $this->log('refund not found | params['.json_encode($list).']');
                                $this->unlock_save($list['iUin'],$list['sRefundKey']);
                                continue;
                            } elseif ($arrRefundInfo['refund_status_0'] == 'SUCCESS') { //退款成功
                                $data = array(
                                    'iRetStatus' => Lib_Constants::REFUND_RET_STATUS_SUCC,
                                    'iRefundId' => $arrRefundInfo['refund_id_0'],
                                    'iRetCode' => $arrRefundInfo['result_code'],
                                    'sRetDetail' => json_encode($arrRefundInfo),
                                    'iLastModTime' => time()
                                );
                                $refund_data = array_merge($data,array('iRefundPrice' => $arrRefundInfo['refund_fee_0']));
                                if ($this->unlock_save($list['iUin'],$list['sRefundKey'],$refund_data)) { //更新订单状态
                                    $this->refund_order_status($list, $arrRefundInfo);
                                }
                            } else { //直接更新结果 不该状态
                                $data = array(
                                    'iRetCode' => $arrRefundInfo['result_code'],
                                    'sRetDetail' => json_encode($arrRefundInfo)
                                );
                                $this->unlock_save($list['iUin'],$list['sRefundKey'],$data);
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
            }
        }
    }


    /**
     * @param $data
     * 退款成功，则更新订单退款信息
     */
    private function refund_order_status($data, $arrRefundInfo)
    {
        switch ($data['iBuyType']) {
            case Lib_Constants::ORDER_TYPE_GROUPON:
                $this->load->model('groupon_order_model');
                if(in_array($data['iRefundType'], Lib_Constants::$rebate_refund_type)) {
                    $udata = array(
                        'iRebatedAmount' => $arrRefundInfo['refund_fee_0'],
                        'iRebateStatus' =>  Lib_Constants::GROUPON_REBATE_DONE
                    );
                } else {
                    $udata = array(
                        'iRefundedAmount' => $arrRefundInfo['refund_fee_0'],
                        'iRefundStatus' =>  Lib_Constants::GROUPON_REBATE_DONE
                    );
                }

                if($this->groupon_order_model->update_row($udata, array('sOrderId'=>$data['sOrderId']))) {
                    return true;
                } else {
                    $this->log("update groupon order status failed | data:".json_encode($data)."");
                    return false;
                }
                break;
        }
    }


    /**
     * 锁定任务，避免重复发起任务
     */
    protected function lock($uin,$RefundKey)
    {
        if($this->order_refund_model->query("UPDATE `t_order_refund` SET `iLocked`=1,`iFixTimes`=`iFixTimes`+1,`iLastModTime`=".time()." WHERE `sRefundKey`='".$RefundKey."' AND iUin='".$uin."'", true)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 解锁并且保存退款结果
     */
    protected function unlock_save($uin,$RefundKey,$data=array(),$islock = 0)
    {
        $update_data = array();
        if (!empty($data)) {
            if (isset($data['sRefundId'])) {
                $update_data['sRefundId'] = $data['sRefundId'];
            }
            if (isset($data['iRetStatus'])) {
                $update_data['iRetStatus'] = $data['iRetStatus'];
            }
            if (isset($data['iRetCode'])) {
                $update_data['iRetCode'] = $data['iRetCode'];
            }
            if (isset($data['sRetDetail'])) {
                $update_data['sRetDetail'] = $data['sRetDetail'];
            }
        }

        $update_data['iLastModTime'] = time();
        $update_data['iLocked'] = $islock;

        if($this->order_refund_model->update_row($update_data,array('sRefundKey'=>$RefundKey,'iUin'=>$uin))){
            $this->log('| DB |  unlock_save | success | data:  '.json_encode($data).' | sql : '.$this->order_refund_model->db->last_query().'');
            return true;
        }else{
            $this->log('| DB |  unlock_save | failed | data:  '.json_encode($data).' | sql : '.$this->order_refund_model->db->last_query().'');
            return false;
        }
    }
}