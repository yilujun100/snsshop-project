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
            <label>内部退款单号</label>
            <span class="f-value"><?=empty($item['sRefundKey'])?'':$item['sRefundKey']?></span>
        </div>
        <div class="f-item">
            <label>订单类型</label>
            <span class="f-value"><?=!isset($item['iBuyType'])?'':Lib_Constants::$order_type[$item['iBuyType']]?></span>
        </div>
        <div class="f-item">
            <label>退款方式</label>
            <span class="f-value"><?=!isset($item['iPayAgentType'])?'':Lib_Constants::$order_pay_type[$item['iPayAgentType']]?></span>
        </div>
        <div class="f-item">
            <label>退款金额</label>
            <span class="f-value"><?=!isset($item['iRefundPrice'])?'':price_format($item['iRefundPrice'])?></span>
        </div>
        <div class="f-item">
            <label>退款券数</label>
            <span class="f-value"><?=empty($item['iRefundCoupon'])?'':$item['iRefundCoupon']?></span>
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
            <label>退款结果</label>
            <span class="f-value">
                <?=!isset($item['iRetStatus'])?'':Lib_Constants::$refund_ret_status[$item['iRetStatus']]?>
            </span>
        </div>
        <div class="f-item">
            <label>备注</label>
            <span class="f-value">
                <?=$item['sRemark']?>
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
