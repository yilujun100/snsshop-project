$(function () {

	$('.btn-prize-confirm').on('click', function () {

		var $this =  $(this);

		if (! active_name_checker()) {
			return;
		}

		var url = $this.attr('data-url');

		DUOBAO._post(url, {
			goods_id: $this.attr('data-id'),
			code_num: $('#codeNum').val(),
			active_name: $('#activeName').val(),
            is_open:$('#isOpen').val()
		}, function (res) {
			if (0 == res.retCode) {
				layer.msg(res.retMsg, {time: 1000, icon: 1}, function(){location.href = $this.attr('data-detail') + '?share=1&id=' + res.retData.id});
			} else {
				layer.msg(res.retMsg, {time: 5000, icon: 2});
			}
		});
	});

	$('#activeName').on('blur', function () {
		active_name_checker();
	});

	function active_name_checker() {
		var active_name = $('#activeName').val();
		if (! active_name) {
			// layer.tips('不能为空', $('#activeName'), {tips: 1});
			layer.alert('活动名称不能为空', {title: false, closeBtn: 0}, function(index){
				$('#activeName').focus();
				layer.close(index);
			});
			return false;
		}
		if (! /^[a-zA-Z\u4E00-\u9FA5\uF900-\uFA2D]+$/.test(active_name)) {
			// layer.tips('非法字符', $('#activeName'), {tips: 1});
			layer.alert('非法字符', {title: false, closeBtn: 0}, function(index){
				$('#activeName').focus();
				layer.close(index);
			});
			return false;
		}
		var length_max = $('#activeName').attr('data-max');
		if (active_name.length > length_max) {
			// layer.tips('长度不能大于' + length_max, $('#activeName'), {tips: 1});
			layer.alert('长度不能大于'+ length_max, {title: false, closeBtn: 0}, function(index){
				layer.close(index);
			});
			return false;
		}
		return true;
	}

	$('.quantity-increase').on('click', function () {
		var code_num = $('#codeNum').val();
		if (code_num) {
			set_code_price(++ code_num);
		}
	});
	$('.quantity-decrease').on('click', function () {
		var code_num = $('#codeNum').val();
		if (code_num > 1) {
			set_code_price(-- code_num);
		}
	});
	$('#codeNum').on('keyup', function () {
		var code_num = $(this).val();
		if (code_num) {
			set_code_price(code_num);
		}
	});
	var goods_price = $('#goodsPrice').val();
	function set_code_price(code_num) {
		$('.code-price').text(Math.ceil(goods_price / code_num / 100));
	}
	
	DUOBAO.chooseQty.init();
});
