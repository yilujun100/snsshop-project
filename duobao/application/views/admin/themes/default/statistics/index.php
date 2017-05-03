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
                <input type="text" name="beginTime" id="beginTime" class="date datetime" value="<?=empty($beginTime)?'':date(DATE_FORMATTER, $beginTime)?>">
            </p>
            <p>
                <label for="endTime">结束时间</label>
                <input type="text" name="endTime" id="endTime" class="date datetime" value="<?=empty($endTime)?'':date(DATE_FORMATTER, $endTime)?>">
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'statistics/index','button'=>Lib_Constants::BTN_OK,'id'=>'statistics-search-do'))?>
                <?php $this->widget('button', array('node'=>'statistics/index','button'=>Lib_Constants::BTN_RESET,'id'=>'statistics-search-reset'))?>
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
            <th width="5%">流量量(PV)</th>
            <th width="12%">用户量(UV)</th>
            <th width="8%">现金流水</th>
            <th width="10%">用券数量</th>
            <th width="10%">订单数量</th>
            <th width="10%">订单金额</th>
            <th width="10%">活跃用户数</th>
            <th width="10%">新增用户数</th>
            <th width="10%">累积用户</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($statistics_list)) {?>
            <tr><td colspan="9">暂无数据</td></tr>
        <?php } else {  ?>
            <tr >
                <td><?=$statistics_list['iUserPV']?></td>
                <td><?=$statistics_list['iUserUV']?></td>
                <td><?=$statistics_list['money']?></td>
                <td><?=$statistics_list['couponCount']?></td>
                <td><?=$statistics_list['orderCount']?></td>
                <td><?=$statistics_list['orderMoney']?></td>
                <td><?=$statistics_list['iActivityUser']?></td>
                <td><?=$statistics_list['iNewUser']?></td>
                <td><?=$statistics_list['iAccumulationUser']?></td>
            </tr>
        <?php }?>
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
            $.yRedirect('<?=node_url('statistics/index')?>' + '?' + search_str);
        });

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('statistics/index')?>');
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
                search.type = 1;
                search_str = $.param(search);
                $.yRedirect('<?=node_url('statistics/excel')?>' + '?' + search_str);
            });
        });
    });
</script>