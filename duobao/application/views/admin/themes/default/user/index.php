<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'user/add','button'=>Lib_Constants::BTN_ADD,'id'=>'admin-user-add'))?>
    </span>
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="10%">用户名</th>
            <th width="15%">昵称</th>
            <th width="15%">角色</th>
            <th width="15%">状态</th>
            <th width="15%">最后登录时间</th>
            <th width="30%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($user_list['count'] <= 0) {?>
            <tr><td colspan="6">暂无数据</td></tr>
        <?php } else { foreach ($user_list['list'] as $v) { ?>
        <tr>
            <td><?=$v['sName']?></td>
            <td><?=$v['sNickName']?></td>
            <td><?=$v['sRoleName']?></td>
            <td><?=Lib_Constants::$activated_states[$v['iState']]?></td>
            <td><?=empty($v['iLastLoginTime'])?'':date('Y-m-d H:i:s', $v['iLastLoginTime'])?></td>
            <td data-id="<?=$v['iUserId']?>">
                <?php $this->widget('button', array('node'=>'user/add','button'=>Lib_Constants::BTN_EDIT,'class'=>'admin-user-edit'))?>
                <?php
                if (1 == $v['iState']) {
                    $this->widget('button', array('node'=>'user/state','button'=>Lib_Constants::BTN_INACTIVE,'class'=>'admin-user-state','attr'=>array('data-state'=>'inactive')));
                } else {
                    $this->widget('button', array('node'=>'user/state','button'=>Lib_Constants::BTN_ACTIVATED,'class'=>'admin-user-state','attr'=>array('data-state'=>'activated')));
                }
                ?>
                <?php $this->widget('button', array('node'=>'user/add','button'=>Lib_Constants::BTN_DELETE,'class'=>'admin-user-delete'))?>
            </td>
        </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="6"><?php $this->widget('pagination', $user_list)?></td></tr></tfoot>
    </table>
</div>

<div class="form-con" id="admin-popup" style="display: none">
    <form class="form" id="admin-form">
        <div class="f-item">
            <label for="adminUsername">用户名</label>
            <input type="text" name="adminUsername" id="adminUsername" placeholder="用户名">
        </div>
        <div class="f-item">
            <label for="adminNickname">姓名</label>
            <input type="text" name="adminNickname" id="adminNickname" placeholder="昵称">
        </div>
        <div class="f-item">
            <label for="adminRole">角色</label>
            <select name="adminRole">
                <option value="-1">请选择</option>
                <?php foreach ($role_list as $v) {?>
                    <option value="<?=$v['iRoleId']?>"><?=$v['sName']?></option>
                <?php }?>
            </select>
        </div>
        <div class="f-item">
            <label for="adminPassword">密码</label>
            <input type="password" name="adminPassword" id="adminPassword">
        </div>
        <div class="f-item">
            <label></label>
            <span>
                <p>
                    <input type="checkbox"
                           name="adminActivate"
                           value="1"
                        <?=isset($item['iSate']) && 1 == $item['iSate'] ? 'checked="checked"' : ''?>>
                    <label>启用</label>
                </p>
            </span>
        </div>
        <div class="f-item">
            <label for="adminRemark">备注</label>
            <textarea name="adminRemark" id="adminRemark" placeholder="备注" style="width:260px; height: 100px"></textarea>
        </div>
        <input type="hidden" name="op" value="add">
        <input type="hidden" name="user_id" value="">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $popup = $('#admin-popup').yForm('新增/编辑用户', 700),
            $form = $('#admin-form'),
            $add = $('#admin-user-add'),
            $edit = $('.admin-user-edit'),
            $delete = $('.admin-user-delete'),
            $state = $('.admin-user-state'),
            validator;

        $add.click(function () {
            var def = {
                adminUsername: '',
                adminNickname: '',
                adminRole: -1,
                adminPassword: '',
                adminActivate: false,
                adminRemark: '',
                user_id: '',
                op: 'add'
            };
            validator.resetForm();
            $('#adminPassword').rules('add', 'required');
            $('#adminUsername').yEnable();
            $popup.ySetForm(def).yForm('open');
        });

        $edit.click(function () {
            var $this = $(this),
                id = $this.parent().attr('data-id'),
                valObj;
            if (! id) {
                return;
            }
            $this.yAjax({
                url: '<?=node_url('user/get_user')?>' + '/' + id,
                success: function (data) {
                    if (0 === data.retCode) {
                        valObj = {
                            adminUsername: data.retData.sName,
                            adminNickname: data.retData.sNickName,
                            adminRole: data.retData.iRoleId,
                            adminPassword: '',
                            adminActivate: data.retData.iState,
                            adminRemark: data.retData.sRemark,
                            user_id: data.retData.iUserId,
                            op: 'edit'
                        };
                        validator.resetForm();
                        $('#adminPassword').rules('remove', 'required');
                        $('#adminUsername').yDisable();
                        $popup.ySetForm(valObj).yForm('open');
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $state.click(function () {
            var $this = $(this),
                id = $this.parent().attr('data-id'),
                state;
            if (! id) {
                return;
            }
            $this.yAjax({
                url: '<?=node_url('user/state')?>' + '/' + id,
                data: {state: 'activated' == $this.attr('data-state') ? 1 : 0},
                success: function (data) {
                    if (0 === data.retCode) {
                        $.ySuccess('启用/禁用成功');
                        $.yRefresh(1000);
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $delete.click(function () {
            var $this = $(this),
                id = $this.parent().attr('data-id');
            if (! id) {
                return;
            }
            $.yConfirm('确定要删除该用户吗?<br><br>注: 删除后不可恢复!', '确认', function () {
                $this.yAjax({
                    url: '<?=node_url('user/delete')?>' + '/' + id,
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
                adminUsername: {
                    required: true,
                    minlength: 5,
                    maxlength: 12,
                    identifier: true,
                    remote: '<?=node_url('user/name_valid')?>'
                },
                adminNickname: {required: true, minlength: 2, maxlength: 5, chinese: true},
                adminRole: {required: true, select: true},
                adminPassword: {required: true, minlength: 6, maxlength: 13},
                adminRemark: {maxlength: 100}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('user/add')?>';
                } else {
                    url = '<?=node_url('user/edit')?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('user/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
    });
</script>