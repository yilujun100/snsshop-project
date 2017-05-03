/*
 * 地址管理(新增&编辑) addr_manage.js
 * author:yilj@snsshop.cn
 * date:2016-4-1
*/

$(function(){
    DUOBAO.addrManage = {
        newAdd: function(){
            var consignee = $('#consignee'),
                contact = $('#tel'),
                area = $('#addrArea_dummy'),
                detail = $('#addrDetail');

            if (isEmpty('#consignee')) {
                layer.msg('请输入收货人姓名');
            } else if (!this.reg.consignee.test(trim(consignee.val()))) {
                layer.msg('输入有误,请重新输入');
            } else if (isEmpty('#tel')) {
                layer.msg('请输入收货人联系方式');
            } else if (!this.reg.mobile.test(trim(contact.val()))) {
                layer.msg('请输入11位手机号码');
            } else if (isEmpty('#addrArea_dummy')) {
                layer.msg('请选择所在省市区');
            } else if (isEmpty('#addrDetail')) {
                layer.msg('请输入详细地址');
            } else {
                // 提交数据 @后台
                DUOBAO._post(DUOBAO.url.save_addr,{name:consignee.val(),phone:contact.val(),area: area.val(),address:detail.val(),address_id:$('#addr-id').val(),order_id:$('#order_id').val()||''},function(res){
                    if(res.retCode == 0){
                        layer.msg('保存成功',function(){
                            location.href = DUOBAO.url.redirect_url ? DUOBAO.url.redirect_url : location.href;
                        });
                    }else{
                        layer.msg('保存失败，请稍侯再试~~');
                    }
                });
            }
        },
        edit: function(){
            var consigneeEdit = $('#consigneeEdit'),
                contactEdit = $('#telEdit'),
                areaEdit = $('#addrArea1_dummy'),
                detailEdit = $('#addrDetailEdit');

            if (isEmpty('#consigneeEdit')) {
                layer.msg('请输入收货人姓名');
            } else if (!this.reg.consignee.test(trim(consigneeEdit.val()))) {
                layer.msg('输入有误,请重新输入');
            } else if (isEmpty('#telEdit')) {
                layer.msg('请输入收货人联系方式');
            } else if (!this.reg.mobile.test(trim(contactEdit.val()))) {
                layer.msg('请输入11位手机号码');
            } else if (isEmpty('#addrArea1_dummy')) {
                layer.msg('请选择所在省市区');
            } else if (isEmpty('#addrDetailEdit')) {
                layer.msg('请输入详细地址');
            } else {
                // 提交数据 @后台
                DUOBAO._post(DUOBAO.url.save_addr, {name:consigneeEdit.val(),phone:contactEdit.val(),area: areaEdit.val(),address: detailEdit.val(),address_id:$('#addr-id').val(),order_id:$('#order_id').val()||''},function(res){
                    if(res.retCode == 0){
                        layer.msg('编辑修改成功',function(){
                            location.href = DUOBAO.url.redirect_url ? DUOBAO.url.redirect_url : location.href;
                        });
                    }else{
                        layer.msg('地址更新失败，请稍侯再试~~');
                    }
                });
            }
        },
        init: function(){
            if (DUOBAO.isEmptyAddr == 0) {
                $('.addr-new').show().next().hide();
            } else {
                $('.addr-new').hide().next().show();
            }
        },
        reg: {
            consignee: /[\u4e00-\u9fa0]+/,
            mobile: /^1[3-9][0-9]{9}$/
        }
    };


	var tmpHtml = '';
	for (var key in window._regionMap) {
		tmpHtml += '<li data-val="'+ window._regionMap[key][0] +'">'+ window._regionMap[key][0];
			tmpHtml += '<ul>';
				var objCity = window._regionMap[key][2];
				for (var key1 in objCity) {
					tmpHtml += '<li cityId="'+ key1 +'" data-val="'+ objCity[key1][0] +'">'+ objCity[key1][0];
						tmpHtml += '<ul>';
							var objArea = objCity[key1][2];
							for (var key2 in  objArea) {
								tmpHtml += '<li areaId="'+ key2 +'" data-val="'+ objArea[key2] +'">'+ objArea[key2] +'</li>';
							}
						tmpHtml += '</ul>';
					tmpHtml += '</li>';
				}
			tmpHtml += '</ul>';
		tmpHtml += '</li>';
	}
	$('#addrArea, #addrArea1').append(tmpHtml);
	$('#addrArea').mobiscroll().treelist({
		theme: 'mobiscroll',
		lang: 'zh',
		display: 'bottom',
		fixedWidth: [100, 100, 100],
		placeholder: '省、市、区',
		labels: ['province', 'city', 'district'],
		setText: '完成',
		onSelect:  function (valueText, inst) {
			$('#addrAreaClone').val(valueText);
		}
	});

	// 地址管理初始化
	DUOBAO.addrManage.init();

	// 新增地址
	$('#addrNewSave').on('click', function(){
		DUOBAO.addrManage.newAdd();
	});


	var areaEdit = $('#itemAddrEditArea').attr('data-area-edit');
	var province = areaEdit.split(' ')[0],
		city = areaEdit.split(' ')[1],
		district = areaEdit.split(' ')[2];
	$('#addrArea1').mobiscroll().treelist({
		theme: 'mobiscroll',
		lang: 'zh',
		display: 'bottom',
		fixedWidth: [100, 100, 100],
		labels: ['province', 'city', 'district'],
		setText: '完成',
		defaultValue: [province, city, district],
		onSelect:  function (valueText, inst) {
			$('#addrAreaClone1').val(valueText);
		}
	});
	$('#addrArea1_dummy').val(province + ' ' + city + ' ' + district);

	// 编辑修改地址
	$('#addrEditSave').on('click', function(){
		DUOBAO.addrManage.edit();
	});

})