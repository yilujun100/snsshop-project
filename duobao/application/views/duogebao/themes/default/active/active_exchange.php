<div class="viewport v-quick-pay">
    <!-- pay progress -->
    <div class="grid-1 pay-progress">
        <ul class="clearfix">
            <li class="step-on">
                <i class="icon-pay-step icon-pro-gift"></i>
                <em class="icon-dotted"></em>
                <h3>选择夺宝活动</h3>
            </li>
            <li class="step-on">
                <i class="icon-pay-step icon-cash"></i>
                <em class="icon-dotted"></em>
                <h3>确认支付</h3>
                <s></s>
            </li>
            <li>
                <i class="icon-pay-step icon-codes"></i>
                <em class="icon-dotted"></em>
                <h3>系统发放夺宝码</h3>
                <s></s>
            </li>
            <li>
                <i class="icon-pay-step icon-cup-1"></i>
                <em class="icon-dotted"></em>
                <h3>集齐人数开奖</h3>
                <s></s>
            </li>
        </ul>
    </div>

    <!-- indiana list -->
    <div class="grid indiana-list mt-10">
        <div class="grid-1 indiana-list-hd clearfix">
            <h3 class="fl">夺宝列表</h3>
            <span class="fr">单次价格</span>
        </div>
        <div class="indiana-list-con">
            <ul>
                <li>
                    <em class="indiana-name"><?=$detail['sGoodsName']?></em>
                    <span class="single-price">￥<?=price_format($detail['iCodePrice']*$detail['iLotCount'])?></span>
                </li>
            </ul>
            <div class="indiana-list-bott clearfix">
                <form id="myform" action="" name="myform">
                    <div class="parti-number">
                        <em>兑换数量：</em>
                        <div class="quantity-wrap">
                            <a href="javascript:;" class="quantity-decrease">-</a>
                            <input type="text" class="quantity" value="1" name="qty">
                            <a href="javascript:;" class="quantity-increase">+</a>
                        </div>
                    </div>
                    <input type="hidden" name="peroid_str" value="<?=$peroid_str?>">
                    <input type="hidden" name="price" id="price" value="<?=($detail['iCodePrice']*$detail['iLotCount'])?>">
                    <input type="hidden" name="total" id="total" value="<?=($detail['iCodePrice']*$detail['iLotCount'])?>">
                    <p class="total-cost">总计：<strong>￥<?=price_format($detail['iCodePrice']*$detail['iLotCount'])?></strong></p>
                </form>
            </div>
        </div>
    </div>

    <a href="javascript::void(0)" id="submit-btn" class="btn btn-block btn-red" <?=(empty($stock)?'disabled="disabled"':'')?>>去支付</a>
</div>



<script>
    $(function(){
        $('#submit-btn').click(function(){
            var data = $('#myform').serialize();
            DUOBAO._post('<?=gen_uri('/active/ajax_order',array('type'=>'exchange'))?>',data,function(rs){
                rs = 'string' === typeof rs ? $.parseJSON(rs) : rs;
                if(rs.retCode != 0){
                    layer.msg(rs.retMsg || '订单生成失败，请稍候再试~');
                }else{
                    location.href = '<?=$pay_url?>?callback_url=<?=urlencode($order_callback)?>&order_id='+rs.retData.order_id;
                }
            })
        })

        DUOBAO.chooseQty.init(function(num){
            var price = '<?=($detail['iCodePrice']*$detail['iLotCount'])?>';
            var total = parseInt(price)*num;
            $('.total-cost strong').text('￥'+((total/100).toFixed(2)));
            $('#total').val(total);
        });
    })
</script>