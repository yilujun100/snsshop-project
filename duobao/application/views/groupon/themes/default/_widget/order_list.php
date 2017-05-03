<?php foreach ($order_list as $v) {?>
    <div class="grid order-item mt-10">
        <div class="goods-info">
            <div class="goods-pic">
                <img src="<?=$v['sImg']?>" width="84" height="84" alt="">
            </div>
            <div class="goods-info-basic">
                <div class="goods-name"><?=$v['sGoodsName']?></div>
                <div class="goods-tag"><label><?=$v['sBuyTypeDesc']?></label></div>
                <div class="goods-price clearfix">
                    <span class="fl">总价：<strong>¥ <?=price_format($v['iTotalPrice'])?></strong></span>
                    <span class="fr">状态：<em class="status-order status-o-<?=$v['state']['btn']?>"><?=$v['state']['text']?></em></span>
                </div>
            </div>
        </div>
        <div class="order-item-bott">
            <?php foreach ($v['btns'] as $vv) {?>
                <a href="<?=$vv['url']?>" class="btn-order-<?=$vv['btn']?>" <?php if (!empty($vv['data-url'])){?>data-url="<?=$vv['data-url']?>" data-op="<?=$vv['op']?>" data-id="<?=$v['sOrderId']?>"<?php }?>><?=$vv['text']?></a>
            <?php }?>
        </div>
    </div>
<?php }?>