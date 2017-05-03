<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">角色授权</h3>
</div>

<div class="form-con">
    <form class="form" id="admin-purview">
        <div class="f-item">
            <div class="treelist clearfix">
                <?php foreach ($admin_menu as $v) {?>
                    <div class="treelist-item">
                        <p class="has-sub">
                            <input type="checkbox" class="select-parent">
                            <label><?=$v['name']?></label>
                        </p>
                        <ul class="lvl1-ul">
                        <?php if (empty($v['sub'])) {continue;}foreach ($v['sub'] as $vv) {?>
                            <li>
                            <p class="has-sub">
                                <input type="checkbox" class="select-sub">
                                <label><?=$vv['name']?></label>
                            </p>
                                <ul class="lvl2-ul">
                                    <?php if (empty($vv['sub'])) {continue;} foreach ($vv['sub'] as $vvv) {?>
                                    <li>
                                        <p><input type="checkbox" class="select-lv3" /><label><?=$vvv['name']?></label></p>
                                        <ul class="treelist-item-inner clearfix lvl3-ul">
                                            <?php if (empty($vvv['sub'])) {continue;} foreach ($vvv['sub'] as $vvvv) {?>
                                                <li>
                                                    <p class="select-lv4">
                                                        <input type="checkbox" class="select-lv4" name="purview[]" value="<?=$vvvv['node']?>" <?=is_granted($vvvv['node'], $item['iRoleId'])?'checked="checked"':''?>>
                                                        <?php if (isset($vvvv['depend'])) {foreach ($vvvv['depend'] as $node) {?>
                                                        <input type="checkbox" class="select-lv4 depend hide" name="purview[]" value="<?=$node?>" <?=is_granted($node, $item['iRoleId'])?'checked="checked"':''?>>
                                                        <?php }}?>
                                                        <label><?=$vvvv['name']?></label>
                                                    </p>
                                                </li>
                                            <?php }?>
                                        </ul>
                                    </li>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php }?>
                        </ul>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function(){

        var $form = $('#admin-purview');

        $form.submit(function (event) {
            var $node = $('input[name="purview[]"]:checked', $form);
            event.preventDefault();
            if ($node.length < 1) {
                $.yError('请至少选择要给节点');
                return;
            }
            $form.yAjax({
                url: '<?=node_url('role/purview') . '/' . $item['iRoleId']?>',
                data: $node.serialize(),
                success: function (data) {
                    if (0 === data.retCode) {
                        $.ySuccess('授权成功');
                        $.yRedirect('<?=node_url('role/index')?>', 1000);
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $('.select-parent').on('click', function(){
            if ($(this).is(':checked')) {
                $(this).parent().siblings().children().find(':checkbox').prop('checked', true);
            } else {
                $(this).parent().siblings().children().find(':checkbox').prop('checked', false);
            }
        });
        $('.select-sub').on('click', function(){
            if ($(this).is(':checked')) {
                $(this).parent().siblings().children().find(':checkbox').prop('checked', true);
            } else {
                $(this).parent().siblings().children().find(':checkbox').prop('checked', false);
            }
        });
        $('.select-lv3').on('click', function(){
            if ($(this).is(':checked')) {
                $(this).parent().siblings().children().find(':checkbox').prop('checked', true);
            } else {
                $(this).parent().siblings().children().find(':checkbox').prop('checked', false);
            }
        });
        $('.select-lv4 input:checkbox:not(".depend")').on('click', function(){
            console.log($(this).siblings(':checkbox'));
            if ($(this).is(':checked')) {
                $(this).siblings(':checkbox').prop('checked', true);
            } else {
                $(this).siblings(':checkbox').prop('checked', false);
            }
        });
        $('.lvl3-ul').each(function () {
            var $this = $(this),
                $lvl4Checkbox = $this.find('input:checkbox');
            if ($lvl4Checkbox.length === $lvl4Checkbox.filter(':checked').length) {
                $this.prev().children('input:checkbox').prop('checked', true);
            }
        });
        $('.lvl2-ul').each(function () {
            var $this = $(this),
                $lvl3Checkbox = $this.find('input:checkbox');
            if ($lvl3Checkbox.length === $lvl3Checkbox.filter(':checked').length) {
                $this.prev().children('input:checkbox').prop('checked', true);
            }
        });
        $('.lvl1-ul').each(function () {
            var $this = $(this),
                $lvl2Checkbox = $this.find('input:checkbox');
            if ($lvl2Checkbox.length === $lvl2Checkbox.filter(':checked').length) {
                $this.prev().children('input:checkbox').prop('checked', true);
            }
        });
        $('.btn-form-cancel').click(function () {
            $.yRedirect('<?=node_url('role/index')?>');
        });
    });
</script>