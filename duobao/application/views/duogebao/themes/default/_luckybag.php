<?=$layout_header?>

<body <?php if(!empty($body_tab_init)) {?>onload="tabInit()"<?php }?>>
    <div class="content-container">
        <?=$layout_content?>
    </div>

    <div class="menu-container">
        <div class="menu-container">
            <div class="fixed-nav clearfix">
                <a href="<?=gen_uri('/home/index')?>" ><i class="icon-nav icon-home"></i>首页</a>
                <a href="<?=gen_uri('/share/index')?>"><i class="icon-nav icon-camera"></i>晒单</a>
                <a href="<?=gen_uri('/activity/index')?>"><i class="icon-nav icon-gift"></i>活动</a>
                <a href="<?=gen_uri('/cart/index')?>" class="nav-cart"><span class="icon-nav icon-cart"></span><!-- <i class="icon-nav icon-cart"></i> -->清单</a>
                <a href="<?=gen_uri('/my/index')?>"><i class="icon-nav icon-avatar"></i>个人中心</a>
            </div>
        </div>
    </div>
    <?php $this->widget('weixin_share', array('signPackage'=>isset($signPackage)?$signPackage:array(), 'shareData'=>isset($shareData) ? $shareData:array())) ?>
    <footer class="footer-container">
        <?=$layout_footer?>
    </footer>
</body>
</html>