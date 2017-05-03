/*
 * 拼团公共组件
 * author: yilj@snsshop.cn
 * date: 2016-5-11
 */

var PT = PT  || {};

/*
 * backToTop 返回顶部
*/
PT.backToTop = function () {
	$(window).on('scroll', function(){
		if ($(window).scrollTop() > 300) {
			$('#backTop').fadeIn(300);
		} else {
			$('#backTop').fadeOut(300);
		}
	});
	$('#backTop').on('click', function(){
		$('html, body').animate({scrollTop: 0}, 300, function(){
			$('#backTop').fadeOut(300);
		});
	});
};

/*
 * tab 选项卡
 * @param: ele=>tab标题id或class con=>tab内容
*/
PT.tab = function (ele, con) {	
	var aTit = $(ele);
	aTit.each(function(i){
		
		var _this = $(this);
		_this.on('click', function(){
			_this.addClass('tab-active').siblings().removeClass('tab-active');
			$(con).eq(i).show().siblings(con).hide();
			return false;
		});
		
	})
};

/*
 * countDown 倒计时
 * @param: ele=>指定元素id或class time=>剩余时间 tag=>状态(0or1) 1:显示天
*/
PT.countDown = function(ele, time, tag){
	var el = ele;
	var timer = null;
	function getTimerString(time) {   
            d = Math.floor(time / 86400),     
            h = Math.floor((time % 86400) / 3600),     
            m = Math.floor(((time % 86400) % 3600) / 60),     
            s = Math.floor(((time % 86400) % 3600) % 60); 

        if (time>0) {
        	if (tag == 1) {
        		rootHtml(d, h, m, s); 
        	} 
        	if (tag == 0) {
        		rootHtml(h, m, s);
        	}

        }         
        else {
			clearInterval(timer);
			if (tag == 1) {
				rootHtml(0, 00, 00, 00); 
			}
			if (tag == 0) {
				// rootHtml(00, 00, 00); 
				$(ele).parent().html('拼团已结束');
			}
        }   
    } 

    function parseFn() {
		getTimerString(time-=1); 
    }  

    function rootHtml() {
	    	var countDownObj = $(el);
	    	var day = countDownObj.find('.day'),
	    		hour = countDownObj.find('.hour'),
	    		min = countDownObj.find('.min'),
	    		sec = countDownObj.find('.sec');

			if (arguments.length == 3) {
				hour.html(toDouble(arguments[0]));
				min.html(toDouble(arguments[1]));
				sec.html(toDouble(arguments[2])); 				
			}
			if (arguments.length == 4) {
				day.html(arguments[0]);
				hour.html(toDouble(arguments[1]));
				min.html(toDouble(arguments[2]));
				sec.html(toDouble(arguments[3]));				
			} 

    } 
	timer = setInterval(function(){
		parseFn();
	},1000);	
    var toDouble = function(num){
		return num < 10 ? '0'+num : num;
	};

	var haomiao = function(num) {
		if (num < 10) return '00' + num.toString();
		if (num < 100) return '0' + num.toString();
		return num.toString();
	};
	parseFn();
};


(function($){
	PT.backToTop();
})(jQuery);
