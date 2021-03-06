/*
 * 地址管理 addr_manage.js
 * author:yilj@snsshop.cn
 * date:2016-2-2
*/

var addrInit = function () {

	if (luckyBag.isEmptyAddr == 0) {
		$('#addrNew').show().next().hide();
	} else {
		$('#addrNew').hide().next().show();
	}

};


var addrValidat = {
	reg: {
		consignee: /[\u4e00-\u9fa0]+/,
		mobile: /^1[3-9][0-9]{9}$/			
	},
	newAdd: function () {
		var consignee = $('#consignee'),
			phoneNum = $('#phoneNum'),
			addrArea = $('#addrArea_dummy'),
			addrDetail = $('#addr');
		var flag = false;		
	
		if (isEmpty('#consignee')) {
			layer.msg('请输入收货人姓名');
		} else if (!this.reg.consignee.test(trim(consignee.val()))) {
			layer.msg('输入有误,请重新输入');
		} else if (isEmpty('#phoneNum')) {
			layer.msg('请输入收货人联系方式');
		} else if (!this.reg.mobile.test(trim(phoneNum.val()))) {
			layer.msg('请输入11位手机号码');
		} else if (isEmpty('#addrArea_dummy')) {
			layer.msg('请输入所在省市区');
		} else if (isEmpty('#addr')) {
			layer.msg('请输入详细地址');
		} else {
			flag = true;
		}
		return flag;
	},
	edit: function () {
		var editConsignee = $('#consignee1'),
			editPhoneNum = $('#phoneNum1'),
			editAddrArea = $('#addrArea1_dummy'),
			editAddrDetail = $('#addr1');
			
		var flag = false;		
	
		if (isEmpty('#consignee1')) {
			layer.msg('请输入收货人姓名');
		} else if (!this.reg.consignee.test(trim(editConsignee.val()))) {
			layer.msg('输入有误,请重新输入');
		} else if (isEmpty('#phoneNum1')) {
			layer.msg('请输入收货人联系方式');
		} else if (!this.reg.mobile.test(trim(editPhoneNum.val()))) {
			layer.msg('请输入11位手机号码');
		} else if (isEmpty('#addrArea1_dummy')) {
			layer.msg('请输入所在省市区');
		} else if (isEmpty('#addr1')) {
			layer.msg('请输入详细地址');
		} else {
			flag = true;
		}
		return flag;
	}
}
