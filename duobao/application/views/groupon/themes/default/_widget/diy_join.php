<?php
//开团参团记录
!isset($diy_join_list['list']) OR $diy_join_list = $diy_join_list['list'];
if (!empty($diy_join_list) && !empty($groupon_diy)) {
?>
    <div class="end-time-con mt-10">
        <ul class="join-list clearfix">
<?php
    $i = 0;
    foreach ($diy_join_list as $item) {
?>
            <li class="group-header">
                <div class="join-member-avatar">
                    <img src="<?=$item['sHeadImg']?>" width="48" height="48" alt="">
                    <?php if($item['iIsColonel']) { ?><em>团长</em><?php } ?>
                </div>
            </li>
<?php
        $i++;
    }
    if ($groupon_diy['iPeopleNum'] >$i) {
        $len = $groupon_diy['iPeopleNum'] -$i;
        for($i=0; $i<$len; $i++) {
?>
        <li>
            <div class="join-member-avatar">
                <img src="<?=$cdn_groupon_url?>images/icon_avatar_default.png" width="32" height="32" alt="">
            </div>
        </li>
<?php
        }
    }
?>
        </ul>
        <?php if ($groupon_diy['iBuyNum'] >= $groupon_diy['iPeopleNum']) { ?>
            <p>拼团成功了，团长威武～</p>
        </div>
        <div class="status status-success"></div>
        <?php } elseif ($groupon_diy['iEndTime'] <= time()) { //已结束 没有成团 ?>
            <p>真遗憾，拼团失败了，已支付的金额将会原路退回哦～</p>
        </div>
        <div class="status status-failure"></div>
        <?php } else { ?>
            <p>还差<strong><?=($groupon_diy['iPeopleNum'] - $groupon_diy['iBuyNum'])?></strong>人，快去召唤小伙伴！</p>
        </div>
        <?php } ?>
<?php } ?>