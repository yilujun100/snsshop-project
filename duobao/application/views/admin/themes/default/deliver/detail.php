<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">发货详情</h3>
</div>
<div class="form-con">
    <form class="form">
        <div class="f-item">
            <label>订单ID</label>
            <span class="f-value"><?=empty($item['sOrderId'])?'':$item['sOrderId']?></span>
        </div>
        <div class="f-item">
            <label>订单类型</label>
            <span class="f-value"><?=!isset($item['iCreateTime'])?'':Lib_Constants::$order_type[$item['iType']]?></span>
        </div>
        <div class="f-item">
            <label>商品图</label>
            <span class="f-value"><?=empty($item['sGoodsImg'])?'':'<img style="width:100px; height:100px;" src="' . $item['sGoodsImg'] . '">'?></span>
        </div>
        <div class="f-item">
            <label>商品ID</label>
            <span class="f-value"><?=!isset($item['iGoodsId'])?'':$item['iGoodsId']?></span>
        </div>
        <div class="f-item">
            <label>商品名称</label>
            <span class="f-value"><?=!isset($item['sGoodsName'])?'':$item['sGoodsName']?></span>
        </div>
        <div class="f-item">
            <label>用户 ID</label>
            <span class="f-value"><?=empty($item['iUin'])?'':$item['iUin']?></span>
        </div>
        <div class="f-item">
            <label>发货状态</label>
            <span class="f-value"><?=Lib_Constants::$deliver_status[$item['iDeliverStatus']]?></span>
        </div>
        <div class="f-item">
            <label>收货状态</label>
            <span class="f-value"><?=Lib_Constants::$deliver_confirm[$item['iConfirmStatus']]?></span>
        </div>
        <div class="f-item">
            <label>快递公司</label>
            <span class="f-value"><?=empty($item['sExpressName'])?'':$item['sExpressName']?></span>
        </div>
        <div class="f-item">
            <label>快递单号</label>
            <span class="f-value"><?=empty($item['sExpressId'])?'':$item['sExpressId']?></span>
        </div>
        <div class="f-item">
            <label>收货人</label>
            <span class="f-value"><?=empty($item['sName'])?'':$item['sName']?></span>
        </div>
        <div class="f-item">
            <label>手机</label>
            <span class="f-value"><?=empty($item['sMobile'])?'':$item['sMobile']?></span>
        </div>
        <div class="f-item">
            <label>收货地址</label>
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
