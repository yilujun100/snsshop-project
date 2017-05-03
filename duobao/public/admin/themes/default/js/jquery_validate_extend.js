/*
 *表单校验新增自定义规则
 *author:yilj@snsshop.cn
 *date:2016-3-14
 */
// account
jQuery.validator.addMethod("account", function(value, element){
	return this.optional(element) || /^[a-zA-Z0-9]{6,20}$/.test(value);
}, "账号由6~20位字母数字组成");

// phoneNumber
jQuery.validator.addMethod("phoneNumber", function(value, element){
	return this.optional(element) || /^1[34578][0-9]{9}$/.test(value);
}, "手机号格式错误");

// password
jQuery.validator.addMethod("pwd", function(value, element){
	return this.optional(element) || /^[a-zA-Z0-9]{6,20}$/.test(value);
}, "密码由6~20位字母数字组成");

// username
jQuery.validator.addMethod("userName", function(value, element){
	return this.optional(element) || /^[a-zA-Z0-9]{6,20}$/.test(value);
}, "用户名由6~20位字母数字组成");