<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>充值上榜，大爷来！——百分好礼</title>
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
    <link rel="stylesheet" href="<?=$resource_url?>/css/base.css">
    <!-- style common -->
    <link rel="stylesheet" href="<?=$resource_url?>/css/common.css">
    <!-- style local tyrants -->
    <link rel="stylesheet" href="<?=$resource_url?>/css/layout_local_tyrants_v2.css">
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body style="padding-bottom: 52px;">
<section class="indexPage">
<div class="banner"><img src="<?=$resource_url?>/images/local_tyrants_v2/banner.jpg"  alt=""/></div>
<div class="time-rule"><span>活动时间：6月23日10:00 - 6月23日22:00</span><a href="#rule">活动规则</a></div>
<div class="disclose">
    <div class="ovhd w-marquee">
        <?php foreach($bullet_screen as $k => $li){ if($k < count($bullet_screen)/2) continue; ?>
            <p><?=$li['nick_name']?><em>充值<?=($li['total_price'])?>张赠送<?=($li['present_count'])?>张</em></p>
        <?php } ?>
    </div>
    <div class="ovhd w-marquee">
        <?php foreach($bullet_screen as $k => $li){ if($k >= count($bullet_screen)/2) continue; ?>
            <p><?=$li['nick_name']?><em>充值<?=($li['total_price'])?>张赠送<?=($li['present_count'])?>张</em></p>
        <?php } ?>
    </div>
</div>
<div class="topup-tab">
<nav class="t">
    <div class="t_pad clearfix">
        <span class="bod-r rline choose">充值</span>
        <span class="bod-l rline">榜单</span>
    </div>
</nav>
<div class="m">
<div class="item medal" style=" display:block;">
    <ul class="clearfix">
        <li><a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>100))?>"><img src="<?=$resource_url?>/images/local_tyrants_v2/cz1.png"  alt=""/></a></li>
        <li><a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>50))?>"><img src="<?=$resource_url?>/images/local_tyrants_v2/cz2.png"  alt=""/></a></li>
        <li><a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>30))?>"><img src="<?=$resource_url?>/images/local_tyrants_v2/cz3.png"  alt=""/></a></li>
        <li><a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>10))?>"><img src="<?=$resource_url?>/images/local_tyrants_v2/cz4.png"  alt=""/></a></li>
    </ul>
    <div class="ls-b-yellowbg"></div>
</div>
<div class="item prize" style=" display:none;">
<div class="user-rank">
<div class="bd-user"><img src="<?=$resource_url?>/images/local_tyrants_v2/userbg.png"  alt=""/>
    <div class="user-msg">
        <div class="mas-top">
            <div class="msg-top-left">
                <em><img src="<?=$user_info['head_img']?>"  alt=""/></em>
                <h3><?=$user_info['nick_name']?></h3>
            </div>
            <div class="msg-top-right">
                <p>当前充值金额：<?=(empty($user_pay) ? 0 : intval($user_pay['total_price']/100))?></p>
                <p>当前排行：<?=(empty($user_rank) ? '未上榜' : $user_rank)?></p>
                <p>当前剩余券数：<?=(empty($user_pay) ? 0 : $user_pay['coupon'])?></p>
                <a href="<?=gen_uri('/home/index')?>"><img src="<?=$resource_url?>/images/local_tyrants_v2/btn.png"  alt=""/></a>
            </div>
        </div>
    </div>
    <p class="uscz-tips"><img src="<?=$resource_url?>/images/local_tyrants_v2/tipico.png"  alt=""/>充值排名上榜，统统有礼！排名以系统截止时间为准哦！</p>
</div>
<div class="rank-list">
    <dl>
        <dt>
        <div class="flex"><em>当前排名</em></div>
        <div class="flex3"><em>昵称</em></div>
        <div class="flex2"><em>充值金额</em></div>
        <div class="flex3"><em>奖品价值</em></div>
        <div class="flex"><em>奖品</em></div>
        </dt>

        <?php foreach($list as $k => $li){ if($k >= 10) break; ?>
        <dd>
            <div class="flex">
                <div class="rank-uspcc"><i><?=($k+1)?></i><em><img src="<?=$li['head_img']?>"  alt=""/></em></div>
            </div>
            <div class="flex">
                <p class="rank-name" attr="<?=$li['uin']?>"><?=$li['nick_name']?></p>
            </div>
            <div class="flex5">
                <p class="rank-money"><?=(intval($li['total_price']/100))?></p>
            </div>
            <div class="flex3">
                <div class="rank-goods">
                    <span><?=$prize_list[$k]['price']?>元</span>
                    <h4><?=$prize_list[$k]['title']?></h4>
                </div>
            </div>
            <div class="flex">
                <div class="rank-gpic"><img src="<?=$prize_list[$k]['pic']?>"  alt=""/></div>
            </div>
        </dd>
        <?php } ?>
    </dl>
<div class="ls-b-yellowbg"></div>
</div>
</div>
</div>
</div>
</div>


<div class="rule" id="rule">
    <h3>活动规则</h3>
    <strong><em>1.</em><p>活动时间为2016年6月23日10:00-6月23日22:00</p></strong>
    <strong><em>2.</em><p>活动期间，单次：充10张送1张；充30张送5张；充50张送8张；充100张送18张。赠券与正常券价值相同</p></strong>
    <strong><em>3.</em><p>充值金额越高,排名越高,前10名可获得上榜奖励</p></strong>
    <strong><em>4.</em><p>充值上榜奖励排名以截止时间为准：即6月23日22:00。届时请留意推送消息，中奖者填写相关收货信息后，5个工作日内发放上榜奖品哦！</p></strong>
    <strong><em>5.</em><p>充值金额用于购买券，参与"百分好礼"，充值款项无法退回</p></strong>
    <strong><em>6.</em><p>同等充值金额，排名以充值系统时间顺序为准</p></strong>
    <strong><em>7.</em><p>如有疑问，请联系客服:0755-86721139</p></strong>
</div>
<div class="copy"><em></em>在法律允许的情况下，微团购将保留活动的最终解释权</div>
</section>

<?php $this->widget('menus', array('menus_show'=>true)) ?>

<!-- jquery lib script -->
<script type="text/javascript" src="<?=$resource_url?>/js/jquery-2.1.4.min.js"></script>
<!-- common script -->
<script type="text/javascript" src="<?=$resource_url?>/js/lib.js"></script>
<!-- jquery marquee script -->
<script type="text/javascript" src="<?=$resource_url?>/js/jquery.marquee.js"></script>
<script>
    $(function(){
// tab
// fnTab('.t_pad span', '.m > div');
        var aTit = $('.t_pad span');
        aTit.each(function(i){

            var _this = $(this);
            _this.on('click', function(){
                _this.addClass('choose').siblings().removeClass('choose');
                $('.m > div').eq(i).show().siblings().hide();
                return false;
            });

        })

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
<?php $this->widget('weixin_share', array()) ?>
<?php if(defined('ENVIRONMENT') && ENVIRONMENT === 'production'){ ?>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=55791706" charset="UTF-8"></script>
    <script type="text/javascript" src="http://ta.nexto2o.com/js/ta.js?id=1&siteid=683&key=<?=get_nex_to_key()?>"></script>
<?php } ?>
</body>
</html>