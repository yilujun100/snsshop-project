<link rel="stylesheet" href="<?=$resource_url?>css/layout_recharge.css">
<div class="viewport v-activity">
    <!-- banner -->
    <div class="banner-acti">
        <img src="<?=$resource_url?>images/banner_recharge.jpg" width="100%" alt="">
        <a href="<?=gen_uri('/help/index',array('item'=>'rules_active'))?>" class="btn-know">了解百分好礼&gt;</a>
    </div>

    <!-- recharge list -->
    <div class="recharge-list">
        <ul>
            <li>
                <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>100))?>" class="recharge-item"><img src="<?=$resource_url?>images/coupon_1.png" width="100%" alt="充值100"></a>
            </li>
            <li>
                <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>50))?>" class="recharge-item"><img src="<?=$resource_url?>images/coupon_2.png" width="100%" alt="充值50"></a>
            </li>
            <li>
                <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>30))?>" class="recharge-item"><img src="<?=$resource_url?>images/coupon_3.png" width="100%" alt="充值30"></a>
            </li>
            <li>
                <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>20))?>" class="recharge-item"><img src="<?=$resource_url?>images/coupon_5.png" width="100%" alt="充值20"></a>
            </li>
            <li>
                <a href="<?=gen_uri('/coupon/buy_coupon',array('val'=>10))?>" class="recharge-item"><img src="<?=$resource_url?>images/coupon_4.png" width="100%" alt="充值10"></a>
            </li>
        </ul>
    </div>

    <!-- instructions -->
    <div class="instructions">
        <div class="instru-hd">
            <div class="hr hr-1"></div>
            <div class="hr hr-2"></div>
            <h3>活动说明</h3>
        </div>
        <div class="instru-con">
            <dl>
                <dt>活动时间</dt>
                <dd>2016年5月5日-2016年5月11日</dd>
            </dl>
            <dl>
                <dt>活动规则</dt>
                <dd>1.活动期间，单次充值：满10元送1元；满20元送3元；满30元送5元；满50元送9元；满100元送18元；</dd>
                <dd>2.充值活动后，赠券将自动发到您的账户中，您可前往个人中心查询；</dd>
                <dd>3.赠券和购买券持有同等效力，均用于参与活动，不可兑换现金；</dd>
                <dd>4.活动最终解释权归微团购所有。</dd>
            </dl>
        </div>
    </div>
</div>