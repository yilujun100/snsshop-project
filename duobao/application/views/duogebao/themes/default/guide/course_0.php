<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>活动规则——百分好礼</title>
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
    <link rel="stylesheet" href="<?=$resource_url?>css/base.css">
    <!-- style common -->
    <link rel="stylesheet" href="<?=$resource_url?>css/common.css">
    <!-- style rules -->
    <link rel="stylesheet" href="<?=$resource_url?>css/layout_rules.css">
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="<?=$resource_url?>js/zepto.min.js" type="text/javascript"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
</head>
<body style="background: #e43942">
<div class="viewport v-rules">
    <img src="<?=$resource_url?>images/rules/course.jpg" width="100%"  alt="">
</div>

<?php $this->widget('weixin_share', array()) ?>
</body>
</html>