/*
 * 夺宝列表 list.js
 * author: yilj@snsshop.cn
 * date: 2016-3-30
*/

(function(){	

	// 商品分类
	$('#category > a').on('click', function(){
		if ($(this).next('.cate-sub').is(':hidden')) {
			$('.cate-sub').css({'height': $(window).height() - 47});
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
	aBtnSort.on('click', function(){
		var i = $(this).find('i');
		if ($(this).hasClass('curr')) {
			if (i.hasClass('arrow-asc')) {
				$(this).find('i').removeClass('arrow-asc').addClass('arrow-desc');
			} else {
				$(this).find('i').removeClass('arrow-desc').addClass('arrow-asc');
			}	
		}
		$(this).addClass('curr').siblings().removeClass('curr');
	});

	// 加载更多列表		
	window.pageCount = null;
	var loadMore = {
		pageHref: './js/data/more.js',
		backDataType: 'html',
		fnParse: function(data){
			var result = eval('('+ data +')');
			var html = [];
			for (var i=0; i<result.retData.list.length; i++) {
				html.push('<li>');
				html.push('<div class="prize-pic"><img src="'+ result.retData.list[i].pic +'" width="100%" alt=""></div>');
				html.push('<div class="prize-name">'+ result.retData.list[i].title +'</div>');
				html.push('<div class="lott-schedule" data-lott-schedule="'+ result.retData.list[i].lotterySchedule +'">');
				html.push('<div class="progress-bar"><span class="progress-bar-on" style="width: '+ result.retData.list[i].lotterySchedule +'"></span></div><em>'+ result.retData.list[i].lotterySchedule +'</em>');
				html.push('</div>');
				html.push('<div class="prize-bott"><a href="#" class="btn-parti btn-parti-1">立即参与</a><a href="javascript:;" onclick="DUOBAO.addOneParseFn(this)" class="add-to-cart">添加到购物车<span class="icon-plus">+</span></a></div>')
				html.push('</li>');
			}
			$('#more').append(html.join(''));
			// console.log(result.retData.count);
			pageCount = result.retData.count;
		}
	};
	DUOBAO.loadMore(loadMore.pageHref, loadMore.backDataType, function(data){		
		loadMore.fnParse(data);
	});

	// 购物车加1
	DUOBAO.cartAddOne('.add-to-cart');

	// 关键字文本框清空
	DUOBAO.fnInputClear('#listKeywords');

})(jQuery);