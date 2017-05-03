<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<link type="text/css" href="/<?=$theme_dir?>css/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="/<?=$theme_dir?>css/jquery-ui-timepicker-addon.css?v=<?=$version?>">
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-addon.js?v=<?=$version?>"></script>
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-zh-CN.js?v=<?=$version?>"></script>
<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p class="f-item">
                <label for="beginTime">开始时间</label>
                <input type="text" name="beginTime" id="beginTime" class="date datetime" value="<?=empty($beginTime)?'':date('Y-m-d H:i:s',$beginTime)?>" />
            </p>
            <p class="f-item">
                <label for="beginTime">结束时间</label>
                <input type="text" name="endTime" id="endTime" class="date datetime" value="<?=empty($endTime)?'':date('Y-m-d H:i:s',$endTime)?>" />
            </p>
            <p class="f-item">
                <label></label>
                <?php $this->widget('button', array('node'=>'robot/stat_delay','button'=>Lib_Constants::BTN_OK,'id'=>'robot-search-do'))?>
                <?php $this->widget('button', array('node'=>'robot/stat_delay','button'=>Lib_Constants::BTN_RESET,'id'=>'robot-search-reset'))?>
            </p>
        </div>
    </form>
</div>
<style type="text/css">
    .total-con {text-align: center; margin-top: 20px;}
    .total-con li {display: inline-block; }
    .total-con li dl{border: 1px #939393 solid;margin: 0 10px;background-color: #DDEBF6;}
    .total-con li dt{border-bottom: 1px #939393 solid; line-height: 16px; padding: 10px;  width: 150px;}
    .total-con li dd{line-height: 18px; padding: 9px 10px 9px 10px;}
    .stat-title th{background: #FFF;}
</style>
<div class="total-con" style="">
    <ul>
        <li><dl>
            <dt>总开奖次数(次)</dt>
            <dd><?=empty($total_stat['iOpenCount']) ? 0 : $total_stat['iOpenCount']?></dd>
        </dl></li>
        <li><dl>
            <dt>用户参与次数</dt>
            <dd><?=empty($total_stat['iJoinCoupon']) ? 0 : $total_stat['iJoinCoupon']?></dd>
        </dl></li>
        <li><dl>
            <dt>用户中奖金额(元)</dt>
            <dd><?=empty($total_stat['iWinAmount']) ? 0 : price_format($total_stat['iWinAmount'])?></dd>
        </dl></li>
        <li><dl>
            <dt>用户盈亏(元)</dt>
            <dd><?=empty($total_stat['iFloatAmount']) ? 0 : price_format($total_stat['iFloatAmount'])?></dd>
        </dl></li>
        <li><dl>
            <dt>采购盈亏(元)</dt>
            <dd><?=empty($total_stat['iFloatSourAmount']) ? 0 : price_format($total_stat['iFloatSourAmount'])?></dd>
        </dl></li>
        <li><dl>
            <dt>平台盈亏(元)</dt>
            <dd><?=empty($total_stat['iFloatPlatAmount']) ? 0 : price_format($total_stat['iFloatPlatAmount'])?></dd>
        </dl></li>
    </ul>
</div>
<div class="table-con">
    <table class="table mt-10 fr">
        <thead>
            <tr class="stat-title">
                <th colspan="10"  style="border-right: 0;">夺宝平台盈亏表</th>
                <th style="border-left: 0"><?php $this->widget('button', array('node'=>'robot/excel_delay','button'=>Lib_Constants::BTN_EXPORT_EXCEL,'id'=>'robot-excel'))?></th>
            </tr>
            <tr>
                <th width="9%">日期</th>
                <th width="9%">总开奖次数</th>
                <th width="9%">总开奖金额(元)</th>
                <th width="9%">用户参与次数</th>
                <th width="9%">用户参与金额(元)</th>
                <th width="9%">用户中奖次数</th>
                <th width="9%">用户中奖金额(元)</th>
                <th width="9%">用户盈亏(元)</th>
                <th width="9%">用户奖品成本【参考】(元)</th>
                <th width="9%">采购盈亏【参考】(元)</th>
                <th width="9%">平台盈亏【参考】(元)</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($list_stat)) { ?>
            <tr><td colspan="11">暂无记录</td></tr>
        <?php } else { ?>
            <tr>
                <td>字段备注</td>
                <td>*只针对当天(0点-24点)已开奖夺宝单统计</td>
                <td>*当天已开奖夺宝单的累计价值，即每单的码数*每码价格</td>
                <td>*当天已开奖夺宝单中用户实际参与的次数</td>
                <td>当天已开奖夺宝单中用户实际参与的券数金额</td>
                <td>*当天已开奖夺宝单中用户实际中奖的次数</td>
                <td>*当天已开奖夺宝单中用户实际中奖的夺宝单累计价值</td>
                <td>*用户中奖金额-用户参与券数金额</td>
                <td>*对应商品的[最低售价]字段</td>
                <td>*用户中奖金额-用户奖品成本</td>
                <td>*采购盈亏-用户盈亏</td>
            </tr>
        <?php foreach ($list_stat as $v) { ?>
            <tr data-unique="<?=$v['iStatTime']?>">
                <td ><?=date('Y-m-d', $v['iStatTime'])?></td>
                <td ><?=$v['iOpenCount']?></td>
                <td ><?=price_format($v['iOpenAmount'])?></td>
                <td ><?=$v['iJoinCoupon']?></td>
                <td ><?=price_format($v['iJoinAmount'])?></td>
                <td ><?=$v['iWinCount']?></td>
                <td ><?=price_format($v['iWinAmount'])?></td>
                <td ><?=price_format($v['iFloatAmount'])?></td>
                <td ><?=price_format($v['iCostAmount'])?></td>
                <td ><?=price_format($v['iFloatSourAmount'])?></td>
                <td ><?=price_format($v['iFloatPlatAmount'])?></td>
            </tr>
        <?php }} ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $form = $('#search-form'),
            $beginTime = $('#beginTime', $form),
            $endTime = $('#endTime', $form);

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


        var $excel = $('#robot-excel'),
            $search_do = $('#robot-search-do'),
            $search_rest = $('#robot-search-reset'),
            validator;

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
            $.yRedirect('<?=node_url('robot/stat_delay')?>' + '?' + search_str);
        });

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('robot/stat_delay')?>');
        });

        $excel.click(function () {
            $.yConfirm('确定要下载该数据文件吗?', '确认', function () {
                var search = {},
                    search_str,
                    beginTime = $('#beginTime').val(),
                    endTime = $('#endTime').val();
                if (beginTime) {
                    search.beginTime = encodeURIComponent(beginTime);
                }
                if (endTime) {
                    search.endTime = encodeURIComponent(endTime);
                }
                search_str = $.param(search);
                $.yRedirect('<?=node_url('robot/excel_delay')?>' + '?' + search_str);
            });
        });
    });
</script>