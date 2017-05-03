<?=$layout_header?>

<body>
    <div class="content-container">
        <?=$layout_content?>
    </div>

    <div class="menu-container">
<?php
    $menus = isset($menus) ? $menus : 'menus';
    $this->widget($menus, array('menus_show'=>isset($menus_show)?$menus_show:true))
?>
    </div>

    <!-- acti end start -->
    <div class="pop-mask"></div>
    <div class="pop-window pop-acti-end">
        <div class="pop-acti-end-content">
            <p>活动已结束，<br>敬请关注微团购其他活动。</p>
        </div>
        <div class="pop-acti-end-btns">
            <a href="http://2w.gaopeng.com">&nbsp;&nbsp;微商城&nbsp;&nbsp;</a>
            <a href="http://duogebao.gaopeng.com/duogebao/home">百分好礼</a>
        </div>
    </div>
    <!-- acti end end -->

    <footer class="footer-container">
        <?=$layout_footer?>
    </footer>
</body>
</html>