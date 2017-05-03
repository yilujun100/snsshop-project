<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <!--<p>
                <label for="share_goods_id">商品 ID</label>
                <input type="text" name="share_goods_id" id="share_goods_id" value="<?/*=empty($share_goods_id)?'':$share_goods_id;*/?>">
            </p>
            <p>
                <label for="share_act_id">活动 ID</label>
                <input type="text" name="share_act_id" id="share_act_id" value="<?/*=empty($share_act_id)?'':$share_act_id;*/?>">
            </p>-->
            <p>
                <label for="beginTime">起始时间</label>
                <input type="text" name="beginTime" id="beginTime" class="date datetime" value="<?=empty($beginTime)?'':$beginTime;?>">
            </p>
            <p>
                <label for="endTime">结束时间</label>
                <input type="text" name="endTime" id="endTime" class="date datetime" value="<?=empty($endTime)?'':$endTime;?>">
            </p>
            <p>
                <label for="robotUin">机器人Uin</label>
                <input type="text" name="robotUin" class="uin" id="robotUin" value="<?=empty($robotUin)?'':$robotUin;?>">
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'robot/share','button'=>Lib_Constants::BTN_OK,'id'=>'share-search-do'))?>
                <?php $this->widget('button', array('node'=>'robot/share','button'=>Lib_Constants::BTN_RESET,'id'=>'share-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="15%">中奖订单ID</th>
            <th width="10%">中奖时间</th>
            <th width="10%">类别</th>
            <th width="5%">夺宝单ID</th>
            <th width="5%">中奖期数</th>
            <th width="20%">商品名称</th>
            <th width="10%">机器人昵称</th>
            <th width="10%">机器人Uin</th>
            <th width="5%">状态</th>
            <th width="10%" style="min-width: 100px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="10">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['sWinnerOrder']?></td>
                <td><?=date(TIME_FORMATTER, $v['iLotTime'])?></td>
                <td><?=Lib_Constants::$active_type[$v['iActType']]?></td>
                <td><?=$v['iActId']?></td>
                <td><?=$v['iPeroid']?></td>
                <td class="text-left"><?=cn_substr($v['sGoodsName'], 22)?></td>
                <td><?=$v['sWinnerNickname']?></td>
                <td><?=$v['iWinnerUin']?></td>
                <td><?=empty($v['iShareId'])?'未晒单':'已晒单'?></td>
                <td data-id="<?=empty($v['iShared'])?'':$v['iShareId']?>" data-act="<?=$v['iActId']?>" data-period="<?=$v['iPeroid']?>">
                    <?php
                    if (empty($v['iShared'])) {
                        $this->widget('button', array('node'=>'robot/share_add','button'=>Lib_Constants::BTN_SHARE_ADD,'class'=>'share-add'));
                    } else {
                        $this->widget('button', array('node'=>'robot/share_edit','button'=>Lib_Constants::BTN_SHARE_DETAIL,'class'=>'share-detail'));
                    }
                    ?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="10"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $search_do = $('#share-search-do'),
            $search_rest = $('#share-search-reset'),
            $add = $('.share-add'),
            $edit = $('.share-edit'),
            $detail = $('.share-detail');

        $search_do.click(function () {
            var search = {},
                search_str,/*
                share_goods_id = parseInt($.trim($('#share_goods_id').val()), 10),
                share_act_id = parseInt($.trim($('#share_act_id').val()), 10),
                share_uin = $.trim($('#share_uin').val())*/
                beginTime = $.trim($('#beginTime').val()),
                endTime = $.trim($('#endTime').val()),
                robotUin = $.trim($('#robotUin').val());
            /*if (share_goods_id) {
                search.share_goods_id = share_goods_id;
            }
            if (share_act_id > 0) {
                search.share_act_id = share_act_id;
            }
            if (share_uin) {
                search.share_uin = share_uin;
            }*/
            if (beginTime) {
                search.beginTime = beginTime;
            }
            if (endTime) {
                search.endTime = endTime;
            }
            if (robotUin) {
                search.robotUin = robotUin;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('robot/share')?>' + '?' + search_str);
        });

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('robot/share')?>');
        });

        $('#beginTime').datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });

        $('#endTime').datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });

        $add.click(function () {
            var $this = $(this),
                act = $this.parent().attr('data-act'),
                period = $this.parent().attr('data-period');
            if (! act || ! period) {
                $.yError('参数错误');
                return;
            }
            $.yRedirect('<?=node_url('robot/share_add')?>'+'?act='+act+'&period='+period);
        });

        $edit.click(function () {
            var shareId = $(this).closest('td').attr('data-id');
            $.yRedirect('<?=node_url('robot/share_edit')?>'+'?share_id='+shareId);
        });

        $detail.click(function () {
            var shareId = $(this).closest('td').attr('data-id');
            $.yRedirect('<?=node_url('robot/share_detail')?>'+'?share_id='+shareId);
        });
    });
</script>