<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">添加/编辑角色</h3>
</div>
<div class="form-con">
    <form class="form form-label-130" id="admin-role-form">
        <div class="f-item">
            <label for="roleName">角色名称</label>
            <input type="text" name="roleName" id="roleName" value="<?=empty($item['sName'])?'':$item['sName']?>" placeholder="角色名称" >
        </div>
        <div class="f-item">
            <label for="roleHome">主页节点</label>
            <input type="text" name="roleHome" id="roleHome" value="<?=empty($item['sHomeNode'])?'':$item['sHomeNode']?>" placeholder="主页节点">
        </div>
        <div class="f-item">
            <label for="roleRemark">备注</label>
            <textarea name="roleRemark" id="roleRemark" placeholder="备注" style="width: 460px; height: 100px;"><?=empty($item['sRemark'])?'':$item['sRemark']?></textarea>
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
            <input type="hidden" name="op" value="<?=empty($item)?'add':'edit'?>">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $form = $('#admin-role-form'),
            $cancel = $('.btn-form-cancel', $form),
            validator;

        $cancel.click(function () {
            $.yRedirect('<?=node_url('active/index')?>');
        });

        validator = $form.validate({
            rules: {
                roleName: {required: true, minlength: 2, maxlength: 10, chinese: true},
                roleHome: {required: true, minlength: 3, maxlength: 30, node: true},
                roleRemark: {maxlength: 100}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('active/add')?>';
                } else {
                    url = '<?=node_url('active/edit')?>' + '/<?=empty($item['iActId'])?'':$item['iActId']?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('active/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
    });
</script>
