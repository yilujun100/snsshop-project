/*
 * 兑换数量 exchange_num.js
 * author:yilj@snsshop.cn
 * date:2016-1-30
*/

$(function(){

	fnInit();
	fnChangePartiNum();
	fnChangeStampsNum();
	fnStatusUseStamps();

});

var fnChangePartiNum = function () { 
	var partiNum = luckyBag.pariNum;
	var cost;	
	
	$('#partiIncrease, #partiDecrease').on('click', function(){
		var num = $('#parti-num').val();
		
		if ($(this).attr('id') == 'partiIncrease') {
			num++;
			if (num > luckyBag.maxBuyQty) {
				num = luckyBag.maxBuyQty;
				layer.msg('已达到购买上限');
			}
		}

		if ($(this).attr('id') == 'partiDecrease') {
			num--;
			if (num <= luckyBag.qtyMin) {
				num = 1;
			}
		}

		$('#parti-num').val(num);
		$('#parti-times').text(num * luckyBag.area);
		$('#amount-money').text('￥'+ num * luckyBag.areaPrice);
		// 参与数量和使用夺宝券联动 @ 2016-2-25
		if (luckyBag.isUseStamps) {
			$('#stamps-num').val(num); 
			if (num > luckyBag.remainStamps) {
				$('#stamps-num').val(luckyBag.remainStamps);
			}
		}
		cost = luckyBag.isUseStamps ? ($('#amount-money').text().substring(1) - $('#stamps-num').val()) * 1 : $('#amount-money').text().substring(1) * 1;
		if (luckyBag.isUseStamps) {
			if (parseInt(num * luckyBag.areaPrice) - parseInt($('#stamps-num').val()) < 0) { // 改变参与次数: 参与所需支付金额小于使用夺宝券张数
				cost = 0;
			}
		}
		$('#needPayAmount, #totalPay').text('￥'+ cost);
	});

	$('#parti-num').on('change', function(){
		var num = $(this).val();
		if (num < 0 || num == '') {
			layer.msg('输入有误');
			num = luckyBag.qtyMin;
		}
		if (num > luckyBag.maxBuyQty) {
			num = luckyBag.maxBuyQty;
			layer.msg('已达到购买上限');
		}
		$(this).val(num);
		$('#parti-times').text(num * luckyBag.area);
		$('#amount-money').text('￥'+ num * luckyBag.areaPrice);
		if (luckyBag.isUseStamps) {
			$('#stamps-num').val(num); 
			if (num > luckyBag.remainStamps) {
				$('#stamps-num').val(luckyBag.remainStamps);
			}
		}
		cost = luckyBag.isUseStamps ? ($('#amount-money').text().substring(1) - $('#stamps-num').val()) * 1 : $('#amount-money').text().substring(1) * 1; 
		$('#needPayAmount, #totalPay').text('￥'+ cost);
	});
};


var fnChangeStampsNum = function () {
	var partiNum;
	var cost;

	$('#stampsIncrease, #stampsDecrease').on('click', function(){
		if (!luckyBag.isUseStamps) {
			return;
		} else {
			if (luckyBag.remainStamps == 0) { // 当前无夺宝券
				return;
			} else {

				var num = $('#stamps-num').val();
				partiNum = $('#parti-times').text();
				if ($(this).attr('id') == 'stampsIncrease'){
					num++;
					if (num >= luckyBag.remainStamps) {
						num = luckyBag.remainStamps;
					}
				}
				if ($(this).attr('id') == 'stampsDecrease') {
					num--;
					if (num <= luckyBag.qtyMin) {
						num = 1;
					}
				}
				cost = (partiNum - num) * 1;
				if (num > partiNum) {
					cost = 0;
				}
				$('#stamps-num').val(num);
				$('#needPayAmount, #totalPay').text('￥'+ cost);

			}			
		}
	});

	$('#stamps-num').on('change', function(){
		var num = $(this).val();
		partiNum = $('#parti-times').text();
		if (num < 0 || num == '') {
			num = 0;
			layer.msg('输入有误');
		}
		if (num > luckyBag.remainStamps) {
			num = luckyBag.remainStamps;
			layer.msg('已超出剩余夺宝券张数');
		}
		$(this).val(num);
		cost = luckyBag.isUseStamps ? ($('#amount-money').text().substring(1) - $('#stamps-num').val()) * 1 : $('#amount-money').text().substring(1) * 1;
		if (num > partiNum) {
			cost = 0;
		}
		$(this).val(num);
		$('#needPayAmount, #totalPay').text('￥'+ cost);
	})
};

var fnStatusUseStamps = function () {
	var useStamps = $('#use-stamps');
	var cost;
	useStamps.on('click', function(){
		var useStampsNum = $('#stamps-num').val();
		if (!$(this).is(':checked')) {
			cost = $('#parti-times').text();
			$(this).prop('checked', false);
			luckyBag.isUseStamps = false;
			$('#needPayAmount, #totalPay').text('￥'+ cost);
		} else {
			cost = ($('#parti-times').text() - useStampsNum) *1;
			luckyBag.isUseStamps = true;
			$(this).prop('checked', true);
			$('#needPayAmount, #totalPay').text('￥'+ cost);
		}
	});
};

var fnInit = function () {
	var minPartiNum = 1;
	$('#areaUnitPrice b').text(luckyBag.areaPrice);
	$('#parti-num').val(luckyBag.pariNum); // 数量可编辑 @2016-2-25
	$('#parti-times').text(luckyBag.pariNum * luckyBag.areaPrice);
	$('#amount-money').text('￥'+ luckyBag.pariNum * luckyBag.areaPrice);
	$('#remain-stamps').text(luckyBag.remainStamps);
	$('#stamps-num').val(luckyBag.pariNum * luckyBag.areaPrice);
	// $('#stamps-num').text(luckyBag.remainStamps);

	if (luckyBag.remainStamps == 0) { // 当前无夺宝券
		$('#stamps-num').val(0);
	}
	
	
	var partiNumCost = parseInt($('#amount-money').text().substring(1));
	var remainStampsUse = parseInt($('#stamps-num').val());
	var totalCost = partiNumCost - remainStampsUse;
	if (partiNumCost >= remainStampsUse) {
		totalCost = 0;
	}
	if (luckyBag.pariNum * luckyBag.areaPrice >= luckyBag.remainStamps) {
		$('#stamps-num').val(luckyBag.remainStamps);
		totalCost = partiNumCost - $('#stamps-num').val();
	}
	if (luckyBag.pariNum * luckyBag.areaPrice <= $('#stamps-num').val()) {
		totalCost = 0;
	}
	
	$('#use-stamps').prop('checked', true);
	$('#remain-stamps').text(luckyBag.remainStamps);

	$('#needPayAmount, #totalPay').text('￥'+ totalCost);
	
};