<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| 微信分享配置
|--------------------------------------------------------------------------
*/
$config['wx_share'] = array(
    'default_share_img' => 'images/active_share_default.jpg',
    'default' => array( // 默认
        'shareTitle' => '百分好礼”限时充值送！你敢充我就敢送！',
        'sendFriendTitle' => '“百分好礼”限时充值送！',
        'sendFriendDesc' => '你敢充我就敢送！',
        'shareUrl' => '',
        'shareImg' => ''
    ),
    'active' => array( // 夺宝
        'shareTitle' => '最新一期[{{sGoodsName|20}}]百分就有，立马拔草!',
        'sendFriendTitle' => '【小宝@你】一百分我就跟你走！',
        'sendFriendDesc' => '最新一期[{{sGoodsName|20}}]百分就有，立马拔草!',
        'shareUrl' => '',
        'shareImg' => '{{sImg}}'
    ),
    'active_custom' => array( // 私人定制
        'shareTitle' => '[{{user.sNickName}}]发起了夺宝，再不应战就被人抢走了！',
        'sendFriendTitle' => '【小宝@你】[{{sActName|20}}]',
        'sendFriendDesc' => '[{{user.sNickName}}]发起了夺宝，再不应战就被人抢走了！',
        'shareUrl' => '',
        'shareImg' => '{{sImg}}'
    ),
    'activity' => array( // 活动中心
        'shareTitle' => '世间福利千万，我独好这口',
        'sendFriendTitle' => '福利到',
        'sendFriendDesc' => '世间福利千万，我独好这口',
        'shareUrl' => '',
        'shareImg' => ''
    ),
    'help' => array( // 帮助中心
        'shareTitle' => '【帮助中心】哪里不会点哪里',
        'sendFriendTitle' => '帮助中心',
        'sendFriendDesc' => '哪里不会点哪里',
        'shareUrl' => '',
        'shareImg' => ''
    ),
    'luckybag' => array( // 福袋相关页面分享
        'shareTitle' => '有土豪发福袋，快抢！',
        'sendFriendTitle' => '有土豪发福袋，快抢！',
        'sendFriendDesc' => '抢完福袋抢好礼 IPhone6S闪亮到手！',
        'shareUrl' => '',
        'shareImg' => 'http://imgcache.qq.com/vipstyle/tuan/duobao/luckybag/images/share_logo.png'
    )
);
