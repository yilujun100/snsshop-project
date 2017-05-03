<div class="viewport v-collect">
    <?php if(!empty($list['list'])){ ?>
        <div class="grid collect-list">
            <form id="myform" name="myform" action="<?=gen_uri('/active/collect_buy')?>" method="get">
                <ul>
                    <?php foreach($list['list'] as $li){ ?>
                        <li data-price="<?=price_format($li['iCodePrice'])?>">
                            <div class="items <?=$li['new_periods']==0?'items-invalid':''?>">
                                <div class="collect-item-core">
                                    <div class="collect-product-cell-1 us-pic-bg"><img src="<?=$li['sImg']?>" width="84" height="84" alt=""></div>
                                    <div class="collect-product-cell-2">
                                        <div class="collect-product-name"><?=$li['sGoodsName']?></div>
                                        <div class="collect-opt">
                                            <span class="single-cost">单次<strong>￥<?=price_format($li['iCodePrice'])?></strong></span>
                                            <a href="javascript:void(0)" class="btn-collect-item-del delFav" id="<?=$li['iActId']?>">删除</a>
                                            <?php
                                                if($li['new_periods'] == 0)
                                                {
                                            ?>
                                                    <button class="btn-detailed"><img src="<?=$resource_url?>images/btn/btn-jrqd.png" alt=""/></button>
                                            <?php
                                                }
                                                else
                                                {
                                            ?>
                                                    <a class="btn-detailed" peroid-str="<?=period_code_encode($li['iActId'],$li['new_periods'])?>"  onclick="DUOBAO.addCart($(this))"><img src="<?=$resource_url?>images/btn/btn-jrqd.png" alt=""/></a>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </form>
        </div>
    <?php }else{?>
        <div class="collect-empty-wrap">
            <i class="icon-collect-empty"></i>
            <p>暂无收藏记录</p>
        </div>
    <?php } ?>
</div>

<script>
    DUOBAO.collect = {
        perTimes: 1, // 每件奖品参与的次数
        totalPrize: 0, // 总的奖品件数
        totalPartiTimes: 0, // 总的参与次数
        cost: 0 // 总计支付金额
    };

    DUOBAO.url = {
        'collect_url': '<?=gen_uri('/collect/del')?>',
        'add_cart': '<?=gen_uri('/cart/ajax_add')?>'
    };

    $(function(){
        $('.delFav').click(function(){
            var _this = $(this);
            var act_id = _this.attr('id');
            DUOBAO.popWinConfirm.init('确认删除所选项', '确定', '取消', function(){
                DUOBAO._get(DUOBAO.url.collect_url+'?act_id='+act_id,function(rs){
                    rs = 'string' === typeof rs ? $.parseJSON(rs) : rs;
                    DUOBAO.popWinConfirm.hide();
                    if(rs.retCode != 0){
                        layer.msg('删除失败，请稍侯再试~');
                    }else{
                        _this.parents('li').remove();
                        DUOBAO.collect.init();
                        layer.msg('成功删除收藏~');
                    }
                });
            });

        });

    })

</script>
<script type="text/javascript" src="<?=$resource_url?>js/my_collect.js"></script>