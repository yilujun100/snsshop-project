<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends Admin_Base
{
    /**
     * 日期格式
     */
    const DATE_PATTERN = '/^\d{8}$/';

    /**
     * 数据源文件目录
     *
     * @var string
     */
    protected static $data_dir;

    /**
     * 开始时间
     *
     * @var
     */
    protected $begin;

    /**
     * 结束时间
     *
     * @var
     */
    protected $end;

    /**
     * 源数据
     *
     * @var
     */
    protected $data;

    /**
     * Tools constructor
     */
    public function __construct()
    {
        parent::__construct();

        self::$data_dir = APPPATH . 'data/';
    }


    /**
	 * 运行工具
	 */
	public function run()
	{
        set_time_limit(0);
        $tool = $this->get_post('tool', '');
        if (! is_callable(array($this, $tool))) {
            show_error('参数错误');
        }
        $this->{$tool}();
	}

    /**
     * 拼团-发货表
     */
    protected function groupon_deliver_order()
    {
        /**
         * 2016/05/17 06:00:00 1463436000
         * 2016/05/19 23:59:59 1463673599
         */

        $this->init_time();

        $log_label = 'admin tool | groupon_deliver_order | ' . $this->get_log_series();

        $this->load->model('order_deliver_model');
        $this->load->model('groupon_order_model');

        $sql = "SELECT iUin,sOrderId,sName,sMobile,sAddress,sRemark FROM `t_order_deliver` WHERE iType=6 AND iCreateTime>={$this->begin} AND iCreateTime<={$this->end} AND iDeliverStatus=0 AND iConfirmStatus=0 ORDER BY iCreateTime ASC;";
        $deliver_arr = $this->order_deliver_model->query($sql, true);

        $data = array();

        $order_not_exist = 0;
        $order_not_paid = 0;
        $order_refunded = 0;
        $order_deliver = 0;

        foreach ($deliver_arr as $deliver) {
            $log_data = array('deliver' => $deliver);

            $order = $this->groupon_order_model->get_row($deliver['sOrderId']);

            if (empty($order)) { // 订单不存在
                $this->log->alert($log_label, 'order not exist', $log_data);
                $order_not_exist ++;
                continue;
            }
            $log_data['order'] = array(
                'iSpecId'=>$order['iSpecId'],
                'iDiyId'=>$order['iDiyId'],
                'iPayAmount'=>$order['iPayAmount'],
                'sTransId'=>$order['sTransId'],
                'sName'=>$order['sName']
            );
            if (Lib_Constants::PAY_STATUS_PAID != $order['iPayStatus']) { // 未支付
                $this->log->alert($log_label, 'order not paid', $log_data);
                $order_not_paid ++;
                continue;
            }
            if ($order['iRefundedAmount'] > 0 || $order['iRefundingAmount'] > 0) { // 已退款
                $this->log->alert($log_label, "order has refunded", $log_data);
                $order_refunded ++;
                continue;
            }

            $order_deliver ++;

            $row = array(
                '`' . $order['sOrderId'],
                '`' . $order['sTransId'],
                '`' . $order['iUin'],
                $deliver['sName'],
                '`' . $deliver['sMobile'],
                $deliver['sAddress'],
                date('Y-m-d H:i:s', $order['iPayTime']),
                price_format($order['iPayAmount']),
                $order['iCount'],
                '',
                '',
                '',
                $deliver['sRemark']
            );

            $this->log->alert($log_label, "order deliver row", $row);

            $data[] = $row;
        }
        $this->log->alert($log_label, "deliver order summary", array(
            'order_not_exist' => $order_not_exist,
            'order_not_paid' => $order_not_paid,
            'order_refunded' => $order_refunded,
            'order_deliver' => $order_deliver,
        ));
        $head = array(
            array('value'=>'订单号', 'width'=>26),
            array('value'=>'流水号', 'width'=>30),
            array('value'=>'用户ID', 'width'=>21),
            array('value'=>'姓名', 'width'=>10),
            array('value'=>'手机', 'width'=>15),
            array('value'=>'收货地址', 'width'=>35),
            array('value'=>'支付时间', 'width'=>20),
            array('value'=>'支付金额', 'width'=>11),
            array('value'=>'购买数量', 'width'=>11),
            array('value'=>'物流公司代号', 'width'=>15),
            array('value'=>'物流公司名称', 'width'=>25),
            array('value'=>'快递单号', 'width'=>27),
            array('value'=>'备注', 'width'=>25),
        );
        $excel = new Lib_Excel($head, $data);
        $excel->download('拼团发货表-' . date('Ymd', $this->begin) . '-' . date('Ymd', $this->end) . '.xlsx');
    }

    /**
     * 拼团-退款
     */
    protected function groupon_refund()
    {
        $this->init_data();

        $log_label = 'admin tool | groupon_refund | ' . $this->get_log_series();

        $this->load->service('groupon_service');

        $failed = $success = 0;

        foreach ($this->data as $row => $item) {
            $log_data = array(
                'order_source' => $item[0],
                'row' => $row
            );

            $order_id = trim($item[0], '` ');

            $log_data['order_id'] = $order_id;

            if (($ret = $this->groupon_service->groupon_order_refund($order_id, Lib_Constants::REFUND_TYPE_CUSTOMER)) < Lib_Errors::SUCC) {
                $failed ++;
                $log_data['error'] = $ret;
                $this->log->alert($log_label, 'refund failed', $log_data);
                $item['error'] = $ret;
                pr($item);
            } else {
                $success ++;
                $this->log->alert($log_label, 'refund success', $log_data);
            }
        }

        pr("成功: {$success}; 失败: {$failed}");
    }

    /**
     * 拼团-批量发货
     */
    protected function groupon_deliver()
    {
        $this->init_data();

        $log_label = 'admin tool | groupon_deliver | ' . $this->get_log_series();

        $this->load->model('groupon_order_model');
        $this->load->model('order_deliver_model');
        $this->load->model('user_model');
        $this->load->service('groupon_service');

        $notify = get_variable('notify_on');

        $failed = $success = 0;

        foreach ($this->data as $row => $item) {
            $log_data = array(
                'order_source' => $item[0],
                'row' => $row
            );

            $order_id = trim($item[0], '\'` ');

            $log_data['order_id'] = $order_id;

            $order = $this->groupon_order_model->get_row($order_id, true, false);

            if (empty($order)) { // 订单不存在
                $this->log->alert($log_label, 'order not exist', $log_data);
                $item['error'] = 'order not exist';
                $failed ++;
                pr($item);
                continue;
            }
            $log_data['order'] = array(
                'iSpecId'=>$order['iSpecId'],
                'iDiyId'=>$order['iDiyId'],
                'iPayAmount'=>$order['iPayAmount'],
                'sTransId'=>$order['sTransId'],
                'sName'=>$order['sName']
            );
            if (Lib_Constants::PAY_STATUS_PAID != $order['iPayStatus']) { // 未支付
                $this->log->alert($log_label, 'order not paid', $log_data);
                $item['error'] = 'not paid';
                $failed ++;
                pr($item);
                continue;
            }
            if ($order['iRefundedAmount'] > 0 || $order['iRefundingAmount'] > 0) { // 已退款
                $this->log->alert($log_label, "order has refunded", $log_data);
                $item['error'] = 'has refunded';
                $failed ++;
                pr($item);
                continue;
            }

            $now = time();
            $ext[3] = $now;
            $ext[4] = $now;

            $expressId = trim($item[4]);
            $expressName = '顺丰快递';
            $expId = 5;

            if (empty($expressId)) {
                $this->log->alert($log_label, "express data exception", $log_data);
                $item['error'] = 'express data exception';
                $failed ++;
                pr($item);
                continue;
            }

            $user = $this->user_model->get_user_by_uin($order['iUin']);

            if (empty($user)) { // 用户不存在
                $this->log->alert($log_label, 'user does not exist', $log_data);
                $item['error'] = 'user does not exist';
                pr($item);
                $failed ++;
                continue;
            }
            if (empty($user['iContactState'])) { // 用户未关注
                $this->log->alert($log_label, 'user not contact', $log_data);
                $item['error'] = 'user not contact';
                pr($item);
                $failed ++;
                continue;
            }

            $notify_params = array(
                'express_id' => $expressId,
                'deliver_user' => $order['sName'].','.$order['sMobile'].','.$order['sAddress'],
                'openId' => $user['sOpenId'],
                'url' => gen_uri('/order/detail', array('order_id'=>$order['sOrderId']), 'groupon'),
            );

            if (! $notify || 'on' != $notify) {
                pr($notify_params);
                $success ++;
            } else {
                $data = array(
                    'sExpressId' => $expressId,
                    'iExpId' => $expId,
                    'sExpressName' => $expressName,
                    'sExtField' => json_encode($ext),
                    'iDeliverStatus' => 1,
                );
                $params = array(
                    'sOrderId' => $order_id
                );
                if ($this->order_deliver_model->update_row($data, $params)) {
                    $this->load->service('order_service');
                    if (($ret = $this->order_service->confirm_deliver($order['iUin'], $order['sOrderId'])) != Lib_Errors::SUCC) {
                        $this->log->alert($log_label, 'confirm_deliver failed', array('code'=>$ret,'order'=>$order));
                        $item['error'] = 'confirm_deliver failed';
                        pr($item);
                        $failed ++;
                        continue;
                    }
                } else {
                    $this->log->alert($log_label, 'update order_deliver_model failed', $log_data);
                    $item['error'] = 'update order_deliver_model failed';
                    pr($item);
                    $failed ++;
                    continue;
                }

                $log_data['params'] = $notify_params;
                if (Lib_WeixinNotify::deliverNotify($notify_params)) { // 通知成功
                    $this->log->alert($log_label, "notify success", $log_data);
                    $success ++;
                } else {
                    $this->log->alert($log_label, "notify failed", $log_data);
                    $item['error'] = 'notify failed';
                    pr($item);
                    $failed ++;
                }
            }
        }

        pr("成功: {$success}; 失败: {$failed}");
    }

    /**
     * 预售-批量发货
     */
    protected function groupon_deliver_wtg()
    {
        if (1 == $this->get('type', 0)) {
            $order_desc = '【预售】恋红妆烟台大樱桃限量预售109元/3斤';
        } else {
            $order_desc = '【预售】恋红妆烟台大樱桃限量预售89元/3斤';
        }

        $this->init_data();

        $log_label = 'admin tool | groupon_deliver | ' . $this->get_log_series();

        $notify = get_variable('notify_on');

        $this->load->model('user_model');

        $failed = $success = $pc = 0;

        foreach ($this->data as $row => $item) {
            $log_data = $item;

            if (! ($open_id = $this->get_open_id_wtg($item[1]))) {
                $this->log->alert($log_label, 'not wtg user', $log_data);
                $pc++;
                continue;
            }

            $user = $this->user_model->get_wx_user_by_openid($open_id);
            $log_data['open_id'] = $open_id;
            if (empty($user)) {
                $error = 'user not exist';
                $this->log->alert($log_label, $error, $log_data);
                $item['error'] = $error;
                $failed ++;
                pr($item);
                continue;
            }
            if (empty($user['iContactState'])) { // 用户未关注
                $error = 'user not contact';
                $this->log->alert($log_label, $error, $log_data);
                $item['error'] = $error;
                $failed ++;
                pr($item);
                continue;
            }

            $order_id = substr(trim($item[0]), 1);

            $notify_params = array(
                'order_desc' => $order_desc,
                'express_id' => trim($item[2]),
                'deliver_user' =>trim($item[3]).','.trim($item[4]).','.trim($item[5]),
                'openId' => $open_id,
                'url' => 'http://w.gaopeng.com/order/detail/'.$order_id.'/1?dealId=888897&S=',
            );

            if (! $notify || 'on' != $notify) {
                pr($notify_params);
                $success ++;
            } else {
                $log_data['params'] = $notify_params;
                if (Lib_WeixinNotify::deliverNotify($notify_params)) { // 通知成功
                    $this->log->alert($log_label, "notify success", $log_data);
                    $success ++;
                } else {
                    $this->log->alert($log_label, "notify failed", $log_data);
                    $item['error'] = 'notify failed';
                    $failed ++;
                    pr($item);
                }
            }
        }

        pr("成功: {$success}; 失败: {$failed}; PC: {$pc}");
    }

    /**
     * 预售-取消订单
     */
    protected function cancel_order_wtg()
    {
        $log_label = 'deliver_delay_notify | cancel_order | ' . $this->get_log_series();
        $data = Lib_Excel::loadToArray(APPPATH . 'data/cancel_order.xlsx');
        unset($data[0]);
        $notify = get_variable('notify_on');
        $this->load->model('user_model');
        $user_not_exist = 0;
        $user_not_contact = 0;
        $user_not_wtg = 0;

        foreach ($data as $item) {
            $order = trim($item[0]);
            $user_id = trim($item[15]);
            $log_data = array(
                'order_id' => $order,
                'user_id' => $user_id
            );
            if (empty($user_id)) {
                $this->log->alert($log_label, "user empty", $log_data);
                continue;
            }
            if (false === strpos($user_id, '@wtg.gaopeng.com')) {
                $this->log->alert($log_label, 'not wtg user', $log_data);
                $user_not_wtg ++;
                continue;
            }
            $open_id = trim(str_replace('@wtg.gaopeng.com', '', $user_id));
            $user = $this->user_model->get_wx_user_by_openid($open_id);
            $log_data['open_id'] = $open_id;
            if (empty($user)) {
                $this->log->alert($log_label, "user not exist", $log_data);
                $user_not_exist ++;
                continue;
            }
            if (empty($user['iContactState'])) { // 用户未关注
                $this->log->alert($log_label, 'user not contact', $log_data);
                $user_not_contact ++;
                continue;
            }
            $order_id = substr($order, 1);
            $params = array(
                'subject' => '樱桃小君基地前方来报',
                'pay_amount' => '89.00元',
                'refund_type' => '原支付账户',
                'refund_time' => '7个工作日',
                'goods_name' => '【预售】恋红妆烟台大樱桃限量预售89元/3斤',
                'order_id' => $order_id,
                'refund_reason' => '亲，您好！由于您购买樱桃的收货地址不在顺丰物流派送范围内，所以需给您取消订单，因客服已联系您多次，无法联系到您，所以请您看到此消息后于5月24日前联系客服400-100-6715。超过5月24日，系统将自动退款至您的原账户，请及时留意到账信息，谢谢',
                'remark' => '请及时留意到账信息，感谢您的理解与支持，谢谢~',
                'openId' => $open_id,
                'url' => "http://w.gaopeng.com/order/detail/{$order_id}/1?dealId=888897&S=",
            );
            if (! $notify || 'on' != $notify) {
                pr($params);
            } else {
                $log_data['params'] = $params;
                if (Lib_WeixinNotify::cancelOrderNotify($params)) { // 通知成功
                    $this->log->alert($log_label, "notify success", $log_data);
                } else {
                    $this->log->alert($log_label, "notify failed", $log_data);
                }
            }
        }
        $this->log->alert($log_label, "summary", array(
            'user_not_exist' => $user_not_exist,
            'user_not_contact' => $user_not_contact,
            'user_not_wtg' => $user_not_wtg,
        ));
        if (! empty($params)) {
            $params['openId'] = $this->get_my();
            Lib_WeixinNotify::cancelOrderNotify($params);
        }
    }

    /**
     * 拼团-延迟发货通知
     */
    protected function delay_groupon()
    {
        /**
         * 2016/05/17 06:00:00 1463436000
         * 2016/05/19 23:59:59 1463673599
         */
        $begin = 1463436000;
        $end = 1463673599;

        $log_label = 'admin tool | delay_groupon | ' . $this->get_log_series();

        $this->load->model('order_deliver_model');
        $this->load->model('user_model');
        $this->load->model('groupon_order_model');

        $sql = "SELECT iUin,sOrderId,sName,sMobile,sAddress,sRemark FROM `t_order_deliver` WHERE iType=6 AND iCreateTime>={$begin} AND iCreateTime<={$end} AND iDeliverStatus=0 AND iConfirmStatus=0 ORDER BY iCreateTime ASC;";
        $deliver_arr = $this->order_deliver_model->query($sql, true);

        $user_not_exist = 0;
        $user_not_contact = 0;
        $order_not_exist = 0;
        $order_not_paid = 0;
        $order_refunded = 0;
        $order_deliver = 0;

        $notify = get_variable('notify_on');

        foreach ($deliver_arr as $deliver) {

            $log_data = array('deliver' => $deliver);

            $user = $this->user_model->get_user_by_uin($deliver['iUin']);
            if (empty($user)) { // 用户存在
                $this->log->alert($log_label, 'user does not exist', $log_data);
                $user_not_exist ++;
                continue;
            }
            if (empty($user['iContactState'])) { // 用户未关注
                $this->log->alert($log_label, 'user not contact', $log_data);
                $user_not_contact ++;
                continue;
            }

            $log_data['user'] = $user;

            $order = $this->groupon_order_model->get_row($deliver['sOrderId']);

            if (empty($order)) { // 订单不存在
                $this->log->alert($log_label, "order does not exist", $log_data);
                $order_not_exist ++;
                continue;
            }
            $log_data['order'] = array(
                'iSpecId'=>$order['iSpecId'],
                'iDiyId'=>$order['iDiyId'],
                'iPayAmount'=>$order['iPayAmount'],
                'sTransId'=>$order['sTransId'],
                'sName'=>$order['sName'],
            );
            if (empty($order['iPayStatus']) || Lib_Constants::PAY_STATUS_PAID != $order['iPayStatus']) { // 未支付
                $this->log->alert($log_label, "order did not paid", $log_data);
                $order_not_paid ++;
                continue;
            }
            if ($order['iRefundedAmount'] > 0 || $order['iRefundingAmount'] > 0) { // 已退款
                $this->log->alert($log_label, "order has refunded", $log_data);
                $order_refunded ++;
                continue;
            }

            $order_deliver ++;

            $params = array(
                'subject' => '樱桃小君基地前方来报',
                'goods_name' => '【超惠拼】恋红妆•烟台大樱桃3人成团，3斤仅需99元',
                'order_id' => $order['sOrderId'],
                'order_time' => $order['iCreateTime'],
                'reason_for_delay' => '亲爱的小伙伴，偷偷告诉你，现在的我已经裹上浅红色的衣装了,正期待与你见面！为了让你遇见最美和口感最好的我，所以我还需吸收更多养分，要比我们约好的日子（5月20日）稍微晚到3-5天噢~~相信我，绝不让你失望。待我成熟时，一定会遇见更美丽的我！',
                'remark' => '',
                'openId' => $user['sOpenId'],
                'url' => gen_uri('/order/detail', array('order_id'=>$order['sOrderId']), 'groupon'),
            );

            if (! $notify || 'on' != $notify) {
                pr($params);
            } else {
                $log_data['params'] = $params;
                if (Lib_WeixinNotify::dailyDeliverNotify($params)) { // 通知成功
                    $this->log->alert($log_label, "notify success", $log_data);
                } else {
                    $this->log->alert($log_label, "notify failed", $log_data);
                }
            }
        }
        $this->log->alert($log_label, "deliver order summary", array(
            'user_not_exist' => $user_not_exist,
            'user_not_contact' => $user_not_contact,
            'order_not_exist' => $order_not_exist,
            'order_not_paid' => $order_not_paid,
            'order_refunded' => $order_refunded,
            'order_deliver' => $order_deliver,
        ));
        if (! empty($params)) {
            $params['openId'] = $this->get_my();
            Lib_WeixinNotify::dailyDeliverNotify($params);
        }
    }

	/**
	 * 积攒-延迟发货通知
	 */
	protected function delay_jizan()
	{
		$log_label = 'deliver_delay_notify | delay_jizan | ' . $this->get_log_series();

		$data = Lib_Excel::loadToArray(APPPATH . 'data/delay_deliver_jizan.xlsx');
        unset($data[0]);

        $this->load->model('user_model');

        $notify = get_variable('notify_on');

        $user_not_exist = 0;
        $user_not_contact = 0;

		foreach ($data as $item) {
			$log_data = array('data' => $item);
            $user_id = trim($item[2]);
            if (empty($user_id)) {
                $this->log->alert($log_label, "user empty", $log_data);
                continue;
            }
            $user = $this->user_model->get_wx_user_by_openid($user_id);
            if (empty($user)) {
                $this->log->alert($log_label, "user not exist", $log_data);
                $user_not_exist ++;
                continue;
            }
            if (empty($user['iContactState'])) { // 用户未关注
                $this->log->alert($log_label, 'user not contact', $log_data);
                $user_not_contact ++;
                continue;
            }
			$params = array(
				'subject' => '樱桃小君基地前方来报',
				'goods_name' => '【集赞】送价值139元3斤烟台大樱桃',
				'order_id' => '已中奖',
				'order_time' => '1463155199',
				'reason_for_delay' => '亲爱的小伙伴，偷偷告诉你，现在的我已经裹上浅红色的衣装了,正期待与你见面！为了让你遇见最美和口感最好的我，所以我还需吸收更多养分，要比我们约好的日子（5月20日）稍微晚到3-5天噢~~相信我，绝不让你失望。待我成熟时，一定会遇见更美丽的我！',
				'remark' => '',
				'openId' => $user_id,
				'url' => 'http://w.gaopeng.com/active/may/cherryGame/',
			);
			if (! $notify || 'on' != $notify) {
				pr($params);
			} else {
				$log_data['params'] = $params;
				if (Lib_WeixinNotify::dailyDeliverNotify($params)) { // 通知成功
					$this->log->alert($log_label, "notify success", $log_data);
				} else {
					$this->log->alert($log_label, "notify failed", $log_data);
				}
			}
		}
        $this->log->alert($log_label, "summary", array(
            'user_not_exist' => $user_not_exist,
            'user_not_contact' => $user_not_contact
        ));
        if (! empty($params)) {
            $params['openId'] = $this->get_my();
            Lib_WeixinNotify::dailyDeliverNotify($params);
        }
	}

	/**
	 * 预售-延迟发货通知
	 */
	protected function delay_yushou()
	{
		$log_label = 'deliver_delay_notify | delay_yushou | ' . $this->get_log_series();
		$data = Lib_Excel::loadToArray(APPPATH . 'data/delay_deliver_yushou.xlsx');
		unset($data[0]);
        $notify = get_variable('notify_on');

        $this->load->model('user_model');

        $user_not_exist = 0;
        $user_not_contact = 0;
        $user_not_wtg = 0;

		foreach ($data as $item) {

            $order = trim($item[0]);
            $user_id = trim($item[15]);

            $log_data = array(
                'order_id' => $order,
                'user_id' => $user_id
            );
            if (empty($user_id)) {
                $this->log->alert($log_label, "user empty", $log_data);
                continue;
            }
			if (false === strpos($user_id, '@wtg.gaopeng.com')) {
				$this->log->alert($log_label, 'not wtg user', $log_data);
                $user_not_wtg ++;
				continue;
			}
			$open_id = trim(str_replace('@wtg.gaopeng.com', '', $user_id));
            $user = $this->user_model->get_wx_user_by_openid($open_id);
            $log_data['open_id'] = $open_id;
            if (empty($user)) {
                $this->log->alert($log_label, "user not exist", $log_data);
                $user_not_exist ++;
                continue;
            }
            if (empty($user['iContactState'])) { // 用户未关注
                $this->log->alert($log_label, 'user not contact', $log_data);
                $user_not_contact ++;
                continue;
            }
			$order_id = substr($order, 1);
			$date_time_str = substr($order_id,10, 4) . '-' .
				substr($order_id,14, 2) . '-' .
				substr($order_id,16, 2) . ' ' .
				substr($order_id,18, 2) . ':' .
				substr($order_id,20, 2) . ':' .
				substr($order_id,22, 2) . ' ';
			$order_time = strtotime($date_time_str);
			$params = array(
				'subject' => '樱桃小君基地前方来报',
				'goods_name' => '【预售】恋红妆烟台大樱桃限量预售89元/3斤',
				'order_id' => $order_id,
				'order_time' => $order_time,
				'reason_for_delay' => '亲爱的小伙伴，偷偷告诉你，现在的我已经裹上浅红色的衣装了,正期待与你见面！为了让你遇见最美和口感最好的我，所以我还需吸收更多养分，要比我们约好的日子（5月20日）稍微晚到3-5天噢~~相信我，绝不让你失望。待我成熟时，一定会遇见更美丽的我！',
				'remark' => '',
				'openId' => $open_id,
				'url' => "http://w.gaopeng.com/order/detail/{$order_id}/1?dealId=888897&S=",
			);
            if (! $notify || 'on' != $notify) {
				pr($params);
			} else {
				$log_data['params'] = $params;
				if (Lib_WeixinNotify::dailyDeliverNotify($params)) { // 通知成功
					$this->log->alert($log_label, "notify success", $log_data);
				} else {
					$this->log->alert($log_label, "notify failed", $log_data);
				}
			}
		}
        $this->log->alert($log_label, "summary", array(
            'user_not_exist' => $user_not_exist,
            'user_not_contact' => $user_not_contact,
            'user_not_wtg' => $user_not_wtg,
        ));
        if (! empty($params)) {
            $params['openId'] = $this->get_my();
            Lib_WeixinNotify::dailyDeliverNotify($params);
        }
	}

    /**
     * 主页
     */
    public function index()
    {
        phpinfo();
    }

    /**
     * 初始化数据
     */
    protected function init_data()
    {
        $data_name = $this->get('data');
        $file = self::$data_dir . $data_name;
        if (! file_exists($file)) {
            show_error('数据源文件不存在');
        }
        $data = Lib_Excel::loadToArray($file);
        unset($data[0]);
        if (empty($data)) {
            show_error('文件数据为空');
        }
        $this->data = $data;
    }

    /**
     * 初始化起始时间
     */
    protected function init_time()
    {
        $begin = $this->get('begin');
        $end = $this->get('end');

        if (! preg_match(self::DATE_PATTERN, $begin)) {
            show_error('开始时间格式错误');
        }
        if (! preg_match(self::DATE_PATTERN, $end)) {
            show_error('结束时间格式错误');
        }

        $this->begin = strtotime($begin);
        $this->end = strtotime($end) + 86399;
    }

    /**
     * 获取 uin
     *
     * @param $user_id
     *
     * @return string|void
     */
    protected function get_open_id_wtg($user_id)
    {
        if (empty($user_id)) {
            return;
        }
        $wtg = '@wtg.gaopeng.com';
        if (false === strpos($user_id, $wtg)) {
            return;
        }
        return trim(str_replace($wtg, '', $user_id));
    }

    /**
     * 开发openid
     *
     * @return string
     */
    protected function get_my()
    {
        if ('development' == ENVIRONMENT) {
            return 'oCNCMs1mm2IWmikSYB987CjgEUG8';
        }
        return 'ooNLujm9MQ_2sms-yzBKe1t93-DE';
    }

    /**
     * 日志序列号
     *
     * @return string
     */
    protected function get_log_series()
    {
        $log_series = get_variable('log_series');
        if (empty($log_series)) {
            $log_series = date('YmdHis');
        }
        return $log_series;
    }
}
