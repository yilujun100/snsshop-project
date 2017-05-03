<div class="viewport v-detail">
    <div class="detail">
        <div class="grid graphic">
            <img src="<?=$detail['sImg']?>" width="100%" alt="">
            <div class="txt">
                <?=$detail['sContent']?>
            </div>
        </div>

        <!-- bottom -->
        <?php $this->widget('cart_bottom', array('peroid_str'=>$peroid_str))?>
    </div>
</div>

<?php $this->widget('right_icon', array('act_id'=>$act_id)) ?>