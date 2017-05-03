/*
 * 下拉刷新 
 * author: yilj@snsshop.cn
 * date: 2016-8-11
 */
(function(){
	var myScroll,
		pullDownEl, pullDownOffset;
	var isPageHome = $('.v-home');

	function pullDownAction () {
		setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
			window.location.reload();
			
			myScroll.refresh();		// Remember to refresh when contents are loaded (ie: on ajax completion)
		}, 1000);	// <-- Simulate network congestion, remove setTimeout from production!
	}

	function loaded() {
		pullDownEl = document.getElementById('pullDown');
		pullDownOffset = pullDownEl.offsetHeight;
		
		myScroll = new iScroll('wrapper', {
			useTransition: true,
			topOffset: pullDownOffset,
			onRefresh: function () {
				if (pullDownEl.className.match('loading-refresh')) {
					pullDownEl.className = '';
					pullDownEl.querySelector('.label-pull-down').innerHTML = '下拉刷新...';
				}
			},
			onScrollMove: function () {
				if (this.y > 5 && !pullDownEl.className.match('flip')) {
					pullDownEl.className = 'flip';
					pullDownEl.querySelector('.label-pull-down').innerHTML = '松开更新...';
					this.minScrollY = 0;
					if (isPageHome) $('.fixed-top').css('top', pullDownOffset + this.y);
				} else if (this.y < 5 && pullDownEl.className.match('flip')) {
					pullDownEl.className = '';
					pullDownEl.querySelector('.label-pull-down').innerHTML = '下拉刷新...';
					this.minScrollY = -pullDownOffset;
					if (isPageHome) $('.fixed-top').css('top', $('.fixed-top').outerHeight() + this.y);
				} else if (isPageHome && this.y > -80 && this.y < 5) {
					$('.fixed-top').css('top', pullDownOffset + this.y);					 
				} else if (isPageHome && this.y > 5) {
					$('.fixed-top').css('top', pullDownOffset + this.y);
				}
				
			},
			onScrollEnd: function () {
				if (pullDownEl.className.match('flip')) {
					pullDownEl.className = 'loading-refresh';
					pullDownEl.querySelector('.label-pull-down').innerHTML = '刷新中...';				
					pullDownAction();	// Execute custom function (ajax call?)
					if (isPageHome) $('.fixed-top').css('top', pullDownOffset + this.y);
				}
			},
			onTouchEnd: function () {
					if (isPageHome) $('.fixed-top').css('top', 0);
			}
		});
		
		// setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
	}

	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

	document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);
})();