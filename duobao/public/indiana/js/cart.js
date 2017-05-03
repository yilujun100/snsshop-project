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

	oBtnIncrease.on('click', function(){
		var item = $(this).closest('.items');
		var checkBox = item.find('.check-wrap input');
		if (checkBox.is(':checked')) {
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text().substring(1);
			qty++;

			DUOBAO.cart.totalPartiTimes++;
			DUOBAO.cart.totalCost = DUOBAO.cart.totalCost + parseInt(singleCost);
			item.find('.quantity').val(qty);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
            $('input[name="total"]').val(DUOBAO.cart.totalCost);
		} else {
			return;
		}
	});

	oBtnDecrease.on('click', function(){
		var item = $(this).closest('.items');
		var checkBox = item.find('.check-wrap input');
		if (checkBox.is(':checked')) {
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text().substring(1);
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
			$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
            $('input[name="total"]').val(DUOBAO.cart.totalCost);
		} else {
			return;
		}
	});

	// 改变列表项选中状态
	var aInput = $('.check-wrap input');
	aInput.on('change', function(){
		if ($(this).is(':checked')) {
			var item = $(this).closest('.items');
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text().substring(1);
			var itemCost = qty * parseInt(singleCost);

			DUOBAO.cart.prizeNum++;
			DUOBAO.cart.totalPartiTimes += parseInt(qty);
			DUOBAO.cart.totalCost += parseInt(itemCost);
			$('#totalQty').text(DUOBAO.cart.prizeNum);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
		} else {
			var item = $(this).closest('.items');
			var qty = item.find('.quantity').val();
			var singleCost = item.find('.single-cost strong').text().substring(1);
			var itemCost = qty * parseInt(singleCost);

			DUOBAO.cart.prizeNum--;
			DUOBAO.cart.totalPartiTimes -= parseInt(qty);
			DUOBAO.cart.totalCost -= parseInt(itemCost);
			$('#totalQty').text(DUOBAO.cart.prizeNum);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
            $('input[name="total"]').val(DUOBAO.cart.totalCost);
		}
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
				var singleCost = item.eq(i).find('.single-cost strong').text().substring(1);
				var itemCost = qty * parseInt(singleCost);

				DUOBAO.cart.prizeNum++;
				DUOBAO.cart.totalPartiTimes += parseInt(qty);
				DUOBAO.cart.totalCost += parseInt(itemCost);
				$('#totalQty').text(DUOBAO.cart.prizeNum);
				$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
				$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
                $('input[name="total"]').val(DUOBAO.cart.totalCost);
			}
		} else {
			$('.items input[type="checkbox"]').prop('checked', false);
			$('#totalQty').text(DUOBAO.cart.prizeNum);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
            $('input[name="total"]').val(DUOBAO.cart.totalCost);
		}
	});

	// 删除
	$('#cartDel').on('click', function(){
		var aInputChecked = $('.items input[type="checkbox"]:checked');
        var _this = $(this);
		if (aInputChecked.length <= 0) {
			layer.msg('请选择删除项');
		} else {
			layer.confirm('确认删除所选项', {
				title: false,
				closeBtn: false
			},
		 	function(){
                layer.closeAll();
                DUOBAO._post(DUOBAO.url.del_cart,$('#myform').serialize(),function($res){
                    if($res.retCode == 0){
                        layer.msg('删除成功',function(){
                            location.reload();
                        });
                    }else{
                        layer.msg('操作失败');
                    }
                })
			});
		}
	});

	// 删除失效
	$('.itemInvalidDel').on('click', function(){
		var itemInvalid = $(this).closest('.items-invalid');
        var _this = $(this);

		layer.confirm('确认删除所选项', {
			title: false,
			closeBtn: false
		},
	 	function(){
			layer.closeAll();
            DUOBAO._post(DUOBAO.url.del_cart,{'peroid_str':_this.attr('peroid-str')},function($res){
                if($res.retCode == 0){
                    layer.msg('删除成功',function(){
                        itemInvalid.parent().remove();
                    });
                }else{
                    layer.msg('操作失败');
                }
            })
		});
	});


	function cartInit(){
		$('.check-wrap input[type="checkbox"]').each(function(){
			var _this = $(this);
			if (_this.is(':checked')) {
				var item = _this.closest('.items');
				var itemSingleCost = item.find('.single-cost strong').text().substring(1);
				var itemPartiTimes = item.find('.quantity').val();
				var itemCost = itemSingleCost * itemPartiTimes;
				DUOBAO.cart.totalPartiTimes += parseInt(itemPartiTimes);
				DUOBAO.cart.totalCost += itemCost;
				DUOBAO.cart.prizeNum++;
			}
			// console.log(DUOBAO.cart.prizeNum, DUOBAO.cart.totalPartiTimes, DUOBAO.cart.totalCost);
			$('#totalQty').text(DUOBAO.cart.prizeNum);
			$('#totalTimes').text(DUOBAO.cart.totalPartiTimes);
			$('#totalCost').text('￥'+ DUOBAO.cart.totalCost);
            $('input[name="total"]').val(DUOBAO.cart.totalCost);
		})
	}
})