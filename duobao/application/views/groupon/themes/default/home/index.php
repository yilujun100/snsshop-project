<div class="viewport v-detail v-home">
    <!-- home start -->
    <div class="detail home">
        <div class="banner">
            <img src="<?=$cdn_groupon_url?>images/banner_01.jpg" width="100%" alt="">
            <a href="http://2w.gaopeng.com" class="btn-entry btn-entry-mall"><i class="icon-home-1"></i>微商城</a>
            <a href="<?=node_url('help/groupon')?>" class="btn-entry btn-entry-rules">活动<br>规则</a>
        </div>


<?php
    if(!empty($groupon_active_list) && !empty($groupon_active_list['list'])) {
        foreach($groupon_active_list['list'] as $item) {
            $water_count = empty($item['iWaterCount']) ? 0 : intval($item['iWaterCount']);
            $this->widget('groupon_detail', array('groupon_detail'=>$item));
            if(!empty($item['spec'])) {
                $i = 0;
?>
        <div class="grid prd-info-basic mt-10">
<?php
                foreach($item['spec'] as $row) {
?>
            <div class="clearfix">
                <div class="fl">
                    <label><?=$row['iPeopleNum']?>人团</label>
                    <em class="prd-price-dis">￥<?=price_format($row['iDiscountPrice'])?></em>
                    <span class="prd-price-original">原价<s>￥139.00</s></span>
                </div>
                <a href="<?=gen_uri('/active/detail', array('gid'=>$item['iGrouponId'],'spec_id'=>$row['iSpecId']), 'groupon')?>" class="btn-group-open fr">去开团</a>
            </div>
<?php
                    if (($item['iGrouponType'] == Lib_Constants::GROUPON_TYPE_STAIR) && $i == 0){
                        break;
                    }
                }
?>
        </div>
<?php
            }
?>
        <p class="txt">已有<strong><?=$item['iSuccCount']+$water_count?></strong>人成功拼团，快去开团吧！</p>
    </div>

    <div class="prd-detail mt-10">
        <dl>
            <dd>
                <img data-src="<?=$cdn_groupon_url?>images/detail.jpg" src="<?=$cdn_groupon_url?>images/detail.jpg" width="100%" alt="">
            </dd>
        </dl>
    </div>
    <div class="ad mt-10">
        <a href="<?=gen_uri('/home/index')?>"><img data-src="<?=$cdn_groupon_url?>images/ad_2.jpg" src="<?=$cdn_groupon_url?>images/ad_2.jpg" width="100%" alt=""></a>
    </div>
<?php
        }
    }
?>
</div>
<!-- home end -->