/*
 * 定制夺宝 indiana_custom.js
 * author: yilj@snsshop.cn
 * date: 2016-3-30
*/

(function($){

	// tabIndianaCustom
	fnTab('#tabIndianaCustom .tab-tit a', '#tabIndianaCustom .tab-con > div');

	// choose prize category
	var swiperCustomCate = new Swiper('#swiperCategory', {
		slidesPerView: 5.5,
		freeMode: true
	});

	fnTab('#swiperCategory .tab-tit a', '#swiperCategory .tab-con > div');

	// choose prize
	var aBtnChoosePrize = $('.btn-prize-choose');
	aBtnChoosePrize.on('click', function(){
		$(this).closest('ul').find('.selected-box').remove();
		$(this).closest('ul').find('.prize-actions').css({'visibility': 'visible'});
		$(this).closest('ul').find('.btn-prize-choose').text('选择');
		$(this).parent().css({'visibility': 'hidden'});
		$(this).parent().parent().append('<div class="selected-box"><em class="icon-selected"></em></div>');
		$(this).text('已选定');
	});

	// 关键字文本框清空
	DUOBAO.fnInputClear('#searchPrizeKeyword');

	// parti number
	DUOBAO.chooseQty.init();

	
	// countDown
	/*var aCountDown = $('.countDown');
	for (var i=0; i<aCountDown.length; i++) {
		var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime())/1000;
		var ele = aCountDown[i];
		DUOBAO.countDown(ele, leftTime);
	}*/

	// custom detail
	$('#maskCustomDetail, #customShare').on('click', function(){
		$('#maskCustomDetail, #customShare').hide();
	});
	$('.btn-custom-share').on('click', function(){
		$('#maskCustomDetail, #customShare').show();
	});

	DUOBAO.codeToggle();

	DUOBAO.toTop();

	window.pageCount = null;
	var partiRecord = {
		pageHref: './js/data/more_custom_parti_record.js',
		backDataType: 'html',		
		fnParse: function(data){
			var result = eval('('+ data +')');
			var html = [];
			for (var i=0; i<result.retData.list.length; i++) {
				html.push('<li>');
				html.push('<div class="user-info">');
				html.push('<img class="user-avatar" src="'+ result.retData.list[i].userAvatar +'" width="40" height="40" alt="">');
				html.push('<span>');
				html.push('<p class="user-name">'+ result.retData.list[i].userName +'</p>');
				html.push('<p class="ip">'+ result.retData.list[i].ip +'</p>');
				html.push('<p class="parti-num-1">参与<strong>'+ result.retData.list[i].partiTimes +'</strong>人次<i class="parti-time">'+ result.retData.list[i].partiDate +'</i></p>');
				html.push('</span>');
				html.push('</li>');
				html.push('</li>');
			}
			$('#more').append(html.join(''));
			// console.log(result.retData.count);
			pageCount = result.retData.count;
		}
	};
	DUOBAO.loadMore(partiRecord.pageHref, partiRecord.backDataType, function(data){		
		partiRecord.fnParse(data);
	});

})(jQuery);