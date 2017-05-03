/*
 * 绑定手机号 bind_phone.js
 * author:yilj@snsshop.cn
 * date:2016-7-25
*/

(function($){
	var phoneNum = $('#phoneNum'),
		verifyCode = $('#verifyCode'),
		tips = $('#tipsSet'),
		btnClear = $('#clear'),
		btnSendCode = $('#btnSendCode'),
		graphVerifyCode = $('#verifyCode1'), // 图形验证码
		btnPopConfirm = $('#btnPopConfirm'),
		btnClose = $('#btnPopClose'),
		btnLogin = $('#btnLogin'),
        btnFresh = $('.btn-refresh'),
		validTime = 60, // 短信有效时间(默认一分钟)
		timer = null,
		regExp = {
			phone: /^1[3-9][0-9]{9}$/,
            code: /^[0-9]{6}$/,
            captcha: /^[1-9A-Za-z]{4}$/
		};

	// 手机号
	phoneNum.on('keyup', checkPhone);

	btnClear.on('click', function(){
		$(this).hide().parent().find('input').val('');
		showInfoTips();
	});

    btnFresh.on('click', function(){
        var _this = $(this);
        _this.prev().attr('src', DUOBAO.url.captcha+'?t='+Math.random());
    });
	// 图形验证提示框
	btnSendCode.on('click', function(){
		if ($(this).hasClass('btn-set-disabled')) return;
		// 图形验证
		$('.pop-mask-set, .pop-graph-verify').show();
	});
	graphVerifyCode.on('keyup', checkGraphVerifyCode);

	btnPopConfirm.on('click', function(){
		if ($(this).hasClass('btn-verify-confirm-disabled')) return;
		// @后台: 发送短信
        var captcha = $('#verifyCode1').val();
        var mobile = $('#phoneNum').val();
        DUOBAO._post(DUOBAO.url.send_message,{captcha:captcha,mobile:mobile},function(res){
            if (res.retCode == 0) {
                $('.pop-mask-set, .pop-graph-verify').hide();
                ctrlTime();
            } else {
                console.log(res);
                $('.content').find('.pop-tips-error').remove();
                $('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>'+res.retMsg+'</span></div>');
                btnPopConfirm.addClass('btn-verify-confirm-disabled');
            }
        });
	});
	btnClose.on('click', function(){
		$('.pop-mask-set, .pop-graph-verify').hide();
	});

	// 手机短信验证码
	verifyCode.on('keyup', checkVerifyCode);

	// 提交表单
	btnLogin.on('click', function(){
		if ($(this).hasClass('btn-form-login-disabled')) return false;
		// @后台: 提交相关数据
        var captcha = $('#verifyCode').val();
        var mobile = $('#phoneNum').val();
        DUOBAO._post(DUOBAO.url.login,{captcha:captcha,mobile:mobile,type:'captcha'},function(res){
            if (res.retCode == 0) {
                DUOBAO.popWinTips.init('登录成功', function(){
                    window.location.href = DUOBAO.url.backurl; // 跳转到注册成功
                }); // 登录成功
            } else {
                if (res.retMsg) {
                    showErrorTips(res.retMsg);
                } else {
                    showErrorTips('操作失败,请重试！');
                }
            }
        });
		return false;
	});

	function showErrorTips(content){	
		tips.addClass('tips-error').
			empty().
			append('<i class="icon-set-info"></i><span>'+ content +'</span>');	
	}

	function showInfoTips(){
		tips.removeClass('tips-error').
			empty();	
	}

	function ctrlTime(){
		$('#btnSendCode').addClass('btn-set-disabled').text(validTime +'s');
		timer = setInterval(function(){
			validTime--;
			$('#btnSendCode').text(validTime +'s');

			if (validTime == -1) {
				clearInterval(timer);
				$('#btnSendCode').removeClass('btn-set-disabled').text('重新发送');
			}
		}, 1000);
	}

	function checkPhone(){
		var e = $(this);
		if (isEmpty('#phoneNum')) {
            e.addClass('phone-error');
			showErrorTips('请输入手机号码');
		} else {
			if (!regExp.phone.test(trim(e.val()))) {
				e.val().length > 0 ? $('.btn-field-clear').show() : $('.btn-field-clear').hide();
                e.addClass('phone-error');
				showErrorTips('手机号码格式不正确，请核对后重新输入');
				$('#btnSendCode').addClass('btn-set-disabled');
				$('i.icon-ok').hide();
			} else { // 已注册手机号
                DUOBAO._post(DUOBAO.url.valid_mobile,{mobile:e.val()},function(res){
                    if (res.retCode == 0) {
                        showInfoTips();
                        e.removeClass('phone-error');
                        $('#btnSendCode').removeClass('btn-set-disabled');
                        $('.btn-field-clear').hide().next('i.icon-ok').show();
                    } else {
                        e.val().length > 0 ? $('.btn-field-clear').show() : $('.btn-field-clear').hide();
                        e.addClass('phone-error');
                        showErrorTips(res.retMsg);
                        $('#btnSendCode').addClass('btn-set-disabled');
                        $('i.icon-ok').hide();
                    }
                });
			}
		}
	}

	function checkGraphVerifyCode(){
		var e = $(this);
		if (isEmpty('#verifyCode1')) {
			$('.content').find('.pop-tips-error').remove();
			$('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>请输入图形验证码</span></div>');
		} else {
            if (!regExp.captcha.test(trim(e.val()))) {
				$('.content').find('.pop-tips-error').remove();
				$('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>验证码错误,请重新输入</span></div>');				
			} else {
                $('.content').find('.pop-tips-error').remove();
                btnPopConfirm.removeClass('btn-verify-confirm-disabled');
			}
		}		
	}

	function checkVerifyCode(){
		var e = $(this);
		if (isEmpty('#verifyCode')) {
			showErrorTips('请输入短信验证码');
		} else {
            if (!regExp.code.test(trim(e.val()))) {
				showErrorTips('验证码错误，请重新填写，或重新发送验证码。');
			} else {
                if ($('#phoneNum').hasClass('phone-error')) {
                    return false;
                } else {
                    showInfoTips();
                    btnLogin.removeClass('btn-form-login-disabled');
                }
			}
		}		
	}

})(jQuery);