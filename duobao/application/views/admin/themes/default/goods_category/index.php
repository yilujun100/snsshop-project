<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'goods_category/add','button'=>Lib_Constants::BTN_ADD,'id'=>'cate-add'))?>
    </span>
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="15%">类目ID</th>
            <th width="30%">类目名称</th>
            <th width="10%">类目级别</th>
            <th width="15%">是否显示</th>
            <th width="30%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($cate_list)) {?>
            <tr><td colspan="6">暂无数据</td></tr>
        <?php } else { foreach ($cate_list as $v) { ?>
            <tr>
                <td><?=$v['iCateId']?></td>
                <td class="text-left"><?=str_repeat('————', ($v['iLvl'] - 1) * 2) . $v['sName']?></td>
                <td><?=$v['iLvl']?></td>
                <td><?=Lib_Constants::$show_states[$v['iIsShow']]?></td>
                <td data-id="<?=$v['iCateId']?>">
                    <?php $this->widget('button', array('node'=>'goods_category/edit','button'=>Lib_Constants::BTN_EDIT,'class'=>'cate-edit-btn'))?>
                    <?php
                    if ($v['iIsShow']) {
                        $this->widget('button', array('node' => 'goods_category/hide', 'button' => Lib_Constants::BTN_HIDE,'class'=>'cate-hide-btn'));
                    } else {
                        $this->widget('button', array('node' => 'goods_category/hide', 'button' => Lib_Constants::BTN_SHOW,'class'=>'cate-show-btn'));
                    }?>
                    <?php $this->widget('button', array('node'=>'goods_category/delete','button'=>Lib_Constants::BTN_DELETE,'class'=>'cate-del-btn'))?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
    </table>
</div>

<div class="form-con" id="cate-popup" style="display: none">
    <form class="form" id="cate-form">
        <div class="f-item">
            <label for="cateName">类目名称</label>
            <input type="text" name="cateName" id="cateName" placeholder="类目名称">
        </div>
        <div class="f-item">
            <label for="cateHide">是否隐藏</label>
            <span><p><input type="checkbox" name="cateHide" id="cateHide"></p></span>
        </div>
        <div class="f-item">
            <label for="cateSort">显示顺序</label>
            <input type="text" name="cateSort" id="cateSort" value="">
        </div>
        <?php $this->widget('category', array('top_cate'=>$top_cate,'parent'=>1))?>
        <div class="f-item">
            <label for="cateRemark">备注</label>
            <textarea name="cateRemark" id="cateRemark" placeholder="备注" style="width:260px; height: 100px"></textarea>
        </div>
        <input type="hidden" name="op" value="add">
        <input type="hidden" name="cate_id" value="cate_id">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var validator,
            $form = $('#cate-form'),
            popup = $('#cate-popup').yForm('新增/编辑类目'),
            $add = $('#cate-add'),
            $edit = $('.cate-edit-btn'),
            $del = $('.cate-del-btn'),
            $hide = $('.cate-hide-btn'),
            $show = $('.cate-show-btn');

        $add.click(function () {
            var defVal = {cateName: '',cateHide: '',cateSort: '',cateLvl1: 0,cateRemark:'',cate_id:''};
            $('.cate-select').yEnable();
            if (validator) {
                validator.resetForm();
            }
            $form.ySetForm(defVal, true).find('input[name="op"]').val('add');
            popup.yForm('open');
        });

        $edit.click(function () {
            var $this = $(this),
                cate_id = parseInt($this.closest('td').attr('data-id'), 10),
                valObj;
            if (! cate_id) {
                return;
            }
            $this.yAjax({
                url: '<?=node_url('goods_category/get_cate')?>',
                data: {cate_id: cate_id},
                success: function (data) {
                    var retData;
                    if (0 === data.retCode) {
                        retData = data.retData;
                        setParents(retData.parents);
                        valObj = {};
                        valObj.cateName = retData.sName;
                        if (1 == retData.iIsShow) {
                            valObj.cateHide = '';
                        } else {
                            valObj.cateHide = 'checked';
                        }
                        valObj.cateSort = retData.iSort;
                        valObj.cateRemark = retData.sRemark;
                        valObj.cate_id = retData.iCateId;
                        $form.ySetForm(valObj);
                        if (validator) {
                            validator.resetForm();
                        }
                        $form.find('input[name="op"]').val('edit');
                        popup.yForm('open');
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $del.click(function () {
            var $this = $(this),
                cate_id = parseInt($this.closest('td').attr('data-id'), 10),
                confirm_tips = '确认要删除该类目吗？<br><br>将删除该类目及其全部子类目！';
            if (! cate_id) {
                return;
            }
            $.yConfirm(confirm_tips, function () {
                $this.yAjax({
                    url: '<?=node_url('goods_category/delete')?>',
                    data: {cate_id: cate_id},
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

        $show.click(function () {
            var $this = $(this),
                cate_id = parseInt($this.closest('td').attr('data-id'), 10),
                confirm_tips = '确认要显示该类目吗？';
            if (! cate_id) {
                return;
            }
            $.yConfirm(confirm_tips, function () {
                $this.yAjax({
                    url: '<?=node_url('goods_category/show')?>',
                    data: {cate_id: cate_id},
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('操作成功');
                            $.yRefresh(1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            });
        });

        $hide.click(function () {
            var $this = $(this),
                cate_id = parseInt($this.closest('td').attr('data-id'), 10),
                confirm_tips = '确认要隐藏该类目吗？';
            if (! cate_id) {
                return;
            }
            $.yConfirm(confirm_tips, function () {
                $this.yAjax({
                    url: '<?=node_url('goods_category/hide')?>',
                    data: {cate_id:cate_id},
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('操作成功');
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
                cateName: {required: true, maxlength: 10},
                cateSort: {digits: true},
                cateRemark: {maxlength: 100}
            },
            messages: {
                cateName: {required: '类目名称不能为空', maxlength: '类目名称不超过10个字符'},
                cateSort: {digits: '显示顺序必须为整数'},
                cateRemark: {maxlength: '备注不超过100个字符'}
            },
            submitHandler: function () {
                var op = $form.find('input[name="op"]').val();
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                $form.yAjax({
                    url: '<?=node_url('goods_category/')?>' + op,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('新增/编辑成功');
                            $.yRefresh(1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });

        function setParents(parents) {
            $('.cate-select').not('#cateLvl1').empty();
            $.each(parents, function (i, v) {
                var option;
                if (0 == i) {
                    $form.find('#cateLvl1').val(v.iCateId);
                } else {
                    option = '<option value="' + v.iCateId + '">' + v.sName + '</option>';
                    $form.find('#cateLvl' + v.iLvl).html(option).val(v.iCateId);
                }
            });
            $('.cate-select').yDisable();
        }
    });
</script>
