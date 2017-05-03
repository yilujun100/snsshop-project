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
            <form id="myform" action="" name="myform">
                <ul>
                    <?php foreach($active as $list){   ?>
                        <li>
                            <em class="indiana-name"><?=$list['sGoodsName']?>*<?=$qty_arr[$list['iActId']]?></em>
                            <span class="single-price">￥<?=price_format($list['iCodePrice'])?></span>
                            <input type="hidden" name="qty[]" value="<?=$qty_arr[$list['iActId']]?>">
                            <input type="hidden" name="active_arr[]" value="<?=period_code_encode($list['iActId'],$list['iPeroid'])?>">
                        </li>
                    <?php } ?>
                </ul>
                <input type="hidden" name="cart" value="1">
                <input type="hidden" name="total" value="<?=$total?>">
            </form>
            <div class="indiana-list-bott clearfix">
                <p class="total-cost">总计：<strong>￥<?=price_format($total)?>
            </div>
        </div>
    </div>

    <a href="javascript::void(0)" id="submit-btn" class="btn btn-block btn-red">去支付</a>
</div>



<script>
    $(function(){
        $('#submit-btn').click(function(){
            var total = $('input[name="total"]').val();
            var data = $('#myform').serialize();
            DUOBAO._post('<?=gen_uri('/active/ajax_multi_order')?>',data,function(rs){
                if(rs.retCode != 0){
                    layer.msg(rs.retMsg || '订单生成失败，请稍候再试~')
                }else{
                    location.href = '<?=$pay_url?>?callback_url=<?=urlencode($order_callback)?>&order_id='+rs.retData.order_id;
                }
            })
        })
    })
</script>