/*
 * 夺宝列表 list.js
 * author: yilj@snsshop.cn
 * date: 2016-3-30
*/

(function(){	

	// 商品分类
	$('#category > a').on('click', function(){
		if ($(this).next('.cate-sub').is(':hidden')) {
			$('.mask, .cate-sub').show();
		} else {			
			$('.mask, .cate-sub').hide();
		}
	});
	$('#mask').on('click', function(){
		$('.mask, .cate-sub').hide();
	});

	// 商品排序
	var aBtnSort = $('.list-sort a');
	aBtnSort.each(function(index){
		var e = $(this);
		e.on('click', function(){
			// alert(index);
			var i = $(this).find('i');
			//if ($(this).hasClass('curr') && index > 1) {
			//	if (i.hasClass('arrow-asc')) {
			//		$(this).find('i:last-child').removeClass('arrow-asc').addClass('arrow-desc');
			//	} else {
			//		$(this).find('i:last-child').removeClass('arrow-desc').addClass('arrow-asc');
			//	}
			//}
			$(this).addClass('curr').siblings().removeClass('curr');

		});
	});

	// 关键字文本框清空
	DUOBAO.fnInputClear('#listKeywords');

	// 进度条
	$('.list-progress-bar').each(function(){
		var _percent = $(this).parent().attr('data-lott-schedule');
		_percent = _percent.substring(0, _percent.length - 1);
		$(this).find('span.list-progress-bar-on').animate({'width': _percent + '%'}, 600);
	});

})(jQuery);