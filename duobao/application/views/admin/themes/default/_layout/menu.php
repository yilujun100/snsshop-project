<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- sidebar start -->
<div class="sidebar">
    <?php
    foreach ($admin_menus as $admin_menu) {
        if (empty($admin_menu['current'])) {
            continue;
        }
        $slide_menu = $admin_menu;
    }
    foreach ($slide_menu['sub'] as $v) {
    ?>
        <dl>
            <dt <?=empty($v['current'])?'':'class="active"'?>><i class="icon-nav icon-gift"></i><?=$v['name']?><i class="arrow-down arrow-down-on"></i></dt>
            <dd <?=empty($v['current'])?'':'style="display: block;"'?>>
                <?php foreach ($v['sub'] as $vv) {?>
                <a href="<?=empty($vv['node'])?'javascript:;':$menu_dir . $vv['node']?>" <?=empty($vv['current'])?'':'class="sub-active"'?>><?=$vv['name']?></a>
                <?php }?>
            </dd>
        </dl>
    <?php }?>
</div>
<!-- sidebar end -->