<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php
if (isset($position_list)) {?>
    <select name="advert_position" id="advert_position">
        <option value="-1">请选择</option>
        <?php foreach ($position_list as $v) {?>
            <option value="<?=$v['iPositionId']?>" <?=isset($advert_position)&&$advert_position==$v['iPositionId']?'selected="selected"':''?>><?=$v['sName']?></option>
        <?php }?>
    </select>
<?php }?>