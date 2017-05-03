<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>支付页面——拼团</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="Copyright" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="<?=$resource_url?>groupon/css/base.css">
    <link rel="stylesheet" href="<?=$resource_url?>groupon/css/common.css">
    <link rel="stylesheet" href="<?=$resource_url?>groupon/css/layout.css">
    <link rel="stylesheet" href="<?=$resource_url?>js/layer/skin/layer.css">
    <link rel="stylesheet" href="<?=$resource_url?>css/layer_skin_extend.css">
    <script src="<?=$resource_url?>js/jquery-2.1.4.min.js"></script>
    <script src="<?=$resource_url?>js/layer/layer.js"></script>
    <script src="<?=$resource_url?>js/lib.js"></script>
</head>
<body>
<div class="viewport v-payment">
    <!-- payment start -->
    <div class="payment">

        <?php if (empty($address)) {?>
            <div class="grid addr-new clearfix">
                <h3 class="fl">添加收货地址</h3>
                <a href="<?=gen_uri('/my/address',array('business'=>Lib_Constants::ORDER_TYPE_GROUPON,'order_id'=>empty($order_id)?'':$order_id,'redirect_url'=>current_url()),'duogebao')?>" class="btn-addr-new fr">添加收货地址</a>
            </div>
        <?php } else {?>
            <div class="grid addr-edit">
                    <span class="addr-info">
                        <p class="clearfix">
                            <label class="consignee fl"><?=$address['sName']?></label>
                            <em class="phone-num fr"><?=$address['sMobile']?></em>
                        </p>
                        <p class="addr-detail"><?=$address['sProvince']?> <?=$address['sCity']?> <?=$address['sDistrict']?> <?=$address['sAddress']?></p>
                    </span>
                <a href="<?=gen_uri('/my/address',array('business'=>Lib_Constants::ORDER_TYPE_GROUPON,'order_id'=>empty($order_id)?'':$order_id,'redirect_url'=>current_url()),'duogebao')?>" class="btn-addr-edit"><i class="icon-edit"></i>修改</a>
            </div>
        <?php }?>

        <div class="grid pay-list mt-10">
            <div class="pay-list-item goods-info">
                <div class="goods-pic">
                    <img src="<?=get_img_resize_url($groupon['sImg'], 84, 84)?>" width="84" height="84" alt="">
                </div>
                <div class="goods-info-basic">
                    <div class="goods-name"><?=$groupon['sGoodsName']?></div>
                    <div class="goods-tag"><label><?=$buy_type_desc?></label></div>
                    <div class="goods-price">总价：<strong>¥ <?=price_format(Lib_Constants::GROUPON_ORDER_DIRECT==$buy_type?$groupon['iPrice']:$spec['iDiscountPrice'])?></strong></div>
                </div>
            </div>
            <div class="pay-list-item">
                <dl>
                    <dt>商品价格</dt>
                    <dd>¥ <?=price_format(Lib_Constants::GROUPON_ORDER_DIRECT==$buy_type?$groupon['iPrice']:$spec['iDiscountPrice'])?></dd>
                </dl>
                <dl>
                    <dt>购买数量</dt>
                    <dd>x1</dd>
                </dl>
                <dl>
                    <dt>快递费用</dt>
                    <dd>¥ 0.00</dd>
                </dl>
            </div>
            <div class="pay-list-item pay-cost">
                <p>实付：<strong>¥ <?=price_format(Lib_Constants::GROUPON_ORDER_DIRECT==$buy_type?$groupon['iPrice']:$spec['iDiscountPrice'])?></strong></p>
            </div>
        </div>
        <form name="pay-form" id="pay-form" style="display: none">
            <input type="hidden" name="result_url" value="<?=$result_url?>">
            <input type="hidden" name="pay_agent" value="<?=Lib_Constants::ORDER_PAY_TYPE_WX?>">
            <input type="hidden" name="order_type" value="<?=$order_type?>">
            <input type="hidden" name="buy_type" value="<?=$buy_type?>">
            <input type="hidden" name="address_id" value="<?=empty($address)?'':$address['iAddressID']?>">
            <input type="hidden" name="groupon_id" value="<?=empty($groupon['iGrouponId'])?'':$groupon['iGrouponId']?>">
            <input type="hidden" name="spec_id" value="<?=empty($spec['iSpecId'])?'':$spec['iSpecId']?>">
            <input type="hidden" name="diy_id" value="<?=empty($diy['iDiyId']) ?'':$diy['iDiyId']?>">
            <input type="hidden" name="order_id" value="<?=empty($order_id)?'':$order_id?>">
        </form>
        <input type="hidden" id="order_detail_url" value="<?=gen_uri('/order/detail','','groupon')?>">
        <input type="hidden" id="order_operate_desc" value="<?=$buy_type_desc?>">
        <script type="text/javascript">
            function create_order_checker () {
                var $pay_form = $('#pay-form');
                if (! $('input[name="address_id"]',$pay_form).val()) {
                    layer.msg('请填写收货地址', {icon: 2, time: 3000});
                    return;
                }
                return true;
            }
        </script>
        <?php $this->widget('pay', array('button'=>"确认「{$buy_type_desc}」",'order_type'=>$order_type,'checker'=>'create_order_checker','pay_form'=>'pay-form'))?>
    </div>
</div>
</body>
</html>