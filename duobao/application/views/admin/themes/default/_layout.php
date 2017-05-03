<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=$layout_header?>
<?=$layout_menu?>
<!-- content start -->
<div class="content">
    <!-- breadcrumb -->
    <div class="breadcrumb">
        <ul>
            <?php
            $last = count($admin_position) - 1;
            foreach ($admin_position as $k => $v) {
                if ($k >= $last) {
                    echo '<li class="location-curr">' . $v['name'] . '</li>';
                } else {
                    echo '<li><a href="' . (empty($v['node'])?'javascript:;':$menu_dir . $v['node']) . ' ">' . $v['name'] . '</a></li><li>&gt;</li>';
                }
            }
            ?>
        </ul>
    </div>
    <div class="container">
        <?=$layout_content?>
    </div>
</div>
<!-- content end -->
<?=$layout_footer?>