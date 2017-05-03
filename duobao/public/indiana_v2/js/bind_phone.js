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
		btnConfirm = $('#btnConfirm'),
		testCode = 'dXXD', // 测试图形验证码
		testCode1 = 2564, // 测试短信验证码
		validTime = 60, // 短信有效时间(默认一分钟)
		timer = null,
		regExp = {
			phone: /^1[3-9][0-9]{9}$/
		};

	phoneNum.on('keyup', function(){
		var e = $(this);
		if (isEmpty('#phoneNum')) {
			showErrorTips('请输入手机号码');
		} else {
			if (!regExp.phone.test(trim(e.val()))) {
				e.val().length > 0 ? $('.btn-field-clear').show() : $('.btn-field-clear').hide();
				showErrorTips('手机号码格式不正确，请核对后重新输入');
				$('#btnSendCode').addClass('btn-set-disabled');
				$('i.icon-ok').hide();
			} else {
				showInfoTips();
				$('#btnSendCode').removeClass('btn-set-disabled');
				$('.btn-field-clear').hide().next('i.icon-ok').show();
			}
		}
	});

	btnClear.on('click', function(){
		$(this).hide().parent().find('input').val('');
		showInfoTips();
	});

	graphVerifyCode.on('keyup', function(){
		var e = $(this);
		if (isEmpty('#verifyCode1')) {
			$('.content').find('.pop-tips-error').remove();
			$('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>请输入图形验证码</span></div>');
		} else {
			if ((e.val()).length != 4) {
				$('.content').find('.pop-tips-error').remove();
				$('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>验证码长度错误,请重新输入</span></div>');
			} else {
				$('.content').find('.pop-tips-error').remove();
				btnPopConfirm.removeClass('btn-verify-confirm-disabled');
			}
		}
	});

	btnPopConfirm.on('click', function(){
		if ($(this).hasClass('btn-verify-confirm-disabled')) return;

        // @后台: 发送短信
        DUOBAO._post('ajax_check_captcha',{'phone_num':phoneNum.val(),'captcha':graphVerifyCode.val()},function($rs){
            if($rs.retCode != 0){
                $('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>'+$rs.retMsg+'</span></div>');
            }else{
                $('.pop-mask-set, .pop-graph-verify').hide();
            }
        });
		ctrlTime();
	});

	btnClose.on('click', function(){
		$('.pop-mask-set, .pop-graph-verify').hide();
	});

	// 手机短信验证码
	verifyCode.on('keyup', function(){
		var e = $(this);
		if (isEmpty('#verifyCode')) {
			showErrorTips('请输入短信验证码');
		} else {
			if ((e.val()).length != 6) {
				showErrorTips('验证码错误，请重新填写，或重新发送验证码。');
			} else {
				showInfoTips();
				btnConfirm.removeClass('btn-form-confirm-disabled');
			}
		}
	});

	// 提交表单
	btnConfirm.on('click', function(){
		if ($(this).hasClass('btn-form-confirm-disabled')) return false;
        var activity = $("#activity").val();
		// @后台: 提交相关数据
        DUOBAO._post('ajax_submit_bind',{'phone_num':phoneNum.val(),'captcha':verifyCode.val(),'activity':activity},function($rs){
            if($rs.retCode != 0){
                $('.content').append('<div class="pop-tips-error"><i class="icon-error-info"></i><span>'+$rs.retMsg+'</span></div>');
            }else{

                var activity_url = $("#activity_url").val();
                if(activity == 1)
                {
                    DUOBAO.popWinTips.init('手机绑定成功,即将跳转到活动页面');
                    setTimeout(function(){
                        window.location.href = activity_url;
                    }, 3000);
                }
                else
                {
                    setTimeout(function(){
                        window.location.href = './bind_success'; // 跳转到绑定成功
                    }, 2000);
                }

            }
        });
        return false;
	});

	// 点击返回
	$('.fixed-nav a:eq(2)').on('click', function(){
		$('.pop-mask-notbind, .pop-not-bind').show();
	});
	$('#btnKnow, #btnPopClose1').on('click', function(){
		$('.pop-mask-notbind, .pop-not-bind').hide();
	});

	function showErrorTips(content){		
		tips.removeClass('tips-info').addClass('tips-error').
			find('i').removeClass('icon-set-horn').addClass('icon-set-info').end().
			find('span').text(content);
	}

	function showInfoTips(){
		tips.removeClass('tips-error').addClass('tips-info').
			find('i').removeClass('icon-set-info').addClass('icon-set-horn').end().
			find('span').text('百分好礼希望在您中奖后第一时间联系您，赶快绑定手机号吧！');		
	}

	function ctrlTime(){
        if($('#btnSendCode').hasClass('btn-set-disabled')) return false;
		$('#btnSendCode').addClass('btn-set-disabled').text(validTime +'s');
		timer = setInterval(function(){
			validTime--;
			$('#btnSendCode').text(validTime +'s');

			if (validTime == -1) {
				clearInterval(timer);
                validTime = 60;
				$('#btnSendCode').removeClass('btn-set-disabled').text('重新发送');
			}
		}, 1000);
	}

})(jQuery);