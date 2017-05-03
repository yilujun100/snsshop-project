<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">订单详情</h3>
</div>
<div class="form-con">
    <form class="form">
        <div class="f-item">
            <label>订单ID</label>
            <span class="f-value"><?=empty($item['sOrderId'])?'':$item['sOrderId']?></span>
        </div>
        <div class="f-item">
            <label>下单时间</label>
            <span class="f-value"><?=empty($item['iCreateTime'])?'':date(TIME_FORMATTER, $item['iCreateTime'])?></span>
        </div>
        <div class="f-item">
            <label>支付时间</label>
            <span class="f-value"><?=empty($item['iPayTime'])?'':date(TIME_FORMATTER, $item['iPayTime'])?></span>
        </div>
        <div class="f-item">
            <label>活动ID</label>
            <span class="f-value"><?=!isset($item['iActivityId'])?'':$item['iActivityId']?></span>
        </div>
        <div class="f-item">
            <label>购买数量</label>
            <span class="f-value"><?=!isset($item['iCount'])?'':$item['iCount']?></span>
        </div>
        <div class="f-item">
            <label>单价</label>
            <span class="f-value"><?=empty($item['iUnitPrice'])?'':$item['iUnitPrice']?></span>
        </div>
        <div class="f-item">
            <label>总价</label>
            <span class="f-value"><?=empty($item['iTotalPrice'])?'':$item['iTotalPrice']?></span>
        </div>
        <div class="f-item">
            <label>原价</label>
            <span class="f-value"><?=empty($item['iOriPrice'])?'':$item['iOriPrice']?></span>
        </div>
        <div class="f-item">
            <label>用户 ID</label>
            <span class="f-value"><?=empty($item['iUin'])?'':$item['iUin']?></span>
        </div>
        <div class="f-item">
            <label>用户昵称</label>
            <span class="f-value"><?=empty($item['sNickName'])?'':$item['sNickName']?></span>
        </div>
        <div class="f-item">
            <label>订单状态</label>
            <span class="f-value">
                <?=!isset($item['iStatus'])?'':Lib_Constants::$pay_status[$item['iStatus']]?>
            </span>
        </div>
        <div class="f-item">
            <label></label>
            <button type="reset" class="btn-form btn-back">返回</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $back = $('.btn-back');
        $back.click(function () {
            $.yBack()
        });
    });
</script>
