/*
 * 中奖记录 winning_record.js
 * author: yilj@snsshop.cn
 * date: 2016-3-30
*/

(function($){

	var total;
	$(window).on('scroll', function(){
		var _win = $(window);
		var viewH = _win.height();
		var contentH = $(document).height();
		var scrollTop = _win.scrollTop();
		if ((contentH - viewH) - scrollTop <= 120) {
			if ($('#more').children().size() >= total) {
				$('#no-more').show();
			} else {
				// 翻页
				$.ajax({
					url: './js/data/more_winning_record.js',
					type: 'get',
					dataType: 'html',
					success: function(data) {
						var result = eval('('+ data +')');
						// console.log(result);
						var html = [];
						for (var i=0; i<result.retData.list.length; i++) {
							html.push('<li>');
							html.push('<a href="#"><i class="icon-alarm"></i><b>恭喜</b><strong>'+ result.retData.list[i].winner +'</strong>'+ result.retData.list[i].winningTime +'获得<strong>'+ result.retData.list[i].prize +'</strong><i class="arrow-rgt"></i></a>');
							html.push('</li>');
						}
						$('#more').append(html.join(''));
						total = result.retData.count;
						// console.log(total);
					},
					beforeSend: function() {
						$('#loadMore').show();
					},
					complete: function() {
						$('#loadMore').hide();
					}
				});
			}
		}
	});

})(jQuery);