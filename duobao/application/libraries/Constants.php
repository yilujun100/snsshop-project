<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 数据库中tinyint字段键值映射
 * Class Constants
 */
class Lib_Constants {
    const PLATFORM_ROBOT  = 0;    // 平台-机器人
    const PLATFORM_WX     = 1;      // 平台-微信
    const PLATFORM_PC     = 2;      // 平台-PC
    const PLATFORM_APP    = 3;      // 平台-APP

    const PLATFORM_WTG    = 4;      // 平台-微团购

    /**
     * 平台列表
     * @var array
     */
    public static $platforms =  array(
        self::PLATFORM_WX  =>  '微信',
        self::PLATFORM_PC   =>  'PC',
        self::PLATFORM_APP  =>  'APP',
    );

    /**
     * 平台UIN前缀
     * @var array
     */
    public static $uin_prefix =  array(
        self::PLATFORM_APP  =>  1000,
        self::PLATFORM_PC   =>  2000,
        self::PLATFORM_APP  =>  3000,
    );

    /**
     * 校验平台
     * @param $platform
     */
    public static function get_platform_prefix($platform) {
        return isset(self::$platforms_prefix[$platform]) ? self::$uin_prefix[$platform] : '';
    }

    /**
     * 校验平台
     * @param $platform
     */
    public static function valid_platform($platform) {
        return array_key_exists($platform, self::$platforms) ? TRUE : FALSE;
    }

    const PUBLISH_STATE_READY = 0;      //发布状态-待确认
    const PUBLISH_STATE_ONLINE = 1;     //发布状态-上线
    const PUBLISH_STATE_END = 2;     //发布状态-已结束
    const PUBLISH_STATE_OFFLINE = 3;    //发布状态-下线

    public static $publish_states = array(
        self::PUBLISH_STATE_READY => '待确认',
        self::PUBLISH_STATE_ONLINE => '已上线',
        self::PUBLISH_STATE_END => '已结束',
        self::PUBLISH_STATE_OFFLINE => '已下线',
    );

    const OPT_ONLINE = 1;           //操作-确认上线
    const OPT_EDIT = 2;             //操作-编辑
    const OPT_OFFLINE = 3;          //操作-下线
    const OPT_DELETE = 4;           //操作-删除
    const OPT_REONLINE = 5;         //操作-重新上线
    const OPT_EDIT_ONLINE = 6;      //线上编辑

    public static $publish_opts = array(
        self::OPT_ONLINE => array('action' => 'audit', 'button' => self::BTN_ONLINE, 'state' => self::PUBLISH_STATE_ONLINE),
        self::OPT_EDIT => array('action' => 'edit', 'button' => self::BTN_EDIT),
        self::OPT_OFFLINE => array('action' => 'audit', 'button' => self::BTN_OFFLINE, 'state' => self::PUBLISH_STATE_OFFLINE),
        self::OPT_DELETE => array( 'action' => 'delete', 'button' => self::BTN_DELETE),
        self::OPT_REONLINE => array('action' => 'audit', 'button' => self::BTN_REONLINE,'state' => self::PUBLISH_STATE_ONLINE),
        self::OPT_EDIT_ONLINE => array('action' => 'edit_online', 'button' => self::BTN_EDIT_ONLINE),
        self::BTN_TERMINATE => array('action' => 'terminate', 'button' => self::BTN_TERMINATE),
    );

    const NEED_VALID_ONLINE = 1;        // 需要检验是否有线上活动
    const PUBLISH_STATE_NO_CHANGE = 2;  // 无状态变更
    const PUBLISH_STATE_CAN_CHANGE = 3; // 无状态变更

    /**
     * 校验发布状态
     * @param $ori
     * @param $new
     */
    public static function valid_publish_state($ori, $new)
    {
        if($new == $ori) {
            return self::PUBLISH_STATE_NO_CHANGE;
        }
        switch ($ori) {
            case self::PUBLISH_STATE_READY: //确认状态  只能上线
                if ($new == self::PUBLISH_STATE_ONLINE) {
                    return self::PUBLISH_STATE_CAN_CHANGE;
                }
                break;
            case self::PUBLISH_STATE_ONLINE:    //上线状态 只能下线
                if ( $new == self::PUBLISH_STATE_OFFLINE) {
                    return self::NEED_VALID_ONLINE;
                }
                break;
            case self::PUBLISH_STATE_END:   //结束状态 只能下线
                if ( $new == self::PUBLISH_STATE_OFFLINE) {
                    return self::NEED_VALID_ONLINE;
                }
                break;
            case self::PUBLISH_STATE_OFFLINE:   //下线状态 只能重新上线或删除
                if ($new == self::PUBLISH_STATE_ONLINE) {
                    return self::PUBLISH_STATE_CAN_CHANGE;
                }
                break;
        }
        return 0;
    }

    /**
     * 取操作类型
     * @param $state
     * @param $class
     * @return int
     */
    public static function get_publish_opt($state, $class = 'default')
    {
        $opts = array();
        switch ($state) {
            case self::PUBLISH_STATE_READY: // 待确认
                $opts =  array(
                    self::OPT_ONLINE => self::$publish_opts[self::OPT_ONLINE],
                    self::OPT_EDIT => self::$publish_opts[self::OPT_EDIT],
                    self::OPT_DELETE => self::$publish_opts[self::OPT_DELETE]
                );
                break;
            case self::PUBLISH_STATE_ONLINE:    // 已上线
                $opts =  array(
                    self::OPT_OFFLINE => self::$publish_opts[self::OPT_OFFLINE]
                );
                if ('goods' == $class) {
                    $opts[self::OPT_EDIT_ONLINE] = self::$publish_opts[self::OPT_EDIT_ONLINE];
                }
                if ('active' == $class) {
                    $opts[self::BTN_TERMINATE] = self::$publish_opts[self::BTN_TERMINATE];
                    $opts[self::OPT_EDIT_ONLINE] = self::$publish_opts[self::OPT_EDIT_ONLINE];
                }
                break;
            case self::PUBLISH_STATE_END:    // 已结束
                if ('goods' != $class && 'active' != $class) {
                    $opts =  array(
                        self::OPT_OFFLINE => self::$publish_opts[self::OPT_OFFLINE]
                    );
                }
                break;
            case self::PUBLISH_STATE_OFFLINE:   // 已下线
                $opts =  array(
                    self::OPT_REONLINE => self::$publish_opts[self::OPT_REONLINE],
                );
                if ('goods' != $class && 'active' != $class) {
                    $opts[self::OPT_EDIT] = self::$publish_opts[self::OPT_EDIT];
                    $opts[self::OPT_DELETE] = self::$publish_opts[self::OPT_DELETE];
                }
                if ('active' == $class) {
                    $opts[self::BTN_TERMINATE] = self::$publish_opts[self::BTN_TERMINATE];
                }
                break;
        }
        return $opts;
    }

    public static function get_publish_opt_btn($primary, $class, $state)
    {
        if ($opts = self::get_publish_opt($state, $class)) {
            $ci = & get_instance();
            foreach($opts as $key => $opt) {
                $click_evt = '';
                switch ($opt['action']) {
                    case 'audit':
                        $click_evt = 'ADMIN.Opts.audit('.$primary.',\''.$class.'\', '.$key.')';
                        break;
                    case 'delete':
                        $click_evt = 'ADMIN.Opts.del('.$primary.',\''.$class.'\')';
                        break;
                    case 'edit':
                        $click_evt = 'ADMIN.Opts.edit('.$primary.',\''.$class.'\',\'#editView\')';
                        break;
                    case 'edit_online':
                        $click_evt = 'ADMIN.Opts.edit_online('.$primary.',\''.$class.'\')';
                        break;
                    case 'terminate':
                        $click_evt = 'ADMIN.Opts.terminate('.$primary.',this,\''.$class.'\')';
                        break;
                }
                $ci->widget('button', array(
                    'node' => $class.'/'.$opt['action'],
                    'button' => $opt['button'],
                    'class' => '' ,
                    'id' => '',
                    'attr' => array('onclick' => $click_evt)));
            }
        }
    }

    const AWARDS_PRIZE_SCORE = 1;       //奖励管理-奖励内容-积分
    const AWARDS_PRIZE_COUPON = 2;      //奖励管理-奖励内容-夺宝券
    public static $recharge_activity_config = array(  //夺宝券充值配置
        array('c'=>20,'s'=>0),
        array('c'=>50,'s'=>0),
        array('c'=>100,'s'=>0),
        array('c'=>200,'s'=>0),
        array('c'=>350,'s'=>0),
        array('c'=>500,'s'=>0),
        array('c'=>1000,'s'=>0),
    );

    public static $awards_prizes = array(
        self::AWARDS_PRIZE_SCORE => array('name'=>'积分','ename'=>'score') ,
        self::AWARDS_PRIZE_COUPON =>  array('name'=>'夺宝券','ename'=>'coupon')
    );


    /*
     * 激活状态
     */
    const INACTIVE = 0; // 禁用
    const ACTIVATED = 1; // 启用
    public static $activated_states = array(
        self::INACTIVE => '已禁用',
        self::ACTIVATED => '已启用',
    );

    /**
     * 显示状态
     */
    const HIDDEN = 0; // 隐藏
    const SHOW = 1; // 显示
    public static $show_states = array(
        self::HIDDEN => '隐藏',
        self::SHOW => '显示',
    );

    /**
     * 功能按钮
     */
    const BTN_ADD = 0; // 添加
    const BTN_EDIT = 1; // 编辑
    const BTN_DELETE = 2; // 删除
    const BTN_SHOW = 3; // 显示
    const BTN_HIDE = 4; // 隐藏
    const BTN_ONLINE = 5; // 上线
    const BTN_OFFLINE = 6; // 下线
    const BTN_INACTIVE = 7; // 禁用
    const BTN_ACTIVATED = 8; // 启用
    const BTN_REONLINE = 9; // 重新上线
    const BTN_OK = 10; // 确认
    const BTN_RESET = 11; // 重置
    const BTN_PURVIEW = 12; // 授权
    const BTN_EDIT_WEIGHT = 13; // 编辑权重
    const BTN_CANCEL_RECOMMEND = 14; // 取消推荐
    const BTN_DETAIL = 15; // 查看详情
    const BTN_DELIVER_DO = 16; // 发货
    const BTN_EDIT_ONLINE = 17; // 线上编辑
    const BTN_EXPORT_EXCEL = 18; // 导出表格
    const BTN_DELIVER_BATCH = 19; // 批量发货
    const BTN_TERMINATE = 20; // 结单
    const BTN_SHARE_EDIT = 21; // 编辑晒单
    const BTN_SHARE_ADD = 22; // 去晒单
    const BTN_SHARE_DETAIL = 23; // 查看晒单
    const BTN_EDIT_REMARK = 24; // 编辑备注
    public static $btn_states = array(
        self::BTN_ADD => array('text' => '添加', 'type' => 'primary'),
        self::BTN_EDIT => array('text' => '编辑', 'type' => 'success'),
        self::BTN_DELETE => array('text' => '删除', 'type' => 'danger'),
        self::BTN_SHOW => array('text' => '显示', 'type' => 'info'),
        self::BTN_HIDE => array('text' => '隐藏', 'type' => 'warning'),
        self::BTN_ONLINE => array('text' => '确认上线', 'type' => 'info'),
        self::BTN_OFFLINE => array('text' => '下线', 'type' => 'warning'),
        self::BTN_ACTIVATED => array('text' => '启用', 'type' => 'info'),
        self::BTN_INACTIVE => array('text' => '禁用', 'type' => 'warning'),
        self::BTN_REONLINE => array('text' => '重新上线', 'type' => 'info'),
        self::BTN_OK => array('text' => '确认', 'type' => 'danger'),
        self::BTN_RESET => array('text' => '重置', 'type' => 'default'),
        self::BTN_PURVIEW => array('text' => '授权', 'type' => 'info'),
        self::BTN_EDIT_WEIGHT => array('text' => '编辑权重', 'type' => 'success'),
        self::BTN_CANCEL_RECOMMEND => array('text' => '取消推荐', 'type' => 'info'),
        self::BTN_DETAIL => array('text' => '查看详情', 'type' => 'info'),
        self::BTN_DELIVER_DO => array('text' => '发货', 'type' => 'primary'),
        self::BTN_EDIT_ONLINE => array('text' => '线上编辑', 'type' => 'success'),
        self::BTN_EXPORT_EXCEL => array('text' => '导出表格', 'type' => 'warning'),
        self::BTN_DELIVER_BATCH => array('text' => '批量发货', 'type' => 'success'),
        self::BTN_TERMINATE => array('text' => '结单', 'type' => 'danger'),
        self::BTN_SHARE_EDIT => array('text' => '编辑晒单', 'type' => 'success'),
        self::BTN_SHARE_ADD => array('text' => '去晒单', 'type' => 'primary'),
        self::BTN_SHARE_DETAIL => array('text' => '查看晒单', 'type' => 'info'),
        self::BTN_EDIT_REMARK => array('text' => '编辑备注', 'type' => 'success'),
    );


    /**
     * 活动角标
     */
    const CORNER_MARK_10 = 1;
    const CORNER_MARK_APPLE = 2;
    const CORNER_MARK_HOT = 3;
    const CORNER_MARK_ACTIVE = 4;
    public static $corner_mark = array(
        self::CORNER_MARK_10 => array('text'=>'十元', 'img'=>''),
        self::CORNER_MARK_APPLE => array('text'=>'苹果', 'img'=>''),
        self::CORNER_MARK_HOT => array('text'=>'热门', 'img'=>''),
        self::CORNER_MARK_ACTIVE => array('text'=>'活动', 'img'=>''),
    );

    /**
     * 商品类型
     */
    const GOODS_TYPE_GENERAL = 0;
    const GOODS_TYPE_TICKET = 1;
    public static $goods_type = array(
        self::GOODS_TYPE_GENERAL => '普通商品',
        self::GOODS_TYPE_TICKET => '券',
    );

    /**
     * 夺宝单类型
     */
    const ACTIVE_TYPE_SYS = 1;
    const ACTIVE_TYPE_CUSTOM = 2;
    public static $active_type = array(
        self::ACTIVE_TYPE_SYS => '系统活动',
        self::ACTIVE_TYPE_CUSTOM => '私人定制',
    );

    /**
     * 夺宝开奖状态
     */
    const ACTIVE_LOT_NOT = 0;
    const ACTIVE_LOT_ING = 1;
    const ACTIVE_LOT_DONE = 2;
    public static $active_lot = array(
        self::ACTIVE_LOT_NOT => '待开奖',
        self::ACTIVE_LOT_ING => '即将揭晓',
        self::ACTIVE_LOT_DONE => '已开奖',
    );


    //***************活动配置*******************************
    const ACTIVE_LOT_STATE_DEFAULT = 0;//未开奖
    const ACTIVE_LOT_STATE_GOING = 1; //即奖开奖
    const ACTIVE_LOT_STATE_OPENED = 2; //已开奖
    const ACTIVE_STATE_DEFAULT = 0;//未开始
    const ACTIVE_STATE_ONLINE = 1;//已上线
    const ACTIVE_STATE_DONE = 2;//已结束
    const ACTIVE_STATE_OUTLINE = 3;//已下线
    const ACTIVE_IS_CRAZY = 60;//疯抢限制


    //***************订单相关********************************
    //支付方式
    const ORDER_PAY_TYPE_WX = 1;//微信支付
    const ORDER_PAY_TYPE_QQ = 2;//手Q支付
    const ORDER_PAY_TYPE_CFT = 3;//财付通支付
    const ORDER_PAY_TYPE_ZFB = 4;//支付宝支付
    const ORDER_PAY_TYPE_COUPON = 9; //夺宝券支付,目前仅用于退款
    public static $order_pay_type = array(
        self::ORDER_PAY_TYPE_WX => '微信支付',
        self::ORDER_PAY_TYPE_QQ => '手Q支付',
        self::ORDER_PAY_TYPE_CFT => '财付通支付',
        self::ORDER_PAY_TYPE_ZFB => '支付宝支付',
        self::ORDER_PAY_TYPE_COUPON => '夺宝券支付'
    );

    const ORDER_OPERATE_NUM = 3; //订单失败操作次数


    //订单支付状态
    const PAY_STATUS_PAID = 1;  //已支付
    const PAY_STATUS_UNPAID = 0;//未支付
    public static $pay_status = array(
        self::PAY_STATUS_PAID => '已支付',
        self::PAY_STATUS_UNPAID => '未支付',
    );

    //发货类型
    const DELIVER_TYPE_ACTIVE = 1; //夺宝
    const DELIVER_TYPE_COUPON = 2; //券兑换
    const DELIVER_TYPE_SCORE = 3; //积分兑换
	public static $deliver_type = array(
        self::DELIVER_TYPE_ACTIVE => '夺宝',
        self::DELIVER_TYPE_COUPON => '券兑换',
        self::DELIVER_TYPE_SCORE => '积分兑换',
    );

	const DELIVER_NOT_DEL_STATUS = 0; // 未发货
    const DELIVER_DEL_STATUS = 1;// 已发货
    const DELIVER_DONE_STATUS = 2;// 已确认
    public static $deliver_status = array(
        self::DELIVER_NOT_DEL_STATUS => '未发货',
        self::DELIVER_DEL_STATUS => '已发货',
        self::DELIVER_DONE_STATUS => '已确认',
    );

    //确认收货状态
    const DELIVER_NOT_CONFIRM_STATUS = 0;   //未确认
    const DELIVER_CONFIRM_STATUS = 1;       //确认收货
    public static $deliver_confirm = array(
        self::DELIVER_NOT_CONFIRM_STATUS => '未确认',
        self::DELIVER_CONFIRM_STATUS => '确认收货',
    );

    //订单状态
    const STATUS_DELETED = 0;//已删除
    const STATUS_NOT_DELETED = 1;//正常

    //订单退款状态
    const REFUND_STATUS_DEFAULT = 0;//无退款
    const REFUND_STATUS_GOING = 1;//退款中
    const REFUND_STATUS_END = 2;//已全额退款
    public static $refund_status = array(
        self::REFUND_STATUS_DEFAULT => '无退款',
        self::REFUND_STATUS_GOING => '退款中',
        self::REFUND_STATUS_END => '已退款',
    );

    const REFUND_RET_STATUS_DEFAULT = 0; //未处理退款申请
    const REFUND_RET_STATUS_FAIL = -1 ;//退款失败
    const REFUND_RET_STATUS_SUBMIT = 1;//已提交退款申请
    const REFUND_RET_STATUS_SUCC = 2;//退款成功
    public static $refund_ret_status = array(
        self::REFUND_RET_STATUS_DEFAULT => '未处理退款',
        self::REFUND_RET_STATUS_FAIL => '退款失败',
        self::REFUND_RET_STATUS_SUBMIT => '已提交退款',
        self::REFUND_RET_STATUS_SUCC => '退款成功',
    );

    //退款类型
    const REFUND_TYPE_BUY = 0; //直接购买
    const REFUND_TYPE_REBATE = 1; //拼团返利退款
    const REFUND_TYPE_STOCK_EMPTY = 2;  //库存不足退款
    const REFUND_TYPE_DIY_FAILED = 3;  //开团失败退款
    const REFUND_TYPE_JOIN_FAILED = 4; //参团失败
    const REFUND_TYPE_GROUPON_FAILED = 5; //拼团失败
    const REFUND_TYPE_GROUPON_FREE = 6; //团长免单返利退款
    const REFUND_TYPE_CUSTOMER = 7; // 客服人工退款

    public static $refund_types = array(
        self::REFUND_TYPE_BUY => '直接购买退款',
        self::REFUND_TYPE_REBATE => '返利退款',
        self::REFUND_TYPE_STOCK_EMPTY => '库存不足退款',
        self::REFUND_TYPE_DIY_FAILED => '开团失败退款',
        self::REFUND_TYPE_JOIN_FAILED => '参团失败退款',
        self::REFUND_TYPE_GROUPON_FAILED => '拼团失败退款',
        self::REFUND_TYPE_GROUPON_FREE => '团长免单返利退款',
        self::REFUND_TYPE_CUSTOMER => '客服人工退款',
    );

    /**
     * 返利退款类型
     * @var array
     */
    public static $rebate_refund_type = array(
        self::REFUND_TYPE_REBATE,
        self::REFUND_TYPE_GROUPON_FREE
    );

    // 订单发码状态
    const CODE_STATE_NOT = 0;
    const CODE_STATE_DONE = 1;
    public static $code_states = array(
        self::CODE_STATE_NOT => '未发码',
        self::CODE_STATE_DONE => '已发码',
    );

    //活动类型
    const ACTIVE_CURRENT_PEROID = 1; //表示当前期
    const ACTIVE_NOT_CURRENT_PEROID = 0;//表示不在当前期

    //订单类型
    const ORDER_TYPE_ACTIVE = 1; //夺宝活动订单
    const ORDER_TYPE_ACTIVE_EXCHANGE = 2; //夺宝活动兑换
    const ORDER_TYPE_COUPON = 3; //购买夺宝券
    const ORDER_TYPE_BAG = 4; //购买福袋
    const ORDER_TYPE_POINT_EXCHANGE = 5;//积分兑换

    const ORDER_TYPE_GROUPON = 6;// 拼团订单
    const ORDER_TYPE_ACTIVITY = 7;//活动奖品
    const ORDER_TYPE_WTG_FAKE = 9; // 魔法森林伪造的订单

    public static $order_type = array(
        self::ORDER_TYPE_ACTIVE => '夺宝活动订单',
        self::ORDER_TYPE_ACTIVE_EXCHANGE => '夺宝活动兑换',
        self::ORDER_TYPE_COUPON => '购买夺宝券',
        self::ORDER_TYPE_BAG => '购买福袋',
        self::ORDER_TYPE_POINT_EXCHANGE => '积分兑换',
        self::ORDER_TYPE_GROUPON => '拼团订单',
        self::ORDER_TYPE_ACTIVITY => '活动奖品',
    );

    // 夺宝订单
    public static $order_type_active = array(
        self::ORDER_TYPE_ACTIVE => '夺宝活动订单',
        self::ORDER_TYPE_ACTIVE_EXCHANGE => '夺宝活动兑换',
    );

    // 需发货的夺宝订单
    public static $order_type_deliver_duobao = array(
        self::ORDER_TYPE_ACTIVE => '夺宝活动订单',
        self::ORDER_TYPE_ACTIVE_EXCHANGE => '夺宝活动兑换',
        self::ORDER_TYPE_POINT_EXCHANGE => '积分兑换',
        self::ORDER_TYPE_ACTIVITY => '活动奖品',
    );

    //券单价
    const COUPON_UNIT_PRICE = 100;  //1券=100分
    const LUCKY_CODE_BASE = 10000001; //夺宝码的基数
    const LUCKY_LOT_TIME = 10;//等待开奖时间，单位分钟

    //积分收支类型
    const ACTION_INCOME = 1;  //收入
    const ACTION_OUTCOME = 2; //支出

    //奖励类型
    const AWARDS_TYPE_SIGN = 11;            //签到
    const AWARDS_TYPE_SHARE = 12;           //晒单
    const AWARDS_TYPE_LIKE = 13;            //晒单点赞
    const AWARDS_TYPE_PAY = 14;             //签到
    const SCORE_EXCHANGE = 15;              //积分兑换
    const BUY_DUOBAO_ACTIVE = 16;           //购买夺宝活动
    const BUY_DUOBAO_ACTIVE_GIFT = 17;      //购买夺宝活动
    const EXCHANGE_DUOBAO_ACTIVE = 18;      //兑换夺宝活动
    const EXCHANGE_DUOBAO_ACTIVE_GIFT = 19; //兑换夺宝活动赠送
    const BUY_COUPON = 20;                  //购买券
    const BUY_COUPON_GIFT = 21;             //购买券-赠送
    const REFUND_COUPON = 22;               //退款退券
    const AWARDS_TYPE_WTG_ACT = 23;         //微团购活动
    const SHARE_INVITE = 24;         //分享有礼-邀请赠送
    const SHARE_GIFT = 25;         //分享有礼-新用户赠送

    //奖励类型英文简称
    const AWARDS_TYPE_TAG_SIGN = 'sign';     //签到
    const AWARDS_TYPE_TAG_SHARE = 'share';    //晒单
    const AWARDS_TYPE_TAG_LIKE = 'like';     //晒单点赞
    const AWARDS_TYPE_TAG_PAY = 'pay';      //签到
    const AWARDS_TYPE_TAG_WTG_ACT = 'wtg_activity';      //微团购活动
    const AWARDS_TYPE_TAG_SHARE_INVITE = 'share_invite';      //分享有礼
    const AWARDS_TYPE_TAG_SHARE_GIFT = 'share_newuser';      //分享有礼

    public static $awards_types = array(
        self::AWARDS_TYPE_TAG_SIGN => array('action'=>self::AWARDS_TYPE_SIGN, 'message' => ''),
        self::AWARDS_TYPE_TAG_SHARE => array('action'=>self::AWARDS_TYPE_SHARE, 'message' => ''),
        self::AWARDS_TYPE_TAG_LIKE => array('action'=>self::AWARDS_TYPE_LIKE, 'message' => ''),
        self::AWARDS_TYPE_TAG_PAY => array('action'=>self::AWARDS_TYPE_PAY, 'message' => ''),
        self::AWARDS_TYPE_TAG_WTG_ACT => array('action'=>self::AWARDS_TYPE_WTG_ACT, 'message' => ''),
        self::AWARDS_TYPE_TAG_SHARE_INVITE => array('action'=>self::SHARE_INVITE, 'message' => 'shareInviteAwardsPush'),
        self::AWARDS_TYPE_TAG_SHARE_GIFT => array('action'=>self::SHARE_GIFT, 'message' => 'shareInviteAwardsPush'),
    );

    const SHARE_OPT_VIEW = 1;   //晒单查看
    const SHARE_OPT_LIKE = 2;   //晒单点赞
    public static $share_opts = array(
        self::SHARE_OPT_VIEW,
        self::SHARE_OPT_LIKE,
    );
    const SHARE_AUDIT_DEFAULT = 0 ;//未审核
    const SHARE_AUDIT_SUCC = 1; //审核通过
    const SHARE_AUDIT_FAILD = -1; //审核不通过
    public static $share_audit = array(
        self::SHARE_AUDIT_DEFAULT => '未审核',
        self::SHARE_AUDIT_SUCC => '审核通过',
        self::SHARE_AUDIT_FAILD => '审核不通过',
    );

    //*************自动脚本相关************************
    const SCRIPT_EREPEAT_OPERATE = 3; //操作失败则重复操作次数
    const SCRIPT_REFUND_TRY_TIMES = 5; //退款失败则最多会尝试发起退款次数
    const SCRIPT_REFUND_FIX_TIMES = 50; //退款状态更新次数


    //*************API相关*****************************
    const VERSION = 1.0;//API当前版本号

    //福袋相关
    const BAG_IS_DONE = 1;          //福袋已领完
    const BAG_NOT_DONE = 0;         //福袋未完成

    const BAG_IS_TIMEOUT = 1;       //福袋已过期
    const BAG_NOT_TIMEOUT = 0;      //福袋未过期

    const BAG_TYPE_NORMAL = 1;      //普通福袋
    const BAG_TYPE_RAND = 2;        //拼手气福袋
    public static $bag_types = array(
        self::BAG_TYPE_NORMAL,
        self::BAG_TYPE_RAND
    );

    const SIGN_PARAM = '3!2=022%dd';//生成sign必要基数

    const LUCKY_BAG_WISH = '送你一份千元豪礼！';
    const BAG_TIME_OUT = 48;        //福袋超时时间,单位：小时

    const LUCKY_BAG_TYPE_1 = 1;//普通福袋
    const LUCKY_BAG_TYPE_2 = 2;//拼手气福袋

    const BAG_STATUS_NORMAL = 1; //福袋状态- 正常
    const BAG_STATUS_ACTIVE = 2; //福袋状态- 激活
    const BAG_STATUS_FROZEN = 0; //福袋状态- 冻结

    const BAG_ACTION_GET = 1;       //福袋领取
    const ACTION_GET_BUY = 2;       //购买
    const ACTION_GET_TIME_OUT = 3;  //超时退回
    const ACTION_USE_ACTIVE = 4;    //参与活动
    const ACTION_USE_EXCHANGE = 5;  //兑换
    const ACTION_USE_BAG = 6;       //发福袋
    const ACTION_USE_COUPON = 7;    //券被领取

    public static $coupon_actions = array(
        self::AWARDS_TYPE_SIGN => '签到',
        self::AWARDS_TYPE_SHARE => '晒单',
        self::AWARDS_TYPE_LIKE => '点赞',
        self::AWARDS_TYPE_PAY => '支付',
        self::SCORE_EXCHANGE => '积分兑换',
        self::BUY_DUOBAO_ACTIVE => '参与夺宝',
        self::BUY_DUOBAO_ACTIVE_GIFT => '参与夺宝赠送',
        self::EXCHANGE_DUOBAO_ACTIVE_GIFT => '兑换夺宝赠送',
        self::EXCHANGE_DUOBAO_ACTIVE => '兑换夺宝',
        self::BUY_COUPON => '购买夺宝券',
        self::BUY_COUPON_GIFT => '购买夺宝券赠送',
        self::BAG_ACTION_GET => '福袋领取',
        self::ACTION_GET_BUY => '购买夺宝券',
        self::ACTION_GET_TIME_OUT => '福袋超时退回',
        self::ACTION_USE_ACTIVE => '参与夺宝',
        self::ACTION_USE_EXCHANGE => '兑换商品',
        self::ACTION_USE_BAG => '发福袋',
        self::REFUND_COUPON => '退款退夺宝券',
        self::SHARE_INVITE => '分享有礼-邀请赠送',
        self::SHARE_GIFT => '分享有礼-新用户赠送',
    );

    //积分
    const SCORE_ACTION_EXCHANGE = 1; //积分兑换

    //商品
    const GOODS_TYPE_NORMAL = 1; //普通商品
    const GOODS_TYPE_COUPON = 2; //兑券
    const GOODS_TYPE_VIRTUAL = 3; //虚拟商品

    //晒单图片尺寸
    const SHARE_IMG_SMALL = 220; //小图
    const SHARE_IMG_MIDDLE = 440;  //大图
    const SHARE_IMG_LIST = 320;  //所有列表图片size
    const SHARE_IMG_BIG = 670;  //大图

    /**
     * 单个夺宝码价格
     *
     * @var array
     */
    public static $code_price_opt = array(100, 500, 1000,0);

    /**
     * 快递公司
     *
     * @var array
     */
    public static $express_company = array(
        '顺丰快递',
        '圆通快递',
        '申通快递',
        '韵达快递'
    );

    /**
     * 广告位
     *
     * @var array
     */
    public static $advert_position = array(
        1 => '微信-首页轮播',
        2 => '微信-活动推广',
        3 => '微信-下单结果',
    );

    /**
     * 消息类型
     */
    const MSG_WIN_PRIZE = 1; // 夺宝单中奖通知
    const MSG_ACTIVITY_SUCCESS = 2; // 积分商城兑换成功通知
    const MSG_DELIVER = 3; // 夺宝单发货通知
    public static $msg_type = array(
        self::MSG_WIN_PRIZE => '夺宝单中奖通知',
        self::MSG_ACTIVITY_SUCCESS => '积分商城兑换成功通知',
        self::MSG_DELIVER => '夺宝单发货通知',
    );

    /**
     * 消息通知类型
     */
    const MSG_NOTIFY_MY = 1; // 个人中心通知
    const MSG_NOTIFY_SMS = 2; // 短信通知
    const MSG_NOTIFY_WX = 3; // 微信模板消息
    public static $msg_notify = array(
        self::MSG_NOTIFY_MY => '个人中心通知',
        self::MSG_NOTIFY_SMS => '短信通知',
        self::MSG_NOTIFY_WX => '微信模板消息',
    );

    /**
     * 订阅类型
     */
    const SUBSCRIBE_TYPE_FREE_ACTIVE = 1;//0元夺宝订阅
    const SUBSCRIBE_STATE_DEFAULT = 1;//订阅状态
    const SUBSCRIBE_STATE_CANCEL = -1;//取消订阅

    /**
     * 模板ID配置
     */
    const MSG_TEM_DELIVER = 1; // 发货模板
    const MSG_TEM_COUPON_SUCC = 2; //充值夺宝券模板
	const MSG_TEM_SHARE_INVITE_AWARDS = 9; //分享有礼模板
    const MSG_TEM_SHARE_ORDER_PUSH = 3;
    const MSG_TEM_SHARE_ORDER_AUDIT = 4;
    const MSG_TEM_ORDER_RANK_AWARDS = 5;
    const MSG_TEM_ORDER_REFUND = 6;
    public static $msg_business_type = array(
        self::MSG_TEM_DELIVER => '',
        self::MSG_TEM_COUPON_SUCC => 'buyCouponSucc',//成功充值充值夺宝券
        self::MSG_TEM_SHARE_ORDER_PUSH => 'shareOrderPush',//晒单成功push
        self::MSG_TEM_SHARE_ORDER_AUDIT => 'shareOrderAudit',//晒单审核
        self::MSG_TEM_SHARE_INVITE_AWARDS => 'shareInviteAwardsPush',//分享有礼push
        self::MSG_TEM_ORDER_RANK_AWARDS => 'orderRankListPush',//排行榜通知模板
        self::MSG_TEM_ORDER_REFUND => 'orderRefundPush', //发码退款通知
    );

    /**
     * 变量类型
     */
    const VARIABLE_TYPE_SYS = 1; // 系统相关变量
    const VARIABLE_TYPE_WX = 2; // 微信相关配置
    const VARIABLE_TYPE_OPERATE = 3;//运营相关配置
    public static $variable_types = array(
        self::VARIABLE_TYPE_SYS => '系统相关变量',
        self::VARIABLE_TYPE_WX => '微信相关配置',
        self::VARIABLE_TYPE_OPERATE => '运营相关配置',
    );
    const VARIABLE_FREE_COUPON_ACTIVE_KEY = 'free_coupon_active';

    /**
     * 常用变量 key
     */
    const VAR_SITE_NAME = 'site_name'; // 站点名称
    const VAR_ASSERT_VERSION = 'asset_version'; // 静态资源版本号
    const VAR_LOAD_LEVELING = 'load_leveling'; // 系统负载级别
    const VAR_SLOW_LOG_ON = 'slow_log_on'; // 慢日志开关
    const VAR_SLOW_LOG_TIME = 'slow_log_time'; // 慢日志基准时间
    const VAR_WX_SHARE = 'wx_share'; // 微信分享

    /**
     * 团购订单
     */
    const GROUPON_ORDER_DIY = 1; // 开团
    const GROUPON_ORDER_JOIN = 2; // 参团
    const GROUPON_ORDER_DIRECT = 3; // 直接买
    public static $groupon_order = array(
        self::GROUPON_ORDER_DIY => '开团',
        self::GROUPON_ORDER_JOIN => '凑团',
        self::GROUPON_ORDER_DIRECT => '任性购',
    );

    /**
     * 团购类型
     */
    const GROUPON_TYPE_FIX = 1; // 固定团
    const GROUPON_TYPE_STAIR = 2; // 阶梯团

    /**
     * 拼团进行状态
     */
    const GROUPON_DIY_ING = 0; // 进行中
    const GROUPON_DIY_DONE = 1; // 已成团
    const GROUPON_DIY_FAILED = 2; // 未成团
    public static $groupon_diy_states = array(
        self::GROUPON_DIY_ING => '拼团中',
        self::GROUPON_DIY_DONE => '拼团成功',
        self::GROUPON_DIY_FAILED => '拼团失败',
    );

    const GROUPON_DIY_UNFINISH = 0; //未开团
    const GROUPON_DIY_FINISHED = 1; //已开团

    const GROUPON_REBATE_NONE = 0; //无返利
    const GROUPON_REBATE_DONE = 1; //已返利

    /**
     * 订单状态
     */
    const ORDER_STATE_UNPAID = 1; // 待付款
    const ORDER_STATE_UNDELIVERED = 2; // 待发货
    const ORDER_STATE_DELIVERING = 3; // 发货中
    const ORDER_STATE_DONE = 4; // 已完成
    const ORDER_STATE_INVALID = 5; // 已失效
    const ORDER_STATE_REFUNDING = 6; // 退款中
    const ORDER_STATE_REFUNDED = 7; // 已退款
    const ORDER_STATE_REBATING = 8; // 返利中
    const ORDER_STATE_REBATED = 9; // 已返利
    public static $order_state = array(
        self::ORDER_STATE_UNPAID => array('text'=>'待付款','btn'=>'warning'),
        self::ORDER_STATE_UNDELIVERED => array('text'=>'待发货','btn'=>'default'),
        self::ORDER_STATE_DELIVERING => array('text'=>'发货中','btn'=>'default'),
        self::ORDER_STATE_DONE => array('text'=>'已完成','btn'=>'success'),
        self::ORDER_STATE_INVALID => array('text'=>'已失效','btn'=>'default'),
        self::ORDER_STATE_REFUNDING => array('text'=>'退款中','btn'=>'default'),
        self::ORDER_STATE_REFUNDED => array('text'=>'已退款','btn'=>'default'),
        self::ORDER_STATE_REBATING => array('text'=>'返利中','btn'=>'default'),
        self::ORDER_STATE_REBATED => array('text'=>'已返利','btn'=>'default'),
    );

    const ORDER_STATUS_DEFAULT = 0; // 默认
    const ORDER_STATUS_INVALID = 1; // 已失效
    const ORDER_STATUS_CANCEL = 2; // 已取消
    const ORDER_STATUS_DELETED = 3; // 已删除

    const ORDER_DELIVER_NOT = 0; // 未发货
    const ORDER_DELIVER_ING = 1; // 发货中
    const ORDER_DELIVER_DONE = 2; // 已确认

    /**
     * 获取订单操作按钮
     *
     * @param $order
     *
     * @return array
     */
    public static function get_order_btn($order) {
        $btn_arr = array();
        switch ($order['iState']) {
            case Lib_Constants::ORDER_STATE_UNPAID;
                $btn_arr[] = array('url'=>'javascript:;','text'=>'取消订单','btn'=>'default','op'=>'cancel','data-url'=>node_url('order/my_order_cancel'));
                $url = pay_uri(array(
                    'order_type'=>Lib_Constants::ORDER_TYPE_GROUPON,
                    'buy_type'=>$order['iBuyType'],
                    'groupon_id'=>$order['iGrouponId'],
                    'spec_id'=>$order['iSpecId'],
                    'diy_id'=>$order['iDiyId'],
                    'order_id'=>$order['sOrderId']
                ));
                $btn_arr[] = array('url'=>$url,'text'=>'去支付','btn'=>'warning','op'=>'pay');
                break;
            case Lib_Constants::ORDER_STATE_UNDELIVERED;
                break;
            case Lib_Constants::ORDER_STATE_DELIVERING;
//                $btn_arr[] = array('url'=>node_url('order/trace').'?order_id='.$order['sOrderId'],'text'=>'订单跟踪','btn'=>'default','op'=>'trace');
                $btn_arr[] = array('url'=>'javascript:;','text'=>'确认收货','btn'=>'warning','op'=>'receipt','data-url'=>node_url('order/my_order_receipt'));
                break;
            case Lib_Constants::ORDER_STATE_DONE;
                break;
            case Lib_Constants::ORDER_STATE_INVALID;
                $btn_arr[] = array('url'=>'javascript:;','text'=>'删除','btn'=>'warning','op'=>'del','data-url'=>node_url('order/my_order_delete'));
                break;
            case Lib_Constants::ORDER_STATE_REFUNDING;
                break;
            case Lib_Constants::ORDER_STATE_REFUNDED;
                break;
            case Lib_Constants::ORDER_STATE_REBATING;
                break;
            case Lib_Constants::ORDER_STATE_REBATED;
                break;
        }
        $url = node_url('order/detail').'?order_id='.$order['sOrderId'];
        $btn_arr[] = array('url'=>$url,'text'=>'查看详情','btn'=>'default','op'=>'detail');
        return $btn_arr;
    }

    /**
     * 信息中心-模版类型
     */
    public static $message_template_type  = array(
        1   =>  '系统模版',
        2   =>  '微信模版'
    );
    public static $notify_type  = array(
        1   =>  '个人中心',
        2   =>  '短信',
        3   =>  '微信'
    );

	/**
     * 平台统计
     */
    public static $statistics_excel_sheet_title = array(    //导出EXCEL表头
        '日期','浏览量(PV)','用户量(UV)','现金流水','用券数量','订单数量','订单金额','活跃用户数','新增用户数','平台累计用户数'
    );

    public static $statistics_excel_sheet_detail_title = array( //导出EXCEL表头
        '日期','类别','已支付人数','已支付订单','已支付现金金额','已使用券','订单ARPU值','未支付人数','未支付订单数','未支付金额'
    );

    public static $statistics_type = array( //平台流水明细类型
        1   =>  '充值',
        2   =>  '夺宝',
        3   =>  '兑换',
        4   =>  '福袋'
    );

    const STATUS_0 = 0;
    const STATUS_1 = 1;

    const ACTIVITY_ID = 1;

    const VARIABLE_SHARE_INVITE_SUCC_DEFAULT = 'share_invite_succ_default';

    /**
     * 期号展示的时候加的基础数字
     */
    const BASE_PERIOD_CODE = 10300017900;


    /**
     * 性别
     */
    const GENDER_UNKNOWN = 0; // 未知
    const GENDER_MALE = 1; // 男
    const GENDER_FEMALE = 2; // 女
    public static $genders = array(
        self::GENDER_UNKNOWN => '未知',
        self::GENDER_MALE => '男性',
        self::GENDER_FEMALE => '女性',
    );

    /**
     * 机器人状态
     */
    const ROBOT_STATE_DEF = 0; // 默认状态
    const ROBOT_STATE_ENABLED = 1; // 启用状态
    const ROBOT_STATE_DISABLED = 2; // 禁用状态
    public static $robot_states = array(
        self::ROBOT_STATE_ENABLED => '启用状态',
        self::ROBOT_STATE_DISABLED => '禁用状态',
    );

    /**
     * 采购支付方式
     */
    const PURCHASE_PAY_ZFB = 1;
    const PURCHASE_PAY_JDBT = 2;
    const PURCHASE_PAY_JDGR = 3;
    public static $purchase_pay_type = array(
        self::PURCHASE_PAY_ZFB => '支付宝',
        self::PURCHASE_PAY_JDBT => '京东白条',
        self::PURCHASE_PAY_JDGR => '京东个人',
    );
}