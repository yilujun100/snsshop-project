/*
 * 购买夺宝券 buy_indiana_stamps.js
 * author:yilj@snsshop.cn
 * date:2016-1-30
*/

$(function(){
	$('#buyStampsNum').on('blur', function(){
		var buyNumVal = $(this).val();
		$('.pay-amount strong').text(buyNumVal);
		$('#needPay').text('￥'+ buyNumVal);
	});
	
	$('#btnConfirm').on('click', function(){
		var buyNum = $('#buyStampsNum');
		var reg = /^\+?[1-9]\d*$/;
		if (isEmpty(buyNum)) {
			layer.msg('请输入'+ $('#f-buy-stamps label').text());
		} else if (!reg.test(buyNum.val())) {
			layer.msg('格式错误');
		} else {
			// @后台： 提交后台数据
			// $.ajax();
			$('.popup-buy-success .popup-tit strong').text(buyNum.val());
			$('.mask, .popup-buy-success').show();
			$('#btnPopupClose').on('click', function(){
				$('.mask, .popup-buy-success').hide();
			});
		}
		return false;
	});
});