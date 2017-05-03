<div class="viewport v-order">
    <!-- order detail start -->
    <div class="order-detail">
        <div class="grid order-info-basic">
            <p><em>订单状态</em><span class="status-order status-o-<?=$detail['state']['btn']?>"><?=$detail['state']['text']?></span></p>
            <p><em>订单编号</em><span><?=$detail['sOrderId']?></span></p>
            <p><em>下单时间</em><span><?=date(TIME_FORMATTER, $detail['iCreateTime'])?></span></p>
        </div>

        <div class="grid addr-edit mt-10">
				<span class="addr-info">
					<p class="clearfix">
                        <label class="consignee fl"><?=$detail['sName']?></label>
                        <em class="phone-num fr"><?=$detail['sMobile']?></em>
                    </p>
					<p class="addr-detail"><?=$detail['sAddress']?></p>
				</span>
        </div>
        <div class="grid order-pay-type mt-10">
            <div class="order-pay-type-hd clearfix">
                <h3 class="fl">支付方式</h3>
                <em class="fr">微信支付</em>
            </div>
            <div class="order-pay-type-con">
                <div class="goods-info">
                    <div class="goods-pic">
                        <img src="<?=$detail['groupon']['sImg']?>" width="84" height="84" alt="">
                    </div>
                    <div class="goods-info-basic">
                        <div class="goods-name"><?=$detail['groupon']['sGoodsName']?></div>
                        <div class="goods-tag"><label><?=Lib_Constants::$groupon_order[$detail['iBuyType']]?></label></div>
                        <div class="goods-price clearfix"><span class="fl">总价：<strong>¥ <?=price_format($detail['iTotalPrice'])?></strong></span></div>
                    </div>
                </div>
                <div class="order-pay-bott mt-10 clearfix">
                    <em class="fl">共<strong>1</strong>件商品</em>
                    <span class="fr">实付：<strong>¥ <?=price_format($detail['iRealPrice'])?></strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?=$cdn_common_url?>css/layer_skin_extend.css?ver=<?=$version?>">
<script type="text/javascript" src="<?=$cdn_groupon_url?>js/order.js?version=<?=$version?>"></script>