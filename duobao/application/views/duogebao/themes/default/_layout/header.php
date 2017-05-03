<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=!empty($page_title)?$page_title:"百分好礼"?></title>
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
    <!-- style swiper -->
    <link rel="stylesheet" href="<?=$resource_url?>js/swiper/swiper.min.css">
    <!-- style layout -->
    <link rel="stylesheet" href="<?=$resource_url?>css/layout.css">
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
    <!-- jquery lib script -->
    <script type="text/javascript" src="<?=$resource_url?>js/jquery-2.1.4.min.js"></script>
    <!-- common script -->
    <script type="text/javascript" src="<?=$resource_url?>js/lib.js"></script>
    <!-- swiper script -->
    <script type="text/javascript" src="<?=$resource_url?>js/swiper/swiper.min.js"></script>
    <script type="text/javascript" src="<?=$resource_url?>js/layer/layer.js"></script>

</head>