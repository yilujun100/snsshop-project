/*
 * 绑定手机号 bind_phone.js
 * author:yilj@snsshop.cn
 * date:2016-7-25
*/

(function($){
	var phoneNum = $('#phoneNum'),
			 pwd = $('#pwd'),
		    tips = $('#tipsSet'),
		btnClear = $('#clear'),
		btnLogin = $('#btnLogin'),
		unregisteredPhone = 13972947073, // 未注册手机号
		testPwd = 'abc123', // 测试唯一密码
		regExp = {
			phone: /^1[3-9][0-9]{9}$/,
			pwd:  /^[0-9a-zA-Z]{6,11}/
		};
	var flag1, flag2;

	// 手机号
	phoneNum.on('keyup, change', checkPhone);

	btnClear.on('click', function(){
		$(this).hide().parent().find('input').val('');
		showInfoTips();
	});

	// 密码
	pwd.on('keyup', checkPwd);

	// 提交表单
	btnLogin.on('click', function(){
        if ($(this).hasClass('btn-form-login-disabled')) return false;
        if (checkPhone(1)) {
            checkPwd();
        }
		if ($(this).hasClass('btn-form-login-disabled')) return false;
		// @后台: 提交相关数据
        var pwd = $('#pwd').val();
        var mobile = $('#phoneNum').val();
        DUOBAO._post(DUOBAO.url.login,{pwd:pwd,mobile:mobile,type:'pwd'},function(res){
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

	function checkPhone(flag){
		var e = phoneNum;
		if (isEmpty('#phoneNum')) {
            e.addClass('phone-error');
			showErrorTips('请输入手机号码');
			return false;
		} else {
			if (!regExp.phone.test(trim(e.val()))) {
				e.val().length > 0 ? $('.btn-field-clear').show() : $('.btn-field-clear').hide();
                e.addClass('phone-error');
				showErrorTips('手机号码格式不正确，请核对后重新输入');
				$('i.icon-ok').hide();
				btnLogin.addClass('btn-form-login-disabled');
				return false;
			} else {
                if (flag) {
                    DUOBAO._post(DUOBAO.url.valid_mobile,{mobile:e.val()},function(res){
                        if (res.retCode == 0) {
                            e.removeClass('phone-error');
                            showInfoTips();
                            $('.btn-field-clear').hide().next('i.icon-ok').show();
                            if (pwd.val().length > 0) {
                                checkPwd() ? btnLogin.removeClass('btn-form-login-disabled') : btnLogin.addClass('btn-form-login-disabled') ;
                            }
                            return true;
                        } else {
                            e.addClass('phone-error');
                            e.val().length > 0 ? $('.btn-field-clear').show() : $('.btn-field-clear').hide();
                            showErrorTips(res.retMsg);
                            $('i.icon-ok').hide();
                            btnLogin.addClass('btn-form-login-disabled');
                            return false;
                        }
                    });
                } else {
                    e.removeClass('phone-error');
                    showInfoTips();
                    $('.btn-field-clear').hide().next('i.icon-ok').show();
                    if (pwd.val().length > 0) {
                        checkPwd() ? btnLogin.removeClass('btn-form-login-disabled') : btnLogin.addClass('btn-form-login-disabled') ;
                    }
                    return true;
                }
            }
		}
	}

	function checkPwd(){
		var e = pwd;
		if (isEmpty('#pwd')) {
			showErrorTips('请填写登录密码');
			btnLogin.addClass('btn-form-login-disabled');
			return false;
		} else {
			if (!regExp.pwd.test(trim(e.val()))) {
				showErrorTips('密码格式错误，请重新输入');
				btnLogin.addClass('btn-form-login-disabled');
				return false;
			} else {
                if ($('#phoneNum').hasClass('phone-error')) {
                    return false;
                } else {
                    showInfoTips();
                    if (phoneNum.parent().find('i.icon-ok').is(":visible")) {
                        btnLogin.removeClass('btn-form-login-disabled');
                    }
                    return true;
                }
			}
		}
	}
})(jQuery);