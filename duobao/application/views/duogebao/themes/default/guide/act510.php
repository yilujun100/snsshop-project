<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="telephone=no" name="format-detection">
    <link rel="stylesheet" type="text/css" href="<?=$resource_url?>css/act/act510.css">
    <link rel="stylesheet" type="text/css" href="<?=$resource_url?>css/loading.css">
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
    <title>百分好礼积分活动</title>
</head>

<body>
<div class="loadingbg"></div>
<section class="loading">
    <div class="ld">
        <div><img src="<?=$resource_url?>images/act510/loading.gif"><br>加载中...</div>
    </div>
</section>
<div class="main">
    <div class="pic banner">
        <div class="rule">
            <img src="<?=$resource_url?>images/act510/rule.png" alt="">
        </div>
        <a href="<?=gen_uri('/guide/course')?>">
            <img src="<?=$resource_url?>images/act510/banner.jpg" alt="">
        </a>
    </div>
    <div class="pic pic1">
        <img src="<?=$resource_url?>images/act510/pic1.png" alt="">
    </div>
    <div class="pic pic2">
        <a href="<?=gen_uri('/home/index')?>"><div class="btn">去签到</div></a>
        <img src="<?=$resource_url?>images/act510/pic2.jpg" alt="">
    </div>
    <div class="pic pic3">
        <a href="<?=gen_uri('/my/active',array('cls'=>'winner'))?>"><div class="btn">去晒单</div></a>
        <img src="<?=$resource_url?>images/act510/pic3.jpg" alt="">
    </div>
    <div class="pic pic4">
        <a href="<?=gen_uri('/share/index')?>"><div class="btn">去点赞</div></a>
        <img src="<?=$resource_url?>images/act510/pic4.jpg" alt="">
    </div>
    <div class="pic pic5">
        <a href="<?=gen_uri('/score/mall')?>"><div class="btn">立即兑换</div></a>
        <img src="<?=$resource_url?>images/act510/pic5.jpg" alt="">
    </div>
    <div class="pic pic6">
        <a href="<?=gen_uri('/help/index')?>"><div class="btn">了解百分好礼</div></a>
        <img src="<?=$resource_url?>images/act510/pic6.jpg" alt="">
    </div>
    <div class="pic pic7">
        <img src="<?=$resource_url?>images/act510/pic7.jpg" alt="">
    </div>
    <div class="pic pic8">
        <img src="<?=$resource_url?>images/act510/pic8.jpg" alt="">
    </div>
    <div class="pic pic9">
        <img src="<?=$resource_url?>images/act510/pic9.jpg" alt="">
    </div>
    <div class="back" style="display: none;"><img src="<?=$resource_url?>images/act510/back.png" alt=""></div>
</div>
<script src="<?=$resource_url?>js/zepto.min.js" type="text/javascript"></script>
<script src="<?=$resource_url?>js/zepto-touch.js" type="text/javascript"></script>
<script src="<?=$resource_url?>js/zepto-animate.js" type="text/javascript"></script>
<script src="<?=$resource_url?>js/touch-right.js" type="text/javascript"></script>
<script src="<?=$resource_url?>js/main.js" type="text/javascript"></script>
<script type="text/javascript">
    //靠右悬浮可拖动
    var demo = $(".back");
    right(demo);
</script>
<?php $this->widget('weixin_share', array()) ?>
</body>

</html>
