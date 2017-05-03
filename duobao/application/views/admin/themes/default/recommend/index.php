<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="active_id">夺宝单ID</label>
                <input type="text" name="active_id" id="active_id" value="<?=empty($active_id)?'':$active_id;?>">
            </p>
            <p>
                <label for="active_state">状态</label>
                <select name="active_state" id="active_state">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$publish_states as $k => $v) {?>
                        <option value="<?=$k?>" <?=$k == $active_state ? 'selected' : ''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'active/index','button'=>Lib_Constants::BTN_OK,'id'=>'admin-advert_item-search-do'))?>
                <?php $this->widget('button', array('node'=>'active/index','button'=>Lib_Constants::BTN_RESET,'id'=>'admin-advert_item-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'advert_item/add','button'=>Lib_Constants::BTN_ADD,'id'=>'admin-recommend-add'))?>
    </span>
    <table class="table  mt-10 fr">
        <thead>
        <tr>
            <th width="10%">夺宝单ID</th>
            <th width="10%">商品ID</th>
            <th width="30%">商品名称</th>
            <th width="10%">单个夺宝码价格</th>
            <th width="10%">推荐权重</th>
            <th width="10%">状态</th>
            <th width="20%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="7">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['iActId']?></td>
                <td><?=$v['iGoodsId']?></td>
                <td class="text-left"><?=$v['sGoodsName']?></td>
                <td><?=price_format($v['iCodePrice'])?></td>
                <td><?=$v['iRecWeight']?></td>
                <td><?=Lib_Constants::$publish_states[$v['iState']]?></td>
                <td data-act-id="<?=$v['iActId']?>" data-weight="<?=$v['iRecWeight']?>">
                    <?php $this->widget('button', array('node'=>'recommend/edit','button'=>Lib_Constants::BTN_EDIT_WEIGHT,'class'=>'admin-recommend-edit'))?>
                    <?php $this->widget('button', array('node'=>'recommend/cancel','button'=>Lib_Constants::BTN_CANCEL_RECOMMEND,'class'=>'admin-recommend-cancel'))?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="7"><?php $this->widget('pagination', $result_list)?></td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="form-con" id="admin-recommend-popup" style="display: none">
    <form class="form form-label-130" id="admin-recommend-form">
        <div class="f-item">
            <label for="act_id">夺宝单ID</label>
            <input type="text" name="act_id" id="act_id" value="" placeholder="夺宝单ID">
        </div>
        <div class="f-item">
            <label for="rec_weight">权重</label>
            <input type="text" name="rec_weight" id="rec_weight" value="1" placeholder="权重">
        </div>
        <input type="hidden" name="op" value="add">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $add = $('#admin-recommend-add'),
            $edit = $('.admin-recommend-edit'),
            $cancel = $('.admin-recommend-cancel'),
            $search_do = $('#admin-advert_item-search-do'),
            $search_rest = $('#admin-advert_item-search-reset'),
            $popup = $('#admin-recommend-popup').yForm('添加/编辑推荐'),
            $form = $('#admin-recommend-form'),
            validator;

        $add.click(function () {
            var def = {
                act_id: '',
                rec_weight: 1,
                op: 'add'
            };
            validator.resetForm();
            $('#act_id').yEnable();
            $popup.ySetForm(def).yForm('open');
        });

        $edit.click(function () {
            var $this = $(this),
                $data = $this.parent(),
                valObj = {
                    act_id: $data.attr('data-act-id'),
                    rec_weight: $data.attr('data-weight'),
                    op: 'edit'
                };
            if (valObj.act_id < 1) {
                return;
            }
            $('#act_id').yDisable();
            $popup.ySetForm(valObj).yForm('open');
        });

        $cancel.click(function () {
            var $this = $(this),
                act_id = $this.parent().attr('data-act-id');
            if (act_id < 1) {
                return;
            }
            $.yConfirm('确认要取消推荐吗？', '确认', function () {
                $this.yAjax({
                    url: '<?=node_url('recommend/cancel')?>',
                    data: {act_id: act_id},
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.yError('取消推荐成功');
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
                act_id: {required: true, digits: true, min: 1, max: 9999999, remote: '<?=node_url('recommend/check')?>'},
                rec_weight: {required: true, digits: true, min: 1, max: 9999999}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url, data;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('recommend/add')?>';
                } else {
                    url = '<?=node_url('recommend/edit')?>';
                }
                $('#act_id').yEnable();
                data = $form.serialize();
                $('#act_id').yDisable();
                $form.yAjax({
                    url: url,
                    data: data,
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRefresh(1000);
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
                active_id = $('#active_id').val(),
                active_state = $('#active_state').val();
            if (active_id > 0) {
                search.active_id = active_id;
            }
            if (active_state > -1) {
                search.active_state = active_state;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('recommend/index')?>' + '?' + search_str);
        });

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('recommend/index')?>');
        });
    });
</script>