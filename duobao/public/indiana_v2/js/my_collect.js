/*
 * 我的收藏 my_collect.js
 * author:yilj@snsshop.cn
 * date:2016-4-5
*/


$(function(){
	DUOBAO.collect.init();
	DUOBAO.collect.changePartiTimes();
	DUOBAO.collect.changeStatus();
})

DUOBAO.collect.init = function() {
	var aItem = $('.items').not('.items-invalid');
	aItem.each(function(){
		var _this = $(this);
		var itemInput = _this.find('.check-wrap input');
		var itemSingleCost = _this.find('.single-cost strong').text().substring(1);
		var partiTime = $('.quantity').val();
		var itemCost = partiTime * parseInt(itemSingleCost);

		itemInput.prop('checked', true);
		DUOBAO.collect.totalPrize++;
	 	DUOBAO.collect.totalPartiTimes += parseInt(partiTime);
	 	DUOBAO.collect.cost += parseInt(itemCost);
	 	// console.log(DUOBAO.collect.totalPrize, DUOBAO.collect.totalPartiTimes, DUOBAO.collect.cost);
	 	$('#totalQty').text(DUOBAO.collect.totalPrize);
	 	$('#totalTimes').text(DUOBAO.collect.totalPartiTimes);
	 	$('.total-cost strong').text('￥'+ DUOBAO.collect.cost);
	});
};
DUOBAO.collect.changePartiTimes = function() {
	var oBtnIncrease = $('.quantity-increase');
	var oBtnDecrease = $('.quantity-decrease');
	var oInput = $('.quantity');

	oBtnIncrease.on('click', function(){
		var aItem = $('.items').not('.items-invalid');
		var partiTimes = $(this).parent().find('.quantity').val();
		partiTimes++;

		$(this).parent().find('.quantity').val(partiTimes);
		aItem.each(function(){
			console.log(DUOBAO.collect.totalPartiTimes, DUOBAO.collect.cost);
			var itemInput = $(this).find('.check-wrap input');
			var itemSingleCost = $(this).find('.single-cost strong').text().substring(1);
			if (itemInput.is(':checked')) {
				DUOBAO.collect.totalPartiTimes++;
				DUOBAO.collect.cost += parseInt(itemSingleCost);
			}
			$('#totalTimes').text(DUOBAO.collect.totalPartiTimes);
			$('.total-cost strong').text('￥'+ DUOBAO.collect.cost);
		});
	});

	oBtnDecrease.on('click', function(){
		var aItem = $('.items').not('.items-invalid');
		var partiTimes = $(this).parent().find('.quantity').val();

		aItem.each(function(){
			var itemInput = $(this).find('.check-wrap input');
			var itemSingleCost = $(this).find('.single-cost strong').text().substring(1);
			if (itemInput.is(':checked')) {
				if (partiTimes <= 1) {
					DUOBAO.collect.totalPartiTimes = DUOBAO.collect.totalPartiTimes;
					DUOBAO.collect.cost = DUOBAO.collect.cost;
				} else {
					DUOBAO.collect.totalPartiTimes--;
					DUOBAO.collect.cost -= parseInt(itemSingleCost);						
				}
			}
			$('#totalTimes').text(DUOBAO.collect.totalPartiTimes);
			$('.total-cost strong').text('￥'+ DUOBAO.collect.cost);
		});
		partiTimes--;
		if (partiTimes <= 1) {
			partiTimes = 1;
		}
		$(this).parent().find('.quantity').val(partiTimes);
	});

	oInput.on('blur', function(){
		var qty = $(this).val();
		DUOBAO.collect.totalPartiTimes = 0;
		DUOBAO.collect.cost = 0;
		var aItem = $('.items').not('.items-invalid');

		aItem.each(function(){
			var itemInput = $(this).find('.check-wrap input');
			var itemSingleCost = $(this).find('.single-cost strong').text().substring(1);
			var itemCost = qty * parseInt(itemSingleCost);
			if (itemInput.is(':checked')) {
				DUOBAO.collect.totalPartiTimes += parseInt(qty);
				DUOBAO.collect.cost += parseInt(itemCost);
			}
			$('#totalTimes').text(DUOBAO.collect.totalPartiTimes);
			$('.total-cost strong').text('￥'+ DUOBAO.collect.cost);
		});
	});
};
DUOBAO.collect.changeStatus = function() {
	var aInput = $('.check-wrap input');

	aInput.on('change', function(){
		var qty = $('.quantity').val();
		var itemSingleCost = $(this).closest('.items').find('.single-cost strong').text().substring(1);
		var itemCost = qty * parseInt(itemSingleCost);
		if ($(this).is(':checked')) {
			DUOBAO.collect.totalPrize++;
			DUOBAO.collect.totalPartiTimes += parseInt(qty);
			DUOBAO.collect.cost += parseInt(itemCost);
		} else {
			DUOBAO.collect.totalPrize--;
			DUOBAO.collect.totalPartiTimes -= qty;
			DUOBAO.collect.cost -= itemCost;
		}
	 	$('#totalQty').text(DUOBAO.collect.totalPrize);
	 	$('#totalTimes').text(DUOBAO.collect.totalPartiTimes);
	 	$('.total-cost strong').text('￥'+ DUOBAO.collect.cost);
	});
};