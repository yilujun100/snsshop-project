<?php if($data['return_code'] == Lib_Errors::SUCC){ ?>
    <div class="viewport v-pay-success">
        <div class="pay-success">
            <div class="success-info">
                <i class="icon-pay-success"><img src="<?=$resource_url?>images/win-ico.png" alt=""/></i>
                <h3 class="suc-txt">下单成功！</h3>
                <div class="indiana-info">
                    <p>订单号：<?=$data['order_id']?></p>
                    <!--                    <p>苹果专区 IPHONE 6S粉红色过年就买新手机新手机</p>-->
                    <!--                    <p>参与次数：5次</p>-->
                    <!--                    <p>赠送积分：25分</p>-->
                    <!-- <b>本次下单X个码，只为您抢到Y个码，多支付的X张夺宝券将返回您的账户</b> -->
                </div>
                <div class="btn-groups mt-10">
                    <p>*参与码将通过微信推送</p>
                    <a href="<?=gen_uri('/home/index')?>" class=""><img src="<?=$resource_url?>images/btn/btn-xu.png" alt=""/></a>
<!--                    <a href="--><?//=gen_uri('/my/active',array('cls'=>'going'))?><!--" class=""><img src="--><?//=$resource_url?><!--images/btn/btn-dd.png" alt=""/></a>-->
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
            <?php if (empty($share_list['list'])) { ?>
                <div class="grid hot mt-10" id="swiper">
                    <ul class="swiper-wrapper clearfix">
                        <?php
                        if(!empty($active_list))
                        {
                            foreach($active_list as $v)
                            {
                                ?>
                                <li class="swiper-slide">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($v['iActId'],$v['iPeroid'])))?>">
                                        <div class="goods-pic">
                                            <img src="<?=get_img_resize_url($v['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt="">
                                            <label class="tag-hot">热门</label>
                                        </div>
                                        <div class="goods-name"><?=$v['sGoodsName']?></div>
                                    </a>
                                </li>
                            <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
<?php }else{ ?>
    <div class="viewport v-pay-failure">
        <div class="pay-success">
            <div class="success-info">
                <i class="icon-pay-success"><img src="<?=$resource_url?>images/win-ico2.png" alt=""/></i>
                <h3 class="suc-txt">下单失败！</h3>
                <div class="indiana-info">
                    <strong>此次参与下单失败<br>并未支付成功</strong><!--下单失败时显示-->
                    <p class="status-info-failure">注：若券有减少，减少的券将会原路返回您的账户。</p>
                </div>
                <a href="#" class="btn btn-error btn-back-home"><span>返回首页</span></a>
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
            <?php if (empty($share_list['list'])) { ?>
                <div class="grid hot mt-10" id="swiper">
                    <ul class="swiper-wrapper clearfix">
                        <?php
                        if(!empty($active_list))
                        {
                            foreach($active_list as $v)
                            {
                                ?>
                                <li class="swiper-slide">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($v['iActId'],$v['iPeroid'])))?>">
                                        <div class="goods-pic">
                                            <img src="<?=get_img_resize_url($v['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt="">
                                            <label class="tag-hot">热门</label>
                                        </div>
                                        <div class="goods-name"><?=$v['sGoodsName']?></div>
                                    </a>
                                </li>
                            <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>



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
//setTimeout(function(){
//   location.href = '<?//=gen_uri('/my/index')?>//';
//},2000);
</script>