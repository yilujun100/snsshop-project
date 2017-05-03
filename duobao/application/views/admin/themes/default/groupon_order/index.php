<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="order_id">订单ID</label>
                <input type="text" name="order_id" id="order_id" value="<?=empty($order_id)?'':$order_id;?>" style="width: 250px;">
            </p>
            <p>
                <label for="order_uin">用户 ID</label>
                <input type="text" name="order_uin" id="order_uin" value="<?=empty($order_uin)?'':$order_uin;?>" style="width: 200px;">
            </p>
            <p>
                <label for="deliver_type">购买类型</label>
                <select name="deliver_type" id="deliver_type">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$groupon_order as $k => $v) {?>
                        <option value="<?=$k?>" <?=isset($buy_type)&&$k==$buy_type?'selected':''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'groupon_order/index','button'=>Lib_Constants::BTN_OK,'id'=>'order-search-do'))?>
                <?php $this->widget('button', array('node'=>'groupon_order/index','button'=>Lib_Constants::BTN_RESET,'id'=>'order-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="13%">订单ID</th>
            <th width="11%">下单时间</th>
            <th width="10%">订单状态</th>
            <th width="10%">支付金额</th>
            <th width="20%">商品名称</th>
            <th width="7%">商品ID</th>
            <th width="7%">拼团单ID</th>
            <th width="7%">购买类型</th>
            <th width="15%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="9">请输入订单ID或用户ID进行查询</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr data-id="<?=$v['sOrderId']?>">
                <td><?=$v['sOrderId']?></td>
                <td><?=date(TIME_FORMATTER, $v['iCreateTime'])?></td>
                <td><?=$v['sStateDesc']?></td>
                <td><?=price_format($v['iPayAmount'])?></td>
                <td><?=$v['sGoodsName']?></td>
                <td><?=$v['iGoodsId']?></td>
                <td><?=$v['iGrouponId']?></td>
                <td><?=Lib_Constants::$groupon_order[$v['iBuyType']]?></td>
                <td><?php $this->widget('button', array('node'=>'groupon_order/detail','button'=>Lib_Constants::BTN_DETAIL,'class'=>'order-detail'))?></td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="9"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $search_do = $('#order-search-do'),
            $search_rest = $('#order-search-reset'),
            $detail = $('.order-detail');

        $search_do.click(function () {
            var search = {},
                search_str,
                order_id = $.trim($('#order_id').val()),
                order_uin = $.trim($('#order_uin').val());
            if (! order_id && ! order_uin) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            if (order_id) {
                if (! /[0-9]{2,}/.test(order_id)) {
                    $.yError('请先输入正确的订单号');
                    return;
                }
                search.order_id = order_id;
            }
            if (order_uin) {
                if (! /[0-9]{2,}/.test(order_uin)) {
                    $.yError('请先输入正确的用户 ID');
                    return;
                }
                search.order_uin = order_uin;
            }
            search_str = $.param(search);
            $.yRedirect('<?=node_url('groupon_order/index')?>' + '?' + search_str);
        });
        $search_rest.click(function () {
            $.yRedirect('<?=node_url('groupon_order/index')?>');
        });
        $detail.click(function () {
            var order_id = $(this).closest('tr').attr('data-id');
            $.yRedirect('<?=node_url('groupon_order/detail/')?>' +  order_id);
        });
    });
</script>