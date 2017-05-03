/*
 * 兑换数量 exchange_num.js
 * author:yilj@snsshop.cn
 * date:2016-1-30
*/

$(function(){

	fnInit();
	fnChangeExchangeNum();
	fnChangeStampsNum();
	fnStatusUseStamps();

});

var fnChangeExchangeNum = function () { 
	var needStampsOne = luckyBag.stampsOne;
	var cost;	
	
	$('#exchangeIncrease, #exchangeDecrease').on('click', function(){
		var num = $('#exchangeNum').val();
		var needStampsNum = $('#totalStamps').text();
		var stampsUse = $('#remain-stamps-use').val();
		
		if ($(this).attr('id') == 'exchangeIncrease') {
			num++;
		}

		if ($(this).attr('id') == 'exchangeDecrease') {
			num--;
			if (num <= luckyBag.qtyMin) {
				num = 1;
			}
		}

		$('#exchangeNum').val(num);
		$('#exchangeCase').text(num);
		$('#totalStamps').text(needStampsOne*num);	
		cost = luckyBag.isUseStamps ? ($('#totalStamps').text() - $('#remain-stamps-use').val()) * 1 : $('#totalStamps').text() *1;
		if (luckyBag.isUseStamps && parseInt($('#totalStamps').text()) < parseInt($('#remain-stamps-use').val())) {
			cost = 0;
		}
		$('#needPayAmount, #totalPay').text('￥'+ cost);
	});

	$('#exchangeNum').on('change', function(){
		var num = $(this).val();
		if (num < 0 || num == '') {
			layer.msg('输入有误');
			num = luckyBag.qtyMin;
		}
		if (num < luckyBag.qtyMin) {
			layer.msg('最少兑换1件');
			num = luckyBag.qtyMin;
		}
		$(this).val(num);
		$('#exchangeCase').text(num);
		$('#totalStamps').text(needStampsOne*num);
		cost = luckyBag.isUseStamps ? ($('#totalStamps').text() - $('#remain-stamps-use').val()) * 1 : $('#totalStamps').text() *1;

		$('#needPayAmount, #totalPay').text('￥'+ cost);
	});
};

var fnChangeStampsNum = function () {
	var cost;

	$('#stampsIncrease, #stampsDecrease').on('click', function(){
		if (!luckyBag.isUseStamps) {
			return;
		} else {
			if (luckyBag.remainStamps == 0) { // 当前无夺宝券
				return;
			} else {
				var needStampsNum = $('#totalStamps').text();
				var num = $('#remain-stamps-use').val();
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
				cost = (needStampsNum - num) * 1;
				if (num > needStampsNum) {
					cost = 0;
				}
				$('#remain-stamps-use').val(num);
				$('#needPayAmount, #totalPay').text('￥'+ cost);				
			}			
		}
	});

	$('#remain-stamps-use').on('change', function(){
		var num = $(this).val();
		var needStampsNum = $('#totalStamps').text();

		if (num < 0 || num == '') {
			num = 0;
			layer.msg('输入有误');
		}
		if (num > luckyBag.remainStamps) {
			num = luckyBag.remainStamps;
			layer.msg('已超出剩余夺宝券张数');						
		}
		cost = luckyBag.isUseStamps ? (needStampsNum - num) * 1 : needStampsNum;
		if (parseInt(num) > parseInt(needStampsNum)) {
			num = needStampsNum;
			cost = 0;
			layer.msg('不需要这么多张券哦');
		}
		$('#remain-stamps-use').val(num);
		$('#needPayAmount, #totalPay').text('￥'+ cost);
	});
};

var fnStatusUseStamps = function () {
	var useStamps = $('#use-stamps');
	var cost;
	useStamps.on('click', function(){
		var needStampsNum = $('#totalStamps').text();
		var useStampsNum = $('#remain-stamps-use').val();
		if (!$(this).is(':checked')) {
			cost = needStampsNum;
			$(this).prop('checked', false);
			luckyBag.isUseStamps = false;
			$('#needPayAmount, #totalPay').text('￥'+ cost);
		} else {
			cost = (needStampsNum - useStampsNum) *1;
			if (parseInt($('#totalStamps').text()) < parseInt($('#remain-stamps-use').val())) {
				cost = 0;
			}
			luckyBag.isUseStamps = true;
			$(this).prop('checked', true);
			$('#needPayAmount, #totalPay').text('￥'+ cost);
		}
	});
};

var fnInit = function () {

	$('#totalStamps').text(luckyBag.stampsOne);
	$('.use-stamps strong').text(luckyBag.remainStamps);
	$('#remain-stamps-use').val((luckyBag.remainStamps >= luckyBag.stampsOne ? luckyBag.stampsOne : luckyBag.remainStamps));
	
	if (luckyBag.remainStamps == 0) { // 当前无夺宝券
		$('#remain-stamps-use').val(0);
	}


	var needStamps = $('#totalStamps').text();
	var useStamps = $('#remain-stamps-use').val();
	var totalCost = needStamps - useStamps;
	
	
	$('#use-stamps').prop('checked', true);
	$('#remain-stamps').text(luckyBag.defaultCoupon);

	$('#needPayAmount, #totalPay').text('￥'+ totalCost);
	
};