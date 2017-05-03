<style>
	.indiana-list { padding: 0 0 10px; border-bottom: 0; }
	.indiana-list-hd { border-bottom: 1px solid #cdcdcd; }
	.indiana-list-con ul { margin: 0 10px; }
	.indiana-list-con li { padding: 10px 0; height: 20px; border-bottom: 1px solid #cdcdcd; font-size: 12px; color: #333; }
	.indiana-list-con li .indiana-name { float: left; }
	.indiana-list-con li .single-price { float: right; }
	.parti-number { margin-top: 10px; margin-right: 10px; text-align: right; }
	.parti-number em { font-size: 12px; color: #666; }
	.quantity-wrap { display: inline-block; vertical-align: -10px; }
	.total-cost { margin: 0; margin-top: 10px; margin-right: 10px; text-align: right; }
	.total-cost strong { color: #f03e3c; }
	#submit-btn { display: block; margin: 30px 20px; }
</style>
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
                <h3><?=(empty($detail['iCodePrice']) ? '截止时间开奖' : '集齐人数开奖')?></h3>
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
                    <span class="single-price">￥<?=price_format($detail['iCodePrice'])?></span>
                </li>
            </ul>
            <div class="indiana-list-bott clearfix">
                <form id="myform" action="" name="myform">
                    <?php if(!empty($detail['iCodePrice'])){ ?>
                    <p style="text-align:right;clear:both;margin:5px 0;font-size:12px;color:#666;">剩余：<strong style="color: #f03e3c;"><?=($detail['iLotCount']-$detail['iSoldCount'])?></strong></p>
                    <?php } ?>
                    <div class="parti-number">
                        <em>参与次数：</em>
                        <div class="quantity-wrap">
                            <a href="javascript:;" class="quantity-decrease">-</a>
                            <input type="text" class="quantity" value="1" name="qty">
                            <a href="javascript:;" class="quantity-increase">+</a>
                        </div>
                    </div>
                    <input type="hidden" name="peroid_str" value="<?=$peroid_str?>">
                    <input type="hidden" name="price" id="price" value="<?=$detail['iCodePrice']?>">
                    <input type="hidden" name="total" id="total" value="<?=$detail['iCodePrice']?>">
                    <p class="total-cost">总计：<strong>￥<?=price_format($detail['iCodePrice'])?></strong></p>
                </form>
            </div>
        </div>
    </div>

    <a href="javascript::void(0)" id="submit-btn" class="btn btn-block btn-error" <?=($stock_out?'disabled="disabled"':'')?>><span><?=(empty($detail['iCodePrice']) ? '确认参与' : '去支付')?></span></a>
</div>



<script>
    $(function(){
        $('#submit-btn').click(function(){
            var data = $('#myform').serialize();
            DUOBAO._post('<?=gen_uri('/active/ajax_order')?>',data,function(rs){
                rs = 'string' === typeof rs ? $.parseJSON(rs) : rs;
                if(rs.retCode != 0){
                    layer.msg(rs.retMsg || '订单生成失败，请稍候再试~');
                }else{
                    if(rs.retData.is_paid == 1){
                        layer.msg('参与成功~',{time:1000},function(){
                            location.href = '<?=$order_callback?>';
                        });
                    }else{
                        location.href = '<?=$pay_url?>?callback_url=<?=urlencode($order_callback)?>&order_id='+rs.retData.order_id;
                    }
                }
            })
        })

        DUOBAO.chooseQty.init(function(num){
            var price = '<?=$detail['iCodePrice']?>';
            var total = parseInt(price)*num;
            $('.total-cost strong').text('￥'+((total/100).toFixed(2)));
            $('#total').val(total);
        });
    })
</script>