<?=$layout_header?>

<body>
<!--    <div class="content-container">-->
        <?=$layout_content?>
<!--    </div>-->


    <div class="menu-container">
        <?php $this->widget('menus', array('menus_show'=>isset($menus_show)?$menus_show:true)) ?>
    </div>


<!--    <footer class="footer-container">-->
        <?=$layout_footer?>
<!--    </footer>-->
</body>
</html>