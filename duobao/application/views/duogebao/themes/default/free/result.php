<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="telephone=no" name="format-detection">
    <link rel="stylesheet" type="text/css" href="<?=$resource_url?>/free/css/style.css">
    <!-- jquery lib script -->
    <script type="text/javascript" src="<?=$resource_url?>js/jquery-2.1.4.min.js"></script>
    <!-- common script -->
    <script type="text/javascript" src="<?=$resource_url?>js/lib.js"></script>
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="<?=$resource_url?>js/zepto.min.js" type="text/javascript"></script>
    <title>0元夺宝——百分好礼</title>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
    <style>
        .bf-avast{
            width: 12.4rem;
            height: 12.4rem;
            margin:-2rem auto 2rem;
            overflow: hidden;
            box-shadow: 0 0 0 5px #f96682;
            position: relative;
            z-index: 10;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
        }
        .bf-avast img { position: absolute; top: 0; left: 0; z-index: 9; display: block; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%; }
    </style>
</head>

<body>
<div class="indexPage">
    <div class="page bg">
        <div class="header">
            <img src="<?=$resource_url?>/free/img/bfhl_hbg.png" alt=""/>
        </div>
        <div class="bf-avast">
            <img src="<?=$detail['sImg']?>" alt=""/>
        </div>
        <div class="bf-mtext">
            <img src="<?=$resource_url?>/free/img/bfhl_text.png" alt=""/>
        </div>
        <a class="bf-btn btn-color" href="javascript:void(0)" id="share">去分享</a>
        <a class="bf-btn btn-color margin-bottom20" href="<?=gen_uri('/home/index')?>">回首页</a>
    </div>
</div>

<!--分享-->
<div class="mask mask-share" id="maskCustomDetail" style="z-index: 101;"></div>
<div class="pop-share" id="customShare">
    <img src="<?=$resource_url?>images/share_weixin_1.png" width="100%" alt="">
</div>

<script>
    $(function(){
        $('#share').on('click',function(){
            $('.mask').show();
            $('.pop-share').show();
        })

        $('.mask,.pop-share').on('click',function(){
            $('.mask').hide();
            $('.pop-share').hide();
        })
    })
</script>
<?php $this->widget('weixin_share', array()) ?>

<?php if(defined('ENVIRONMENT') && ENVIRONMENT === 'production'){ ?>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=55791706" charset="UTF-8"></script>
<?php } ?>
</body>
</html>
