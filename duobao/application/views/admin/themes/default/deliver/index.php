<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="deliver_order">订单ID</label>
                <input type="text" name="deliver_order" id="deliver_order" value="<?=empty($deliver_order)?'':$deliver_order;?>" style="width: 250px;">
            </p>
            <p>
                <label for="deliver_uin">用户 ID</label>
                <input type="text" name="deliver_uin" id="deliver_uin" value="<?=empty($deliver_uin)?'':$deliver_uin;?>" style="width: 200px;">
            </p>
            <p>
                <label for="deliver_type">订单类型</label>
                <select name="deliver_type" id="deliver_type">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$order_type_deliver_duobao as $k => $v) {?>
                        <option value="<?=$k?>" <?=isset($deliver_type)&&$k==$deliver_type?'selected':''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'deliver/index','button'=>Lib_Constants::BTN_OK,'id'=>'deliver-search-do'))?>
                <?php $this->widget('button', array('node'=>'deliver/index','button'=>Lib_Constants::BTN_RESET,'id'=>'deliver-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="12%">订单ID</th>
            <th width="8%">订单类型</th>
            <th width="10%">商品图</th>
            <th width="5%">商品ID</th>
            <th width="25%">商品名称</th>
            <th width="11%">用户 ID</th>
            <th width="7%">发货状态</th>
            <th width="7%">收货状态</th>
            <th width="15%" style="min-width: 180px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="9">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
        <tr data-id="<?=$v['iAutoId']?>">
            <td><?=$v['sOrderId']?></td>
            <td><?=Lib_Constants::$order_type[$v['iType']]?></td>
            <td><img src="<?=$v['sGoodsImg']?>" style="width: 100px; height: 100px" alt=""></td>
            <td><?=$v['iGoodsId']?></td>
            <td><?=$v['sGoodsName']?></td>
            <td><?=$v['iUin']?></td>
            <td><?=Lib_Constants::$deliver_status[$v['iDeliverStatus']]?></td>
            <td><?=Lib_Constants::$deliver_confirm[$v['iConfirmStatus']]?></td>
            <td>
                <?php if (Lib_Constants::DELIVER_NOT_CONFIRM_STATUS == $v['iDeliverStatus']) {
                    $this->widget('button', array('node'=>'deliver/confirm','button'=>Lib_Constants::BTN_DELIVER_CONFIRM,'class'=>'deliver_confirm'));
                }
                $this->widget('button', array('node'=>'deliver/detail','button'=>Lib_Constants::BTN_DETAIL,'class'=>'deliver_detail'));
                ?>
            </td>
        </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="9"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<div class="form-con" id="admin-popup" style="display: none">
    <form class="form" id="admin-form">
        <div class="f-item">
            <label for="deliver_express">快递公司</label>
            <select name="deliver_express" id="deliver_express">
                <option value="-1">请选择</option>
                <?php foreach ($express_list as $v) {?>
                    <option value="<?=$v['iExpId']?>"><?=$v['sName']?></option>
                <?php }?>
            </select>
        </div>
        <div class="f-item">
            <label for="deliver_express_num">快递单号</label>
            <input type="text" name="deliver_express_num" id="deliver_express_num" placeholder="快递单号">
        </div>
        <input type="hidden" name="deliver_id" value="">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $search_do = $('#deliver-search-do'),
            $search_rest = $('#deliver-search-reset'),
            $detail = $('.deliver_detail'),
            $popup = $('#admin-popup').yForm('确认发货', 650),
            $form = $('#admin-form'),
            $confirm = $('.deliver_confirm');

        $search_do.click(function () {
            var search = {},
                search_str,
                deliver_order = $.trim($('#deliver_order').val()),
                deliver_uin = $.trim($('#deliver_uin').val()),
                deliver_type = $.trim($('#deliver_type').val());
            if (deliver_order) {
                search.deliver_order = deliver_order;
            }
            if (deliver_uin) {
                search.deliver_uin = deliver_uin;
            }
            if (deliver_type > -1) {
                search.deliver_type = deliver_type;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('deliver/index')?>' + '?' + search_str);
        });
        $search_rest.click(function () {
            $.yRedirect('<?=node_url('deliver/index')?>');
        });

        $detail.click(function () {
            var deliver_id = $(this).closest('tr').attr('data-id');
            $.yRedirect('<?=node_url('deliver/detail/')?>' +  deliver_id);
        });

        $confirm.click(function () {
            var $this = $(this),
                id = $this.closest('tr').attr('data-id'),
                defObj = {
                    deliver_express_num: '',
                    deliver_express: -1,
                    deliver_id: id
                };
            if (! id) {
                return;
            }
            $popup.ySetForm(defObj).yForm('open');
        });

        $form.validate({
            rules: {
                deliver_express_num: {required: true, minlength: 6, maxlength: 20},
                deliver_express: {required: true, select: true}
            },
            submitHandler: function () {
                var id = $('input[name="deliver_id"]', $form).val();
                if (! id) {
                    return;
                }
                $form.yAjax({
                    url: '<?=node_url('deliver/confirm')?>',
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('操作成功');
                            $.yRefresh(1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
    });
</script>