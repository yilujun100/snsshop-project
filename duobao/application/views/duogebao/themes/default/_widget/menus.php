<?php
if($menus_show) {
    $menus_active_index = isset($menus_active_index) ? $menus_active_index : '';
?>
<div class="fixed-nav clearfix">
    <a href="<?=gen_uri('/home/index')?>" ><i class="icon-nav icon-home"></i>首页</a>
    <a href="<?=gen_uri('/share/index')?>"><i class="icon-nav icon-camera"></i>晒单</a>
    <a href="<?=gen_uri('/activity/index')?>"><i class="icon-nav icon-acti"></i>活动</a>
    <a href="<?=gen_uri('/cart/index')?>" class="nav-cart"><span class="icon-nav icon-cart"><em class="cart-num" id="cartNum"><?=$widget['cart_num']?></em></span><!-- <i class="icon-nav icon-cart"></i> -->清单</a>
    <a href="<?=gen_uri('/my/index')?>"><i class="icon-nav icon-avatar"></i>个人中心</a>
</div>
<?php } ?>