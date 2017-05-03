<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>购买券——百分好礼</title>
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
<div class="viewport v-buy-stamps">
    <!-- buy stamps start -->
    <div class="grid buy-stamps">
        <div class="buy-stamps-hd">
            <h3><i class="icon-dollar"></i>充值</h3>
        </div>
        <form name="myform" action="" id="myform">
            <div class="buy-stamps-con clearfix">

                <?php
                $i = 1;
                foreach($conf['sConf'] as $key=>$val) {
                    if ($i < 7) {
                        ?>
                        <p>
                            <input type="radio" name="stampsNumber" <?= ($i == 1 ? 'checked' : '') ?> id="number<?= $val['c'] ?>" value="<?=$key?>" money="<?= $val['c'] ?>">
                            <label for="number<?= $val['c'] ?>">
                                <?= $val['c'] ?>张<?= (empty($val['s']) ? '' : '<strong id="result_coupon'.$val['c'].'">赠' . $val['s'] . '张</strong>') ?>
                            </label>
                        </p>
                    <?php
                    }
                    $i++;
                }
                ?>
                <p class="num-other"><input type="number" id="numberOther" name="numberOther" placeholder="可输入其他数量，最低2张起充"></p>
                <input type="hidden" id="conf-id" name="is_activity" value="<?=(isset($conf['iActivityId']) ? $conf['iActivityId'] : 0)?>">
                <div class="wave-bott"></div>
                <input type="hidden" name="order_id" value="">
                <input type="hidden" name="pay_disabled" value="<?=(isset($pay_disabled) && $pay_disabled ? '1' : 0)?>">
            </div>
        </form>
    </div>

    <div class="stamps-cost">需支付：<strong id="now_pay">￥0</strong></div>

    <!-- pay type -->
    <div class="grid column pay-type">
        <div class="column-hd pay-type-hd">
            <h3>支付方式</h3>
        </div>
        <div class="pay-type-con">
            <div class="pay-type-item">
                <input type="radio" name="payType" id="payWenxin" checked>
                <label for="payWenxin">微信支付</label>
            </div>
            <!-- <div class="pay-type-item">
                <input type="radio" name="payType" id="payQQ">
                <label for="payQQ">手Q支付</label>
            </div> -->
        </div>
    </div>

    <a href="javascript:void(0)" class="btn btn-block btn-error btn-pay" id="order-submit"><span>确认支付</span></a>
    <!-- buy stamps end -->
</div>
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
        <a href="<?=gen_uri('/pay/buy_coupon',array(),'payment')?>" class="btn btn-error"><span>重新充值</span></a>
    </div>
</div>
<!-- pop recharge end -->
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
        var checkedRadio = $('.buy-stamps input:radio:checked');
        $('#now_pay').html('￥'+checkedRadio.attr('money'));

        //选择单选框,改变需支付金额
        $('input[type="radio"]').on('change',function(){
            var _this = $(this);
            var val = _this.attr('money');
            $('#now_pay').html('￥'+val);
            $('#numberOther').val('');
        })

        // 文本框输入值,改变需支付金额
        $('#numberOther').on('blur', function(){
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

        $('.pop-recharge-bott a:first-child').on('click', function(){
            $('.pop-recharge-mask, .pop-recharge-fail').hide();
        });


        var click = false;
        $('#order-submit').on('click', function(){
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
                $('#now_pay').html('￥'+checkedRadio.attr('money'));
            }

            if(click) return;
            click = true;
            var data = $('#myform').serialize();
            DUOBAO._post('/duogebao/coupon/ajax_coupon_order',data,function(rs){
                rs = typeof(rs) === 'string' ? $.parseJSON(rs) : rs;
                if(rs.retCode != 0){
                    layer.msg('订单生成失败，请稍侯再试~~');
                    return false;
                }else{
                    $("#order_id").val(rs.retData);
//                    location.href = '<?//=$pay_url?>//?callback_url=<?//=urlencode($callback_url)?>//&order_id='+rs.retData;
                    DUOBAO._post('/payment/pay/ajax_buy_now',$('#myform').serialize(),function($res){
                        $res = typeof($res) === 'string' ? $.parseJSON($res) : $res;
                        var order_id = $res.retData.order_id;
                        buy.weixinGetBrandWCPayV3Request($res.retData,function(res){
                            if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:finished") {
                                DUOBAO._post('/payment/pay/ajax_get_order',{order_id:order_id},function($res){
                                    click = false;
                                    try{
                                        $res = typeof $res == 'string' ? $.parseJSON($res) : $res;
                                        if($res.retCode == 0 && $res.retData.is_paid == 'true'){
//                                            layer.msg('支付成功~',{time: 1000, icon: 1},function(){
//                                                location.reload();
//                                                location.href = '<?//=$callback_url?>//?order_id='+order_id;
                                                $("#pay_money").html($res.retData.total_money);
                                                $("#pay_coupon").html($res.retData.coupon_count);
                                                $("#pay_result_coupon").html($res.retData.present_count);
                                                $('.pop-recharge-mask, .pop-recharge-succ').show();
                                                setTimeout(function(){
                                                    $('.pop-recharge-mask, .pop-recharge-succ').hide();
                                                    location.href = '<?=gen_uri('/home/index')?>';
                                                }, 3000);
//                                            });
                                        }else{
                                            $('.pop-recharge-mask, .pop-recharge-fail').show();
                                            $('#error_msg').html('支付失败，如果已支付，请稍侯通过个人中心查看~');
//                                            layer.msg('支付失败，如果已支付，请稍侯通过个人中心查看~');
                                            return false
                                        }
                                    } catch (e){
                                        $('.pop-recharge-mask, .pop-recharge-fail').show();
                                        $('#error_msg').html('订单支付失败，请重新支付');
//                                        layer.msg('订单支付失败，请重新支付');
                                        return false
                                    }
                                })
                            } else {
                                click = false;
                                $('.pop-recharge-mask, .pop-recharge-fail').show();
                                $('#error_msg').html('支付失败，请重新支付');
//                                layer.msg('支付失败，请重新支付');
                                //layer.msg(JSON.stringify(res));
                                return false
                            }
                        });
                    });
                }
            });
        })
    })
</script>
<?php $this->widget('weixin_share', array()) ?>
</body>
</html>