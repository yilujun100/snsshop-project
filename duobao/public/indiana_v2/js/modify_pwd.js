/*
 * 绑定手机号 bind_phone.js
 * author:yilj@snsshop.cn
 * date:2016-7-25
*/

(function($){
	var pwd = $('#pwd'),
	    tips = $('#tipsSet'),
		btnPwdShow = $('#pwdShow'),
		btnSubmit = $('#btnSubmit'),
		regExp = {
			pwd:  /^[0-9a-zA-Z]{6,11}/
		};

	// 密码
	pwd.on('keyup', checkPwd);

	// 提交表单
	btnSubmit.on('click', function(){
		checkPwd();
		if ($(this).hasClass('btn-form-submit-disabled')) return false;
		// @后台: 提交相关数据
		DUOBAO.popWinTips.init('修改成功'); // 修改成功
		return false;
	});
	btnPwdShow.on('click', togglePassword);

	function showErrorTips(content){		
		tips.removeClass('tips-success').addClass('tips-error').
			find('i').removeClass('icon-set-ok').addClass('icon-set-info').end().
			find('span').text(content);	
	}

	function showSuccessTips(){
		tips.removeClass('tips-error').addClass('tips-success').
			find('i').removeClass('icon-set-info').addClass('icon-set-ok').end().
			find('span').text('身份验证成功，请设置新密码');
	}

	function checkPwd(){
		var e = pwd;
		if (isEmpty('#pwd')) {
			showErrorTips('请设置登录密码');
			btnSubmit.addClass('btn-form-submit-disabled');
			return false;
		} else {
			if (!regExp.pwd.test(trim(e.val()))) {
				showErrorTips('格式错误，请输入6-11位数字或字母');
				btnSubmit.addClass('btn-form-submit-disabled');
				return false;
			} else {
				showSuccessTips();
				btnSubmit.removeClass('btn-form-submit-disabled');
				return true;
			}
		}
	}

	function togglePassword(){
		var input = $(this).parent().find('input');
		'password' == input.attr('type') ? input.attr('type', 'text') : input.attr('type', 'password');
	}

})(jQuery);