<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>我的福袋</title>
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
    <link rel="stylesheet" href="<?=$luckybag_url?>css/base.css">
    <!-- style common -->
    <link rel="stylesheet" href="<?=$luckybag_url?>css/common.css">
    <!-- style layout -->
    <link rel="stylesheet" href="<?=$luckybag_url?>css/layout.css">

    <!-- layer skin extend style -->
    <link rel="stylesheet" href="<?=$luckybag_url?>css/layer_skin_extend.css">

    <script type="text/javascript" src="<?=$resource_url?>js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
</head>