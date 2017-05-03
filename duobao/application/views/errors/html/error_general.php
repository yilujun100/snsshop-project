<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$ci = &get_instance();
$resourec_url = $ci->config->item('resource_url');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>错误页——百分好礼</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="Copyright" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!-- style base -->
    <link rel="stylesheet" href="<?=$resourec_url?>css/base.css">
    <!-- style common -->
    <link rel="stylesheet" href="<?=$resourec_url?>css/common.css">
    <!-- style layout -->
    <link rel="stylesheet" href="<?=$resourec_url?>css/layout.css">
    <style>
        .error p{text-align: center}
    </style>
</head>
<body>
<div class="viewport v-error">
    <div class="error">
        <img src="<?=$resourec_url?>images/v2/icon_error.png" width="90" height="auto" alt="">
        <p><?=$message?></p>
        <div class="action-opera clearfix">
            <a href="javascript:history.go(0)" class="btn btn-block btn-error fl"><span>刷新</span></a>
            <a href="javascript:history.go(-1)" class="btn btn-block btn-warning fr"><span>返回上页</span></a>
        </div>
    </div>
</div>

<div class="fixed-nav">
    <a href="http://duogebao.gaopeng.com/duogebao/home/index"><i class="icon-nav icon-home"></i>首页</a>
    <a href="http://duogebao.gaopeng.com/duogebao/share/index"><i class="icon-nav icon-camera"></i>晒单</a>
    <a href="http://duogebao.gaopeng.com/duogebao/activity/index"><i class="icon-nav icon-acti"></i>活动</a>
    <a href="http://duogebao.gaopeng.com/duogebao/cart/index"><i class="icon-nav icon-cart"></i>清单</a>
    <a href="http://duogebao.gaopeng.com/duogebao/my/index"><i class="icon-nav icon-avatar"></i>个人中心</a>
</div>
</body>
</html>