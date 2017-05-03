<div class="grid pay-type mt-10">
    <div class="pay-type-hd">支付方式</div>
    <div class="pay-type-con">
        <div class="pay-type-item">
            <input type="radio" id="pay-weixin" value="<?=Lib_Constants::ORDER_PAY_TYPE_WX?>" checked>
            <label for="pay-weixin"><i class="icon-weixin"></i><?=Lib_Constants::$order_pay_type[Lib_Constants::ORDER_PAY_TYPE_WX]?></label>
        </div>
    </div>
</div>
<div class="bott-btn">
    <a href="javascript:;" id="btn-pay-submit" class="btn-block btn-pay"><?=empty($order_id)?(empty($button)?'提交订单':$button):'确认支付'?></a>
</div>
<script type="text/javascript">
    var $submit = $('#btn-pay-submit'),
        $pay_form = $('#<?=empty($pay_form)?'pay-form':$pay_form?>'),
        result_url = $('input[name="result_url"]', $pay_form).val(),
        checker;
    if (typeof <?=empty($checker)?0:$checker?> !== 'function') {
        checker = function(){return true;};
    } else {
        checker = <?=empty($checker)?0:$checker?>;
    }
    $submit.on('click', function () {
        var $this = $(this);
        if ($this.hasClass('disabled') || ! checker.call($submit)) {
            console.log('failed');
            return;
        }
        if (! $('input[name="order_type"]',$pay_form).val()) {
            layer.msg('订单类型异常', {icon: 2, time: 3000});
            return;
        }
        if (! $('input[name="pay_agent"]',$pay_form).val()) {
            layer.msg('请选择支付方式', {icon: 2, time: 3000});
            return;
        }
        $this.addClass('disabled');
        DUOBAO._post('<?=(gen_uri('/order/ajax_create_order',array(),'payment'))?>', $pay_form.serialize(), function (res) {
            if ($('input[name="order_id"]').val()) {
                if (0 != res.retCode) {
                    layer.msg(res.retMsg, {icon: 2, time: 3000});
                    $submit.removeClass('disabled');
                    return;
                }
                yes(res.retData, 0);
            } else {
                if (0 != res.retCode) {
                    layer.msg('订单生成失败，请稍侯再试', {icon: 2, time: 3000});
                    $submit.removeClass('disabled');
                    return;
                }
                var layer_index = layer.confirm('订单已成功提交', {
                    title: false,
                    closeBtn: false,
                    btn: ['去支付', '查看订单']
                }, function () {yes(res.retData, layer_index);}, function(){cancel(res.retData, layer_index);});
            }
        });
    });
    function yes(data, layer_index) {
        layer.close(layer_index);
        invokeWxPay(data.jsapicall, function (res) {
            if ('get_brand_wcpay_request:ok' !== res.err_msg) {
                layer_index = layer.confirm('支付失败', {
                    title: false,
                    closeBtn: false,
                    btn: ['重新支付', '查看订单']
                }, function () {yes(data, layer_index);}, function(){cancel(data, layer_index);});
            } else {
                DUOBAO._post('<?=gen_uri('/order/ajax_get_order','','payment')?>', {order_id: data.order_id}, function (res) {
                    $submit.removeClass('disabled');
                    if (res.retCode == 0 && res.retData.is_paid == 'true') {
                        if (res.retData.is_refunded == 'false') {
                            layer.msg('支付成功~', {time: 1500, icon: 1}, function() {
                                var separator = result_url.indexOf('?') > -1 ? '&' : '?';
                                location.href = result_url + separator + 'order_id=' + data.order_id;
                            });
                        } else {
                            var op_desc = '「'+($('#order_operate_desc').val() || '支付')+'」';
                            layer.confirm(op_desc + '失败，已自动转入退款', {
                                title: false,
                                closeBtn: false,
                                btn: ['查看订单']
                            }, function(){cancel(data, layer_index);});
                        }
                    } else {
                        layer.msg('支付失败，如果已支付，请稍侯通过个人中心查看~');
                        return false
                    }
                });
            }
        });
    }
    function cancel(data, layer_index) {
        location.href = $('#order_detail_url').val()+'?order_id='+data.order_id;
    }
    function invokeWxPay(jsapicall, callback) {
        if (typeof WeixinJSBridge == "undefined") {
            if (document.addEventListener) {
                document.removeEventListener('WeixinJSBridgeReady', onBridgeReady, false);
                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            } else if (document.attachEvent) {
                document.detachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.detachEvent('onWeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
            }
        } else {
            onBridgeReady();
        }
        function onBridgeReady() {
            WeixinJSBridge.invoke('getBrandWCPayRequest', jsapicall, callback);
        }
    }
</script>