<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$config['api_map'] = array(
    'share_list' => array('uri'=>'share/share_list', 'prefix'=>'share_list', 'ttl'=>30,  'open' => 1), //晒单列表
    'share_detail' => array('uri'=>'share/detail', 'table_cache' => 1, 'open' => 1, 'prefix'=>'share_', 'cache_column' => array('share_id'), 'format'=>array('share_model','format_share_detail')),   //晒单详情
    'share_operate' => array('uri'=>'share/operate'), //晒单操作 [点赞|查看]
    'share_liked' => array('uri'=>'share/is_liked'),  //是否点赞]
    'share_add' => array('uri'=>'share/add'),         //晒单新增

    'user_base_info' => array('uri'=>'user/base_info', 'table_cache' => 1, 'open' => 1, 'prefix'=>'base_info_', 'cache_column' => array('uin'), 'format'=>array('user_model','format_wx_user')),   //用户基础信息 is_db_cache:是否读表缓存
    'user_ext_info' => array('uri'=>'user/ext_info', 'table_cache' => 1, 'open' => 1, 'prefix'=>'ext_info_', 'cache_column' => array('uin'), 'format'=>array('user_ext_model','format_user_ext')),     //用户扩展信息
    'update_wx_user' => array('uri'=>'user/update_wx_user'),            //更新微信用户信息
    'update_wtg_wx_user' => array('uri'=>'user/update_wtg_wx_user'),    //更新微团购微信用户信息
    'get_wx_user' => array('uri'=>'user/get_wx_user', 'prefix'=>'wx_user_', 'cache_column' => array('openid'), 'open' => 1,  'ttl'=>86400*10),             //更新微信用户信息
    'get_wtg_wx_user' => array('uri'=>'user/get_wtg_wx_user', 'cache_column' => array('openid'), 'prefix'=>'wtg_user_', 'open' => 1, 'ttl'=>86400*10),     //更新微信用户信息
    'set_wx_new_user' => array('uri'=>'user/set_wx_new_user'),             //更新微信用户信息
    'check_wx_new_user' => array('uri'=>'user/check_wx_new_user'),             //更新微信用户信息


    'score_activity_list' => array('uri'=>'score/activity_list', 'prefix'=>'score_act_list_', 'open' => 1, 'ttl'=>300),     //积分活动列表
    'score_log_list' => array('uri'=>'score/log_list', 'prefix'=>'', 'ttl'=>30),       //积分日志列表
    'score_exchange' => array('uri'=>'score/exchange'),                                 //积分兑换

    'action_sign' => array('uri'=>'sign/add'),                                          //签到
    'free_coupon' => array('uri'=>'free/add'),                                          //领取券

    'user_bag_list' => array('uri'=>'luckybag/bag_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1),     //福袋列表
    'bag_order_info' => array('uri'=>'luckybag/order_info', 'prefix'=>'', 'ttl'=>30, 'open' => 1),  //福袋订单列表
    'bag_info' => array('uri'=>'luckybag/bag_info', 'prefix'=>'', 'ttl'=>30, 'open' => 1),          //福袋详情
    'bag_action_log_list' => array('uri'=>'luckybag/action_log_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1),    //福袋操作日志列表
    'is_user_got_bag' => array('uri'=>'luckybag/is_user_got_bag', 'prefix'=>'', 'ttl'=>30, 'open' => 0),        //用户是否已领取福袋
    'coupon_log_list' => array('uri'=>'coupon/log_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1),                 //抵用券操作日志列表
    'get_free_coupon' => array('uri'=>'coupon/get_free_coupon','prefix'=>'', 'ttl'=>30, 'open' => 1),           //获取夺宝券，一般需要相关活动支持
    'luckybag_add' => array('uri'=>'luckybag/add'),           //添加福袋信息
    'active_bag' => array('uri'=>'luckybag/active_bag'),      //福袋激活
    'user_get_bag'=> array('uri'=>'luckybag/user_get_bag'),   //领取福袋

    'active_ongoing' => array('uri'=>'active/get_active_ongoing', 'cache_key'=> 'active_ongoing', 'ttl'=>600, 'open' => 1), //所有进行中的活动
    'active_1000_opened' => array('uri'=>'active/get_active_1000_opened', 'cache_key'=> 'active_1000_opened', 'ttl'=>600, 'open' => 1), //取1000条已开奖的活动
    'active_opening' => array('uri'=>'active/get_active_opening', 'cache_key'=> 'active_opening', 'ttl'=>600, 'open' => 1), //取即将开奖的活动

    'history_peroid' => array('uri'=>'active/history', 'cache_key'=> 'history_peroid', 'ttl'=>5, 'list_cache_ttl'=>10,  'open' => 1,'list_format'=>array('Lib_DataFormat','active_detail')), //查看往期
    'goods_cate' => array('uri'=>'category/get_category', 'ttl'=>600, 'open' => 1), //
    'active_msg' => array('uri'=>'active/msg','cache_key'=> 'active_msg', 'ttl'=>5, 'open' => 1, 'list_cache_ttl'=>10), //
    'active_crazy' => array('uri'=>'active/crazy', 'cache_key'=> 'active_crazy', 'ttl'=>5, 'open' => 1, 'list_cache_ttl'=>10, 'list_format'=>array('Lib_DataFormat','active_detail')),//首页最后疯抢
    'active_free' => array('uri'=>'active/active_free', 'prefix'=>'', 'ttl'=>5, 'open' => 1, 'list_cache_ttl'=>10, 'list_format'=>array('Lib_DataFormat','active_detail')),//0元夺宝列表
    'active_zone' => array('uri'=>'active/zone', 'prefix'=>'', 'ttl'=>5, 'open' => 1, 'list_cache_ttl'=>10, 'list_format'=>array('Lib_DataFormat','active_detail')),  //1元区/10元区/苹果专区
    'active_past' => array('uri'=>'active/active_past', 'prefix'=>'', 'ttl'=>20, 'open' => 1, 'list_format'=>array('Lib_DataFormat','active_detail')),//当个活动的往期
    'active_winner' => array('uri'=>'active/active_winner', 'prefix'=>'active_winner', 'cache_column' => array('p_index','p_size'), 'ttl'=>5, 'open' => 1),//所有活动中奖往期
    //'active_winner' => array('uri'=>'active/active_winner', 'prefix'=>'active_winner', 'cache_column' => array('p_index','p_size'), 'ttl'=>5, 'open' => 1,'list_format'=>array('Lib_DataFormat','active_detail')),//所有活动中奖往期
    'my_active_winner' => array('uri'=>'active/my_active_winner', 'prefix'=>'', 'ttl'=>30, 'open' => 1,'list_format'=>array('Lib_DataFormat','active_detail')),//用户所有中奖往期
    'active_search' => array('uri'=>'active/search_lists', 'prefix'=>'', 'ttl'=>30, 'open' => 1),//搜索活动列表
    'active_currect_list' => array('uri'=>'active/currect_active_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1, 'list_format'=>array('Lib_DataFormat','active_detail')), //当前可用活动列表
    'active_detail' => array('uri'=>'active/detail', 'prefix'=>'active_peroid_', 'table_cache' => 1, 'open' => 1, 'cache_column' => array('act_id','peroid'),), //夺宝活动详情
    'active_config' => array('uri'=>'active/get_active_config', 'prefix'=>'', 'ttl'=>30, 'open' => 1),//活动配置列表
    'current_peroid' => array('uri'=>'peroid/get_current_active', 'prefix'=>'', 'ttl'=>30, 'open' => 0),//某个活动的当前期
    'static_peroid_detail' => array('uri'=>'peroid/get_static_active', 'prefix'=>'', 'ttl'=>600, 'open' => 1),//返回不在进行中的夺宝单详情,此接口可以永久caceh
    'summary_list' => array('uri'=>'summary/active_summary', 'prefix'=>'', 'ttl'=>10, 'open' => 1), //返回t_luckycode_summary表列表数据,某一活动某一期
    'summary_order' => array('uri'=>'summary/order_summary', 'prefix'=>'', 'ttl'=>10, 'open' => 0), //返回t_order_summary表列表数据
    'get_summary_order' => array('uri'=> 'summary/get_peroid_order_summary', 'prefix'=>'', 'ttl'=>30, 'open' => 0), //返回t_order_summary表列表数据
    'peroid_list' => array('uri'=>'peroid/get_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1),//返回夺宝单列表，参数where数组
    'goods_detail' => array('uri'=>'goods/get_detail', 'prefix'=>'', 'ttl'=>30, 'open' => 1),//获取商品详情
    'activity_conf' => array('uri'=>'recharge/get_activity_conf', 'prefix'=>'', 'ttl'=>30, 'open' => 1),//获取充值夺宝券配置
    'create_coupon_order' => array('uri'=>'order/create_coupon_order'),//生成夺宝券订单
    'create_active_order' => array('uri'=>'order/create_active_order'),//生成夺宝订单
    'order_detail' => array('uri'=>'order/get_order_detail', 'prefix'=>'', 'ttl'=>30, 'open' => 1),//获取各类型的订单详细

    'get_collect' => array('uri'=>'collect/get_collect', 'prefix'=>'', 'ttl'=>30),  //获取单条收藏记录
    'del_collect' => array('uri'=>'collect/del_collect'),  //删除收藏
    'add_collect' => array('uri'=>'collect/add_collect'), //添加收藏
    'my_collect' => array('uri'=>'collect/get_list', 'prefix'=>'', 'ttl'=>30), //收藏列表
    'get_deliver' => array('uri'=>'deliver/get_detail', 'prefix'=>'', 'ttl'=>0, 'open' => 0),//发货详情
    'confirm_addr' => array('uri'=>'deliver/update_deliver_addr'), //确认收货地址
    'confirm_deliver' => array('uri'=>'deliver/confirm_deliver','prefix'=>'', 'ttl'=>0, 'open' => 0), //确认收货
    'empty_deliver' => array('uri'=>'deliver/get_empty_address_deliver','prefix'=>'', 'ttl'=>0, 'open' => 0),//获取用户中奖没有填写地址记录

    'add_cart' => array('uri'=>'cart/add'),  //添加购物车
    'update_cart' => array('uri'=>'cart/update_cart'), //更新购物车
    'del_cart' => array('uri'=>'cart/del_cart'),//删除购物车
    'del_carts' => array('uri'=>'cart/del_cart_list'), //批量删除购物车
    'my_cart' => array('uri'=>'cart/get_cart_list'), //购物车列表
    'my_cart_count' => array('uri'=>'cart/get_cart_count'), //购物车数量
    'my_exchange' => array('uri'=> 'order/get_exchange_list'),//我的兑换
    'addr_list' => array('uri'=>'address/default_addr'),//收货地址
    'addr_save' => array('uri'=>'address/addr_save'),//保留或更新

    'ad_list' => array('uri'=>'advert/ad_list', 'prefix'=>'', 'ttl'=>600, 'open' => 1), // 广告列表

    'msg_list' => array('uri'=>'message/fetch_unread', 'prefix'=>'', 'ttl'=>600, 'open' => 1), // 未读消息列表
    'msg_count' => array('uri'=>'message/fetch_count', 'prefix'=>'', 'ttl'=>600, 'open' => 1),  // 未读消息数目
    'msg_read' => array('uri'=>'message/read'), // 设置消息为已读
    'msg_clean' => array('uri'=>'message/clean'),  // 清理全部消息

    'goods_category' => array('uri'=>'goods_category/top_cate', 'prefix'=>'', 'ttl'=>600, 'open' => 1), // 顶级类目
    'goods_list' => array('uri'=>'goods_item/goods_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1), // 商品列表
    'goods_item' => array('uri'=>'goods_item/goods_detail', 'prefix'=>'', 'ttl'=>600, 'open' => 1), // 商品详情

    'act_custom_list' => array('uri'=>'active_custom/fetch_list'),  // 自定义夺宝列表
    'custom_generate' => array('uri'=>'active_custom/generate'), // 生成自定义夺宝单

	'create_groupon_order_check' => array('uri'=>'groupon_order/create_order_check'), // 拼团订单检查
	'create_groupon_order' => array('uri'=>'groupon_order/create_order'), // 创建拼团订单
	'pay_later_data' => array('uri'=>'groupon_order/pay_later_data'), // 稍后支付的处理
	'my_order_list' => array('uri'=>'groupon_order/my_order_list','prefix'=>'','ttl'=>5,'open'=>1), // 我的订单
	'my_order_detail' => array('uri'=>'groupon_order/my_order_detail'), // 订单详情
	'my_order_cancel' => array('uri'=>'groupon_order/my_order_cancel'), // 取消订单
	'my_order_delete' => array('uri'=>'groupon_order/my_order_delete'), // 删除订单
	'my_order_receipt' => array('uri'=>'groupon_order/my_order_receipt'), // 确认收货
	'my_order_express' => array('uri'=>'groupon_order/my_order_express'), // 订单发货数据
	'groupon_active_list' => array('uri'=>'groupon/active_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1), //拼团活动列表);
	'groupon_active_detail' => array('uri'=>'groupon/active_detail', 'ttl'=>30, 'open' => 1), //拼团活动-活动详情
    'groupon_diy_list' => array('uri'=>'groupon/diy_list', 'prefix'=>'', 'ttl'=>5, 'open' => 1), //拼团活动-开团列表
    'groupon_diy_detail' => array('uri'=>'groupon/diy_detail', 'prefix'=>'', 'ttl'=>30, 'open' => 0), //拼团活动-开团详情
    'groupon_diy_join_list' => array('uri'=>'groupon/diy_join_list', 'prefix'=>'', 'ttl'=>600, 'open' => 0), //拼团活动-指定开团参团列表
    'groupon_my_groupon' => array('uri'=>'groupon/my_groupons', 'prefix'=>'', 'ttl'=>600, 'open' => 0), //拼团活动-我的团

    'share_invite_succ_list' => array('uri'=>'activity/share_invite_succ_list', 'prefix'=>'', 'ttl'=>30, 'open' => 1), //百分好礼活动 - 分享有礼 - 邀请成功列表
    'add_share_invite_succ' => array('uri'=>'activity/add_share_invite_succ'), //百分好礼活动 - 分享有礼 - 添加邀请成功记录
    'get_share_invite_succ' => array('uri'=>'activity/get_share_invite_succ', 'table_cache' => 1, 'open' => 1, 'prefix'=>'share_invite_', 'cache_column' => array('iToUin')), //百分好礼活动 - 分享有礼 - 添加邀请成功记录
    'get_share_invite_awards' => array('uri'=>'activity/get_share_invite_awards'), //百分好礼活动 - 分享有礼 - 添加邀请成功记录
    'check_share_invite_awards' => array('uri'=>'activity/check_share_invite_awards'), //百分好礼活动 - 分享有礼 - 添加邀请成功记录
    'get_invite_coupon_count' => array('uri'=>'activity/get_invite_coupon_count','prefix'=>'', 'ttl'=>30, 'open' => 1), //百分好礼活动 - 分享有礼 - 邀请成功获得券数
    'get_rank_list' => array('uri'=>'activity/get_rank_list','prefix'=>'', 'ttl'=>60, 'open' => 1), //土豪排行榜活动
    'get_my_pay' => array('uri'=>'activity/get_my_pay','prefix'=>'', 'ttl'=>60, 'open' => 1), //土豪排行_获取用户充值数据
    'get_order_bullet_screen' => array('uri'=>'activity/get_order_bullet_screen','prefix'=>'', 'ttl'=>120, 'open' => 1), //土豪排行_获取用户充值数据
);