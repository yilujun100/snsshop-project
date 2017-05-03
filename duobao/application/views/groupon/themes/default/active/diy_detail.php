<div class="viewport v-detail">
    <!-- detail start -->
    <div class="detail">
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
        <div class="grid end-time mt-10">
            <div class="end-time-hd">
                <div class="tit-hr"></div>
                <?php
                    if ($groupon_diy['iState'] == Lib_Constants::GROUPON_DIY_ING || $groupon_diy['iFinished'] != Lib_Constants::GROUPON_DIY_FINISHED) {
                        $start_time = time();
                        $end_time = $groupon_diy['iEndTime'];
                    } else {
                        $start_time = $end_time = time();
                    }
                ?>
                <h3 class="count-down" id="countDown" data-start-time="<?=date('Y/m/d H:i:s', $start_time)?>" data-end-time="<?=date('Y/m/d H:i:s', $end_time)?>" >
                    <span><i class="icon-time"></i>剩余</span>
                    <ul>
                        <li class="day">0</li>
                        <li class="sepera">天</li>
                        <li class="hour">00</li>
                        <li class="sepera">时</li>
                        <li class="min">00</li>
                        <li class="sepera">分</li>
                        <li class="sec">00</li>
                        <li class="sepera">秒</li>
                    </ul>
                </h3>
            </div>
            <?php $this->widget('diy_join', array('diy_join_list'=>$diy_join_list, 'groupon_diy'=>$groupon_diy))?>
        </div>

        <div class="prd-detail mt-10">
            <dl>
                <dd>
                    <img data-src="<?=$cdn_groupon_url?>images/detail.jpg" src="<?=$cdn_groupon_url?>images/detail.jpg" width="100%" alt="">
                </dd>
            </dl>
        </div>
    </div>
    <!-- detail end -->
</div>
<script>
    $(function(){
        <?php if(!empty($groupon_diy['iFinished'])) { ?>
            MAISHA.OpenUrl.shareImg = '<?=$cdn_groupon_url?>images/share_1.png';
        <?php } else { ?>
            MAISHA.OpenUrl.shareImg = '<?=$cdn_groupon_url?>images/share_2.png';
        <? } ?>
        // countDown
        var leftTime = (new Date($('#countDown').attr('data-end-time')).getTime() - new Date($('#countDown').attr('data-start-time')).getTime())/1000;
        MAISHA.countDown('#countDown', leftTime, 1);
        <?php  if(!empty($is_share) && empty($is_load)) { ?>
        MAISHA.share.show();
        <?php } ?>

        $('#groupon_diy_share').on('click', function(){
            MAISHA.share.show();
        });
    })
</script>