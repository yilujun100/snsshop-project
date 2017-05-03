<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>谁是土豪——百分好礼</title>
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
    <!-- style local tyrants -->
    <link rel="stylesheet" href="<?=$resource_url?>css/layout_local_tyrants.css">
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
</head>
<body>
<div class="viewport v-local-tyrants">
    <!-- local tyrants start -->
    <div class="local-tyrants">
        <div class="banner">
            <img src="<?=$resource_url?>images/local_tyrants/banner.jpg" width="100%" alt="">
            <div class="acti-time">活动时间：6月3日-5日</div>
        </div>

        <div class="user-info clearfix">
            <div class="user-info-l fl">
                <div class="user-avatar"><img src="<?=$self_list['self_head_img']?>" width="56" height="56" alt=""></div>
                <div class="user-name"><?=$self_list['self_name']?></div>
            </div>
            <div class="user-info-r fr">
                <p>当前参与次数：<?=$self_list['self_count']?></p>
                <p>当前排名：<?=$self_list['self_rank']?></p>
                <p>当前夺宝券数：<?=$self_list['self_coupon']?></p>
                <a href="<?=gen_uri('/home/index')?>" class="btn-parti">去夺宝</a>
            </div>
        </div>

        <div class="barrage" id="barrage">
            <div class="w-barrage">
                <div class="w-marquee">
                    <?php
                    if(!empty($begin_list))
                    {
                        foreach($begin_list as $list)
                        {
                            ?>
                            <p><?=$list['sNickName']?>参与了<b><?=$list['count']?>次</b></p>
                        <?php
                        }
                    }
                    ?>
                </div>
                <div class="w-marquee">
                    <?php
                    if(!empty($end_list))
                    {
                        foreach($end_list as $forgery)
                        {
                            ?>
                            <p><?=$forgery['sNickName']?>参与了<b><?=$forgery['count']?>次</b></p>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="tab">
            <div class="tab-hd">
                <a href="javascript:;" class="tab-active">土豪榜</a>
                <a href="javascript:;">奖励</a>
            </div>
            <div class="tab-con">
                <!-- 土豪榜 -->
                <div class="riche-list" style="display: block;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th width="24%"><h3>当前排名</h3></th>
                            <th width="46%"><h3>名称</h3></th>
                            <th width="30%"><h3>夺宝次数</h3></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($result_list))
                        {
                            $i = 1;
                            foreach($result_list as $v)
                            {
                                if($i > 10)
                                {
                                    break;
                                }
                                ?>
                                <tr>
                                    <td><em><?=$i?></em><img src="<?=$v['headImg']?>" width="40" height="40" alt=""></td>
                                    <td><?=$v['sNickName']?></td>
                                    <td><?=$v['count']?></td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <!-- 奖励 -->
                <div class="reward">
                    <table class="table">
                        <thead>
                        <tr>
                            <th width="20%"><h3>排名奖励</h3></th>
                            <th width="58%"><h3>奖品名称</h3></th>
                            <th width="22%"><h3>奖品图片</h3></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>第1名</td>
                            <td>【2188元】Apple iPad mini 2 平板</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/1.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第2名</td>
                            <td>【1288元】周大福佛公足金吊坠</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/2.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第3名</td>
                            <td>【508元】美的高档静音塔扇</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/3.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第4名</td>
                            <td>【188元】SKG家用专业榨汁机</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/4.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第5名</td>
                            <td>【168元】飞科水洗电动剃须刀</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/5.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第6名</td>
                            <td>【128元】南极人冰丝凉席三件套</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/6.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第7名</td>
                            <td>【99元】小米智能手环</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/7.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第8名</td>
                            <td>【66元】先科无线蓝牙音箱</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/8.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第9名</td>
                            <td>【50元】手机话费50元</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/9.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        <tr>
                            <td>第10名</td>
                            <td>【48元】高端电子称体重秤</td>
                            <td><img src="<?=$resource_url?>images/local_tyrants/10.jpg" width="40" height="40" alt=""></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="rules">
            <h3>活动规则</h3>
            <p>1.活动时间为2016年6月3日10：00-6月5日22：00</p>
            <p>2.活动期间，参与“百分好礼”夺宝次数越多，则排名越高</p>
            <p>3.活动结束时，排名最高的前10名。将获得对应排名奖励</p>
            <p>4.参与次数相同时系统按达到该次数的先后顺序排名</p>
            <p>5.奖品将在活动结束后的5个工作日内发放，活动结束后请在个人中心填写相关收货信息</p>
            <p>6.如有疑问，请联系客服0755-86721139</p>
        </div>
        <div class="instruc"><i class="icon-diamond"></i>在法律允许的情况下，微团购将保留活动的最终解释权</div>
    </div>
    <!-- local tyrants end -->
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
        // tab
        fnTab('.tab-hd a', '.tab-con div');

        // barrage
        var aMarquee = $('.w-marquee');
        var aMarP = $('.w-marquee p');
        aMarP.each(function(){
            var randomNum = parseInt(Math.random()*(100-20+1) + 20);
            $(this).css('margin-right', randomNum);
        });
        aMarquee.each(function(){
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