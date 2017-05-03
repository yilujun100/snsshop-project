/*
 * 立即参与快捷支付 payment.js
 * author:yilj@snsshop.cn
 * date:2016-4-5
*/

$(function(){
	DUOBAO.chooseQty.init();
	DUOBAO.payment.init();
	DUOBAO.payment.changeNum();
	DUOBAO.payment.stampsStaus();
});
DUOBAO.payment.init = function() {
	var partiNum = $('.quantity').val();
	var totalCost = partiNum * DUOBAO.payment.singleCost;
	var needPay = totalCost - DUOBAO.payment.stampsRemain;

	if (needPay <= 0) {
		needPay = 0;
	}
	DUOBAO.payment.partiTimes = partiNum;
	DUOBAO.payment.cost = needPay;
	$('#useStamps').prop('checked', true);
	$('.single-price').text('￥'+ DUOBAO.payment.singleCost);
	$('.total-cost strong').text('￥'+ totalCost);
	$('.stamps-remain b').text(DUOBAO.payment.stampsRemain);
	$('.need-pay strong').text(DUOBAO.payment.cost);
};
DUOBAO.payment.changeNum = function() {
	var qtyBox = $('.quantity-wrap');
	var oBtnQty = qtyBox.find('a');
	oBtnQty.on('click', function(){
		var qty = $(this).parent().find('.quantity').val();
		var totalCost = qty * DUOBAO.payment.singleCost;
		var needPay;
		if (DUOBAO.payment.useStamps == true) {
			needPay = totalCost - DUOBAO.payment.stampsRemain;
			if (needPay <= 0) {
				needPay = 0;
			}
		} else {
			needPay = totalCost;
		}
		DUOBAO.payment.total = totalCost;
		DUOBAO.payment.cost = needPay;
		$('.total-cost strong').text('￥'+ DUOBAO.payment.total);
		$('.need-pay strong').text(DUOBAO.payment.cost);
	});
};
DUOBAO.payment.stampsStaus = function() {
	var statusInput = $('#useStamps');
	statusInput.on('change', function(){
		if ($(this).is(':checked')) {
			DUOBAO.payment.useStamps = true;
		}else {
			DUOBAO.payment.useStamps = false;
		}

		if (DUOBAO.payment.useStamps == true) {
			var qty = $('.quantity').val();
			var needPay = (qty * DUOBAO.payment.singleCost) - DUOBAO.payment.stampsRemain;
			if (needPay <= 0) {
				needPay = 0;
			}
		} else {
			var qty = $('.quantity').val();
			var needPay = qty * DUOBAO.payment.singleCost;
		}
		DUOBAO.payment.cost = needPay;
		$('.need-pay strong').text(DUOBAO.payment.cost);
	});
};