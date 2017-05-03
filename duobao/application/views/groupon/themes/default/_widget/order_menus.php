<div class="bott-btn">
    <?php $btn_count=0; foreach ($detail['btns'] as $v) { if ('detail'==$v['op']) {continue;}?>
        <a href="<?=$v['url']?>" class="btn-detail btn-detail-<?=$v['btn']?>" <?php if (!empty($v['data-url'])){?>data-url="<?=$v['data-url']?>" data-op="<?=$v['op']?>" data-id="<?=$detail['sOrderId']?>"<?php }?>><?=$v['text']?></a>
    <?php $btn_count++;} if ($btn_count < 1) {?>
        <a href="<?=node_url('active/detail')."?gid={$detail['iGrouponId']}&spec_id={$detail['iSpecId']}"?>" class="btn-detail btn-detail-warning">去开团</a>
    <?php }?>
</div>