<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>立即参与快捷支付——百分好礼</title>
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
<div class="viewport v-quick-pay">
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

    <!-- parti detail -->
    <form id="myform" action="" name="myform">
        <div class="parti-detail">
            <div class="parti-detail-column-1">
                <div class="parti-detail-cell-1">
                    <div class="pay-product-pic">
                        <img src="<?=$detail['sImg']?>" width="60" height="60" alt="">
                    </div>
                    <h3 class="pay-product-name"><?=$detail['sGoodsName']?></h3>
                    <p class="pay-product-price">总需<b><?=$detail['iLotCount']?></b>人次，剩余<strong><?=($detail['iLotCount']-$detail['iSoldCount'])?></strong>人次</p>
                    <p class="single-cost">单次用券<strong><?=price_format($detail['iCodePrice'])?></strong></p>
                </div>
                <div class="parti-detail-cell-2 mt-10 clearfix">
                    <div class="parti-number">
                        <em>参与次数</em>
                        <div class="quantity-wrap">
                            <a href="javascript:;" class="quantity-decrease">-</a>
                            <input type="text" class="quantity" value="1" name="qty">
                            <a href="javascript:;" class="quantity-increase">+</a>
                        </div>
                    </div>
                    <div class="probability">获奖概率<b>0.01</b>%</div>
                </div>
                <div class="parti-detail-cell-3 mt-10 clearfix">
                    <label>提升概率</label>
                    <div class="choose-times">
                        <ul>
                            <li data-times="5">5</li>
                            <li data-times="10">10</li>
                            <li data-times="20">20</li>
                            <li data-times="50">50</li>
                        </ul>
                        <a href="javascript:;" class="btn-pay-buyout">包尾</a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="peroid_str" value="<?=$peroid_str?>">
            <input type="hidden" name="price" id="price" value="<?=$detail['iCodePrice']?>">
            <input type="hidden" name="total" id="total" value="<?=$detail['iCodePrice']?>">
            <div class="parti-detail-column-2">
                <p class="parti-times">共参与<strong>5</strong>次</p>
                <p class="stamps-use">总计用券<strong>50</strong>张</p>
            </div>
            <div class="parti-detail-column-3 clearfix">
                <span class="stamps-my fl">我的券：<b><?=$coupon?></b>张</span>
                <span class="stamps-use-1 fr">本次使用<strong>50</strong>张</span>
                <span class="stamps-insuff fr">券不足,请先充值</span>
            </div>
            <a href="javascript:;" id="submit-btn" class="btn btn-error btn-block btn-parti-confirm"><span>确认参与</span></a>
        </div>
    </form>
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
        <a href="#" class="btn btn-block btn-error btn-pay" id="order-submit"><span>确认支付</span></a>
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
    DUOBAO.payment = {
        partiTimes: <?=$detail['iCodePrice']==0?1:5?>, // 参与次数(默认5次)
        stampsRemain: <?=$coupon?>, // 剩余夺宝券
        singleCost: <?=price_format($detail['iCodePrice'])?>, // 单次价格
        total: 0, // 总计用券
        cost: 0, // 支付金额
        remainTimes: <?=($detail['iLotCount']-$detail['iSoldCount'])?>, // 剩余人次
        needTimes: <?=$detail['iLotCount']?> // 总需人次
    };
</script>
<!-- payment script -->
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
//        DUOBAO.payment.buyOut();
        $('.btn-pay-buyout').on('click', function(){
            DUOBAO.payment.buyOut();
        })
        DUOBAO.payment.partiConfirm(parsePay);

        var checkedRadio = $('.buy-stamps input:radio:checked');
        $('#now_pay').html('￥'+checkedRadio.attr('value_key'));

        $('.pop-recharge-bott a:first-child').on('click', function(){
            $('.pop-recharge-mask, .pop-recharge-fail').hide();
        });

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
                        layer.msg('帮您抢码中.....',{time:1000},function(){
                            location.href = rs.retData.pay_redirect+'?order_id='+rs.retData.order_id;
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
            DUOBAO._post('/payment/pay/ajax_buy_now',$('#coupon_form').serialize(),function($res){
                $res = typeof($res) === 'string' ? $.parseJSON($res) : $res;
                var order_id = $res.retData.order_id;
                buy.weixinGetBrandWCPayV3Request($res.retData,function(res){
                    if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:finished") {
                        DUOBAO._post('/payment/pay/ajax_get_order',{order_id:order_id},function($res){
                            click = false;
                            try{
                                $res = typeof $res == 'string' ? $.parseJSON($res) : $res;
                                if($res.retCode == 0 && $res.retData.is_paid == 'true'){
                                    layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                        $('.pop-mask-iframe, .pop-iframe').hide();
                                        $("#pay_money").html($res.retData.total_money);
                                        $("#pay_coupon").html($res.retData.coupon_count);
                                        $("#pay_result_coupon").html($res.retData.present_count);
                                        $('.pop-recharge-mask, .pop-recharge-succ').show();
                                        setTimeout(function(){
                                            $('.pop-recharge-mask, .pop-recharge-succ').hide();
                                            location.reload();
                                        }, 3000);

                                    });
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
                                return false;
                            }
                        })
                    } else {
                        click = false;
                        $('.pop-mask-iframe, .pop-iframe').hide();
                        $('.pop-recharge-mask, .pop-recharge-fail').show();
                        $('#error_msg').html('支付失败，请重新支付');
                        return false;
                    }
                });
            });


        });
        $('.pop-close-1').on('click', function(){
            $('.pop-mask-iframe, .pop-iframe').hide();
        });
        // 详情页跳转包尾
        var url = window.location.href;
        if (/buyOut$/.test(url)) {
            var urlParam = url.split('&')[1];
            var urlParamId = urlParam.split('=')[1];
            if (urlParamId == 'buyOut') {
                DUOBAO.payment.buyOut();
            }
        }
    });
</script>
<?php $this->widget('weixin_share', array()) ?>
</body>
</html>