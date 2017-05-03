<?php
if($menus_show) {
    $menus_active_index = isset($menus_active_index) ? $menus_active_index : '';
?>
<div class="fixed-bott-menu clearfix">
    <a href="<?=node_url('/home/index')?>" <?=($menus_active_index == 1 ? 'class="active"' : '')?>">为爱拼团</a>
    <a href="<?=node_url('/my/groupons')?>" <?=($menus_active_index == 2 ? 'class="active"' : '')?>">我的团</a>
    <a href="<?=node_url('/order/index')?>" <?=($menus_active_index == 3 ? 'class="active"' : '')?>">我的订单</a>
</div>
<?php } ?>