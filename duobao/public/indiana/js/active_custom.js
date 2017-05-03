/*
 * 定制夺宝 indiana_custom.js
 * author: yilj@snsshop.cn
 * date: 2016-3-30
*/

$(function () {
	// tabIndianaCustom
	fnTab('#tabIndianaCustom .tab-tit a', '#tabIndianaCustom .tab-con > div');

	// choose prize category
	var swiperCustomCate = new Swiper('#swiperCategory', {
		slidesPerView: 5.5,
		freeMode: true
	});

	fnTab('#swiperCategory .tab-tit a', '#swiperCategory .tab-con > div');

	// choose prize
	var aBtnChoosePrize = $('.btn-prize-opera');
	aBtnChoosePrize.on('click', function(){
		if ($(this).hasClass('btn-prize-choose')) {
			$(this).removeClass('btn-prize-choose').addClass('btn-prize-selected');
			$(this).text('已选定');
		}
	});

	// parti number
	DUOBAO.chooseQty.init();
});
