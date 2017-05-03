<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="notify_type">通知类型</label>
                <select name="notify_type" id="notify_type">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$notify_type as $k => $v) {?>
                        <option value="<?=$k?>" <?=$k == $notify_type ? 'selected' : ''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label for="name">模版名称</label>
                <input type="text" name="name" id="name" value="<?=empty($name)?'':$name;?>" style="width: 200px;">
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'deliver/index','button'=>Lib_Constants::BTN_OK,'id'=>'template-search-do'))?>
                <?php $this->widget('button', array('node'=>'deliver/index','button'=>Lib_Constants::BTN_RESET,'id'=>'template-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'template/add','button'=>Lib_Constants::BTN_ADD,'id'=>'template-add'))?>
    </span>
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="5%">模板ID</th>
            <th width="12%">模版名称</th>
            <th width="8%">业务变量</th>
            <th width="10%">通知类型</th>
            <th width="15%" class="table-btn-2">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="9">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
        <tr >
            <td><?=$v['iTempId']?></td>
            <td><?=$v['sName']?></td>
            <td><?=$v['sMsgType']?></td>
            <td>
                <?php foreach (Lib_Constants::$notify_type as $k2 => $v2) {
                    if($k2 == $v['iNotifyType'])
                    {
                        echo $v2;
                        break;
                    }
                }?>
            </td>
            <td data-id="<?=$v['iTempId']?>">
                <?php
                    $this->widget('button', array('node'=>'template/edit','button'=>Lib_Constants::BTN_EDIT,'class'=>'template-edit'));
                    /*$this->widget('button', array('node'=>'template/delete','button'=>Lib_Constants::BTN_DELETE,'class'=>'template-delete'));*/
                ?>
            </td>
        </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="9"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<div class="form-con" id="template-popup" style="display: none">
    <form class="form" id="template-form">
        <div class="f-item">
            <label for="name">模版名称</label>
            <input type="text" name="name" id="name" placeholder="模版名称">
        </div>
        <div class="f-item">
            <label for="msgType">业务变量(唯一)</label>
            <input type="text" name="msgType" id="msgType" placeholder="业务变量">
        </div>
        <div class="f-item">
            <label for="notifyType">通知类型</label>
            <select name="notifyType" id="notifyType">
                <option value="-1">请选择</option>
                <?php foreach (Lib_Constants::$notify_type as $k => $v) {?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php }?>
            </select>
        </div>
        <div class="f-item">
            <label for="extField">模块ID/其他</label>
            <input type="text" name="extField" id="extField" placeholder="模块ID/其他" style="width:400px;">
        </div>
        <div class="f-item">
            <label for="template">模版</label>
            <textarea name="template" id="template" placeholder="模版" style="width:400px; height: 300px;overflow:auto;"></textarea>
        </div>
        <div class="f-item">
            <label for="dataConfig">模版数据</label>
            <textarea name="dataConfig" id="dataConfig" placeholder="模版数据" style="width:400px; height: 100px;overflow:auto;"></textarea>
        </div>
        <div class="f-item">
            <label for="remark">备注</label>
            <textarea name="remark" id="remark" placeholder="备注" style="width:400px; height: 100px"></textarea>
        </div>
        <input type="hidden" name="op" value="add">
        <input type="hidden" name="tempId" value="">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $popup = $('#template-popup').yForm('新增/编辑模版', 700),
            $add = $('#template-add'),
            $edit = $('.template-edit'),
            $delete = $('.template-delete'),
            $form = $('#template-form'),
            $search_do = $('#template-search-do'),
            $search_rest = $('#template-search-reset'),
            validator;

        $add.click(function () {
            var def = {
                name: '',
                msgType: -1,
                notifyType: -1,
                extField: '',
                template: '',
                dataConfig: '',
                remark: '',
                tempId: '',
                op: 'add'
            };
            validator.resetForm();
            $popup.ySetForm(def).yForm('open');
        });

        $edit.click(function () {
            var $this = $(this),
                tempId = $this.parent().attr('data-id'),
                valObj;
            if (! tempId) {
                return;
            }
            $this.yAjax({
                url: '<?=node_url('template/get_template')?>' + '/' + tempId,
                success: function (data) {
                    if (0 === data.retCode) {
                        valObj = {
                            name: data.retData.sName,
                            msgType: data.retData.sMsgType,
                            notifyType: data.retData.iNotifyType,
                            extField: data.retData.sExtField,
                            template: data.retData.sTemplate,
                            dataConfig: data.retData.sDataConfig,
                            remark: data.retData.sRemark,
                            tempId: data.retData.iTempId,
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

        $search_do.click(function () {
            var search = {},
                search_str,
                notify_type = $('#notify_type').val(),
                name = $('#name').val();
            if (notify_type > 0) {
                search.notify_type = notify_type;
            }
            if (name) {
                search.name = name;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('template/index')?>' + '?' + search_str);
        });

        $delete.click(function () {
            var $this = $(this),
                tempId = $this.parent().attr('data-id');
            if (! tempId) {
                return;
            }
            $.yConfirm('确定要删除该模版吗?<br><br>注: 删除后不可恢复!', '确认', function () {
                $this.yAjax({
                    url: '<?=node_url('template/delete')?>' + '/' + tempId,
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

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('template/index')?>');
        });

        validator = $form.validate({
            rules: {
                name: {required: true, minlength: 2, maxlength: 10},
                msgType: {required: true, select: true},
                notifyType: {required: true, select: true},
                template: {required:true},
                dataConfig: {maxlength: 1000},
                extField: {maxlength: 255},
                remark: {maxlength: 100}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('template/add')?>';
                } else {
                    url = '<?=node_url('template/edit')?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('template/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
    });
</script>