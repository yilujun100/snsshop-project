<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 站点名称
|--------------------------------------------------------------------------
*/
$config['site_name'] = '一元夺宝管理后台';

/*
|--------------------------------------------------------------------------
| 版本号
|--------------------------------------------------------------------------
*/
$config['version'] = '0.8.3';

/*
|--------------------------------------------------------------------------
| 登陆白名单
|--------------------------------------------------------------------------
|
*/
$config['login_white_list'] = array('login/index', 'login/logout');

/*
|--------------------------------------------------------------------------
| 后台白名单
|--------------------------------------------------------------------------
|
*/
$config['admin_white_list'] = array('welcome/index');

/*
|--------------------------------------------------------------------------
| 默认首页
|--------------------------------------------------------------------------
|
*/
$config['home_default'] = 'welcome/index';

/*
|--------------------------------------------------------------------------
| 登陆入口
|--------------------------------------------------------------------------
|
*/
$config['login_portal'] = 'login/index';

/*
|--------------------------------------------------------------------------
| 通用前端资源
|--------------------------------------------------------------------------
|
*/
$config['asset'] = array(
    'validate' => array('js'=>array('jquery.validate.min','jquery.validate.admin.min')),
    'datepicker' => array(
        'css' => array('smart-forms','smart-themes/red','font-awesome.min'),
        'js' => array('jquery-ui-datepicker-zh-CN'),
    ),
    'datetimepicker' => array(
        'css' => array('jquery-ui','jquery-ui-timepicker-addon','smart-forms','smart-themes/red','font-awesome.min'),
        'js' => array('jquery-ui-datepicker-zh-CN','jquery-ui-timepicker-addon','jquery-ui-timepicker-zh-CN')
    ),
    'upload' => array(
        'jQuery-File-Upload/css/jquery.fileupload.css',
        'jQuery-File-Upload/js/vendor/jquery.ui.widget.js',
        'jQuery-File-Upload/js/jquery.iframe-transport.js',
        'jQuery-File-Upload/js/jquery.fileupload.js',
    ),
);

/*
|--------------------------------------------------------------------------
| 首页节点列表
|--------------------------------------------------------------------------
|
*/
$config['home_node'] = array(
    'welcome/index' => array('name'=>'默认首页','depend'=>array()),
    'advert_item/index' => array('name'=>'广告管理','depend'=>array()),
    'goods/index' => array('name'=>'商品管理','depend'=>array()),
    'active/index' => array('name'=>'夺宝单管理','depend'=>array()),
    'awards_type/index' => array('name'=>'奖励类型','depend'=>array()),
    'active_order/index' => array('name'=>'夺宝订单','depend'=>array()),
    'user/index' => array('name'=>'用户列表','depend'=>array()),
    'groupon_order/index' => array('name'=>'拼团订单','depend'=>array()),
);

/*
|--------------------------------------------------------------------------
| 后台菜单
|--------------------------------------------------------------------------
*/
$config['admin_menus'] = array(
    array(
        'name' => '推广管理中心',
        'node' => 'advert_item/index',
        'sub' => array(
            array(
                'name' => '广告管理',
                'node' => 'advert_item/index',
                'sub' => array(
                    array(
                        'name' => '广告管理',
                        'node' => 'advert_item/index',
                        'sub' => array(
                            array(
                                'name' => '广告列表',
                                'node' => 'advert_item/index',
                            ),
                            array(
                                'name' => '添加广告',
                                'node' => 'advert_item/add',
                                'depend' => array('advert_item/index','advert_item/img_upload','advert_item/ad_pos')
                            ),
                            array(
                                'name' => '编辑广告',
                                'node' => 'advert_item/edit',
                                'depend' => array('advert_item/index','advert_item/img_upload','advert_item/ad_pos')
                            ),
                            array(
                                'name' => '删除广告',
                                'node' => 'advert_item/delete',
                                'depend' => array('advert_item/index')
                            ),
                            array(
                                'name' => '上/下线广告',
                                'node' => 'advert_item/audit',
                                'depend' => array('advert_item/index')
                            ),
                        )
                    ),
                )
            ),
        )
    ),
    array(
        'name' => '商品管理中心',
        'node' => 'goods/index',
        'sub' => array(
            array(
                'name' => '商品管理',
                'node' => 'goods/index',
                'sub' => array(
                    array(
                        'name' => '商品管理',
                        'node' => 'goods/index',
                        'sub' => array(
                            array(
                                'name' => '商品列表',
                                'node' => 'goods/index',
                            ),
                            array(
                                'name' => '添加商品',
                                'node' => 'goods/add',
                                'depend' => array('goods/index','goods/img_upload','goods/detail_img','goods_category/children')
                            ),
                            array(
                                'name' => '编辑商品',
                                'node' => 'goods/edit',
                                'depend' => array('goods/index','goods/img_upload','goods/detail_img','goods_category/children')
                            ),
                            array(
                                'name' => '删除商品',
                                'node' => 'goods/delete',
                                'depend' => array('goods/index')
                            ),
                            array(
                                'name' => '审核商品',
                                'node' => 'goods/audit',
                                'depend' => array('goods/index')
                            ),
                            array(
                                'name' => '线上编辑',
                                'node' => 'goods/edit_online',
                                'depend' => array('goods/edit_online')
                            ),
                        )
                    ),
                    array(
                        'name' => '类目管理',
                        'node' => 'goods_category/index',
                        'sub' => array(
                            array(
                                'name' => '类目列表',
                                'node' => 'goods_category/index',
                                'depend' => array('goods_category/children','goods_category/get_cate')
                            ),
                            array(
                                'name' => '添加类目',
                                'node' => 'goods_category/add',
                                'depend' => array('goods_category/index','goods_category/children','goods_category/get_cate')
                            ),
                            array(
                                'name' => '编辑类目',
                                'node' => 'goods_category/edit',
                                'depend' => array('goods_category/index','goods_category/children','goods_category/get_cate')
                            ),
                            array(
                                'name' => '删除类目',
                                'node' => 'goods_category/delete',
                                'depend' => array('goods_category/index','goods_category/children','goods_category/get_cate')
                            ),
                            array(
                                'name' => '显示类目',
                                'node' => 'goods_category/show',
                                'depend' => array('goods_category/index','goods_category/children','goods_category/get_cate')
                            ),
                            array(
                                'name' => '隐藏类目',
                                'node' => 'goods_category/hide',
                                'depend' => array('goods_category/index','goods_category/children','goods_category/get_cate')
                            ),
                        )
                    ),
                )
            )
        )
    ),
    array(
        'name' => '夺宝单管理中心',
        'node' => 'active/index',
        'sub' => array(
            array(
                'name' => '夺宝单管理',
                'node' => 'active/index',
                'sub' => array(
                    array(
                        'name' => '夺宝单管理',
                        'node' => 'active/index',
                        'sub' => array(
                            array(
                                'name' => '夺宝单列表',
                                'node' => 'active/index',
                            ),
                            array(
                                'name' => '添加夺宝单',
                                'node' => 'active/add',
                                'depend' => array('active/index')
                            ),
                            array(
                                'name' => '编辑夺宝单',
                                'node' => 'active/edit',
                                'depend' => array('active/index', 'goods/get_goods_info')
                            ),
                            array(
                                'name' => '删除夺宝单',
                                'node' => 'active/delete',
                                'depend' => array('active/index')
                            ),
                            array(
                                'name' => '审核夺宝单',
                                'node' => 'active/audit',
                                'depend' => array('active/index')
                            ),
                            array(
                                'name' => '结束夺宝单',
                                'node' => 'active/terminate',
                                'depend' => array('active/index')
                            ),
                            array(
                                'name' => '线上编辑',
                                'node' => 'active/edit_online',
                                'depend' => array('active/edit_online')
                            ),
                        )
                    ),
                )
            ),
            array(
                'name' => '推荐管理',
                'node' => 'recommend/index',
                'sub' => array(
                    array(
                        'name' => '推荐管理',
                        'node' => 'recommend/index',
                        'sub' => array(
                            array(
                                'name' => '推荐查询',
                                'node' => 'recommend/index'
                            ),
                            array(
                                'name' => '新增推荐',
                                'node' => 'recommend/add',
                                'depend' => array('recommend/index', 'recommend/check')
                            ),
                            array(
                                'name' => '编辑推荐',
                                'node' => 'recommend/edit',
                                'depend' => array('recommend/index', 'recommend/check')
                            ),
                            array(
                                'name' => '取消推荐',
                                'node' => 'recommend/cancel',
                                'depend' => array('recommend/index')
                            ),
                        )
                    ),
                )
            ),
            array(
                'name' => '0元夺宝管理',
                'node' => 'free/index',
                'sub' => array(
                    array(
                        'name' => '开奖管理',
                        'node' => 'free/index',
                        'sub' => array(
                            array(
                                'name' => '开奖列表',
                                'node' => 'free/index',
                            ),
                            array(
                                'name' => '强制开奖',
                                'node' => 'free/lottery',
                                'depend' => array('free/index')
                            )
                        )
                    ),
                )
            )
        )
    ),
    array(
        'name' => '运营活动中心',
        'node' => 'awards_type/index',
        'sub' => array(
            array(
                'name' => '用户奖励',
                'node' => 'awards_type/index',
                'sub' => array(
                    array(
                        'name' => '奖励类型',
                        'node' => 'awards_type/index',
                        'sub' => array(
                            array(
                                'name' => '奖励类型查询',
                                'node' => 'awards_type/index'
                            ),
                            array(
                                'name' => '奖励类型新增',
                                'node' => 'awards_type/add',
                                'depend' => array('awards_type/index')
                            ),
                            array(
                                'name' => '奖励类型删除',
                                'node' => 'awards_type/delete',
                                'depend' => array('awards_type/index')
                            ),
                            array(
                                'name' => '奖励类型编辑',
                                'node' => 'awards_type/edit',
                                'depend' => array('awards_type/index')
                            ),
                            array(
                                'name' => '奖励类型发布',
                                'node' => 'awards_type/audit',
                                'depend' => array('awards_type/index')
                            )
                        )
                    ),
                    array(
                        'name' => '奖励管理',
                        'node' => 'awards_activity/index',
                        'sub' => array(
                            array(
                                'name' => '奖励活动查询',
                                'node' => 'awards_activity/index',
                            ),
                            array(
                                'name' => '奖励活动新增',
                                'node' => 'awards_activity/add',
                                'depend' => array('awards_activity/index')
                            ),
                            array(
                                'name' => '奖励活动删除',
                                'node' => 'awards_activity/delete',
                                'depend' => array('awards_activity/index')
                            ),
                            array(
                                'name' => '奖励活动编辑',
                                'node' => 'awards_activity/edit',
                                'depend' => array('awards_activity/index')
                            ),
                            array(
                                'name' => '奖励活动发布',
                                'node' => 'awards_activity/audit',
                                'depend' => array('awards_activity/index')
                            )
                        )
                    ),
                )
            ),
            array(
                'name' => '购券奖励',
                'node' => 'recharge_activity/index',
                'sub' => array(
                    array(
                        'name' => '购夺宝券奖励',
                        'node' => 'recharge_activity/index',
                        'sub' => array(
                            array(
                                'name' => '积分兑换活动查询',
                                'node' => 'recharge_activity/index',
                            ),
                            array(
                                'name' => '积分兑换活动新增',
                                'node' => 'recharge_activity/add',
                                'depend' => array('recharge_activity/index')
                            ),
                            array(
                                'name' => '积分兑换活动删除',
                                'node' => 'recharge_activity/delete',
                                'depend' => array('awards_activity/index')
                            ),
                            array(
                                'name' => '积分兑换活动编辑',
                                'node' => 'recharge_activity/edit',
                                'depend' => array('recharge_activity/index')
                            ),
                            array(
                                'name' => '积分兑换活动发布',
                                'node' => 'recharge_activity/audit',
                                'depend' => array('recharge_activity/index')
                            )
                        )
                    ),
                )
            ),
            array(
                'name' => '积分商城管理',
                'node' => 'score_activity/index',
                'sub' => array(
                    array(
                        'name' => '积分兑换活动',
                        'node' => 'score_activity/index',
                        'sub' => array(
                            array(
                                'name' => '积分兑换活动查询',
                                'node' => 'score_activity/index',
                                'depend' => array('score_activity/index', 'score_activity/goods_info')
                            ),
                            array(
                                'name' => '积分兑换活动新增',
                                'node' => 'score_activity/add',
                                'depend' => array('score_activity/index', 'score_activity/goods_info')
                            ),
                            array(
                                'name' => '积分兑换活动删除',
                                'node' => 'score_activity/delete',
                                'depend' => array('score_activity/index')
                            ),
                            array(
                                'name' => '积分兑换活动编辑',
                                'node' => 'score_activity/edit',
                                'depend' => array('score_activity/index','score_activity/goods_info')
                            ),
                            array(
                                'name' => '积分兑换活动发布',
                                'node' => 'score_activity/audit',
                                'depend' => array('score_activity/index')
                            )
                        )
                    ),
                )
            ),
            array(
                'name' => '促销信息管理',
                'node' => 'news/index',
                'sub' => array(
                    array(
                        'name' => '促销信息',
                        'node' => 'news/index',
                         'sub'=>array(
                             array(
                                 'name' => '列表',
                                 'node' => 'news/index',
                             ),
                             array(
                                 'name' => '新增',
                                 'node' => 'news/add',
                                 'depend' => array('news/index', 'news/img_upload')
                             ),
                             array(
                                 'name' => '删除',
                                 'node' => 'news/delete',
                                 'depend' => array('news/index')
                             ),
                             array(
                                 'name' => '修改',
                                 'node' => 'news/edit',
                                 'depend' => array('news/index', 'news/img_upload')
                             ),
                             array(
                                 'name' => '发布',
                                 'node' => 'news/audit',
                                 'depend' => array('news/index')
                             )

                         )
                    ),

                )
            ),

            array(
                'name' => '其他活动配置管理',
                'node' => 'config_activity/index',
                'sub' => array(
                    array(
                        'name' => '配置管理',
                        'node' => 'config_activity/index',
                        'sub' => array(
                            array(
                                'name' => '配置列表',
                                'node' => 'config_activity/index'
                            ),
                            array(
                                'name' => '编辑变量',
                                'node' => 'config_activity/edit',
                                'depend' => array('config_activity/get_item')
                            )
                        ),
                    )
                )
            ),
        )
    ),
    array(
        'name' => '客服中心',
        'node' => 'active_order/index',
        'sub' => array(
            array(
                'name' => '订单管理',
                'node' => 'active_order/index',
                'sub' => array(
                    array(
                        'name' => '夺宝订单',
                        'node' => 'active_order/index',
                        'sub' => array(
                            array(
                                'name' => '订单列表',
                                'node' => 'active_order/index'
                            ),
                            array(
                                'name' => '订单详情',
                                'node' => 'active_order/detail',
                                'depend' => array('active_order/index')
                            ),
                        )
                    ),
                    array(
                        'name' => '夺宝券订单',
                        'node' => 'coupon_order/index',
                        'sub' => array(
                            array(
                                'name' => '订单列表',
                                'node' => 'coupon_order/index',
                            ),
                            array(
                                'name' => '订单详情',
                                'node' => 'coupon_order/detail',
                                'depend' => array('coupon_order/index')
                            ),
                        )
                    ),
                    array(
                        'name' => '福袋订单',
                        'node' => 'bag_order/index',
                        'sub' => array(
                            array(
                                'name' => '订单列表',
                                'node' => 'bag_order/index'
                            ),
                            array(
                                'name' => '订单详情',
                                'node' => 'bag_order/detail',
                                'depend' => array('bag_order/index')
                            ),
                        )
                    ),
                    array(
                        'name' => '积分订单',
                        'node' => 'score_order/index',
                        'sub' => array(
                            array(
                                'name' => '积分订单',
                                'node' => 'score_order/index',
                            ),
                            array(
                                'name' => '订单详情',
                                'node' => 'score_order/detail',
                                'depend' => array('score_order/index')
                            ),
                        )
                    ),
                    array(
                        'name' => '拼团订单',
                        'node' => 'groupon_order/index',
                        'sub' => array(
                            array(
                                'name' => '拼团订单',
                                'node' => 'groupon_order/index',
                            ),
                            array(
                                'name' => '订单详情',
                                'node' => 'groupon_order/detail',
                                'depend' => array('groupon_order/index')
                            ),
                        )
                    ),
                )
            ),
            array(
                'name' => '发货管理',
                'node' => 'deliver/index',
                'sub' => array(
                    array(
                        'name' => '夺宝发货',
                        'node' => 'deliver/index',
                        'sub' => array(
                            array(
                                'name' => '发货列表',
                                'node' => 'deliver/index',
                            ),
                            array(
                                'name' => '确认发货',
                                'node' => 'deliver/confirm',
                                'depend' => array('deliver/index')
                            ),
                            array(
                                'name' => '发货详情',
                                'node' => 'deliver/detail',
                                'depend' => array('deliver/index')
                            ),
                            array(
                                'name' => '批量发货',
                                'node' => 'deliver/batch',
                                'depend' => array('deliver/index')
                            ),
                            array(
                                'name' => '导出表格',
                                'node' => 'deliver/export',
                                'depend' => array('deliver/index')
                            ),
                        )
                    ),
                    array(
                        'name' => '拼团发货',
                        'node' => 'deliver/groupon',
                        'sub' => array(
                            array(
                                'name' => '发货列表',
                                'node' => 'deliver/groupon',
                            ),
                            array(
                                'name' => '确认发货',
                                'node' => 'deliver/groupon_ok',
                                'depend' => array('deliver/groupon')
                            ),
                            array(
                                'name' => '发货详情',
                                'node' => 'deliver/groupon_detail',
                                'depend' => array('deliver/groupon')
                            ),
                            array(
                                'name' => '导出发货数据',
                                'node' => 'deliver/groupon_export',
                                'depend' => array('deliver/groupon')
                            ),
                            array(
                                'name' => '批量发货',
                                'node' => 'deliver/groupon_batch',
                                'depend' => array('deliver/groupon')
                            ),
                        )
                    ),
                )
            ),
            array(
                'name' => '退款管理',
                'node' => 'order_refund/index',
                'sub' => array(
                    array(
                        'name' => '退款管理',
                        'node' => 'order_refund/index',
                        'sub' => array(
                            array(
                                'name' => '退款列表',
                                'node' => 'order_refund/index',
                            ),
                            array(
                                'name' => '退款详情',
                                'node' => 'order_refund/detail',
                                'depend' => array('order_refund/index')
                            ),
                        )
                    ),
                )
            ),
            array(
                'name' => '晒单管理',
                'node' => 'share/index',
                'sub' => array(
                    array(
                        'name' => '晒单管理',
                        'node' => 'share/index',
                        'sub' => array(
                            array(
                                'name' => '晒单列表',
                                'node' => 'share/index',
                            ),
                            array(
                                'name' => '审核通过',
                                'node' => 'share/audit',
                                'depend' => array('share/index')
                            )
                        )
                    ),
                )
            ),
        )
    ),
    array(
        'name' => '数据统计中心',
        'node' => 'statistics/index',
        'sub' => array(
            array(
                'name' => '平台统计',
                'node' => 'statistics/index',
                'sub' => array(
                    array(
                        'name' => '流量统计',
                        'node' => 'statistics/index',
                        'sub' => array(
                            array(
                                'name' => '流量统计',
                                'node' => 'statistics/index',
                                'depend' => array('statistics/excel')
                            ),
                        )
                    ),
                    array(
                        'name' => '流水明细',
                        'node' => 'statistics/detail',
                        'sub' => array(
                            array(
                                'name' => '流水明细统计',
                                'node' => 'statistics/detail',
                                'depend' => array('statistics/excel')
                            ),
                        )
                    ),
                    array(
                        'name' => '实时数据',
                        'node' => 'statistics/real_time_detail',
                        'sub' => array(
                            array(
                                'name' => '实时数据',
                                'node' => 'statistics/real_time_detail',
                                'depend' => array('statistics/excel')
                            ),
                        )
                    ),
                ),
            ),
        )
    ),
    array(
        'name' => '机器人中心',
        'node' => 'robot/index',
        'sub' => array(
            array(
                'name' => '机器人管理',
                'node' => 'robot/index',
                'sub' => array(
                    array(
                        'name' => '基础运行设置',
                        'node' => 'robot/index',
                        'sub' => array(
                            array(
                                'name' => '机器人列表',
                                'node' => 'robot/index',
                            ),
                            array(
                                'name' => '启用/禁用机器人',
                                'node' => 'robot/state',
                                'depend' => array('robot/index')
                            ),
                            array(
                                'name' => '机器人晒单',
                                'node' => 'robot/state',
                                'depend' => array('robot/index')
                            )
                        ),
                    )
                ),
            ),
            array(
                'name' => '机器人统计',
                'node' => 'robot/stat_delay',
                'sub' => array(
                    array(
                        'name' => '全盘统计[10分钟延时]',
                        'node' => 'robot/stat_delay',
                        'sub' => array(
                            array(
                                'name' => '全盘统计[10分钟延时]',
                                'node' => 'robot/stat_delay',
                                'depend' => array('robot/excel_delay')
                            )
                        ),
                    ),
                    array(
                        'name' => '全盘统计[实时]',
                        'node' => 'robot/stat',
                        'sub' => array(
                            array(
                                'name' => '全盘统计[实时]',
                                'node' => 'robot/stat',
                                'depend' => array('robot/excel')
                            )
                        ),
                    )
                ),
            ),
            array(
                'name' => '机器人运营',
                'node' => 'robot/share',
                'sub' => array(
                    array(
                        'name' => '机器人晒单',
                        'node' => 'robot/share',
                        'sub' => array(
                            array(
                                'name' => '晒单列表',
                                'node' => 'robot/share',
                            ),
                            array(
                                'name' => '晒单详情',
                                'node' => 'robot/share_detail',
                                'depend' => array('robot/share')
                            ),
                            array(
                                'name' => '添加晒单',
                                'node' => 'robot/share_add',
                                'depend' => array('robot/share','robot/share_upload_img')
                            ),
                            array(
                                'name' => '编辑晒单',
                                'node' => 'robot/share_edit',
                                'depend' => array('robot/share','robot/share_upload_img')
                            )
                        ),
                    )
                ),
            ),
        )
    ),
    array(
        'name' => '系统管理中心',
        'node' => 'welcome/index',
        'sub' => array(
            array(
                'name' => '欢迎登陆',
                'node' => 'welcome/index',
                'sub' => array(
                    array(
                        'name' => '欢迎登陆',
                        'node' => 'welcome/index',
                        'sub' => array(
                            array(
                                'name' => '欢迎登陆',
                                'node' => 'welcome/index'
                            )
                        )
                    )
                )
            ),
            array(
                'name' => '权限管理',
                'node' => 'user/index',
                'sub' => array(
                    array(
                        'name' => '用户管理',
                        'node' => 'user/index',
                        'sub' => array(
                            array(
                                'name' => '用户列表',
                                'node' => 'user/index'
                            ),
                            array(
                                'name' => '添加用户',
                                'node' => 'user/add',
                                'depend' => array('user/index', 'user/name_valid')
                            ),
                            array(
                                'name' => '编辑用户',
                                'node' => 'user/edit',
                                'depend' => array('user/index', 'user/get_user', 'user/name_valid')
                            ),
                            array(
                                'name' => '删除用户',
                                'node' => 'user/delete',
                                'depend' => array('user/index')
                            ),
                            array(
                                'name' => '激活/禁用',
                                'node' => 'user/state',
                                'depend' => array('user/index')
                            ),
                        )
                    ),
                    array(
                        'name' => '角色管理',
                        'node' => 'role/index',
                        'sub' => array(
                            array(
                                'name' => '角色列表',
                                'node' => 'role/index'
                            ),
                            array(
                                'name' => '添加角色',
                                'node' => 'role/add',
                                'depend' => array('role/index')
                            ),
                            array(
                                'name' => '编辑角色',
                                'node' => 'role/edit',
                                'depend' => array('role/index', 'role/get_role')
                            ),
                            array(
                                'name' => '删除角色',
                                'node' => 'role/delete',
                                'depend' => array('role/index')
                            ),
                            array(
                                'name' => '角色授权',
                                'node' => 'role/purview',
                                'depend' => array('role/index')
                            ),
                        )
                    ),
                )
            ),
            array(
                'name' => '信息中心',
                'node' => 'template/index',
                'sub' => array(
                    array(
                        'name' => '模板管理',
                        'node' => 'template/index',
                        'sub' => array(
                            array(
                                'name' => '模板列表',
                                'node' => 'template/index'
                            ),
                            array(
                                'name' => '添加模板',
                                'node' => 'template/add',
                                'depend' => array('template/index')
                            ),
                            array(
                                'name' => '编辑模板',
                                'node' => 'template/edit',
                                'depend' => array('template/index', 'template/get_template')
                            ),
                            array(
                                'name' => '删除模板',
                                'node' => 'template/delete'
                            )
                        ),
                    ),
                )
            ),
            array(
                'name' => '开发专用',
                'node' => 'variable/index',
                'sub' => array(
                    array(
                        'name' => '变量管理',
                        'node' => 'variable/index',
                        'sub' => array(
                            array(
                                'name' => '变量列表',
                                'node' => 'variable/index'
                            ),
                            array(
                                'name' => '添加变量',
                                'node' => 'variable/add',
                                'depend' => array('variable/get_item')
                            ),
                            array(
                                'name' => '编辑变量',
                                'node' => 'variable/edit',
                                'depend' => array('variable/get_item')
                            ),
                            array(
                                'name' => '删除变量',
                                'node' => 'variable/delete'
                            )
                        ),
                    ),
                    array(
                        'name' => '后台工具',
                        'node' => 'tools/index',
                        'sub' => array(
                            array(
                                'name' => '工具列表',
                                'node' => 'tools/index'
                            ),
                            array(
                                'name' => '运行工具',
                                'node' => 'tools/run'
                            ),
                        ),
                    ),
                )
            ),
        )
    ),
);
