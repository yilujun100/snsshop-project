<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 日志存储策略
|--------------------------------------------------------------------------
|
| 有效值："single", "daily", "leveldaily", "syslog", "errorlog", 'gptcp'
|
*/
$config['log_strategy'] = 'gptcp';

/*
|--------------------------------------------------------------------------
| 保留的最大日志文件数
|--------------------------------------------------------------------------
|
| 0 表示无限制
|
*/
$config['log_max_files'] = 365;

/*
|--------------------------------------------------------------------------
| 日志级别
|--------------------------------------------------------------------------
|
| "debug": 详细的 debug 信息；
| "info": 感兴趣的事件，如用户登录、SQL日志；
| "notice": 正常但有重大意义的事件；
| "warning": 发生异常，如使用了已经过时的API、不合需要的事情（不一定是错误）；
| "error": 运行时发生了错误，错误需要记录下来并监视，但错误不需要立即处理；
| "critical": 关键错误，如应用中的组件不可用；
| "alert": 需要立即采取措施的错误，如整个网站挂掉了、数据库不可用，这个时候触发器会通过SMS通知你；
| "emergency": 紧急的 alert；
| "off": 关闭日志。
|
*/
$config['log_level'] = 'info';

/*
|--------------------------------------------------------------------------
| 日志存储目录
|--------------------------------------------------------------------------
|
| 为空则设置为默认值：application/logs/
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| 日志存储文件名
|--------------------------------------------------------------------------
|
*/
$config['log_file'] = 'duobao.log';

/*
|--------------------------------------------------------------------------
| spp_groupon_log 配置
|--------------------------------------------------------------------------
|
*/
$config['log_networklog'] = array(
    'networklog_address' => '10.104.177.226',
    'networklog_port' => 13454,
    'networklog_head_len' => 13,
    'networklog_ver' => 1,
    'networklog_cmd' => 100,
    'networklog_appid' => 170,
    'networklog_src' => 0,
);

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| If it is not set, then CodeIgniter will try guess the protocol and path
| your installation, but due to security concerns the hostname will be set
| to $_SERVER['SERVER_ADDR'] if available, or localhost otherwise.
| The auto-detection mechanism exists only for convenience during
| development and MUST NOT be used in production!
|
| If you need to allow multiple domains, remember that this file is still
| a PHP script and you can easily do that on your own.
|
*/
$config['resource_url'] = 'http://imgcache.qq.com/vipstyle/tuan/duobao/indiana_v2/'; //静态资源URL
$config['luckybag_url'] = 'http://imgcache.qq.com/vipstyle/tuan/duobao/luckybag/'; //静态资源URL

$config['need_api_cache'] = 1;       //api层缓存开关 1:开 0:关

/**
 * 用户登录授权地址
 */
$config['passport_wx_url'] = 'http://dgbpassport.gaopeng.com/passport/user/weixin';
$config['pay_result_url'] = 'http://duogebao.gaopeng.com/duogebao/active/order_callback';
$config['duogebao_url'] = 'http://duogebao.gaopeng.com/duogebao';
$config['payment_url'] = 'http://dgbpayment.gaopeng.com/payment';
$config['groupon_url'] = 'http://duogebao.gaopeng.com/groupon';
