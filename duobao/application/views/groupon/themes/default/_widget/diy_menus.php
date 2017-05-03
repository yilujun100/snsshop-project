<?php
//拼团开团参团流程中的底部菜单
if($menus_show) {
    $menus_active_index = isset($menus_active_index) ? $menus_active_index : '';
?>
<div class="fixed-bott">
    <div class="bott-entry">
        <ul class="clearfix">
            <?php
            if(!empty($widget['base_node'])) {
                foreach ($widget['base_node'] as $node) {
            ?>
                    <li><a href="<?=empty($node['node_url']) ? 'javascript:;' : $node['node_url']?>"><i class="<?=$node['node_class']?>"></i><?=$node['node_name']?></a></li>
            <?php
                }
            }
            ?>
        </ul>
    </div>
    <div class="btn-groups">
        <!-- @后台: 当库存不足时,显示库存不足按钮 -->
        <?php
        if (!empty($widget['extend_node'])) {
            foreach ($widget['extend_node'] as $node) {
        ?>
             <a href="<?=empty($node['node_url']) ? 'javascript:;' : $node['node_url']?>" id="<?=empty($node['node_id']) ? '' : $node['node_id']?>" class="<?=$node['node_class']?>"><?=$node['node_name']?></a>
        <?php
            }
        }
        ?>
    </div>
</div>
<?php } ?>

