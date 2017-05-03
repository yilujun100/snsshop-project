/*
 * 立即参与快捷支付 payment.js
 * author:yilj@snsshop.cn
 * date:2016-6-13
*/

DUOBAO.payment.init = function() {
	var chooseItem = $('.choose-times li');

	if (DUOBAO.payment.remainTimes < 5) {
		DUOBAO.payment.partiTimes = DUOBAO.payment.remainTimes;
	}

	chooseItem.each(function(){
		var _this = $(this);
		var itemProbability = _this.attr('data-times');
		if (DUOBAO.payment.remainTimes < itemProbability) {
			_this.addClass('choose-disabled');
		}
	});

    $('.pay-product-price b').text(DUOBAO.payment.needTimes);
    $('.pay-product-price strong').text(DUOBAO.payment.remainTimes);
    $('.single-cost strong').text(DUOBAO.payment.singleCost);
    $('.stamps-my b').text(DUOBAO.payment.stampsRemain);
    changePartiTimes(DUOBAO.payment.partiTimes, 0);

};
DUOBAO.payment.init_cart = function(stampsTotalUse) {
    changePartiTimes(stampsTotalUse, 0);
};
// 参与次数
DUOBAO.payment.changePartiTimes = function() {
	var oBtnIncrease = $('#qtyIncrease'),
		oBtnDecrease = $('#qtyDecrease');
	var oInput = $('#qty');

	oBtnIncrease.on('click', function(){
		var qty = $(this).parent().find('.quantity').val();
		qty++;
		if (qty > DUOBAO.payment.remainTimes) {
			qty = DUOBAO.payment.remainTimes;
			DUOBAO.popWinTips.init('参与次数达到上限', parseFn);
			function parseFn(){
				//alert('回调处理函数');
			}
			// layer.msg('参与次数达到上限', {time: 0});
		}
		if (qty > 1) {
			oBtnDecrease.removeClass('btn-qty-disabled');
		}
        changePartiTimes(qty);
	});
	oBtnDecrease.on('click', function(){
		var qty = $(this).parent().find('.quantity').val();
		qty--;
		if (qty <= 1) {
			qty = 1;
			$(this).addClass('btn-qty-disabled');
		}
        changePartiTimes(qty);
	});
	oInput.on('change', function(){
		var qty = $(this).val();
		var ruleReg = /^[1-9]\d*$/;
		if (isEmpty('.quantity')) {
			// layer.msg('请输入参与次数');
			DUOBAO.popWinTips.init('请输入参与次数');
			$(this).focus();
		} else if (!ruleReg.test(qty)) {
			// layer.msg('输入有误');			
			DUOBAO.popWinTips.init('输入有误');
			$(this).val(1);
			$(this).focus();
			qty = 1;
		} else if (qty > DUOBAO.payment.remainTimes) {
			// layer.msg('参与次数达到上限');
			DUOBAO.popWinTips.init('参与次数达到上限');
			$(this).val(DUOBAO.payment.remainTimes);
			qty = DUOBAO.payment.remainTimes;
		} 
		if (qty == 1) {
			oBtnDecrease.addClass('btn-qty-disabled');
		} else {
			oBtnDecrease.removeClass('btn-qty-disabled');
		}
        changePartiTimes(qty);
	});
};
// 提升中奖概率
DUOBAO.payment.probaImprove = function() {
	var chooseItem = $('.choose-times li:not(.choose-disabled)');
    chooseItem.each(function(){
		var _this = $(this);
		_this.on('click', function(){
            var partiTimes = $(this).attr('data-times');
            $('.quantity-decrease').removeClass('btn-qty-disabled');
            changePartiTimes(partiTimes);
		});
	});
};
// 包尾
DUOBAO.payment.buyOut = function() {
    changePartiTimes(DUOBAO.payment.remainTimes);
};

// 确认参与
DUOBAO.payment.partiConfirm = function(callback) {
	$('.btn-parti-confirm').on('click', function(){
		if ($(this).hasClass('btn-parti-disabled')) { // 夺宝券不足
			DUOBAO.popWinConfirm.init('您的券不足，请先充值', '立即充值', '取消', recharge);
		} else if (DUOBAO.payment.has_cash_cards) {
            var t_coupon_use = parseInt($('.coupon-use strong').text(), 10);
            var t_stamps_use = parseInt($('.stamps-use strong').text(), 10);
            var t_stamps_actual = parseInt($('.stamps-actual strong').text(), 10);
            if (t_stamps_use < (t_coupon_use + t_stamps_actual)) {
                //DUOBAO.popWinConfirm.init('您的券不足，请先充值', '立即充值', '取消', callback);
                // 卡包提示框
                $('.pop-card-mask, .pop-card').show();
                $('.pop-card .btn-pop-cancel').on('click', function(){
                    $('.pop-card-mask, .pop-card').hide();
                });
                $('.pop-card .btn-pop-use').on('click', function(){
                    $('.pop-card-mask, .pop-card').hide();
                    callback();
                });
            } else {
                callback();
            }
        } else {
			callback();
		}
	});
};

function changePartiTimes(partiTimes, cardCouponUse, changeTime){
    var stampsTotalUse, actualStampUse;
    if (typeof DUOBAO.payment.is_cart != 'undefined' && DUOBAO.payment.is_cart == 1) {
        stampsTotalUse = partiTimes;
    } else {
        if (typeof changeTime != 'undefined' && changeTime == true) {
            stampsTotalUse = partiTimes;
        } else {
            var winProbability;
            winProbability = ((partiTimes / DUOBAO.payment.needTimes) * 100).toFixed(2);
            stampsTotalUse = partiTimes * DUOBAO.payment.singleCost;
            $('.quantity').val(partiTimes);
            $('.probability b').text(winProbability);
            $('.parti-times strong').text(partiTimes);
        }
    }

    if (typeof cardCouponUse !='undefined') {
        $('.coupon-use strong').text(cardCouponUse)
    }

    if (DUOBAO.payment.has_cash_cards) {
        var couponUse = $('.coupon-use strong').text();
        actualStampUse = stampsTotalUse - couponUse;
        if (actualStampUse <=0) {
            actualStampUse = 0;
        }
        $('.stamps-actual strong').text(actualStampUse);
        $('.stamps-use strong, .stamps-use-1 strong').text(stampsTotalUse);
    } else {
        actualStampUse = stampsTotalUse;
        $('.stamps-use strong, .stamps-use-1 strong').text(stampsTotalUse);
    }
    if (DUOBAO.payment.stampsRemain < actualStampUse) {
        //$('.btn-parti-confirm').addClass('btn-parti-disabled').removeClass('btn-parti-confirm');
        $('.btn-parti-confirm').addClass('btn-parti-disabled');
        $('.stamps-insuff').show().prev().hide();
    } else {
        //$('.btn-parti-disabled').removeClass('btn-parti-disabled').addClass('btn-parti-confirm');
        $('.btn-parti-disabled').removeClass('btn-parti-disabled');
        $('.stamps-insuff').hide().prev().show();
    }
}

function recharge(){
    var buyAmount = getStampsActual();
    changePopQty(buyAmount, 2);
	DUOBAO.popWinConfirm.hide();
	$('.pop-card-package-mask, .pop-card-package').show();
	changeTimes();
}

function getStampsActual() {
    var buyAmount;
    if (DUOBAO.payment.has_cash_cards) {
        buyAmount = $('.stamps-actual strong').text() - DUOBAO.payment.stampsRemain;
    } else {
        buyAmount = $('.stamps-use strong').text() - DUOBAO.payment.stampsRemain;
    }
    if (buyAmount <2) {
        buyAmount = 2;
    }
    return buyAmount;
}

function changePopQty(qtyInit, qtyType) {
    if (qtyType == 1) {
        qtyInit++;
    } else if (qtyType == 0) {
        qtyInit--;
    }
    min = getStampsActual();
    if ($('#popS3').is(":visible") && $('#popS3 input').is(':checked')) {
        var card_stamps = $('#popS3').find('.card-item').attr('data-recharge-num');
        if(card_stamps > min) {
            min = card_stamps;
        }
    }
    if (min < 2) { min = 2; }
    if (qtyInit < min) { qtyInit = min; }
    $('#popQty').val(qtyInit);
    $('#popPay strong').text('￥'+ qtyInit);
}

function changeTimes(){
	var oBtnPopIncrease = $('#popQtyIncrease'),
		oBtnPopDecrease = $('#popQtyDecrease'),
		txtField = $('#popQty'),
		regNumber = /^[0-9]\d*$/;

	oBtnPopIncrease.off().on('click', function(){
        changePopQty($('#popQty').val(), 1);
	});
	oBtnPopDecrease.off().on('click', function(){

        changePopQty($('#popQty').val(), 0);
	});
	txtField.on({
		keyup: function(){
            if (isEmpty('#popQty')) {
                DUOBAO.popWinTips.init('请输入购买券张数');
            } else {
                if (!regNumber.test(trim($(this).val()))) {
                    DUOBAO.popWinTips.init('格式错误');
                } else {
                    changePopQty($(this).val(), 2);
                }
            }
		},
		blur: function(){
            txtField.trigger('keyup');
		}
	});	
}