<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<link type="text/css" href="/<?=$theme_dir?>css/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="/<?=$theme_dir?>css/jquery-ui-timepicker-addon.css?v=<?=$version?>">
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-addon.js?v=<?=$version?>"></script>
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-zh-CN.js?v=<?=$version?>"></script>
<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="statistics-form">
        <div class="form-item">
            <p>
                <label for="beginTime">开始时间</label>
                <input type="text" name="beginTime" id="beginTime" class="date datetime" value="<?=empty($beginTime)?'':date(TIME_FORMATTER, $beginTime)?>">
            </p>
            <p>
                <label for="endTime">结束时间</label>
                <input type="text" name="endTime" id="endTime" class="date datetime" value="<?=empty($endTime)?'':date(TIME_FORMATTER, $endTime)?>">
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'statistics/detail','button'=>Lib_Constants::BTN_OK,'id'=>'statistics-search-do'))?>
                <?php $this->widget('button', array('node'=>'statistics/detail','button'=>Lib_Constants::BTN_RESET,'id'=>'statistics-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'statistics/excel','button'=>Lib_Constants::BTN_EXPORT_EXCEL,'id'=>'statistics-excel'))?>
    </span>
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="5%"></th>
            <th width="12%">已支付人数</th>
            <th width="8%">已支付订单数</th>
            <th width="10%">已支付现金金额</th>
            <th width="10%">已使用券</th>
            <th width="10%">订单ARPU值</th>
            <th width="10%">未支付人数</th>
            <th width="10%">未支付订单数</th>
            <th width="10%">未支付金额</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($statistics_order_list)) {?>
            <tr><td colspan="9">暂无数据</td></tr>
        <?php } else { foreach ($statistics_order_list as $v) { ?>
            <tr >
                <td><?=$v['type']?></td>
                <td><?=$v['iPayUserCount']?></td>
                <td><?=$v['iPayOrderCount']?></td>
                <td><?=$v['iPayMoney']?></td>
                <td><?=$v['iUseCoupon']?></td>
                <td><?=$v['iPayUserCount']==0?0:sprintf("%.2f", ($v['iUseCoupon']+$v['iPayMoney'])/$v['iPayUserCount']);?></td>
                <td><?=$v['iNotPayUserCount']?></td>
                <td><?=$v['iNotPayOrderCount']?></td>
                <td><?=$v['iNotPayMoney']?></td>
            </tr>
        <?php }}?>
        </tbody>

    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $popup = $('#statistics-popup').yForm('新增/编辑模版', 700),
            $form = $('#statistics-form'),
            $beginTime = $('#beginTime', $form),
            $endTime = $('#endTime', $form),
            $excel = $('#statistics-excel'),
            $search_do = $('#statistics-search-do'),
            $search_rest = $('#statistics-search-reset'),
            validator;

        $beginTime.datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });
        $endTime.datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });

        $search_do.click(function () {
            var search = {},
                search_str,
                beginTime = $('#beginTime').val(),
                endTime = $('#endTime').val();
            if (beginTime) {
                search.beginTime = beginTime;
            }
            if (endTime) {
                search.endTime = endTime;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('statistics/detail')?>' + '?' + search_str);
        });

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('statistics/detail')?>');
        });

        $excel.click(function () {
            $.yConfirm('确定要下载该数据文件吗?', '确认', function () {
                var search = {},
                    search_str,
                    beginTime = $('#beginTime').val(),
                    endTime = $('#endTime').val();
                if (beginTime) {
                    search.beginTime = beginTime;
                }
                if (endTime) {
                    search.endTime = endTime;
                }
                search.type = 2;
                search_str = $.param(search);
                $.yRedirect('<?=node_url('statistics/excel')?>' + '?' + search_str);
            });
        });
    });
</script>