<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>收银台——百分好礼</title>
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
    <link rel="stylesheet" href="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/css/base.css">
    <!-- style common -->
    <link rel="stylesheet" href="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/css/common.css">
    <!-- style swiper -->
    <link rel="stylesheet" href="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/js/swiper/swiper.min.css">
    <!-- style layout -->
    <link rel="stylesheet" href="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/css/layout.css">
    <link rel="stylesheet" href="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/css/layer_skin_extend.css">
</head>
<body>
<div class="viewport v-cashier">
    <div class="grid cashier-wrap">
        <div class="cashier-hd">
            <h3>收银台</h3>
        </div>
        <div class="cashier-con">
            <div class="cash-order-id">订单号：<?=$order['sOrderId']?></div>
            <div class="cash-total-cost">总计：<strong>￥<?=price_format($order['iTotalPrice'])?></strong></div>
            <div class="cash-list clearfix">
                <div class="cash-list-hd">
                    <span>订单列表</span>
                    <b>数量</b>
                    <em>价格</em>
                </div>
                <ul>
                    <?php foreach($goods as $val){ ?>
                    <li><span><?=cn_substr($val['goods_name'],13)?></span><b>x<?=$val['goods_count']?></b><em>￥<?=price_format($val['goods_price'])?></em></li>
                    <?php } ?>
                    <?php if($order_type == Lib_Constants::ORDER_TYPE_COUPON && !empty($order['iPresentCount'])){  ?>
                    <li class="item-coupon-giving"><span>[赠 券]<?=$order['iPresentCount']?>张</span><b>x<?=$order['iPresentCount']?></b><em>￥0</em></li>
                    <?php } ?>
                </ul>
                <?php if(count($goods)>3){  ?>
                <a href="javascript:;" class="cash-view-all" id="cashViewAll">查看全部<i class="icon-arrow-dw"></i></a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="grid cashier-pay mt-10">
        <form id="myform" method="post" action="" name="myform">
            <input type="hidden" name="order_id" value="<?=$order['sOrderId']?>">
            <input type="hidden" name="pay_disabled" value="<?=(isset($pay_disabled) && $pay_disabled ? '1' : 0)?>">
            <?php if($order_type != Lib_Constants::ORDER_TYPE_COUPON){  ?>
            <div class="cash-pay-type cash-pay-type-1 clearfix">
                <h3 class="fl">夺宝券支付（余<strong class="stamps-remain"><?=$user_ext['coupon']?></strong>张）</h3>
                    <span class="fr">
                        <?php if(!$disabled && !empty($pay_coupon) && !empty($user_ext['coupon']) ){ ?>
                        <label for="stampsNum" id="stampsNumAvai">￥<?=($user_ext['coupon']< $pay_coupon ? $user_ext['coupon'] : $pay_coupon)?></label>
                        <label class="recommend-selected">推荐勾选</label>
                        <input type="checkbox" id="stampsNum" checked="checked" name="paycoupon" value="<?=$pay_coupon?>"/>
                        <?php }else{ ?>
                        <a href="<?=gen_uri('/coupon/buy_coupon',array('redirect'=>$pay_url.'?order_id='.$order['sOrderId']))?>" class="btn-buy-stamps-entry">夺宝券有买有赠，夺宝更优惠哦</a>
                        <?php } ?>
                    </span>
            </div>
            <?php } ?>

            <?php if(isset($pay_disabled) && !$pay_disabled){?>
            <div class="cash-pay-type cash-pay-type-other">
                <div class="pay-other-hd clearfix">
                    <h3 class="fl">支付方式</h3>
                    <span class="cash-need-pay fr" id="cashNeedPay">￥<?=price_format($order['iTotalPrice'])?></span>
                </div>
                <div class="pay-other-con">
                        <ul>
                            <li><input type="radio" checked="checked" name="payagent" id="payWeixin" value="<?=Lib_Constants::ORDER_PAY_TYPE_WX?>"><label for="payWeixin"><i class="icon-weixin"></i>微信支付</label></li>
                        </ul>
                </div>
            </div>
            <?php  } ?>
        </form>
    </div>

    <?php if($order_type != Lib_Constants::ORDER_TYPE_COUPON && ($disabled || empty($user_ext['coupon']) || $user_ext['coupon'] < $pay_coupon)){ ?>
        <!--<a href="<?/*=gen_uri('/coupon/buy_coupon',array('redirect'=>$pay_url.'?order_id='.$order['sOrderId']))*/?>" class="btn btn-block btn-red">立即充值</a>-->
        <a href="javascript:void(0)" class="btn btn-block btn-red" id="payNow">立即充值</a>
        <p style="color: red;margin:0 auto;text-align:center;">您的夺宝券余额不足，请先购买夺宝券再参与夺宝哦！</p>
    <?php }else{ ?>
        <a href="javascript:void(0);" class="btn btn-block btn-red" id="pay-btn" <?=($order_type != Lib_Constants::ORDER_TYPE_COUPON && ($disabled || empty($user_ext['coupon'])) ? 'disabled="disabled"':'')?>>确认支付</a>
    <?php } ?>
</div>

<!-- buy stamps start -->
<div class="mask mask-buy-stamps"></div>
<div class="pop-buy-stamps grid column buy-stamps">
    <div class="column-hd">
        <h3>购买夺宝券</h3>
        <a href="javascript:;" id="btnClose">关闭</a>
    </div>
    <div class="pop-content buy-stamps-con clearfix">
        <form id="buy-form" name="buy-form" action="" method="post">
            <?php foreach($conf['sConf'] as $key=>$val){ ?>
                <p><input type="radio" name="stampsNumber" checked id="number20" value="<?=$key?>"><label for="number20"><?=$val['c']?>张<?=(empty($val['s'])?'':'（赠'.$val['s'].'张）')?></label></p>
            <?php } ?>
            <p><input type="text" id="numberOther" name="numberOther" placeholder="其他数量"></p>
            <input type="hidden" name="is_activity" value="<?=$is_activity?>">
        </form>
    </div>
	<p class="pay-tips">*温馨提示：单笔最低充值2元 </p>
    <a href="javascript:void(0)" class="btn btn-block btn-red" id="paynow-submit">确认支付</a>
</div>
<!-- buy stamps end -->

<div class="fixed-nav">
    <a href="<?=gen_uri('/home/index')?>" <?=(!empty($menus_active_index) && $menus_active_index == 1  ? 'class="active"' : '')?>><i class="icon-nav icon-home"></i>首页</a>
    <a href="<?=gen_uri('/share/index')?>" <?=(!empty($menus_active_index) && $menus_active_index == 2 ? 'class="active"' : '')?>><i class="icon-nav icon-camera"></i>晒单</a>
    <a href="<?=gen_uri('/activity/index')?>" <?=(!empty($menus_active_index) && $menus_active_index == 3 ? 'class="active"' : '')?>><i class="icon-nav icon-gift"></i>活动</a>
    <a href="<?=gen_uri('/cart/index')?>" <?=(!empty($menus_active_index) && $menus_active_index == 4 ? 'class="active"' : '')?>><i class="icon-nav icon-cart"></i>夺宝车</a>
    <a href="<?=gen_uri('/my/index')?>" <?=(!empty($menus_active_index) && $menus_active_index == 5 ? 'class="active"' : '')?>><i class="icon-nav icon-avatar"></i>个人中心</a>
</div>

<script type="text/javascript" src="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/js/layer/layer.js"></script>
<script type="text/javascript" src="http://imgcache.qq.com/vipstyle/tuan/duobao/indiana/js/lib.js"></script>
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
        // 查看全部
        $('#cashViewAll').on('click', function(){
            if ($(this).find('i').hasClass('icon-arrow-dw')) {
                $(this).prev('ul').addClass('cash-list-toggle');
                $(this).find('i').removeClass('icon-arrow-dw').addClass('icon-arrow-up');
            } else {
                $(this).prev('ul').removeClass('cash-list-toggle');
                $(this).find('i').removeClass('icon-arrow-up').addClass('icon-arrow-dw');
            }
        });

        //立即充值
        $('#payNow').on('click', function(){
            $('.mask, .pop-buy-stamps').show();
        });
        $('#btnClose').on('click', function(){
            $('.mask, .pop-buy-stamps').hide();
        });
        $('#numberOther').on('focus', function(){
            $('.buy-stamps-con').find('input[type="radio"]').prop('checked', false);
        });
		
        $('#numberOther').change(function(){
            var _this = $(this);
            var val = _this.val();
            if(isNaN(val) || (val).indexOf('.') != -1 || val <= 0){
                _this.val(0);
                layer.msg('亲，请输入正整数哦~');
            } else if(val == 1) {
				layer.alert('夺宝券购买数量最少2张,请重新输入', {title: false, closeBtn: 0}, function(index){
					_this.focus();
					_this.val(2);
					layer.close(index);
				});
			}
        });
        var click = false;
        $('#paynow-submit').on('click',function(){
            if(click) return;
            click = true;
            var data = $('#buy-form').serialize();
            DUOBAO._post('/payment/pay/ajax_buy_now',data,function($res){
                var order_id = $res.retData.order_id;
                buy.weixinGetBrandWCPayV3Request($res.retData,function(res){
                    if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:finished") {
                        DUOBAO._post('/payment/pay/ajax_get_order',{order_id:order_id},function($res){
                            click = false;
                            try{
                                $res = typeof $res == 'string' ? $.parseJSON($res) : $res;
                                if($res.retCode == 0 && $res.retData.is_paid == 'true'){
                                    layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                        $('.mask, .pop-buy-stamps').hide();
                                        location.reload();
                                    });
                                }else{
                                    layer.msg('支付失败，如果已支付，请稍侯通过个人中心查看~');
                                    return false
                                }
                            } catch (e){
                                layer.msg('订单支付失败，请重新支付');
                                return false
                            }
                        })
                    } else {
                        click = false;
                        layer.msg('支付失败，请重新支付');
                        //layer.msg(JSON.stringify(res));
                        return false
                    }
                });
            });
        })

        $('#pay-btn').click(function(){
            var is_pay =  $('input[name="pay_disabled"]').val(),
                paycoupon = $('input[name="paycoupon"]:checked').val();
            if(is_pay == 1  && !paycoupon){
                layer.msg('请选择支付方式!');
                return false;
            }

            DUOBAO._post('/payment/pay/ajax_pay',$('#myform').serialize(),function($res){
                if($res.retCode == 0){
                    if($res.retData.ispaid == '<?=Lib_Constants::PAY_STATUS_PAID?>'){
                        layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                            location.href = '<?=$pay_redirect?>?order_id=<?=$order['sOrderId']?>';
                        });
                    }else{
                        buy.weixinGetBrandWCPayV3Request($res.retData,function(res){
                            if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:finished") {
                                DUOBAO._post('/payment/pay/ajax_get_order',{order_id:'<?=$order['sOrderId']?>'},function($res){
                                    try{
                                        $res = typeof $res == 'string' ? $.parseJSON($res) : $res;
                                        if($res.retCode == 0 && $res.retData.is_paid == 'true'){
                                            layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                                location.href = '<?=$pay_redirect?>?order_id=<?=$order['sOrderId']?>';
                                            });
                                        }else{
                                            layer.msg('支付失败，如果已支付，请稍侯通过个人中心查看~');
                                            return false
                                        }
                                    } catch (e){
                                        layer.msg('订单支付失败，请重新支付');
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
        })



        // 夺宝券支付状态
        DUOBAO.useStamps = true;
        DUOBAO.cashier = {
            stampsRemain: '<?=(isset($user_ext['coupon']) ? $user_ext['coupon'] : 0)?>' // 剩余夺宝券
        };
        DUOBAO.cashier.totalCost = '<?=($order['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE)?>';
        DUOBAO.cashier.needPay = null;

        cashierInit();
        function cashierInit() {
            if (DUOBAO.cashier.stampsRemain == 0) {
                $('#stampsNumAvai, .recommend-selected, #stampsNum').hide();
                $('.stamps-remain').text(0)
            } else {
                $('.recommend-selected, .btn-buy-stamps-entry').hide();
                $('.stamps-remain').text(DUOBAO.cashier.stampsRemain);
                $('#stampsNumAvai').text('￥'+ (DUOBAO.cashier.stampsRemain > DUOBAO.cashier.totalCost ? DUOBAO.cashier.totalCost : DUOBAO.cashier.stampsRemain));
            }
            DUOBAO.cashier.needPay = parseInt(DUOBAO.cashier.totalCost) - parseInt(DUOBAO.cashier.stampsRemain);
            $('#cashNeedPay').html('￥'+ DUOBAO.cashier.needPay);
        }

        /*$('#stampsNum').on('change', function(){
            if ($(this).is(':checked')) {
                DUOBAO.useStamps = true;
                DUOBAO.cashier.needPay = parseInt(DUOBAO.cashier.totalCost) - parseInt(DUOBAO.cashier.stampsRemain);
                $('#cashNeedPay').html('￥'+ DUOBAO.cashier.needPay);
                $('.recommend-selected, .btn-buy-stamps-entry').hide();
                $('#stampsNumAvai').show();
            } else {
                DUOBAO.useStamps = false;
                DUOBAO.cashier.needPay = DUOBAO.cashier.totalCost;
                $('#cashNeedPay').html('￥'+ DUOBAO.cashier.needPay);
                $('#stampsNumAvai, .btn-buy-stamps-entry').hide();
                $('.recommend-selected').show();
            }
        });*/

    })
</script>
</body>
</html>