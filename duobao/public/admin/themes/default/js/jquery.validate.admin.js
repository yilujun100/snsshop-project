(function ($) {
	// 自定义messages
	$.extend($.validator.messages, {
		required: "必选字段",
		remote: "请修正该字段",
		email: "请输入正确格式的电子邮件",
		url: "请输入合法的网址",
		date: "请输入合法的日期",
		dateISO: "请输入合法的日期 (ISO).",
		number: "请输入合法的数字",
		digits: "只能输入整数",
		creditcard: "请输入合法的信用卡号",
		equalTo: "请再次输入相同的值",
		accept: "请输入拥有合法后缀名的字符串",
		maxlength: $.validator.format("请输入一个长度最多是 {0} 的字符串"),
		minlength: $.validator.format("请输入一个长度最少是 {0} 的字符串"),
		rangelength: $.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
		range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
		max: $.validator.format("请输入一个最大为 {0} 的值"),
		min: $.validator.format("请输入一个最小为 {0} 的值")
	});

	// 验证消息模版
	var message_templates = {
		depend: function (field) {
			return $.yVadFormat('请先输入正确的「{0}」', field);
		},
		greater_equal: function (field1, field2, append) {
			if (! append) {
				append = '';
			}
			return $.yVadFormat('「{0}」需大于或等于「{1}」' + append, field1, field2);
		},
		lesser_equal: function (field1, field2, append) {
			if (! append) {
				append = '';
			}
			return $.yVadFormat('「{0}」需小于或等于「{1}」' + append, field1, field2);
		}
	};

	// 工具函数
	$.extend({
		yVadAssign: function (method, template) {
			var value;
			if (template in message_templates) {
				value = message_templates[template];
			} else {
				value = template;
			}
			if ($.isFunction(value)) {
				$.validator.messages[method] = value.apply(this, $.makeArray(arguments).slice(2));
			} else {
				$.validator.messages[method] = value;
			}
		},
		yVadFormat: function () {
			return $.validator.format.apply(this, $.makeArray(arguments));
		}
	});


	// 自定义验证方法
	$.validator.addMethod('identifier', function (value, element) {
		return this.optional(element) || /^[a-zA-Z][a-zA-Z0-9]*$/.test(value);
	}, '只能包含字母和数字且需以字母开头');

	$.validator.addMethod('chinese', function (value, element) {
		return this.optional(element) || /^[\u4E00-\u9FA5\uF900-\uFA2D]+$/.test(value);
	}, '请输入中文字符');

	$.validator.addMethod('select', function (value, element) {
		return this.optional(element) || -1 != value;
	}, '请选择');

	$.validator.addMethod('node', function (value, element) {
		return this.optional(element) || /^[a-zA-Z_][a-zA-Z0-9_]*\/[a-zA-Z_][a-zA-Z0-9_]*$/.test(value);
	}, '节点格式不正确「参考: user/index」');

	$.validator.addMethod('price', function (value, element) {
		return this.optional(element) || /^(?:[1-9][0-9]*|[0-9]+\.[0-9]{1,2}|0)$/.test(value);
	}, '价格格式不正确');
}(jQuery));