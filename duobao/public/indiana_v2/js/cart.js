/*
 * 夺宝车 cart.js
 * author:yilj@snsshop.cn
 * date:2016-4-5
*/

$(function(){

	cartInit();

	// 改变奖品数量
	var oBtnIncrease = $('.quantity-increase');
	var oBtnDecrease = $('.quantity-decrease');
	var oBtnBuyOut = $('.end-check');
	var aInputTxt = $('.quantity');

	oBtnIncrease.on('click', function(){
		var item = $(this).closest('.items');
		var checkBox = item.find('.check-wrap input');
		var remainTimes = item.find('.cart-product-price strong').text();
		if (checkBox.is(':checked')) {
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text();
			qty++;

			DUOBAO.cart.totalPartiTimes++;
			DUOBAO.cart.totalCost = DUOBAO.cart.totalCost + parseInt(singleCost);
			if (qty >= remainTimes) {

				var aCheckBox = $('.cart-list input:checked');
				DUOBAO.cart.totalPartiTimes = 0;
				DUOBAO.cart.totalCost = 0;
				console.log(aCheckBox.length);
				aCheckBox.each(function(){
					var changeTimes = $(this).closest('.items').find('.quantity').val();
					DUOBAO.cart.totalPartiTimes += parseInt(changeTimes);
					DUOBAO.cart.totalCost += parseInt(changeTimes) * parseInt(singleCost);
				});
				// console.log(DUOBAO.cart.totalPartiTimes);
				// console.log(DUOBAO.cart.totalCost);
                DUOBAO.popWinTips.init('参与次数达到上限');
				qty = remainTimes;
			}
			item.find('.quantity').val(qty);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
			$(this).closest('.items').find('.cart-product-price strong').text(remainTimes);
		} else {
			// return;
			var qty = item.find('.quantity').val(),
				singleCost = item.find('.single-cost strong').text();
			qty++;
			DUOBAO.cart.totalPartiTimes += qty; 
			DUOBAO.cart.totalCost = DUOBAO.cart.totalCost + parseInt(singleCost * qty);
			if (qty >= remainTimes) {

				var aCheckBox = $('.cart-list input:checked');
				DUOBAO.cart.totalPartiTimes = 0;
				DUOBAO.cart.totalCost = 0;
				aCheckBox.each(function(){
					var changeTimes = $(this).closest('.items').find('.quantity').val();
					DUOBAO.cart.totalPartiTimes += parseInt(changeTimes);
					DUOBAO.cart.totalCost += parseInt(changeTimes) * parseInt(singleCost);
				});
                DUOBAO.popWinTips.init('参与次数达到上限');
				qty = remainTimes;
			}
			checkBox.prop('checked', true);
			$('#totalQty').text($('.cart-list :checkbox:checked').length);
			item.find('.quantity').val(qty);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
		}
	});

	oBtnDecrease.on('click', function(){
		var item = $(this).closest('.items');
		var checkBox = item.find('.check-wrap input');
		if (checkBox.is(':checked')) {
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text();
			if (qty <= 1) {
				qty = 1;
				DUOBAO.cart.totalPartiTimes = DUOBAO.cart.totalPartiTimes;
				DUOBAO.cart.totalCost = DUOBAO.cart.totalCost;
			} else {
				qty--;
				DUOBAO.cart.totalPartiTimes--;
				DUOBAO.cart.totalCost = DUOBAO.cart.totalCost - parseInt(singleCost);					
			}
			item.find('.quantity').val(qty);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
		} else {
			// return;
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text();
			qty--;
			if (qty <= 1) {
				qty = 1;
			}
			DUOBAO.cart.totalPartiTimes += qty; 
			DUOBAO.cart.totalCost = DUOBAO.cart.totalCost + parseInt(singleCost * qty);
			checkBox.prop('checked', true);
			$('#totalQty').text($('.cart-list :checkbox:checked').length);
			item.find('.quantity').val(qty);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
		}
	});

	// 包尾
	oBtnBuyOut.on('click', function(){
		var item = $(this).closest('.items');
		var checkBox = item.find('.check-wrap input');
		var remainTimes = item.find('.cart-product-price strong').text();
		if (checkBox.is(':checked')) {
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text();
			var changeTimes = remainTimes - qty;

			DUOBAO.cart.totalPartiTimes += changeTimes;
			DUOBAO.cart.totalCost = DUOBAO.cart.totalCost + (parseInt(singleCost)*changeTimes);

			$('#totalCost').text(DUOBAO.cart.totalCost);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			item.find('.quantity').val(remainTimes);
			$('#totalQty').text($('.cart-list :checkbox:checked').length);
		} else {
			var singleCost = item.find('.single-cost strong').text();
			var remainCost = remainTimes * parseInt(singleCost);
			var changeTimes = parseInt(remainTimes);

			DUOBAO.cart.totalPartiTimes += changeTimes;
			DUOBAO.cart.totalCost = DUOBAO.cart.totalCost + remainCost;

			item.find('.check-wrap input').prop('checked', true);
			DUOBAO.cart.prizeNum = $(':checkbox:checked').length;
			$('#totalCost').text(DUOBAO.cart.totalCost);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			item.find('.quantity').val(remainTimes);
			$('#totalQty').text($('.cart-list :checkbox:checked').length);
		}
	});


	// 改变列表项选中状态
	var aInput = $('.check-wrap input');
	aInput.on('change', function(){
		if ($(this).is(':checked')) {
			var item = $(this).closest('.items');
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text();
			var itemCost = qty * parseInt(singleCost);

			DUOBAO.cart.totalPartiTimes += parseInt(qty);
			DUOBAO.cart.totalCost += parseInt(itemCost);
			$('#totalQty').text($('.cart-list :checkbox:checked').length);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
		} else {
			var item = $(this).closest('.items');
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text();
			var itemCost = qty * parseInt(singleCost);

			DUOBAO.cart.totalPartiTimes -= parseInt(qty);
			DUOBAO.cart.totalCost -= parseInt(itemCost);
			$('#totalQty').text($('.cart-list :checkbox:checked').length);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
		}
	});

	// 输入框值改变
	aInputTxt.on('change', function(){
		var item = $(this).closest('.items');
		var checkBox = item.find('.check-wrap input');
		var singleCost = item.find('.single-cost strong').text();
		var num = parseInt($(this).val());
		var remainTimes = parseInt(item.find('.cart-product-price strong').text());
		if (num < 0 || num == '') {
            DUOBAO.popWinTips.init('输入有误');
			num = 1;
		}
		if (num > remainTimes) {
            DUOBAO.popWinTips.init('参与次数达到上限');
			num = remainTimes;
		}
		$(this).val(num);

		if (checkBox.is(':checked')) {
			var aCheckBox = $('.cart-list input:checked');
			DUOBAO.cart.totalPartiTimes = 0;
			DUOBAO.cart.totalCost = 0;
			console.log(aCheckBox.length);
			/*aCheckBox.each(function(){
				var changeTimes = $(this).closest('.items').find('.quantity').val();
				DUOBAO.cart.totalPartiTimes += parseInt(changeTimes);
				DUOBAO.cart.totalCost += parseInt(changeTimes) * parseInt(singleCost);
			});*/
			for (var i=0; i<aCheckBox.length; i++) {
				var changeTimes = aCheckBox.eq(i).closest('.items').find('.quantity').val();
				var changeCost = aCheckBox.eq(i).closest('.items').find('.single-cost strong').text();
				var costNew = parseInt(changeTimes) * parseInt(changeCost);
				DUOBAO.cart.totalPartiTimes += parseInt(changeTimes);
				DUOBAO.cart.totalCost += costNew;
			}
		} else {
			return;
		}
		$('#totalQty').text($('.cart-list :checkbox:checked').length);
		$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
		$('#totalCost').text(DUOBAO.cart.totalCost);
	});

	// 全选
	$('#checkAll').on('change', function(){
		DUOBAO.cart.prizeNum = 0;
		DUOBAO.cart.totalPartiTimes = 0;
		DUOBAO.cart.totalCost = 0;

		var item = $('.items').not('.items-invalid');
		if ($(this).is(':checked')) {
			$('.items input[type="checkbox"]').prop('checked', true);
			for (var i=0; i<item.length; i++) {
				var qty = item.eq(i).find('.quantity').val();
				var singleCost = item.eq(i).find('.single-cost strong').text();
				var itemCost = qty * parseInt(singleCost);

				DUOBAO.cart.prizeNum++;
				DUOBAO.cart.totalPartiTimes += parseInt(qty);
				DUOBAO.cart.totalCost += parseInt(itemCost);
				$('#totalQty').text(DUOBAO.cart.prizeNum);
				$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
				$('#totalCost').text(DUOBAO.cart.totalCost);
			}
		} else {
			$('.items input[type="checkbox"]').prop('checked', false);
			$('#totalQty').text(DUOBAO.cart.prizeNum);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);
		}
	});

	// 删除
	$('#cartDel').on('click', function(){
		var aInputChecked = $('.items input[type="checkbox"]:checked');
		if (aInputChecked.length <= 0) {
            DUOBAO.popWinTips.init('请选择删除项');
		} else {
			DUOBAO.popWinConfirm.init('确认删除所选项?', '删除', '取消', function(){
                DUOBAO._post(DUOBAO.url.del_cart,$('#myform').serialize(),function($res){
                    DUOBAO.popWinConfirm.hide();
                    if($res.retCode == 0){
                        DUOBAO.popWinTips.init('删除成功',function(){
                            location.reload();
                        });
                    }else{
                        DUOBAO.popWinTips.init('操作失败');
                    }
                })
            });
		}
	});

	// 删除失效
	$('.delFav').on('click', function(){
		var peroid_str = $(this).attr('peroid-str');

		DUOBAO.popWinConfirm.init('确认删除所选项?', '删除', '取消', function(){
            DUOBAO._post(DUOBAO.url.del_cart,{peroid_str:peroid_str},function($res){
                DUOBAO.popWinConfirm.hide();
                if($res.retCode == 0){
                    DUOBAO.popWinTips.init('删除成功',function(){
                        location.reload();
                    });
                }else{
                    DUOBAO.popWinTips.init('操作失败');
                }
            });
        });
	});



	function cartInit(){
		$('.cart-list .check-wrap input[type="checkbox"]').each(function(){
			var _this = $(this);
			if (_this.is(':checked')) {
				var item = _this.closest('.items');
				var itemSingleCost = item.find('.single-cost strong').text();
				var itemPartiTimes = item.find('.quantity').val();
				var itemCost = itemSingleCost * itemPartiTimes;
				DUOBAO.cart.totalPartiTimes += parseInt(itemPartiTimes);
				DUOBAO.cart.totalCost += itemCost;
				DUOBAO.cart.prizeNum++;
			}
			// console.log(DUOBAO.cart.prizeNum, DUOBAO.cart.totalPartiTimes, DUOBAO.cart.totalCost);
			$('#totalQty').text(DUOBAO.cart.prizeNum);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text(DUOBAO.cart.totalCost);

		})
	}
})