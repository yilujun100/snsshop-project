<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="variable_search_key">变量 Key</label>
                <input type="text" name="variable_search_key" id="variable_search_key" value="<?=empty($variable_key)?'':$variable_key;?>" style="width: 250px;">
            </p>
            <p>
                <label for="variable_name">变量名称</label>
                <input type="text" name="variable_search_name" id="variable_search_name" value="<?=empty($variable_name)?'':$variable_name;?>" style="width: 200px;">
            </p>
            <p>
                <label for="variable_search_type">变量类型</label>
                <select name="variable_search_type" id="variable_search_type">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$variable_types as $k => $v) {?>
                        <option value="<?=$k?>" <?=isset($variable_type)&&$k==$variable_type?'selected="selected"':''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'variable/index','button'=>Lib_Constants::BTN_OK,'id'=>'variable-search-do'))?>
                <?php $this->widget('button', array('node'=>'variable/index','button'=>Lib_Constants::BTN_RESET,'id'=>'variable-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'variable/add','button'=>Lib_Constants::BTN_ADD,'id'=>'variable-add'))?>
    </span>
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="15%">变量 Key</th>
            <th width="15%">变量名称</th>
            <th width="45%">变量值</th>
            <th width="10%">变量类型</th>
            <th width="20%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="5">暂无记录</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr data-unique="<?=$v['sKey']?>">
                <td class="text-left"><?=$v['sKey']?></td>
                <td class="text-left"><?=$v['sName']?></td>
                <td class="text-left"><?=cn_substr($v['sValue'], 80)?></td>
                <td><?=Lib_Constants::$variable_types[$v['iType']]?></td>
                <td>
                    <?php $this->widget('button', array('node'=>'variable/edit','button'=>Lib_Constants::BTN_EDIT,'class'=>'variable-edit'))?>
                    <?php $this->widget('button', array('node'=>'variable/delete','button'=>Lib_Constants::BTN_DELETE,'class'=>'variable-delete'))?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="5"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<div class="form-con" id="variable-popup" style="display: none">
    <form class="form" id="variable-form">
        <div class="f-item">
            <label for="variable_type">变量类型</label>
            <select name="variable_type" id="variable_type">
                <option value="-1">请选择</option>
                <?php foreach (Lib_Constants::$variable_types as $k => $v) {?>
                    <option value="<?=$k?>" <?=isset($variable_type)&&$k==$variable_type?'selected="selected"':''?>><?=$v?></option>
                <?php }?>
            </select>
        </div>
        <div class="f-item">
            <label for="variable_key">变量 Key</label>
            <input type="text" name="variable_key" id="variable_key" placeholder="变量 Key">
        </div>
        <div class="f-item">
            <label for="variable_name">变量名称</label>
            <input type="text" name="variable_name" id="variable_name" placeholder="变量名称">
        </div>
        <div class="f-item">
            <label for="variable_value">变量值</label>
            <textarea name="variable_value" id="variable_value" placeholder="变量值" style="width:520px; height: 200px; overflow-y: auto;"></textarea>
        </div>
        <div class="f-item">
            <label for="variable_remark">备注</label>
            <textarea name="variable_remark" id="variable_remark" placeholder="备注" style="width:520px; height: 100px"></textarea>
        </div>
        <input type="hidden" name="op" value="add">
        <input type="hidden" name="variable_unique" value="">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $search_do = $('#variable-search-do'),
            $search_rest = $('#variable-search-reset'),
            $popup = $('#variable-popup').yForm('新增/编辑变量', 960),
            $form = $('#variable-form'),
            $add = $('#variable-add'),
            $edit = $('.variable-edit'),
            $delete = $('.variable-delete'),
            validator;

        $add.click(function(){
            var def = {
                variable_key: '',
                variable_name: '',
                variable_value: '',
                variable_type: -1,
                variable_remark: '',
                variable_unique: '',
                op: 'add'
            };
            validator.resetForm();
            $('#variable_key').yEnable();
            $popup.ySetForm(def).yForm('open');
        });

        $edit.click(function () {
            var $this = $(this),
                unique = $this.closest('tr').attr('data-unique'),
                valObj;
            if (! unique) {
                return;
            }
            $this.yAjax({
                url: '<?=node_url('variable/get_item')?>',
                data: {unique: unique},
                success: function (data) {
                    if (0 === data.retCode) {
                        valObj = {
                            variable_key: data.retData.sKey,
                            variable_name: data.retData.sName,
                            variable_value: data.retData.sValue,
                            variable_type: data.retData.iType,
                            variable_remark: data.retData.sRemark,
                            variable_unique: data.retData.sKey,
                            op: 'edit'
                        };
                        validator.resetForm();
                        $('#variable_key').yDisable();
                        $popup.ySetForm(valObj).yForm('open');
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $delete.click(function () {
            var $this = $(this),
                unique = $this.closest('tr').attr('data-unique');
            if (! unique) {
                return;
            }
            $.yConfirm('确认要删除该变量吗?', function () {
                $this.yAjax({
                    url: '<?=node_url('variable/delete')?>',
                    data: {unique: unique},
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('删除成功');
                            $.yRedirect('<?=node_url('variable/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            });
        });

        validator = $form.validate({
            rules: {
                variable_key: {required: true, minlength: 6, maxlength: 255},
                variable_name: {required: true, minlength: 2, maxlength: 255},
                variable_value: {required: true},
                variable_type: {required: true, select: true},
                variable_remark: {maxlength: 1022}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('variable/add')?>';
                } else {
                    url = '<?=node_url('variable/edit')?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('variable/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });

        $search_do.click(function () {
            var search = {},
                search_str,
                variable_key = $.trim($('#variable_search_key').val()),
                variable_name = $.trim($('#variable_search_name').val()),
                variable_type = $.trim($('#variable_search_type').val());
            if (variable_key) {
                search.variable_key = variable_key;
            }
            if (variable_name) {
                search.variable_name = variable_name;
            }
            if (variable_type >= 0) {
                search.variable_type = variable_type;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('variable/index')?>' + '?' + search_str);
        });
        $search_rest.click(function () {
            $.yRedirect('<?=node_url('variable/index')?>');
        });
    });
</script>