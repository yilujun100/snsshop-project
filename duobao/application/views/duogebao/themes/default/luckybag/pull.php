<div class="viewport v-send-bag">
    <!-- send lucky bag start -->
    <div class="send-bag">
        <div class="send-bag-hd">
            <h3 class="tit-bag">送夺宝福袋</h3>
        </div>
        <div class="send-bag-con">
            <form action="" method="" class="form f-send-bag" id="f-send-bag">
                <div class="f-item">
                    <label for="number">发放人数</label>
                    <input type="text" name="number" id="number" placeholder="点击输入个数" />
                    <s class="f-unit">人</s>
                </div>
                <div class="f-item f-item-ordinary-number" style="display: none;">
                    <label for="numberOrdinary">发放人数</label>
                    <input type="text" name="numberOrdinary" id="numberOrdinary" placeholder="点击输入个数" />
                    <s class="f-unit">人</s>
                </div>
                <div class="f-item f-item-fight-luck mt-15">
                    <label for="vouchersSum">夺宝券总数</label>
                    <input type="text" name="vouchersSum" id="vouchersSum" placeholder="点击输入张数" />
                    <s class="f-unit">张</s>
                </div>
                <div class="f-item f-item-ordinary mt-15" style="display: none;">
                    <label for="vouchersEach">每人夺宝券张数</label>
                    <input type="text" name="vouchersEach" id="vouchersEach" placeholder="点击输入张数" />
                    <s class="f-unit">张</s>
                </div>
                <div class="tab tab-send-bag mt-15">
                    <div class="tab-tit">
                        <a href="javascript:;" class="tit-fight-luck tab-active">拼手气</a>
                        <a href="javascript:;" class="tit-ordinary">普通</a>
                    </div>
                    <div class="tab-con mt-15">
                        <!-- 拼手气 -->
                        <div class="wishes wishes-fight-luck" style="display: block;">
                            <textarea name="wishesFightLuck" placeholder="送你一份千元豪礼！"></textarea>
                            <!-- <p class="pay-tips" id="payTips">使用夺宝券<em id="voucherNum">8</em>张，还需支付￥<span id="payAmount">20</span></p> -->
                        </div>
                        <!-- 普通 -->
                        <div class="wishes wishes-ordinary">
                            <textarea name="wishesFightLuck" placeholder="送你一份千元豪礼！"></textarea>
                        </div>
                    </div>
                </div>
                <div class="need-pay" id="needPay">￥0</div>
                <input type="submit" class="btn btn-block btn-red" value="朕确认了" id="btnConfirm" />
            </form>
        </div>
    </div>
    <!-- instructions -->
    <div class="instructions">
        <p><strong>*</strong>1张夺宝券可以兑换1个1元夺宝码<a href="<?=gen_uri('/help/index', array('item'=>'rules_lucky_bag'))?>" class="learn-more">了解更多&gt;</a></p>
        <p><strong>*</strong>福袋有效期48小时，夺宝券如未被领取将退还至您的账户，您可以继续用其参与夺宝或发福袋</p>
    </div>
    <!-- send lucky bag end -->
    <!-- <div class="fixed-nav">
        <a href="home.html"><i class="icon-nav icon-home"></i>首页</a>
        <a href="share.html" class="active"><i class="icon-nav icon-camera"></i>晒单</a>
        <a href="#"><i class="icon-nav icon-gift"></i>活动</a>
        <a href="cart.html"><i class="icon-nav icon-cart"></i>夺宝车</a>
        <a href="personal_center.html"><i class="icon-nav icon-avatar"></i>个人中心</a>
    </div> -->
</div>
<!-- 发福袋提示层 start -->
<div class="mask"></div>
<div class="popup popup-send-bag popup-to-center" id="popupToCenter">
    <div class="popup-tit">禀大王，可前往夺宝
        <br>个人中心找到福袋</div>
    <div class="popup-con">
        <div class="popup-btns">
            <a href="<?=gen_uri('/luckybag/records')?>" class="btn-popup btn-popup-1">朕知道了</a>
            <a href="javascript:;" class="btn-popup btn-popup-2 mt-10" id="sendContinue">继续发此福袋</a>
        </div>
        <p class="popup-tips">*福袋中的夺宝券如未被领取, 将于48小时内退还至您的账户</p>
    </div>
    <a href="javascript:;" class="btn-popup-close" id="btnPopupClose">关闭</a>
</div>
<div class="popup popup-send-bag" id="popupInstalled">
    <div class="popup-tit">回大王
        <br>福袋已装好</div>
    <div class="popup-con">
        <div class="popup-btns">
            <a href="javascript:;" class="btn-popup btn-popup-1" id="btnPopSend">朕要去发福袋</a>
        </div>
        <p class="popup-tips">*福袋中的夺宝券如未被领取, 将于48小时内退还至您的账户</p>
    </div>
    <a href="javascript:;" class="btn-popup-close" id="btnPopupClose1">关闭</a>
</div>
<!-- 发福袋提示层 end -->
<!-- 活动规则弹出层 start -->
<div class="pop-rules" id="popRules">
    <div class="pop-rules-hd">
        <h3>活动规则</h3>
    </div>
    <div class="pop-rules-con">
        <p><i>1.</i>活动时间：2016年2月7日~2月29日；</p>
        <p><i>2.</i>每个用户可购买1元夺宝券作为新年礼物赠予好友，用户在赠送好友夺宝券时可个性化选择普通福袋和拼手气福袋（操作类似于微信红包），自主编辑赠予的人数、夺宝券张数，并填写祝福语；</p>
        <p><i>3.</i>获得夺宝券的用户可在夺宝奇兵活动中购买时抵扣现金，例如： 1元夺宝券可抵扣1元。也可用现有的夺宝券直接兑换奖品；</p>
        <p><i>4.</i>48小时之内，已发送出去的福袋中如有夺宝券未被及时领取，可在【个人中心】中找到【可发放福袋】入口，选择继续发放；48小时之后，已发送出去的福袋中如有夺宝券未被及时领取，则系统将会把未被领取的夺宝券退回至您的账户中 ；</p>
        <p><i>5.</i>夺宝券从购买到账之日起永久有效；</p>
        <p><i>6.</i>1张夺宝券可兑换一个夺宝码， 更多夺宝及相关计算规则请详见夺宝奇兵活动页面；</p>
        <p><i>7.</i>本活动最终解释权归微团购所有。</p>
    </div>
    <a href="javascript:;" class="btn-popup-close" id="btnPopupCloseRules">关闭</a>
</div>
<!-- 活动规则弹出层 end -->
<!-- 分享 start -->
<div class="popup popup-share"></div>
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
    var luckyBag = luckyBag || {};
    luckyBag.remainVoucherNum = <?=isset($user_ext['coupon']) ? intval($user_ext['coupon']) : 0?>; // 剩余券张数
    var isClick = false;
    var order = {};
</script>

<script>
$(function(){
    // tab
    fnTab('.tab-tit a', '.tab-con > div');

    $('.tab-tit a').on('click', function(){
        sendBagInit();
        if ($(this).hasClass('tit-fight-luck')) { // 拼手气
            $('.f-item-ordinary-number').hide().prev().show();
            $('.f-item-fight-luck').show().next().hide();
        }
        if ($(this).hasClass('tit-ordinary')) { // 普通
            $('.f-item-ordinary-number').show().prev().hide();
            $('.f-item-ordinary').show().prev().hide();
        }
    });

    var itemInput = $('.f-item input[type="text"]');
    itemInput.each(function(){
        var _this = $(this);
        _this.on('blur', function(){
            var number = $('#number'),
                number1 = $('#numberOrdinary'),
                vouchersSum = $('#vouchersSum'),
                vouchersEach = $('#vouchersEach'),
                numVal = number.val(),
                num1Val = number1.val(),
                vouchersSumVal = vouchersSum.val(),
                vouchersEachVal = vouchersEach.val();

            if ($('.tit-fight-luck').hasClass('tab-active')) { // 拼手气福袋
                if (numVal != '' && vouchersSumVal != '') {
                    if (parseInt(vouchersSumVal) < parseInt(numVal)) {
                        layer.msg('夺宝券张数必须大于发放人数');
                    } else {
                        var stampsUse = parseInt(vouchersSumVal) >= parseInt(luckyBag.remainVoucherNum) ? luckyBag.remainVoucherNum : vouchersSumVal;
                        fnNeedPay(vouchersSumVal, stampsUse);
                    }
                }
            }

            if ($('.tit-ordinary').hasClass('tab-active')) { // 普通福袋
                if (num1Val != '' && vouchersEachVal != '') {
                    var stampsUse = parseInt(luckyBag.remainVoucherNum);
                    var needStamps = parseInt(vouchersEachVal*num1Val);
                    fnNeedPay(needStamps, stampsUse);
                }
            }
        })
    })

    // 确认提交
    $('#btnConfirm').on('click', function(){
        if(isClick == true) return false;
        var numVal = parseInt($('#number').val()),
            num1Val = parseInt($('#numberOrdinary').val()),
            vouchersSumVal = parseInt($('#vouchersSum').val()),
            vouchersEachVal = parseInt($('#vouchersEach').val()),
            cost = $('#needPay').text();
        var flag = validate();
        if (flag) {
            if ($('.tit-fight-luck').hasClass('tab-active')) { // 拼手气
                //参数验证
                if(!(/^\+?[1-9][0-9]*$/).test(numVal) || !(/^\+?[1-9][0-9]*$/).test(vouchersSumVal)){
                    layer.msg('请输入正整数~');
                    return false;
                }else if(numVal > vouchersSumVal){
                    layer.msg('夺宝总数必须大于等于发放人数');
                    return false;
                }
                DUOBAO._post('<?=gen_uri('/luckybag/pull_bag')?>',{people:numVal,coupon:vouchersSumVal,peopleNum:num1Val,perPeople:vouchersEachVal,wish:$('textarea[name="wishesFightLuck"]').eq(0).val(),type:2},function(data){
                    try{
                        if(data.retCode != 0){
                            layer.msg(data.retMsg);
                            isClick = false;
                        }else{
                            data = data.retData;
                            window.order = data;
                            if(data.is_paid){
                                $('.mask, #popupInstalled').show();
                                $('#popupInstalled #btnPopupClose1').on('click', function(){
                                    $('#popupInstalled').hide();
                                    $('#popupToCenter').show();
                                });
                            }else if (data.url) {
                                DUOBAO._post('/payment/pay/ajax_pay',{order_id:data.order_id,pay_disabled:data.disabled,paycoupon:data.pay_coupon,payagent:data.payagent},function($res){
                                    $res = typeof($res) === 'string' ? $.parseJSON($res) : $res;
                                    if($res.retCode == 0){
                                        if($res.retData.ispaid == '<?=Lib_Constants::PAY_STATUS_PAID?>'){
                                            layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                                location.href = data.pay_redirect+'?order_id='+data.order_id;
                                            });
                                        }else{
                                            buy.weixinGetBrandWCPayV3Request($res.retData,function($buy_res){
                                                $buy_res = typeof($buy_res) === 'string' ? $.parseJSON($buy_res) : $buy_res;
                                                alert($buy_res);
                                                if ($buy_res.err_msg == "get_brand_wcpay_request:ok" || $buy_res.err_msg == "get_brand_wcpay_request:finished") {
                                                    DUOBAO._post('/payment/pay/ajax_get_order',{order_id:data.order_id},function($order_res){
                                                        try{
                                                            $order_res = typeof $order_res == 'string' ? $.parseJSON($order_res) : $order_res;
                                                            if($order_res.retCode == 0 && $order_res.retData.is_paid == 'true'){
                                                                layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                                                    location.href = data.pay_redirect+'?order_id='+data.order_id;
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
                                                    layer.msg('支付失败，请重新支付2');
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
//                                location.href = data.url;
                            }
                            isClick = false;
                        }
                    } catch(e){
                        layer.msg('服务异常！');
                        isClick = false;
                    }
                });
            }

            if ($('.tit-ordinary').hasClass('tab-active')) { // 普通
                if(!(/^\+?[1-9][0-9]*$/).test(num1Val) || !(/^\+?[1-9][0-9]*$/).test(vouchersEachVal)){
                    layer.msg('请输入正整数~');
                    return false;
                }else if(num1Val < 1 || vouchersEachVal < 1){
                    layer.msg('发放人数和夺宝券张数需要大于0~');
                    return false;
                }
                DUOBAO._post('<?=gen_uri('/luckybag/pull_bag')?>',{people:numVal,coupon:vouchersSumVal,peopleNum:num1Val,perPeople:vouchersEachVal,wish:$('textarea[name="wishesFightLuck"]').eq(1).val(),type:1},function(data){
                    try{
                        if(data.retCode != 0){
                            layer.msg(data.retMsg);
                            isClick = false;
                        }else{
                            data = data.retData;
                            window.order = data;
                            if(data.is_paid){
                                $('.mask, #popupInstalled').show();
                                $('#btnPopupClose1').on('click', function(){
                                    $('#popupInstalled').hide();
                                    $('#popupToCenter').show();
                                });
                            } else if (data.url) {
                                DUOBAO._post('/payment/pay/ajax_pay',{order_id:data.order_id,pay_disabled:data.disabled,paycoupon:data.pay_coupon,payagent:data.payagent},function($res){
                                    if($res.retCode == 0){
                                        if($res.retData.ispaid == '<?=Lib_Constants::PAY_STATUS_PAID?>'){
                                            layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                                location.href = data.pay_redirect+'?order_id='+data.order_id;
                                            });
                                        }else{
                                            buy.weixinGetBrandWCPayV3Request($res.retData,function(res){
                                                if (res.err_msg == "get_brand_wcpay_request:ok" || res.err_msg == "get_brand_wcpay_request:finished") {
                                                    DUOBAO._post('/payment/pay/ajax_get_order',{order_id:data.order_id},function($res){
                                                        try{
                                                            $res = typeof $res == 'string' ? $.parseJSON($res) : $res;
                                                            if($res.retCode == 0 && $res.retData.is_paid == 'true'){
                                                                layer.msg('支付成功~',{time: 1000, icon: 1},function(){
                                                                    location.href = data.pay_redirect+'?order_id='+data.order_id;
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
                                                    layer.msg('支付失败，请重新支付2');
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
//                                location.href = data.url;
                            }
                            isClick = false;
                        }
                    } catch(e){
                        layer.msg('服务异常！');
                        isClick = false;
                    }
                });
            }
        }
        return false;
    });

    // 朕要发福袋
    $('#btnPopSend').on('click', function(){
        $('.popup-send-bag').hide();
        location.href =  '<?=gen_uri('/luckybag/info')?>?bag_id='+order.order_id;
    });

    // 继续发此福袋
    $('#sendContinue').on('click', function(){
        $('.popup-send-bag').hide();
        location.href = '<?=gen_uri('/luckybag/info')?>?bag_id='+order.order_id;
    });

    $('.mask, .popup-share').on('click', function(){
        $('.mask, .popup').hide();
    });

    $('#btnPopupClose').on('click', function(){
        $('.mask, .popup').hide();
    });

    // 活动规则
    $('#actiRules').on('click', function(){
        $('.mask, #popRules').show();
    });
    $('#btnPopupCloseRules').on('click', function(){
        $('.mask, #popRules').hide();
    });
});


// 初始化
var sendBagInit = function (remainStamps) {
    var num = $('#number'),
        num1 = $('#numberOrdinary'),
        vouchersSum = $('#vouchersSum'),
        vouchersEach = $('#vouchersEach');

    if ($('.tit-fight-luck').hasClass('tab-active')) { // 拼手气福袋
        var numVal = isEmpty(num) ? '' : num.val();
        var vouchersSumVal = isEmpty(vouchersSum) ? '' : vouchersSum.val();
        var needPay = $('.wishes-fight-luck').children().find('span').text() == 0 ? 0 : $('.wishes-fight-luck').children().find('span').text();
        num.val(numVal);
        vouchersSum.val(vouchersSumVal);
        $('#needPay').text('￥'+ needPay);
    }

    if ($('.tit-ordinary').hasClass('tab-active')) { // 普通福袋
        var num1Val = isEmpty(num1) ? '' : num1.val();
        var vouchersEachVal = isEmpty(vouchersEach) ? '' : vouchersEach.val();
        var needPay = $('.wishes-ordinary').children().find('span').text() == 0 ? 0 : $('.wishes-ordinary').children().find('span').text();
        num1.val(num1Val);
        vouchersEach.val(vouchersEachVal);
        $('#needPay').text('￥'+ needPay);
    }

}

// 校验
var validate = function () {
    var isChecked = false;
    var itemInputs = $('.f-item input[type="text"]');
    var num = itemInputs.eq(0);
    var num1 = itemInputs.eq(1);
    var vouchersSum = itemInputs.eq(2);
    var vouchersEach = itemInputs.eq(3);
    var reg = /^\+?[1-9]\d*$/;

    if ($('.tit-fight-luck').hasClass('tab-active')) { // 拼手气福袋
        if (isEmpty(num)) {
            layer.msg('请输入'+ num.prev('label').text());
        } else if (!reg.test(trim(num.val()))) {
            layer.msg('您输入的格式有误，请输入任意正整数！');
        } else if (isEmpty(vouchersSum)) {
            layer.msg('请输入'+ vouchersSum.prev('label').text());
        } else if (!reg.test(trim(vouchersSum.val()))) {
            layer.msg('您输入的格式有误，请输入任意正整数！');
        } else if (parseInt(vouchersSum.val()) < parseInt(num.val())) {
            layer.msg('夺宝券张数必须大于发放人数');
        } else {
            isChecked = true;
        }
    }

    if ($('.tit-ordinary').hasClass('tab-active')) { // 普通福袋
        if (isEmpty(num1)) {
            layer.msg('请输入'+ num1.prev('label').text());
        } else if (!reg.test(trim(num1.val()))) {
            layer.msg('您输入的格式有误，请输入任意正整数！');
        } else if (isEmpty(vouchersEach)) {
            layer.msg('请输入'+ vouchersEach.prev('label').text());
        } else if (!reg.test(trim(vouchersEach.val()))) {
            layer.msg('您输入的格式有误，请输入任意正整数！');
        } else {
            isChecked = true;
        }
    }
    return isChecked;
}

// 支付金额
var fnNeedPay = function (vouchersSum, remainVoucherNum) {
    var needPay = (vouchersSum - remainVoucherNum) * 1; // 还需支付
    if (needPay < 0) {
        needPay = 0;
        if ($('.tit-ordinary').hasClass('tab-active')) {
            remainVoucherNum = vouchersSum;
        }
    }
	if (isNaN(needPay)) {
		remainVoucherNum = 0;
		needPay = 0;
	}
    var payTips = $('<p class="pay-tips">使用夺宝券<em>'+ remainVoucherNum +'</em>张，还需支付￥<span>'+ needPay +'</span></p>');

    $('.wishes').find('.pay-tips').remove();
    if ($('.tit-fight-luck').hasClass('tab-active')) {
        $('.wishes-fight-luck').append(payTips);
    }
    if ($('.tit-ordinary').hasClass('tab-active')) {
        $('.wishes-ordinary').append(payTips);
    }
    $('#needPay').text('￥'+ needPay);
}
</script>