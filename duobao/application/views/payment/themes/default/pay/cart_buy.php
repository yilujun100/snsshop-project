<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>清单结算——百分好礼</title>
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
    <!-- layer skin extend style -->
    <link rel="stylesheet" href="<?=$resource_url?>css/layer_skin_extend.css">
    <!-- style layout -->
    <link rel="stylesheet" href="<?=$resource_url?>css/layout.css">
    <!-- jquery lib script -->
    <script type="text/javascript" src="<?=$resource_url?>js/jquery-2.1.4.min.js"></script>
    <!-- jquery layer script -->
    <script src="<?=$resource_url?>js/layer/layer.js" type="text/javascript"></script>
    <script src="<?=$resource_url?>js/lib.js" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
</head>
<body>
<div class="viewport v-settlement">
    <!-- pay progress -->
    <div class="grid-1 pay-progress">
        <ul class="clearfix">
            <li class="step-on">
                <i class="icon-pay-step icon-pro-gift"></i>
                <em class="icon-dotted"></em>
                <h3>选择参与活动</h3>
            </li>
            <li class="step-on">
                <i class="icon-pay-step icon-cash"></i>
                <em class="icon-dotted"></em>
                <h3>确认参与</h3>
                <s></s>
            </li>
            <li>
                <i class="icon-pay-step icon-codes"></i>
                <em class="icon-dotted"></em>
                <h3>系统发码</h3>
                <s></s>
            </li>
            <li>
                <i class="icon-pay-step icon-cup-1"></i>
                <em class="icon-dotted"></em>
                <h3><?=(empty($detail['iCodePrice']) ? '截止时间开奖' : '集齐人数开奖')?></h3>
                <s></s>
            </li>
        </ul>
    </div>

    <!-- indiana list -->
    <div class="indiana-list">
        <div class="settle-list-w">
            <form id="myform" action="" name="myform">
                <?php foreach($active as $list){   ?>
                    <div class="grid settle-item ">
                        <div class="settle-goods-pic">
                            <img src="<?=$list['sImg']?>" width="60" height="60" alt="">
                        </div>
                        <h3><?=$list['sGoodsName']?></h3>
                        <p class="clearfix"><span class="fl">单次用券<strong><?=$list['iCodePrice']/Lib_Constants::COUPON_UNIT_PRICE?></strong></span><em class="fr">x<?=$qty_arr[$list['iActId']]?></em></p>
                        <input type="hidden" name="qty[]" value="<?=$qty_arr[$list['iActId']]?>">
                        <input type="hidden" name="active_arr[]" value="<?=period_code_encode($list['iActId'],$list['iPeroid'])?>">
                    </div>
                <?php } ?>
                <input type="hidden" name="cart" value="1">
                <input type="hidden" name="total" value="<?=$total?>">
                <input type="hidden" name="my_coupon" value="<?=$user_ext['coupon']?>">
            </form>
        </div>
        <a href="javascript:;" class="btn-settle-toggle btn-settle-down">展开<i></i></a>
    </div>
    <div class="settle-total-stamps">总计用券：<strong><?=$total/Lib_Constants::COUPON_UNIT_PRICE?></strong></div>

    <div class="grid settle-col clearfix">
        <span class="fl">我的券：<?=$user_ext['coupon']?>张</span>
        <span class="settle-stamps-use fr">使用<strong><?=$total/Lib_Constants::COUPON_UNIT_PRICE?></strong>张</span>
        <span class="settle-stamps-insuff fr">券不足,请先充值</span>
    </div>

    <a href="javascript:void(0)" id="submit-btn" class="btn btn-block btn-error btn-confirm-parti"><span>确认参与</span></a>
</div>

<div class="fixed-nav">
    <a href="<?=gen_uri('/home/index')?>"><i class="icon-nav icon-home"></i>首页</a>
    <a href="<?=gen_uri('/share/index')?>"><i class="icon-nav icon-camera"></i>晒单</a>
    <a href="<?=gen_uri('/activity/index')?>"><i class="icon-nav icon-acti"></i>活动</a>
    <a href="<?=gen_uri('/cart/index')?>"><i class="icon-nav icon-cart"></i>清单</a>
    <a href="<?=gen_uri('/my/index')?>"><i class="icon-nav icon-avatar"></i>个人中心</a>
</div>

<!-- pop window start -->

<!-- pop iframe -->
<div class="pop-mask-iframe"></div>
<form id="coupon_form" action="" name="coupon_form">
    <div class="pop-wrap pop-iframe">
        <div class="grid buy-stamps">
            <div class="buy-stamps-hd">
                <h3>充值</h3>
            </div>
            <div class="buy-stamps-con clearfix">
                <?php
                $i = 1;
                foreach($conf['sConf'] as $key=>$val)
                {
                    if($i < 7)
                    {
                        ?>
                        <p><input type="radio" name="stampsNumber" id="number<?=$val['c']?>" <?= ($i == 1 ? 'checked' : '') ?> value="<?=$key?>" value_key="<?=$val['c']?>"><label for="number<?=$val['c']?>"><?=$val['c']?>张</label><?=(empty($val['s'])?'':'<strong>赠'.$val['s'].'张</strong>')?></p>
                    <?php
                    }
                    $i++;
                }
                ?>
                <p class="num-other"><input type="number" id="numberOther" name="numberOther" placeholder="可输入其他数量，最低2张起充"></p>
                <input type="hidden" id="conf-id" name="is_activity" value="<?=(isset($conf['iActivityId']) ? $conf['iActivityId'] : 0)?>">
                <input type="hidden" name="pay_disabled" value="<?=(isset($pay_disabled) && $pay_disabled ? '1' : 0)?>">
                <div class="wave-bott"></div>
            </div>
        </div>
        <div class="stamps-cost">需支付：<strong id="now_pay">￥0</strong></div>
        <div class="grid column pay-type">
            <div class="column-hd pay-type-hd">
                <h3>支付方式</h3>
            </div>
            <div class="pay-type-con">
                <div class="pay-type-item">
                    <input type="radio" name="payType" id="payWenxin" checked="">
                    <label for="payWenxin">微信支付</label>
                </div>
            </div>
        </div>
        <a href="javascript:void(0)" class="btn btn-block btn-error btn-pay" id="order-submit"><span>确认支付</span></a>
        <a href="javascript:;" class="pop-close-1">关闭弹窗</a>
    </div>
</form>

<!-- pop recharge start -->
<div class="pop-recharge-mask"></div>
<div class="pop-recharge-wrap pop-recharge-succ">
    <div class="pop-recharge-con">
        <div class="recharge-status">
            <img src="<?=$resource_url?>images/popWin/recharge_succ.jpg" width="159" height="100" alt="">
            <div class="wave-bott"></div>
        </div>
        <p>本次支付￥<span id="pay_money">0</span>元</p>
        <p>获得<span id="pay_coupon">0</span>张券 赠<span id="pay_result_coupon">0</span>张券</p>
    </div>
</div>
<div class="pop-recharge-wrap pop-recharge-fail">
    <div class="pop-recharge-con">
        <div class="recharge-status">
            <img src="<?=$resource_url?>images/popWin/recharge_fail.jpg" width="159" height="100" alt="">
            <div class="wave-bott"></div>
        </div>
        <p id="error_msg">网络出问题啦~请刷新重试~</p>
        <p>注：如有任何问题，请联系客服0755-86721139</p>
    </div>
    <div class="pop-recharge-bott clearfix">
        <a href="javascript:;" class="btn btn-default"><span>取消</span></a>
        <a href="javascript:location.reload()" class="btn btn-error"><span>重新充值</span></a>
    </div>
</div>

<script>
    $(function(){
        DUOBAO.stampsRemain = <?=$user_ext['coupon']?>; // 我的券
        DUOBAO.stampsUse = <?=$total/Lib_Constants::COUPON_UNIT_PRICE?>; // 本次用券

        if (DUOBAO.stampsRemain < DUOBAO.stampsUse) {
            $('.settle-stamps-use').hide().next().show();
        } else {
            $('.settle-stamps-use').show().next().hide();
        }

        var itemSettle = $('.settle-item');
        if (itemSettle.length > 2) {
            $('.btn-settle-toggle').show();
        } else {
            $('.indiana-list').css('padding-bottom', '20px');
            $('.indiana-list > div').removeClass('settle-list-w');
            $('.btn-settle-toggle').hide();
        }

        $('.btn-settle-toggle').on('click', function(){
            if ($(this).hasClass('btn-settle-down')) {
                $(this).removeClass('btn-settle-down').addClass('btn-settle-up');
                $(this).siblings().removeClass('settle-list-w');
                $(this).html('折叠<i></i>');
            } else if ($(this).hasClass('btn-settle-up')) {
                $(this).removeClass('btn-settle-up').addClass('btn-settle-down');
                $(this).siblings().addClass('settle-list-w');
                $(this).html('展开<i></i>');
            }
        });
    })
</script>

<script>
    DUOBAO.payment = {
        partiTimes: 5, // 参与次数(默认5次)
        stampsRemain: <?=$user_ext['coupon']?>, // 剩余夺宝券
        singleCost: 0, // 单次价格
        total: 0, // 总计用券
        cost: 0, // 支付金额
        remainTimes: 0, // 剩余人次
        needTimes: 0 // 总需人次
    };
</script>
<script type="text/javascript" src="<?=$resource_url?>js/payment.js"></script>
<script>
    var buy = buy ? buy :{};
    buy.weixinGetBrandWCPayV3Request = function (data,callback) {
        data.jsapicall = "string" === typeof  data.jsapicall ? $.parseJSON(data.jsapicall) : data.jsapicall;
        function jsApiCall(callback) {
            WeixinJSBridge.invoke("getBrandWCPayRequest", data.jsapicall, function (res) {
                callback(res);
            })
        }

        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.addEventListener("WeixinJSBridgeReady", jsApiCall, false)
            } else {
                if (document.attachEvent) {
                    document.attachEvent("WeixinJSBridgeReady", jsApiCall);
                    document.attachEvent("onWeixinJSBridgeReady", jsApiCall)
                }
            }
        }
        jsApiCall(callback)
    };
</script>

<script>
    $(function(){
        DUOBAO.payment.init();
        DUOBAO.payment.changePartiTimes();
        DUOBAO.payment.probaImprove();
        DUOBAO.payment.buyOut();
        DUOBAO.payment.partiConfirm(parsePay);

        var checkedRadio = $('.buy-stamps input:radio:checked');
        $('#now_pay').html('￥'+checkedRadio.attr('value_key'));

        $('.pop-recharge-bott a:first-child').on('click', function(){
            $('.pop-recharge-mask, .pop-recharge-fail').hide();
        });

        $('#submit-btn').click(function(){
            var total = $('input[name="total"]').val();
            var my_coupon = $('input[name="my_coupon"]').val();
            if(my_coupon < total/<?=Lib_Constants::COUPON_UNIT_PRICE?>)
            {
                $('.pop-mask-iframe, .pop-iframe').show();
                return false;
            }

            var data = $('#myform').serialize();
            DUOBAO._post('/duogebao/active/ajax_multi_order',data,function(rs){
                if(rs.retCode != 0){
                    layer.msg(rs.retMsg || '订单生成失败，请稍候再试~')
                }else{
//                    location.href = '<?//=$pay_url?>//?callback_url=<?//=urlencode($order_callback)?>//&order_id='+rs.retData.order_id;
                    DUOBAO._post('/payment/pay/ajax_pay',{order_id:rs.retData.order_id,pay_disabled:rs.retData.pay_disabled,paycoupon:rs.retData.pay_coupon,payagent:rs.retData.payagent},function($res){
                        $res = typeof($res) === 'string' ? $.parseJSON($res) : $res;
                        if($res.retCode == 0){
                            if($res.retData.ispaid == '<?=Lib_Constants::PAY_STATUS_PAID?>'){
                                layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                    location.href = '<?=$result_url?>?order_id='+rs.retData.order_id;
                                });
                            }else{
                                buy.weixinGetBrandWCPayV3Request($res.retData,function($buy_res){
                                    $buy_res = typeof($buy_res) === 'string' ? $.parseJSON($buy_res) : $buy_res;
                                    if ($buy_res.err_msg == "get_brand_wcpay_request:ok" || $buy_res.err_msg == "get_brand_wcpay_request:finished") {
                                        DUOBAO._post('/payment/pay/ajax_get_order',{order_id:rs.retData.order_id},function($order_res){
                                            try{
                                                $order_res = typeof $order_res == 'string' ? $.parseJSON($order_res) : $order_res;
                                                if($order_res.retCode == 0 && $order_res.retData.is_paid == 'true'){
                                                    layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                                        location.href = '<?=$result_url?>?order_id='+rs.retData.order_id;
                                                    });
                                                }else{
                                                    layer.msg('支付失败，如果已支付，请稍侯通过个人中心查看~');
                                                    return false
                                                }
                                            } catch (e){
                                                layer.msg('订单支付失败，请重新支付1');
                                                return false
                                            }
                                        })
                                    } else {
                                        layer.msg('支付失败，请重新支付');
                                        //layer.msg(JSON.stringify(res));
                                        return false
                                    }
                                });
                            }
                        }else if($res.retCode == '<?=Lib_Errors::PAYED?>'){
                            layer.msg('亲，订单已支付了哦~~');
                        }else{
                            layer.msg($res.retMsg != '' ? $res.retMsg : '参数异常,请稍侯重试~~');
                        }
                    })
                }
            })
        })

        // @后台: 确认参与调用微信支付接口
        function parsePay() {
            var data = $('#myform').serialize();
            DUOBAO._post('/duogebao/active/ajax_order',data,function(rs){
                rs = 'string' === typeof rs ? $.parseJSON(rs) : rs;
                if(rs.retCode != 0){
                    layer.msg(rs.retMsg || '订单生成失败，请稍候再试~');
                }else{
                    if(rs.retData.ispaid == 1 && rs.retData.is_skip == 1)
                    {
                        layer.msg('帮您抢码中.....',{time: 1000, icon: 1},function(){
                            location.href = rs.retData.pay_redirect+'?order_id='+rs.retData.order_id;
                        });
                        return false;
                    }
                    else if(rs.retCode == '<?=Lib_Errors::PAYED?>'){
                        layer.msg('亲，订单已支付了哦~~');
                        location.href = rs.retData.pay_redirect+'?order_id='+rs.retData.order_id;
                        return false;
                    }
                    if(rs.retData.is_paid == 1){
                        layer.msg('参与成功~',{time:1000},function(){
//                            location.href = '<?//=$order_callback?>//';
                        });
                    }else{
                        layer.msg(rs.retMsg != '' ? rs.retMsg : '参数异常,请稍侯重试~~');
                    }
                }
            })
        }


        //选择单选框,改变需支付金额
        $('input[type="radio"]').on('change',function(){
            var _this = $(this);
            var val = _this.attr('value_key');
            $('#now_pay').html('￥'+val);
            $('#numberOther').val('');
        })

        $('#numberOther').blur(function(){
            var _this = $(this);
            var val = _this.val();
            if(val == '')
            {
                $('#now_pay').html('￥0');
            }
            else
            {
                $('#now_pay').html('￥'+val);
            }
        });

        $('#numberOther').on('focus', function(){
            $('.buy-stamps-con').find('input[type="radio"]').prop('checked', false);
        });

        var click = false;
        $('.btn-pay').on('click', function(){
            var checkedRadio = $('.buy-stamps input:radio:checked');
            if (checkedRadio.length <= 0) {
                var val = $('#numberOther').val();
                if(isNaN(val) || (val).indexOf('.') != -1 || val <= 0){
                    DUOBAO.popWinAlert.init('亲，请输入正整数哦~');
                    $('#numberOther').focus();
                    return false;
                } else if(val < 2) {
                    DUOBAO.popWinAlert.init('夺宝券购买数量最少2张');
                    $('#numberOther').focus();
                    return false;
                }
                $('#now_pay').html('￥'+$('#numberOther').val());
            }
            else
            {
                $('#now_pay').html('￥'+checkedRadio.attr('value_key'));
            }
            if(click) return;
            click = true;
            var data = $('#coupon_form').serialize();
            DUOBAO._post('/payment/pay/ajax_buy_now',data,function($res){
                $res = typeof($res) === 'string' ? $.parseJSON($res) : $res;
                var order_id = $res.retData.order_id;
                buy.weixinGetBrandWCPayV3Request($res.retData,function(res){
                    if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:finished") {
                        DUOBAO._post('/payment/pay/ajax_get_order',{order_id:order_id},function($res){
                            click = false;
                            try{
                                $res = typeof $res == 'string' ? $.parseJSON($res) : $res;
                                if($res.retCode == 0 && $res.retData.is_paid == 'true'){
                                    $('.pop-mask-iframe, .pop-iframe').hide();
                                    $("#pay_money").html($res.retData.total_money);
                                    $("#pay_coupon").html($res.retData.coupon_count);
                                    $("#pay_result_coupon").html($res.retData.present_count);
                                    $('.pop-recharge-mask, .pop-recharge-succ').show();
                                    setTimeout(function(){
                                        $('.pop-recharge-mask, .pop-recharge-succ').hide();
                                        location.reload();
                                    }, 3000);
//                                    layer.msg('支付成功~',{time: 1000, icon: 1},function(){
//                                        $('.mask, .pop-buy-stamps').hide();
//                                        location.reload();
//                                    });
                                }else{
                                    $('.pop-mask-iframe, .pop-iframe').hide();
                                    $('.pop-recharge-mask, .pop-recharge-fail').show();
                                    $('#error_msg').html('支付失败，如果已支付，请稍侯通过个人中心查看~');
                                    return false
                                }
                            } catch (e){
                                $('.pop-mask-iframe, .pop-iframe').hide();
                                $('.pop-recharge-mask, .pop-recharge-fail').show();
                                $('#error_msg').html('订单支付失败，请重新支付');
                                return false
                            }
                        })
                    } else {
                        click = false;
                        $('.pop-mask-iframe, .pop-iframe').hide();
                        $('.pop-recharge-mask, .pop-recharge-fail').show();
                        $('#error_msg').html('支付失败，请重新支付');
                        return false
                    }
                });
            });


        });
        $('.pop-close-1').on('click', function(){
            $('.pop-mask-iframe, .pop-iframe').hide();
        });
    });
</script>
<?php $this->widget('weixin_share', array()) ?>
</body>
</html>