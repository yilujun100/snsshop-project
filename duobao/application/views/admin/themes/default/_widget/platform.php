<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php
if (isset($platform_list)) {?>
    <select name="platform_select" id="platform_select">
        <option value="-1">请选择</option>
        <?php foreach ($platform_list as $k => $v) {?>
            <option value="<?=$k?>" <?=isset($current_platform)&&$current_platform==$k?'selected="selected"':''?>><?=$v?></option>
        <?php }?>
    </select>
<?php }?>