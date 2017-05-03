<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>充值活动——百分好礼</title>
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
    <!-- style recharge -->
    <link rel="stylesheet" href="<?=$resource_url?>css/layout_recharge_1.css">
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
</head>
<body class="bd-recharge">
<div class="viewport v-recharge-1">
    <div class="banner">
        <img src="<?=$resource_url?>images/recharge/banner.png" width="100%" alt="">
    </div>
    <!-- 倒计时 -->
    <div class="time-wrap">
        <div class="time-inner" id="countDown" data-start-time="<?=$time_now?>" data-end-time="2016/6/03 23:59:59">
            <span class="hour">00</span>
            <span class="sepera">:</span>
            <span class="minutes min">00</span>
            <span class="sepera">:</span>
            <span class="seconds sec">00</span>
            <span class="sepera">:</span>
            <span class="ms">000</span>
        </div>
    </div>
    <!-- 弹幕框 -->
    <div class="marquee-wrap">
        <div class="marquee-inner">
            <div class="marquee">
                <?php
                foreach($forgery_list_v1 as $v1)
                {
                    ?>
                    <p><?=$v1['sNickName']?><span class="text-info">充值<?=$v1['count']?>张赠送<?=$v1['largess']?>张</span></p>
                <?php
                }
                ?>
            </div>
            <div class="marquee">
                <?php
                foreach($forgery_list_v2 as $v2)
                {
                    ?>
                    <p><?=$v2['sNickName']?><span class="text-info">充值<?=$v2['count']?>张赠送<?=$v2['largess']?>张</span></p>
                <?php
                }
                ?>
            </div>
            <div class="marquee">
                <?php
                foreach($forgery_list_v3 as $v3)
                {
                    ?>
                    <p><?=$v3['sNickName']?><span class="text-info">充值<?=$v3['count']?>张赠送<?=$v3['largess']?>张</span></p>
                <?php
                }
                ?>
            </div>
            <div class="marquee">
                <?php
                foreach($forgery_list_v4 as $v4)
                {
                    ?>
                    <p><?=$v4['sNickName']?><span class="text-info">充值<?=$v4['count']?>张赠送<?=$v4['largess']?>张</span></p>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <!-- 活动内容 -->
    <div class="activity-content">
        <!-- 充值链接 -->

        <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>100))?>" class="voucher-link voucher-link100"></a>
        <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>50))?>" class="voucher-link voucher-link50"></a>
        <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>30))?>" class="voucher-link voucher-link30"></a>
        <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>20))?>" class="voucher-link voucher-link20"></a>
        <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>10))?>" class="voucher-link voucher-link10"></a>
        <!-- 规则 -->
        <div class="rules-content">
            <h3 class="title">活动时间</h3>
            <p class="">2016年6月3日00:00:00-23:59:59（疯抢仅限24h）</p>
            <h3 class="title">活动规则</h3>
            <p>1. 活动期间，“百分好礼”单次充值券</p>
            <p style="padding-left: 14px;">满10张送1张；满20张送3张；满30张送5张</p>
            <p style="padding-left: 14px;">满50张送8张；满100张送18张；</p>
            <p>2. 充值券及赠券您可前往个人中心查询；</p>
            <p>3. 赠券与正常券等值使用，均用于参与“百分好礼”。</p>
        </div>
        <!-- copyright -->
        <div class="copyright">
            <span>在法律允许的情况下，微团购将保留活动最终解释权</span>
        </div>
    </div>
</div>
<div class="fixed-nav">
    <a href="<?=gen_uri('/home/index')?>" class="active"><i class="icon-nav icon-home"></i>首页</a>
    <a href="<?=gen_uri('/share/index')?>"><i class="icon-nav icon-camera"></i>晒单</a>
    <!-- <a href="#"><i class="icon-nav icon-gift"></i>活动</a> -->
    <a href="<?=gen_uri('/activity/index')?>"><span class="icon-nav icon-gift"><em class="dotted"></em></span>活动</a>
    <a href="<?=gen_uri('/cart/index')?>"><span class="icon-nav icon-cart"><em class="cart-num" id="cartNum">1</em></span>夺宝车</a>
    <a href="<?=gen_uri('/my/index')?>"><i class="icon-nav icon-avatar"></i>个人中心</a>
</div>
<!-- jquery lib script -->
<script type="text/javascript" src="<?=$resource_url?>js/jquery-2.1.4.min.js"></script>
<!-- common script -->
<script type="text/javascript" src="<?=$resource_url?>js/lib.js"></script>
<!-- jquery marquee script -->
<script type="text/javascript" src="<?=$resource_url?>js/jquery.marquee.js"></script>
<script>
    $(function(){
        (function($){
            setTime();

            var startTime = $('#countDown').attr('data-start-time');
            var endTime = $('#countDown').attr('data-end-time')
            var leftTime = new Date(endTime).getTime() - new Date(startTime).getTime();
            var timer = null;
            var fnCountDown = function (str) {
                var timeDistance = str;
                var hm = Math.floor(timeDistance%1000);
                var sec = Math.floor(timeDistance/1000%60);
                var min = Math.floor(timeDistance/1000/60%60);
                var hour = Math.floor(timeDistance/1000/60/60%24);

                $('.hour').html(toDouble(hour));
                $('.min').html(toDouble(min));
                $('.sec').html(toDouble(sec));
                $('.ms').html(haomiao(hm));
                leftTime = leftTime - 50;
            }

            function setTime() {
                timer = setInterval(function(){
                    fnCountDown(leftTime);
                    if (leftTime <= 0) {
                        clearInterval(timer);
                        $('.hour').html('00');
                        $('.min').html('00');
                        $('.sec').html('00');
                        $('.ms').html('000');
                    }
                }, 50);
            };

            var toDouble = function(num){

                return num < 10 ? '0'+num : num;

            };

            var haomiao = function(num) {
                if (num < 10) return '00' + num.toString();
                if (num < 100) return '0' + num.toString();
                return num.toString();
            };
        })(jQuery)



        // barrage
        var aMarquee = $('.marquee');
        var aMarP = $('.marquee p');
        aMarP.each(function(){
            var randomNum = parseInt(Math.random()*(100-20+1) + 20);
            $(this).css('margin-right', randomNum);
        });
        aMarquee.each(function(){
            console.log('test');
            var _this = $(this);
            if (_this.index() % 2 == 0) {
                _this.marquee({
                    duration: 15000,
                    duplicated: true,
                    delayBeforeStart: 2000
                });
            } else {
                _this.marquee({
                    duration: 15000,
                    duplicated: true
                });
            }
        })
    })

</script>
<?php if(!empty($signPackage)){ ?>
    <script>
        //JSSDK 接口配置
        <?php
        if(!empty($signPackage)){
            foreach ($signPackage AS $k => $v){
        ?>
        var <?=$k?> = '<?=$v?>';
        <?php  }} ?>

        /*分享发送数据*/
        var shareTitle = '<?=isset($shareData['shareTitle']) ? $shareData['shareTitle'] : ''?>';
        var sendFriendTitle = '<?=isset($shareData['sendFriendTitle']) ? $shareData['sendFriendTitle'] : ''?>';
        var sendFriendDesc = '<?=isset($shareData['sendFriendDesc']) ? $shareData['sendFriendDesc'] : ''?>';
        var shareUrl =  '<?=isset($shareData['shareUrl']) ? $shareData['shareUrl'] : current_url()?>';
        var shareImg =  '<?=isset($shareData['shareImg']) ? $shareData['shareImg'] : ''?>';

        $(function(){
            //微信分享配置
            wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: appId, // 必填，公众号的唯一标识
                timestamp:timestamp, // 必填，生成签名的时间戳
                nonceStr: nonceStr, // 必填，生成签名的随机串
                signature: signature,// 必填，签名，见附录1
                jsApiList: [
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'showOptionMenu',
                    'hideOptionMenu',
                    'hideAllNonBaseMenuItem',
                    'showMenuItems'
                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
            //微信分享
            wx.ready(function() {
                //屏蔽分享及刷新菜单
                if (typeof DUOBAO.menuShow !== 'undefined' && DUOBAO.menuShow == '0') {
                    wx.hideOptionMenu();
                } else {
                    wx.showOptionMenu();
                    wx.hideAllNonBaseMenuItem();
                    wx.showMenuItems({
                        menuList: [
                            'menuItem:share:appMessage',
                            'menuItem:share:timeline',
                            'menuItem:favorite',
                            'menuItem:copyUrl',
                            'menuItem:share:email',
                            'menuItem:share:brand'
                        ]
                    });
                    //分享到朋友圈
                    wx.onMenuShareTimeline({
                        title:shareTitle, // 分享标题
                        link:shareUrl, // 分享链接
                        imgUrl:shareImg,// 分享图标
                        success: function () {
                            if(url.indexOf('?') == -1){
                                url = url + '?isload=1';
                            }else{
                                url = url + '&isload=1';
                            }
                            location.href = url;
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    //分享给朋友
                    wx.onMenuShareAppMessage({
                        title:sendFriendTitle, // 分享标题
                        desc:sendFriendDesc, // 分享描述
                        link:shareUrl, // 分享链接
                        imgUrl:shareImg, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            if(url.indexOf('?') == -1){
                                url = url + '?isload=1';
                            }else{
                                url = url + '&isload=1';
                            }
                            location.href = url;
                        },
                        cancel: function () {
                        }
                    });
                }

            });
        })
    </script>
<?php } ?>
<?php if(defined('ENVIRONMENT') && ENVIRONMENT === 'production'){ ?>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=55791706" charset="UTF-8"></script>
    <script type="text/javascript" src="http://ta.nexto2o.com/js/ta.js?id=1&siteid=683&key=<?=get_nex_to_key()?>"></script>
<?php } ?>
</body>
</html>
