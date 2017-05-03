<div class="viewport v-activity">
    <?php
        if(empty($activity_advert))
        {
    ?>
            <div class="acti-empty">
                <i class="icon-acti-empty"></i>
                <h3>暂无相关记录哦~</h3>
                <p>赶紧去参加活动吧~</p>
                <a href="<?=gen_uri('/home/index')?>" class="btn btn-default-2 btn-e-join-now"><span>立即参与</span></a>
            </div>
    <?php
        }
        else
        {
            foreach ($activity_advert as $v) {
    ?>
                <div class="acti-item <?=$v['iEndTime']<time()?'acti-end mt-10':''?>">
                    <div class="acti-thumb">
                        <a href="<?=$v['sTarget']?>"><img src="<?=$v['sImg']?>" width="100%" alt=""></a>
                        <?php if ($v['iEndTime']<time()) {?>
                            <div class="acti-end-tips">
                                <h3>活动已结束</h3>
                            </div>
                        <?php }?>
                    </div>
                    <div class="acti-info">
                        <h3 class="acti-name"><?=$v['sTitle']?></h3>
                        <p class="acti-desc"><?=$v['sDesc']?></p>
                        <p class="acti-time">活动时间：<?=date(DATE_FORMATTER, $v['iBeginTime'])?> - <?=date(DATE_FORMATTER, $v['iEndTime'])?></p>
                    </div>
                </div>
    <?php
            }
        }
    ?>
</div>