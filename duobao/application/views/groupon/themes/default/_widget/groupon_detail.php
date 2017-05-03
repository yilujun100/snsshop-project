<?
//商品展示页面
if (!empty($groupon_detail)) {
?>
    <div class="prd-pic">
        <img src="<?=$cdn_groupon_url?>images/goods.jpg" width="100%" alt="<?=$groupon_detail['sGoodsName']?>">
        <span class="stamp-shipping-free"></span>
    </div>
    <div class="prd-info grid">
        <div class="prd-name"><?=$groupon_detail['sGoodsName']?></div>
        <div class="prd-tags mt-10 clearfix">
            <span class="tag-1"><?=$groupon_detail['sSpec']?><em></em></span>
            <?php if($groupon_detail['sKeyword']) {
                $tags = explode(',', $groupon_detail['sKeyword']);
                foreach ($tags as $tag) {
                    if($tag){
                        ?>
                        <i class="tag-2"><?=$tag?></i>
                    <?php
                    }
                }
                ?>
            <?php } ?>
        </div>
        <div class="prd-desc mt-10">
            <p><?=$groupon_detail['sIntro']?></p>
        </div>
        <?php
        if (!empty($show_sell)) { //展示销量
        ?>
            <div class="prd-info-basic mt-10 clearfix">
                <div class="fl">
                    <label><?=$groupon_detail['groupon_spec']['iPeopleNum']?>人团</label>
                    <em class="prd-price-dis">￥<?=$groupon_detail['groupon_spec']['iDiscountPrice']?></em>
                    <span class="prd-price-original">原价<s>￥<?=$groupon_detail['iPrice']?></s></span>
                </div>
                <span class="groups-amount fr">已成团<strong><?=$groupon_detail['iSuccCount']?></strong></span>
            </div>
        <?php
        }
        ?>
    </div>
<?php
}
?>