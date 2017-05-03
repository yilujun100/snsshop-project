<?php
//活动开团列表
!isset($diy_list['list']) OR $diy_list = $diy_list['list'];
if (!empty($diy_list)) {
    $now = time();
    foreach ($diy_list as $item) {
        $sign = gen_sign($item['iUin'], $item['iDiyId']);
?>
        <li>
            <div class="user-avatar"><img src="<?=$item['sHeadImg']?>" width="46" height="46" alt=""></div>
            <div class="parti-cell">
                <em class="user-name"><?=$item['sNickName']?></em>
                    <span>
                        <p>还差<?=($item['iPeopleNum'] >$item['iBuyNum']) ? intval($item['iPeopleNum'] - $item['iBuyNum']) : 0;?>人成团</p>
                        <p>剩余 <strong class="count-down-1" data-start-time="<?=date('Y/m/d H:i:s', $now)?>" data-end-time="<?=date('Y/m/d H:i:s', $item['iEndTime'])?>"><label class="hour">00</label>:<label class="min">00</label>:<label class="sec">00</label></strong> 结束</p>
                    </span>
            </div>
            <a href="<?=gen_uri('/active/diy_detail', array('diy_id'=>$item['iDiyId'], 'sign'=>$sign), 'groupon')?>" class="btn-parti">去凑团</a>
        </li>
<?php
    }
} ?>