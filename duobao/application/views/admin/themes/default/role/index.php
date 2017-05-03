<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'user/add','button'=>Lib_Constants::BTN_ADD,'id'=>'admin-role-add'))?>
    </span>
    <table class="table  mt-10 fr">
        <thead>
        <tr>
            <th width="20%">角色ID</th>
            <th width="30%">角色名称</th>
            <th width="20%">首页</th>
            <th width="30%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($role_list['count'] <= 0) {?>
            <tr><td colspan="4">暂无数据</td></tr>
        <?php } else { foreach ($role_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['iRoleId']?></td>
                <td><?=$v['sName']?></td>
                <td><?=$v['sHomeNode']?></td>
                <td data-id="<?=$v['iRoleId']?>">
                    <?php $this->widget('button', array('node'=>'role/edit','button'=>Lib_Constants::BTN_EDIT,'class'=>'admin-role-edit'))?>
                    <?php $this->widget('button', array('node'=>'role/purview','button'=>Lib_Constants::BTN_PURVIEW,'class'=>'admin-role-purview'))?>
                    <?php $this->widget('button', array('node'=>'role/delete','button'=>Lib_Constants::BTN_DELETE,'class'=>'admin-role-delete'))?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4"><?php $this->widget('pagination', $role_list)?></td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="form-con" id="admin-role-popup" style="display: none">
    <form class="form form-label-130" id="admin-role-form">
        <div class="f-item">
            <label for="roleName">角色名称</label>
            <input type="text" name="roleName" id="roleName" value="<?=empty($item['sName'])?'':$item['sName']?>" placeholder="角色名称" >
        </div>
        <div class="f-item">
            <label for="roleHome">首页</label>
            <select name="roleHome" id="roleHome">
                <?php foreach ($home_node as $k => $v) {?>
                    <option value="<?=$k?>"><?=$v['name']?></option>
                <?php }?>
            </select>
            <p class="form-item-tips">注：如不清楚，可选择「默认首页」</p>
        </div>
        <div class="f-item">
            <label for="roleRemark">备注</label>
            <textarea name="roleRemark" id="roleRemark" placeholder="备注" style="width: 260px; height: 80px;"><?=empty($item['sRemark'])?'':$item['sRemark']?></textarea>
        </div>
        <input type="hidden" name="op" value="<?=empty($item)?'add':'edit'?>">
        <input type="hidden" name="role_id" value="">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $popup = $('#admin-role-popup').yForm('新增/编辑角色', 720),
            $form = $('#admin-role-form'),
            validator;

        $('#admin-role-add').click(function () {
            var def = {
                roleName: '',
                roleHome: '',
                roleRemark: '',
                role_id: '',
                op: 'add'
            };
            validator.resetForm();
            $popup.ySetForm(def).yForm('open');
        });

        $('.admin-role-edit').click(function () {
            var $this = $(this),
                id = $this.parent().attr('data-id'),
                valObj;
            if (! id) {
                return;
            }
            $this.yAjax({
                url: '<?=node_url('role/get_role')?>' + '/' + id,
                success: function (data) {
                    if (0 === data.retCode) {
                        valObj = {
                            roleName: data.retData.roleName,
                            roleHome: data.retData.roleHome,
                            roleRemark: data.retData.roleRemark,
                            role_id: data.retData.roleId,
                            op: 'edit'
                        };
                        validator.resetForm();
                        $popup.ySetForm(valObj).yForm('open');
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $('.admin-role-purview').click(function () {
            var $this = $(this),
                id = $this.parent().attr('data-id');
            if (! id) {
                return;
            }
            $.yRedirect('<?=node_url('role/purview')?>' + '/' + id);
        });

        $('.admin-role-delete').click(function () {
            var $this = $(this),
                id = $.yToInt($this.parent().attr('data-id'));
            if (id < 1) {
                return;
            }
            $.yConfirm('确定要删除该角色吗?<br><br>注: 删除后不可恢复', '确认', function () {
                $this.yAjax({
                    url: '<?=node_url('role/delete')?>' + '/' + id,
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('删除成功');
                            $.yRefresh(1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            });
        });

        validator = $form.validate({
            rules: {
                roleName: {required: true, minlength: 2, maxlength: 10, chinese: true},
                roleHome: {required: true, select: true},
                roleRemark: {maxlength: 100}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('role/add')?>';
                } else {
                    url = '<?=node_url('role/edit')?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('role/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
    });
</script>