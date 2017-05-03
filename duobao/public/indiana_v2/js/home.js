$(function(){
	
	// 首页轮播
	var swiper = new Swiper('#slider', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 5000
    });

    // 商品分类
	$('#category > a').on('click', function(){
		if ($(this).next('.cate-sub').is(':hidden')) {
			$('.mask, .cate-sub').show();
			$('#category').addClass('category-on');
		} else {			
			$('.mask, .cate-sub').hide();
			$('#category').removeClass('category-on');
		}
	});
	$('.mask').on('click', function(){
		$('.mask, .cate-sub').hide();
		$('#category').removeClass('category-on');
	});
	$('#list-category a').on('click', function(){
		$(window).scrollTop(0);
		$('.mask, .cate-sub').show();
		$('#category').addClass('category-on');
	});


	// 关键字文本框清空
	DUOBAO.fnInputClear('#listKeywords');

	// 中奖信息	
	scrollInfo('#scrollInfo');

	// tab
	fnTab('#tab1 a', '#tabCon1 > div');

	// 揭晓商品
	var swiper1 = new Swiper('#swiper-1', {
		slidesPerView: 3,
		spaceBetween: 10,
		slidesOffsetBefore: 10,
		freeMode: true
	});
	$('#tab1 a:eq(0)').on('click', function(){
		var swiper1 = new Swiper('#swiper-1', {
			slidesPerView: 3,
			spaceBetween: 10,
			slidesOffsetBefore: 10,
			freeMode: true
		});
	});

	// 最后疯抢
	var swiper2 = new Swiper('#swiper-2', {
		slidesPerView: 3,
		spaceBetween: 10,
		slidesOffsetBefore: 10,
		freeMode: true
	});

	// 即将揭晓
	var swiper3 = new Swiper('#swiper-3', {
		slidesPerView: 3,
		spaceBetween: 10,
		slidesOffsetBefore: 10,
		freeMode: true
	});
	$('#tab1 a:eq(1)').on('click', function(){
		var swiper3 = new Swiper('#swiper-3', {
			slidesPerView: 3,
			spaceBetween: 10,
			slidesOffsetBefore: 10,
			freeMode: true
		});
	});

	// countDown
	var aCountDown = $('.countDown');
	for (var i=0; i<aCountDown.length; i++) {
		var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime())/1000;
		var ele = aCountDown[i];
		DUOBAO.countDown(ele, leftTime);
	}

	// 商品排序
	var aBtnSort = $('.list-sort a');
	aBtnSort.each(function(index){
		var e = $(this);
		e.on('click', function(){
			// alert(index);
			var i = $(this).find('i');
			if ($(this).hasClass('curr') && index > 1) {
				if (i.hasClass('arrow-asc')) {
					$(this).find('i:last-child').removeClass('arrow-asc').addClass('arrow-desc');
				} else {
					$(this).find('i:last-child').removeClass('arrow-desc').addClass('arrow-asc');
				}	
			}
			$(this).addClass('curr').siblings().removeClass('curr');

		});
	});

	// 进度条
	$('.progress-bar').each(function(){
		var _percent = $(this).parent().attr('data-lott-schedule');
		_percent = _percent.substring(0, _percent.length - 1);
		$(this).find('span.progress-bar-on').animate({'width': _percent + '%'}, 600);
	});
	$('.list-progress-bar').each(function(){
		var _percent = $(this).parent().attr('data-lott-schedule');
		_percent = _percent.substring(0, _percent.length - 1);
		$(this).find('span.list-progress-bar-on').animate({'width': _percent + '%'}, 600);
	});

	// 最后疯抢按钮点击状态
	var aBtnSnap = $('.btn-parti-immediately');
	aBtnSnap.each(function(){
		var _this = $(this);
		_this.on('click', function(){
			if ($(this).hasClass('btn-default')) {
				$(this).addClass('btn-error').removeClass('btn-default');
				return false;
			}
		});
	});

})