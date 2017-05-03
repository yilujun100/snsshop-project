/*
 * 发福袋 send_bag.js
 * author:yilj@snsshop.cn
 * date:2016-1-30
*/

$(function(){
	// tab
	fnTab('.tab-tit a', '.tab-con > div');	
	
	$('.tab-tit a').on('click', function(){
		sendBagInit();
		if ($(this).hasClass('tit-fight-luck')) { // 拼手气
			$('.f-item-ordinary-number').hide().prev().show();
			$('.f-item-fight-luck').show().next().hide();
		}
		if ($(this).hasClass('tit-ordinary')) { // 普通
			$('.f-item-ordinary-number').show().prev().hide();
			$('.f-item-ordinary').show().prev().hide();
		}
	});	
	
	var itemInput = $('.f-item input[type="text"]');
	itemInput.each(function(){
		var _this = $(this);
		_this.on('blur', function(){
			var number = $('#number'),
				number1 = $('#numberOrdinary'),
				vouchersSum = $('#vouchersSum'),
				vouchersEach = $('#vouchersEach'),
				numVal = number.val(),
				num1Val = number1.val(),
				vouchersSumVal = vouchersSum.val(),
				vouchersEachVal = vouchersEach.val();
			
			if ($('.tit-fight-luck').hasClass('active')) { // 拼手气福袋
				if (numVal != '' && vouchersSumVal != '') {
					if (parseInt(vouchersSumVal) < parseInt(numVal)) {
						layer.msg('夺宝券张数必须大于发放人数');
						/*layer.open({
							content: '夺宝券张数必须大于发放人数',
							time: 3
						});*/
					} else {
						var stampsUse = parseInt(vouchersSumVal) >= parseInt(luckyBag.remainVoucherNum) ? luckyBag.remainVoucherNum : vouchersSumVal;
						fnNeedPay(vouchersSumVal, stampsUse);
					}
				}				
			}
			
			if ($('.tit-ordinary').hasClass('active')) { // 普通福袋
				if (num1Val != '' && vouchersEachVal != '') {
					/*if (parseInt(vouchersEachVal) < parseInt(num1Val)) {
						layer.msg('夺宝券张数必须大于发放人数');
					} else {						
						var stampsUse = parseInt(vouchersEachVal) >= parseInt(luckyBag.remainVoucherNum) ? luckyBag.remainVoucherNum : vouchersEachVal;
						fnNeedPay(vouchersEachVal, stampsUse);						
					}*/
					// var stampsUse = parseInt(vouchersSumVal) >= parseInt(luckyBag.remainVoucherNum) ? luckyBag.remainVoucherNum : vouchersSumVal;
					var stampsUse = parseInt(luckyBag.remainVoucherNum);
					var needStamps = parseInt(vouchersEachVal*num1Val);
					fnNeedPay(needStamps, stampsUse);
				}				
			}
		})
	})
	// 确认提交
	$('#btnConfirm').on('click', function(){
		var numVal = $('#number').val(),
			num1Val = $('#numberOrdinary').val(),
			vouchersSumVal = $('#vouchersSum').val(),
			vouchersEachVal = $('#vouchersEach').val(),
			cost = $('#needPay').text();
		// luckyBag.data.fight_lucky.num = $('#number').val();
		var flag = validate();
		if (flag) {
			if ($('.tit-fight-luck').hasClass('active')) { // 拼手气
				/*$.ajax({
					url: luckyBag.url.fight_lucky,
					type: 'post',
					dataType: 'json',
					data: luckyBag.data.fight_lucky,
					success: function () {						
						$('.mask, .popup-send-bag').show();
						$('#btnPopupClose').on('click', function(){
							$('.mask, .popup-send-bag').hide();
						});
					}
				});*/	
				$('.mask, #popupInstalled').show();
				$('#btnPopupClose1').on('click', function(){
					$('#popupInstalled').hide();
					$('#popupToCenter').show();
				});
			}
			if ($('.tit-ordinary').hasClass('active')) { // 普通
				/*$.ajax({
					url: luckyBag.url.ordinary,
					type: 'post',
					dataType: 'json',
					data: {num1: num1Val, vouchersEach: vouchersEachVal, cost: cost},
					success: function () {						
						$('.mask, .popup-send-bag').show();
						$('#btnPopupClose').on('click', function(){
							$('.mask, .popup-send-bag').hide();
						});
					}
				});*/
				/*var num1Val = number1.val();
				var vouchersEachVal = vouchersEach.val();
				var needStamps = num1Val * vouchersEachVal;
				var remainStamps = luckyBag.remainVoucherNum;
				fnNeedPay(needStamps, remainStamps);*/
				$('.mask, #popupInstalled').show();
				$('#btnPopupClose1').on('click', function(){
					$('#popupInstalled').hide();
					$('#popupToCenter').show();
				});
			}
		}
		return false;
	});
	
	// 朕要发福袋
	$('#btnPopSend').on('click', function(){
		$('.popup-send-bag').hide();
		$('.popup-share').show();
	});
	
	// 继续发此福袋
	$('#sendContinue').on('click', function(){
		$('.popup-send-bag').hide();
		$('.popup-share').show();
	});
	
	
	$('.mask, .popup-share').on('click', function(){		
		$('.mask, .popup').hide();
	});
	
	$('#btnPopupClose').on('click', function(){
		$('.mask, .popup').hide();
	});
});


// 初始化
var sendBagInit = function (remainStamps) {
	var num = $('#number'),
		num1 = $('#numberOrdinary'),
		vouchersSum = $('#vouchersSum'),
		vouchersEach = $('#vouchersEach');
		
	if ($('.tit-fight-luck').hasClass('active')) { // 拼手气福袋
		var numVal = isEmpty(num) ? '' : num.val();
		var vouchersSumVal = isEmpty(vouchersSum) ? '' : vouchersSum.val();
		var needPay = $('.wishes-fight-luck').children().find('span').text() == 0 ? 0 : $('.wishes-fight-luck').children().find('span').text();
		num.val(numVal);
		vouchersSum.val(vouchersSumVal);
		$('#needPay').text('￥'+ needPay);
	}
	
	if ($('.tit-ordinary').hasClass('active')) { // 普通福袋
		var num1Val = isEmpty(num1) ? '' : num1.val();
		var vouchersEachVal = isEmpty(vouchersEach) ? '' : vouchersEach.val();
		var needPay = $('.wishes-ordinary').children().find('span').text() == 0 ? 0 : $('.wishes-ordinary').children().find('span').text();
		num1.val(num1Val);
		vouchersEach.val(vouchersEachVal);
		$('#needPay').text('￥'+ needPay);
	}
	
}

// 校验
var validate = function () {
	var isChecked = false;
	var itemInputs = $('.f-item input[type="text"]');
	var num = itemInputs.eq(0);
	var num1 = itemInputs.eq(1);
	var vouchersSum = itemInputs.eq(2);
	var vouchersEach = itemInputs.eq(3);
	var reg = /^\+?[1-9]\d*$/;
	
	if ($('.tit-fight-luck').hasClass('active')) { // 拼手气福袋		
		if (isEmpty(num)) { 
			layer.msg('请输入请输入请输入请输入请输入请输入请输入请输入请输入请输入请输入请输入'+ num.prev('label').text(), {shift: -1, time: 300000}, function(){
				alert(1111);
			});
			/*layer.open({
				content: '请输入请输入请输入请输入请输入请输入请输入请输入请输入请输入请输入请输入'+ num.prev('label').text(),
				time: 3
			});*/
		} else if (!reg.test(trim(num.val()))) {
			layer.msg('格式错误');			
			/*layer.open({
				content: '格式错误',
				time: 3
			});*/
		} else if (isEmpty(vouchersSum)) {
			layer.msg('请输入'+ vouchersSum.prev('label').text());		
			/*layer.open({
				content: '请输入'+ vouchersSum.prev('label').text(),
				time: 3
			});*/
		} else if (!reg.test(trim(vouchersSum.val()))) {
			// layer.msg('格式错误');
			layer.open({
				content: '格式错误',
				time: 3
			});
		} else if (parseInt(vouchersSum.val()) < parseInt(num.val())) {
			layer.msg('夺宝券张数必须大于发放人数');
			/*layer.open({
				content: '夺宝券张数必须大于发放人数',
				time: 3
			});*/
		} else {
			isChecked = true;
		}
	}
	
	if ($('.tit-ordinary').hasClass('active')) { // 普通福袋
		if (isEmpty(num1)) { 
			layer.msg('请输入'+ num1.prev('label').text());
			/*layer.open({
				content: '请输入'+ num1.prev('label').text(),
				time: 3
			});*/
		} else if (!reg.test(trim(num1.val()))) {
			layer.msg('格式错误');
			/*layer.open({
				content: '格式错误',
				time: 3
			});*/
		} else if (isEmpty(vouchersEach)) {
			layer.msg('请输入'+ vouchersEach.prev('label').text());
			/*layer.open({
				content: '请输入'+ vouchersEach.prev('label').text(),
				time: 3
			});*/
		} else if (!reg.test(trim(vouchersEach.val()))) {
			layer.msg('格式错误');
			/*layer.open({
				content: '格式错误',
				time: 3
			});*/
		} else {
			isChecked = true;
		}	
	}
	return isChecked;	
}

// 支付金额
var fnNeedPay = function (vouchersSum, remainVoucherNum) {
	var needPay = (vouchersSum - remainVoucherNum) * 1; // 还需支付
	if (needPay < 0) {
		needPay = 0;
		if ($('.tit-ordinary').hasClass('active')) {
			remainVoucherNum = vouchersSum;

			
		}
	}
	var payTips = $('<p class="pay-tips">使用夺宝券<em>'+ remainVoucherNum +'</em>张，还需支付￥<span>'+ needPay +'</span></p>');
	
	$('.wishes').find('.pay-tips').remove();
	if ($('.tit-fight-luck').hasClass('active')) {
		$('.wishes-fight-luck').append(payTips);
	}
	if ($('.tit-ordinary').hasClass('active')) {
		$('.wishes-ordinary').append(payTips);
	}	
	$('#needPay').text('￥'+ needPay);	
}