<div class="viewport v-share">
    <div class="grid-1 share-detail pd10">
        <div class="share-detail-col1">
            <div class="user-avatar">
                <img src="<?=$share_detail['headimg']?>" width="50" height="50" alt="">
            </div>
            <div class="author mt-10">
                <p><?=$share_detail['nickname']?></p>
                <p class="clearfix"><em class="fl"><?=$share_detail['area']?> IP：<?=$share_detail['ip']?></em><span class="time fr"><?=$share_detail['share_time'] ? date('Y-m-d H:i:s', $share_detail['share_time']) : ''?></span></p>
            </div>
        </div>
        <div class="share-detail-col2">
            <p><i class="ico-sd-item ico-nm"></i><label class="txt2">奖品</label>：<b><?=$share_detail['goods_name']?></b></p>
            <p><i class="ico-sd-item ico-id"></i><label class="txt2">期号</label>：<?=period_code_encode($share_detail['act_id'], $share_detail['period'])?></p>
            <p><i class="ico-sd-item ico-da"></i>本期购买：<strong><?=$share_detail['lot_count']?></strong>人次</p>
            <p><i class="ico-sd-item ico-nb"></i><label class="txt3">幸运码</label>：<strong><?=$share_detail['lucky_code']?></strong></p>
            <p><i class="ico-sd-item ico-tm"></i>揭晓时间：<?=$share_detail['lot_time'] ? date('Y-m-d H:i:s', $share_detail['lot_time']) : ''?></p>
        </div>
        <div class="share-detail-col3 clearfix">
            <p><?=$share_detail['content']?></p>
            <?php
            if($share_detail['imgs']) {
                foreach($share_detail['imgs'] as $img) {
                    ?>
                    <div class="nwes-img-border"><img src="<?=get_img_resize_url($img, Lib_Constants::SHARE_IMG_BIG, Lib_Constants::SHARE_IMG_BIG)?>" alt=""></div>
                <?php } } ?>
            </br>
            <div class="actions us-pic-bg">
                <div class="actions-bg clearfix" data-shareid="<?=$share_detail['share_id']?>">
                    <ul class="clearfix">
                        <li class="like"><i class="<?=$is_liked? 'icon-like-on' : 'icon-like'?>"></i><strong><?=$share_detail['like_count']?></strong>人已赞</li>
                        <li class="views"><strong><?=$share_detail['view_count']?></strong>人已查看</li>
                    </ul>
                </div>
            </div>
<!--            <div class="actions mt-10 clearfix" data-shareid="--><?//=$share_detail['share_id']?><!--">-->
<!--                <ul class="clearfix">-->
<!--                    <li class="like"><i class="--><?//=$is_liked? 'icon-like-on' : 'icon-like'?><!--"></i><strong>--><?//=$share_detail['like_count']?><!--</strong>人已赞</li>-->
<!--                    <li class="views"><strong>--><?//=$share_detail['view_count']?><!--</strong>人已查看</li>-->
<!--                </ul>-->
<!--            </div>-->
        </div>

        <!-- bottom -->
        <div class="detail-bottom">
				<span class="detail-bott-btns detail-bott-btns-1">
					<a href="javascript:;" class="btn btn-error btn-bott-share"><span>分享</span></a>
				</span>
        </div>
        <!-- share start -->
        <div class="pop-mask-share"></div>
        <div class="pop-share">
            <img src="<?=$resource_url?>images/popWin/share.png" width="100%" alt="">
        </div>
        <!--right-fix-->
        <?php $this->widget('right_icon', array('peroid_str'=>period_code_encode($share_detail['act_id'], $share_detail['period']))) ?>
    </div>
</div>
<script type="application/javascript">
    $(function(){
        //查看
        $.post('<?=gen_uri('/share/operate')?>',{share_id:<?=$share_detail['share_id']?>,type:1}, function(ret){});
        //点赞
        DUOBAO.url.share_operate = '<?=gen_uri('/share/operate')?>';
        DUOBAO.url.imgcache = '<?=$resource_url?>';
        $('.icon-like').on('click', function(){
            DUOBAO.clickLike(this);
        });

        $(".btn-bott-share").on('click',function(){
            DUOBAO.popWinShare.init();
        });

        $('.pop-mask-share').on('click', function(){
            DUOBAO.popWinShare.hide();
        });
    })
</script>