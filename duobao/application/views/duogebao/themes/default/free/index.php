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
    <script src="<?=$resource_url?>js/jquery.marquee.js" type="text/javascript"></script>
    <title>0元夺宝——百分好礼</title>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
    <style>
        .disable{background-color: #B7A6A8;}
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
    <div class="page">
        <div class="bfhl-bn">
            <img src="<?=$resource_url?>/free/img/bfhl_hbn.jpg" alt=""/>
        </div>

        <?php foreach($list as $k => $detail){ $peroid_str = period_code_encode($detail['iActId'],$detail['iPeroid']); ?>
        <div class="bfhl-cy bg">
            <div class="flexbox">
                <?php if($k == 0){ ?>
                <div class="flex-hd">
                    <a href="javascript:void(0)" class="bfhl-hbtn">
                        <img src="<?=$resource_url?>/free/img/btn1.png" alt=""/>
                    </a>
                </div>
                <?php } ?>
                <div class="flex">
                    <div class="bfhl-nums">
                        <?php $sold = sprintf('%05s', empty($detail['cache_data']) ? 110 : $detail['cache_data']);?>
                        <span class="bfhl-num"><?=(!substr($sold,0,1) ? 0 : substr($sold,0,1))?></span>
                        <span class="bfhl-num"><?=(!substr($sold,1,1) ? 0 : substr($sold,1,1))?></span>
                        <span class="bfhl-num"><?=(!substr($sold,2,1) ? 0 : substr($sold,2,1))?></span>
                        <span class="bfhl-num"><?=(!substr($sold,3,1) ? 0 : substr($sold,3,1))?></span>
                        <span class="bfhl-num"><?=(!substr($sold,4,1) ? 0 : substr($sold,4,1))?></span>
                    </div>
                </div>
                <?php if($k == 0){ ?>
                <div class="flex-fd">
                    <a href="#share" class="bfhl-hbtn">
                        <img src="<?=$resource_url?>/free/img/btn2.png" alt=""/>
                    </a>
                </div>
                <?php } ?>
            </div>
            <div class="bfhl-good clearfix">
                <div class="left-good-info fl">
                    <div class="info-box">
                        <div class="good-img">
                            <img src="<?=$detail['sImg']?>" alt=""/>
                            <span class="tag-free"><em>本期<br>免费</em></span>
                        </div>
                        <div class="good-name" style="text-align: center;">
                            <?=$detail['sGoodsName']?>
                        </div>
                    </div>
                </div>
                <div class="right-good-info fl">
                    <div class="text-info icon1">
                        <span>当前可参与次数：<?=$user_ext['free_coupon']?></span>
                    </div>
                    <div class="text-info icon2">
                        <span>开始时间：<?=date('Y年m月d日',$detail['iBeginTime'])?></span>
                    </div>
                    <div class="text-info endtime icon3">
                        <span class="countDown" data-start-time="<?=date('Y/m/d H:i:s',time())?>" data-end-time="<?=date('Y/m/d H:i:s',$detail['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_DEFAULT ? $detail['iEndTime'] : time())?>">截止时间：<label class="hour">0</label>小时<label class="min">00</label>分<label class="sec">00</label>秒</span>
                    </div>
                    <a href="javascript:void(0)" class="right-good-btn submit-btn <?=($detail['iEndTime'] <= time() || $detail['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT  ? 'disable':'')?>" data="<?=gen_uri('/active/active_buy',array('peroid_str'=>$peroid_str,'back_url'=>urlencode(gen_uri('/free/result',array('peroid_str'=>$peroid_str)))))?>"><?=($detail['iEndTime'] <= time() || $detail['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_DEFAULT ? '已结束':'0元参与')?></a>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <div class="bfhl-dm bg">
            <img src="<?=$resource_url?>/free/img/bfhl-jc2.jpg" alt="">
            <div class="disclose">
                <div class="ovhd w-marquee">
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                </div>
                <div class="ovhd w-marquee">
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                    <p><em>人生如此美</em>好获得了<em>2次</em>参与机会</p>
                </div>
            </div>
        </div>

        <div class="bfhl-gl bg">
            <img src="<?=$resource_url?>/free/img/bfhl-jc.jpg" alt=""/>
            <img src="<?=$resource_url?>/free/img/bfhl-gl.jpg" alt=""/>
            <div class="bfhl-gl-btn clearfix">
                <!--<div class="btn-box fl">
                    <a href="###" class="gl-btn gl-btn1" attr="on">
                        提醒我
                    </a>
                </div>-->
                <div class="btn-box" style="margin:0 auto;">
                    <a href="javascript:void" class="gl-btn gl-btn2 " id="share" name="#share">
                        邀请好友免费夺宝
                    </a>
                </div>
            </div>
        </div>
        <div class="bfhl-gz bg">
            <img src="<?=$resource_url?>/free/img/bfhl-jc.jpg" alt=""/>
            <div class="bfhl-gz-text">
                <p>活动规则</p>
                <p>1.活动时间为2016年5月17日—5月24日</p>
                <p>2.每日首次进入活动页即可获得1次免费夺宝机会</p>
                <p>3.邀请好友首次参与活动即可获得1次免费夺宝机会，邀请N个好友参与可获得N次参与机会，不设上限</p>
                <p>4.活动结束时系统将按计算规则随机开奖，并在公众号中推送中奖结果，中奖用户请在24小时内填写收货信息，客服将根据收货信息与您联系</p>
                <p>5.如有疑问，请联系客服0755-86721139</p>
                <p>6.在法律允许范围内，微团购保留最终解释权</p>
            </div>
        </div>
    </div>
    <div id="home" style="right: 0;top: 45%;width:32px;position: fixed;">
        <a href="<?=gen_uri('/home/index')?>">
            <img src="<?=$resource_url?>/free/img/home.png">
        </a>
    </div>
    <div id="remind" style="left: 0;top: 45%;width:32px;position: fixed;">
        <a href="javascript:;">
            <img src="<?=$resource_url?>/free/img/remind.png">
        </a>
    </div>
</div>
<!--分享-->
<div class="mask mask-share" id="maskCustomDetail" style="z-index: 101;"></div>
<div class="pop-share" id="customShare">
    <img src="<?=$resource_url?>images/share_weixin_1.png" width="100%" alt="">
</div>

<!-- 每日提醒 提示框 start -->
<style>
    .pop-mask-remind,
    .pop-remind { display: none; }
    .pop-mask-remind { position: fixed; top: 0; left: 0; z-index: 101; width: 100%; height: 100%; background: #000; filter: alpha(opacity=60); opacity: .6; }
    .pop-remind { position: fixed; top: 50%; left: 50%; z-index: 102; margin-top: -62px; margin-left: -150px; width: 300px; height: 124px; background: #fff; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px; }
    .pop-remind-bott { margin-top: 20px; padding: 0 10px; }
    .pop-remind-bott .pop-btns { display: inline-block; margin: 0 8px; width: 122px; height: 36px; line-height: 36px; text-align: center; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; }
    .pop-remind-content { padding: 20px 20px 0; text-align: center; font-size: 13px; color: #333; }
    .pop-remind-bott a:first-child { background: #f3f3f3; color: #666; }
    .pop-remind-bott a:last-child { background: #d83645; color: #fff; }
</style>
<div class="pop-mask-remind"></div>
<div class="pop-remind">
    <div class="pop-remind-content">
        开启提醒后每天10:00将会通知您来免费参与一次哦！
    </div>
    <div class="pop-remind-bott">
        <a href="javascript:;" class="pop-btns btn-pop-cancel">取消</a>
        <a href="javascript:;" class="pop-btns btn-pop-confirm">确认</a>
    </div>
</div>
<!-- 每日提醒 提示框 end -->

<script>
    $(function(){
        $('.submit-btn').on('click',function(){
            if($(this).hasClass('disable')){

            }else{
                var href = $(this).attr('data');
                location.href = href;
            }
        })
        $('#share').on('click',function(){
            $('.mask').show();
            $('.pop-share').show();
        })

        $('.mask,.pop-share').on('click',function(){
            $('.mask').hide();
            $('.pop-share').hide();
        })


        // 倒计时
        $('.countDown').each(function(i,ele){
            ele = $(ele);
            var leftTime = (new Date(ele.attr('data-end-time')).getTime() - new Date(ele.attr('data-start-time')).getTime())/1000;
            countDown(ele, leftTime);
        });


        function countDown(ele, time) {
            var el = ele;
            var timer = null;
            function getTimerString(time) {
                d = Math.floor(time / 86400),
                    h = Math.floor((time % 86400) / 3600),
                    m = Math.floor(((time % 86400) % 3600) / 60),
                    s = Math.floor(((time % 86400) % 3600) % 60);

                if (time>0) {
                    h = h + 24*d;
                    rootHtml(h, m, s);
                }
                else {
                    clearInterval(timer);
                    rootHtml(00, 00, 00);
                }
            }

            function parseFn() {
                getTimerString(time-=1);
            }

            function rootHtml() {
                var countDownObj = $(el);
                var hour = countDownObj.find('.hour'),
                    min = countDownObj.find('.min'),
                    sec = countDownObj.find('.sec');

                hour.html(toDouble(arguments[0]));
                min.html(toDouble(arguments[1]));
                sec.html(toDouble(arguments[2]));
            }
            timer = setInterval(function(){
                parseFn();
            },1000);
            var toDouble = function(num){
                return num < 10 ? '0'+num : num;
            };

            var haomiao = function(num) {
                if (num < 10) return '00' + num.toString();
                if (num < 100) return '0' + num.toString();
                return num.toString();
            };
            parseFn();
        }

        // 参与人数
        function partiNumber(initNumber,index){
            var box = $('.bfhl-nums').eq(index);
            var numbers = box.find('span.bfhl-num');
            var digit = initNumber.toString().length;
            if (digit == 4) {
                initNumber = '0' + initNumber;
            }
            if (digit == 3) {
                initNumber = '00' + initNumber;
            }
            if (digit == 2) {
                initNumber = '000' + initNumber;
            }
            if (digit == 1) {
                initNumber = '0000' + initNumber;
            }
            var newNumber = initNumber.toString().split('');
            for (var i in newNumber) {
                numbers.eq(i).html(newNumber[i]);
            }
        }

        setInterval(function(){
            $.getJSON('<?=gen_uri('/free/ajax_active_num',array('peroid_str'=>$peroid_arr))?>',{},function($rs){
                if($rs.retCode == 0){
                    $rs.retData = typeof $rs.retData === 'string' ? $.parseJSON($rs.retData) : $rs.retData;
                    $.each($rs.retData,function(i,data){
                        if(typeof data == 'number'){
                            partiNumber(data,i);
                        }
                    })
                }
            })
        }, 20000);

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
        }); 

        // 每日提醒
        $('#remind a').on('click', function(){
            $('.pop-mask-remind, .pop-remind').show();
        });
        $('.btn-pop-cancel').on('click', function(){
            $('.pop-mask-remind, .pop-remind').hide();
        });

    })
</script>
<?php $this->widget('weixin_share', array()) ?>
<?php if(defined('ENVIRONMENT') && ENVIRONMENT === 'production'){ ?>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=55791706" charset="UTF-8"></script>
<?php } ?>
</body>
</html>