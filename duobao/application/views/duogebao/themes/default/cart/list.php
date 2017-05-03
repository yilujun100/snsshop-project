<?php
    if(empty($cart_list)) {
?>
        <div class="viewport v-cart">
            <div class="cart-empty-wrap">
                <i class="icon-cart-empty"></i>
                <p>选择心仪的商品，加入清单，<br>就会显示在这里！</p>
                <a href="<?=gen_uri('/home/index')?>" class="btn btn-error btn-e-buy"><span>快去选购心仪的商品吧</span></a>
            </div>
        </div>
        <div class="grid-1 bott-pay">
            <div class="bott-pay-cell-1">
                <p>奖品共计：<strong id="totalQty">0</strong>件</p>
                <p>参与次数：<strong id="totalTimes">0</strong>次</p>
            </div>
            <div class="bott-pay-cell-2">总计用券：<strong id="totalCost">0</strong>张</div>
            <div class="bott-pay-cell-3">
                <a href="javascript:void(0)" id="submit-pay" class="btn-cart-pay"><img src="<?=$resource_url?>images/btn/btn-jsa.png" alt=""/></a>
            </div>
        </div>
<?php
    }
    else
    {
?>
    <div class="viewport v-cart">
        <div class="grid cart-item">
            <div class="grid-1 cart-item-hd clearfix">
                    <span class="check-all fl" id="headCheckAll">
                        <input type="checkbox" checked id="checkAll" checked>
                        <label for="checkAll">全选</label>
                    </span>
                <a href="javascript:;" class="btn-opera-del fr" id="cartDel">删除</a>
            </div>
            <div class="cart-item-con">
                <form name="myform" action="<?=gen_uri('/pay/cart_buy',array(),'payment')?>" id="myform" method="get">
                    <ul class="cart-list">
                        <?php if(isset($list) && !empty($list)){ ?>
                            <?php foreach($list as $li){ ?>
                                <li id="<?=(period_code_encode($li['iActId'],$li['iPeroid']))?>">
                                    <div class="items">
                                        <div class="check-wrap">
                                            <input type="checkbox" name="peroid_str[]"  checked  value="<?=(period_code_encode($li['iActId'],$li['iPeroid']))?>">
                                        </div>
                                        <div class="cart-item-core">
                                            <div class="cart-product-cell-1"><img src="<?=$li['sImg']?>" width="84" height="84" alt=""></div>
                                            <div class="cart-product-cell-2">
                                                <div class="cart-product-name"><?=$li['sGoodsName']?></div>
                                                <div class="cart-product-cell-3">
                                                    <p class="cart-product-price">总需：<?=$li['iLotCount']?>人次，剩余<strong><?=($li['iLotCount']-$li['iSoldCount'])?></strong>人次</p>
                                                </div>
                                                <div class="cart-opt clearfix">
                                                    <span class="single-cost">单次用券<strong><?=$li['iCodePrice']/Lib_Constants::COUPON_UNIT_PRICE?></strong></span>
                                                    <div class="quantity-wrap">
                                                        <a href="javascript:;" class="quantity-decrease">-</a>
                                                        <input type="text" class="quantity" value="<?=$cart_list[$li['iActId']]['iBuyCount']?>" name="<?=(period_code_encode($li['iActId'],$li['iPeroid']))?>_num">
                                                        <a href="javascript:;" class="quantity-increase">+</a>
                                                    </div>
                                                    <a href="javascript:;" class="end-check"><img src="<?=$resource_url?>images/btn/btn-baow.png" alt=""/></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php foreach($active_block_list as $block_list){ ?>
                            <li>
                                <div class="items items-invalid">
                                    <div class="check-wrap">
                                        <label class="label-invalid">失效</label>
                                    </div>
                                    <div class="cart-item-core collect-item-core">
                                        <div class="cart-product-cell-1"><img src="<?=$block_list['sImg']?>" width="84" height="84" alt=""></div>
                                        <div class="cart-product-cell-2">
                                            <div class="cart-product-name"><?=$block_list['sGoodsName']?></div>
                                            <div class="collect-opt">
<!--                                                --><?php
//                                                    if($block_list['new_periods'] > 0)
//                                                    {
//                                                ?>
<!--                                                        <a href="--><?//=gen_uri('/active/detail',array('id'=>period_code_encode($block_list['iActId'],$block_list['new_periods'])))?><!--" peroid-str="--><?//=period_code_encode($block_list['iActId'],0)?><!--" class="btn-collect-item-del delFav">删除</a>-->
<!--                                                --><?php
//                                                    }
//                                                    else
//                                                    {
//                                                ?>
                                                        <a href="javascript:;" id="itemInvalidDel" peroid-str="<?=period_code_encode($block_list['iActId'],0)?>" class="btn-collect-item-del delFav">删除</a>
<!--                                                --><?php
//                                                    }
//                                                ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <input type="hidden" name="total" value="">
                </form>
            </div>
        </div>
    </div>
    <div class="grid-1 bott-pay">
        <div class="bott-pay-cell-1">
            <p>奖品共计：<strong id="totalQty">0</strong>件</p>
            <p>参与次数：<strong id="totalTimes">0</strong>次</p>
        </div>
        <div class="bott-pay-cell-2">总计用券：<strong id="totalCost">0</strong>张</div>
        <div class="bott-pay-cell-3">
            <a href="javascript:void(0)" id="submit-pay" class="btn-cart-pay"><img src="<?=$resource_url?>images/btn/btn-jsa.png" alt=""/></a>
        </div>
    </div>
<?php
    }
?>


<script>
    $(function(){
        DUOBAO.cart = {
            totalPartiTimes: 0,
            totalCost: 0,
            prizeNum: 0
        }

        DUOBAO.url = {
            'del_cart' : '<?=gen_uri('/cart/del')?>'
        }

        $('#submit-pay').click(function(){
            var qty = parseInt($('#totalQty').text());
            var times = parseInt($('#totalTimes').text());

            if(qty <= 0 || times <= 0){
                layer.msg('亲，请先选择夺宝商品哦~~');
                return false;
            }

            $('.cart-list li').css('border','0px solid red');
            $('.cart-list li').find('.msg').hide();
            DUOBAO._post('<?=gen_uri('/active/ajax_check_active')?>',$('#myform').serialize(),function($res){
                $res = typeof($res) === 'string' ? $.parseJSON($res) : $res;

                if($res.retCode == 0){
                    $('#myform').submit();
                }else{
                    if("object" === typeof $res.retData){
                        $.each($res.retData,function(i,j){
                            $('#'+ j.id).css('border','1px solid red');
                            $('#'+ j.id).find('.msg').text(j.msg).show();
                            layer.msg(j.msg);
                        })
                    }else{
                        layer.msg($res.retMsg || '亲，参数异常哦，刷新后请重试');
                    }
                }
            })
        })
    })
</script>
<script type="text/javascript" src="<?=$resource_url?>js/cart.js"></script>