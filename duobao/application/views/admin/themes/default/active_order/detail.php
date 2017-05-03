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
            <span class="f-value"><?=empty($item['iPayTime']) || $item['iPayTime'] < 0?'':date(TIME_FORMATTER, $item['iPayTime'])?></span>
        </div>
        <div class="f-item">
            <label>夺宝车ID</label>
            <span class="f-value"><?=empty($item['sMergeOrderId'])?'':$item['sMergeOrderId']?></span>
        </div>
        <div class="f-item">
            <label>类别</label>
            <span class="f-value"><?=!isset($item['iBuyType'])?'':Lib_Constants::$order_type_active[$item['iBuyType']]?></span>
        </div>
        <div class="f-item">
            <label>支付方式</label>
            <span class="f-value"><?=!isset($item['iPayAgentType'])?'':Lib_Constants::$order_pay_type[$item['iPayAgentType']]?></span>
        </div>
        <div class="f-item">
            <label>支付流水</label>
            <span class="f-value"><?=empty($item['sTransId'])?'':$item['sTransId']?></span>
        </div>
        <div class="f-item">
            <label>购买数量</label>
            <span class="f-value"><?=!isset($item['iCount'])?'':$item['iCount']?></span>
        </div>
        <div class="f-item">
            <label>单价</label>
            <span class="f-value"><?=empty($item['iUnitPrice'])?'':price_format($item['iUnitPrice'])?></span>
        </div>
        <div class="f-item">
            <label>总价</label>
            <span class="f-value"><?=empty($item['iTotalPrice'])?'':price_format($item['iTotalPrice'])?></span>
        </div>
        <div class="f-item">
            <label>活动ID</label>
            <span class="f-value"><?=empty($item['iActId'])?'':$item['iActId']?></span>
        </div>
        <div class="f-item">
            <label>期数</label>
            <span class="f-value"><?=empty($item['iPeroid'])?'':$item['iPeroid']?></span>
        </div>
        <div class="f-item">
            <label>商品名称</label>
            <span class="f-value"><?=empty($item['sGoodsName'])?'':$item['sGoodsName']?></span>
        </div>
        <div class="f-item">
            <label>夺宝码</label>
            <span class="f-value"><?=empty($item['sLuckyCodeJson'])?'':implode(', ', json_decode($item['sLuckyCodeJson']))?></span>
        </div>
        <div class="f-item">
            <label>共参与</label>
            <span class="f-value"><?=empty($item['iLuckyCodeNum'])?'':$item['iLuckyCodeNum'] . '人次'?></span>
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
                <?=!isset($item['iPayStatus'])?'':Lib_Constants::$pay_status[$item['iPayStatus']]?>
                <?=!isset($item['iRefundStatus'])?'':', ' . Lib_Constants::$refund_status[$item['iRefundStatus']]?>
                <?=!isset($item['iLuckyCodeState'])?'':', ' . Lib_Constants::$code_states[$item['iLuckyCodeState']]?>
            </span>
        </div>
        <div class="f-item">
            <label>收货人</label>
            <span class="f-value"><?=empty($item['sName'])?'':$item['sName']?></span>
        </div>
        <div class="f-item">
            <label>手机号</label>
            <span class="f-value"><?=empty($item['sMobile'])?'':$item['sMobile']?></span>
        </div>
        <div class="f-item">
            <label>详细地址</label>
            <span class="f-value"><?=empty($item['sAddress'])?'':$item['sAddress']?></span>
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
