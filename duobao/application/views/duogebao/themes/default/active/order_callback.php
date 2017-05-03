<div class="viewport v-pay-success">
    <div class="pay-success">
        <div class="success-info">
            <i class="icon-pay-success"><img src="<?=$resource_url?>images/win-ico.png" alt=""/></i>
            <h3 class="suc-txt">下单成功！</h3>
            <div class="indiana-info">
                <p>订单号：<?=$merage_order['sMergeOrderId']?></p>
                <p>订单总额：<?=price_format($merage_order['iTotalPrice'])?>元</p>
<!--                <p>现金支付：--><?//=price_format($merage_order['iAmount'])?><!--元</p>-->
                <p>本次用券：<?=$merage_order['iCoupon']?>张</p>
                <!-- <b>本次下单X个码，只为您抢到Y个码，多支付的X张夺宝券将返回您的账户</b> -->
            </div>
            <div class="btn-groups mt-10">
                <p>*参与码将通过微信推送</p>
                <a href="<?=gen_uri('/home/index')?>" class=""><img src="<?=$resource_url?>images/btn/btn-xu.png" alt=""/></a>
                <a href="<?=gen_uri('/my/active',array('cls'=>'going'))?>" class=""><img src="<?=$resource_url?>images/btn/btn-dd.png" alt=""/></a>
            </div>
        </div>
        <?php
        if(!empty($banner_advert))
        {
            ?>
            <div class="win-banner">
                <a href="<?=$banner_advert[0]['sTarget']?>">
                    <img src="<?=$banner_advert[0]['sImg']?>" alt="<?=$banner_advert[0]['sDesc']?>"/>
                </a>
            </div>
        <?php
        }
        ?>
        <!-- hot -->
        <?php if(!empty($recommend['list'])){ ?>
            <div class="grid hot mt-10" id="swiper">
                <ul class="swiper-wrapper clearfix">
                    <?php foreach($recommend['list'] as $commend){ ?>
                        <li class="swiper-slide">
                            <div class="goods-pic">
                                <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($commend['iActId'],$commend['iPeroid'])))?>">
                                    <img src="<?=$commend['sImg']?>" width="100%" alt="">
                                </a>
                                <label class="tag-hot">热门</label>
                            </div>
                            <div class="goods-name"><?=$commend['sGoodsName']?></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>
<!-- share end -->
<script>
    $(function(){
        // 热门
        var swiper = new Swiper('#swiper', {
            slidesPerView: 3,
            spaceBetween: 10,
            slidesOffsetBefore: 10,
            freeMode: true
        });
    })
</script>