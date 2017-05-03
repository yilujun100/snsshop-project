<div class="viewport v-buy-stamps">
    <!-- buy stamps start -->
    <div class="grid column buy-stamps">
        <div class="column-hd buy-stamps-hd">
            <h3>请选择购买夺宝券数量</h3>
        </div>
        <div class="buy-stamps-con clearfix">
            <form name="myform" action="" id="myform">
                <?php foreach($conf['sConf'] as $key=>$val){ ?>
                    <p>
                        <input type="radio" name="stampsNumber" <?=($default == $val['c'] ? 'checked' : '')?> id="number20" value="<?=$key?>">
                        <label for="number1000"><?=$val['c']?>张<?=(empty($val['s'])?'':'<strong>（赠'.$val['s'].'张）</strong>')?></label>
                    </p>
                <?php } ?>
                <p><input type="text" id="numberOther" name="numberOther" placeholder="其他数量"></p>
                <input type="hidden" id="conf-id" name="activityId" value="<?=(isset($conf['iActivityId']) ? $conf['iActivityId'] : 0)?>">
            </form>
        </div>
    </div>

    <a href="javascript:void(0)" class="btn btn-block btn-red" id="order-submit">去支付</a>
    <!-- buy stamps end -->
</div>

<script>
    $(function(){
        var conf = <?=json_encode($conf['sConf'])?>;
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
		
		$('#numberOther').on('focus', function(){
			$('.buy-stamps-con').find('input[type="radio"]').prop('checked', false);
		});

        $('#order-submit').click(function(){
            var data = $('#myform').serialize();
            DUOBAO._post('<?=(gen_uri('/coupon/ajax_coupon_order'))?>',data,function(rs){
                rs = typeof(rs) === 'string' ? $.parseJSON(rs) : rs;
                if(rs.retCode != 0){
                    layer.msg('订单生成失败，请稍侯再试~~');
                    return false;
                }else{
                    location.href = '<?=$pay_url?>?callback_url=<?=urlencode($callback_url)?>&order_id='+rs.retData;
                }
            });
        });	
    })
</script>