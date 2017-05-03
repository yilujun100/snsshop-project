<div class="viewport v-group-open">
    <!-- group open start -->
    <div class="group-open">
        <?php $this->widget('groupon_detail', array('groupon_detail'=>$groupon_detail));?>
        <div class="grid mt-10">
            <p class="instruc">支付开团并邀请<b>2</b>人参团，人数不足自动退款</p>
        </div>

        <div class="grid process mt-10">
            <div class="process-tit">
                <h3>拼团流程</h3>
            </div>
            <div class="process-con mt-10">
                <ul class="clearfix">
                    <li class="process-step process-step-1 step-on">
                        <em>1</em>
                        <p>选择<br>商品</p>
                    </li>
                    <li class="step-arrow"></li>
                    <li class="process-step process-step-2">
                        <em>2</em>
                        <p>支付开团<br>或参团</p>
                    </li>
                    <li class="step-arrow"></li>
                    <li class="process-step process-step-3">
                        <em>3</em>
                        <p>等待好友<br>参团支付</p>
                    </li>
                    <li class="step-arrow"></li>
                    <li class="process-step process-step-4">
                        <em>4</em>
                        <p>达到人数<br>拼团成功</p>
                    </li>
                </ul>
            </div>
        </div>
        <?php if(!empty($groupon_diy_list) && !empty($groupon_diy_list['list'])) { ?>
        <div class="spell-group-list">
            <div class="spell-group-list-hd clearfix">
                <h3 class="fl">以下小伙伴正在发起团购，您可以直接参与哦～</h3>
                <a href="<?=gen_uri('/active/diy_more', array('gid'=>$groupon_detail['iGrouponId']), 'groupon')?>" class="view-more fr">查看更多</a>
            </div>
            <?php
            //活动开团列表
            !isset($groupon_diy_list['list']) OR $groupon_diy_list = $groupon_diy_list['list'];
            if (!empty($groupon_diy_list)) {
                ?>
                <div class="grid spell-group-list-con">
                    <ul>
                        <?php $this->widget('diy_list', array('diy_list'=>$groupon_diy_list))?>
                    </ul>
                </div>
            <?php } ?>

        </div>
        <?php } ?>
        <div class="prd-detail mt-10">
            <dl>
                <dd>
                    <img data-src="<?=$cdn_groupon_url?>images/detail.jpg" src="<?=$cdn_groupon_url?>images/detail.jpg" width="100%" alt="">
                </dd>
            </dl>
        </div>
    </div>
    <!-- group open end -->
</div>
<script>
    $(function(){
        MAISHA.DiyMore = function(){
            var aCountDown = $('.count-down-1');
            for (var i=0, len=aCountDown.length; i<len; i++) {
                var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime())/1000;
                MAISHA.countDown(aCountDown[i], leftTime, 0);
            }
        };
        MAISHA.DiyMore();
    })
</script>