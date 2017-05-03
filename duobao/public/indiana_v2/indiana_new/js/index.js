function bodyHidden() {
	$("body").css('overflow', 'hidden');
	$("html").css('overflow', 'hidden');
}

function bodyAuto() {
	$("body").css('overflow', 'auto');
	$("html").css('overflow', 'auto');
}
//倒计时
function countDown(ele, time) {
	var el = ele;
	var timer = null;
	function getTimerString(time) {
		d = Math.floor(time / 86400),
			h = Math.floor((time % 86400) / 3600),
			m = Math.floor(((time % 86400) % 3600) / 60),
			s = Math.floor(((time % 86400) % 3600) % 60);

		if(d >= 1 && d < 2) {
			h = 24 + h;
		} else if(d >= 2) {
			h = 48 + h;
		}
		if(time > 0) {
			rootHtml(h, m, s);
		} else {
			clearInterval(timer);
			rootHtml(00, 00, 00);
			rockerStart();
		}
	}

	function parseFn() {
		getTimerString(time -= 1);
	}

	function rootHtml() {
		var countDownObj = $(el);
		var hour = countDownObj.find('.hour'),
			min = countDownObj.find('.min'),
			sec = countDownObj.find('.sec');

		hour.html(toDouble(arguments[0]));
		min.html(toDouble(arguments[1]));
		sec.html(toDouble(arguments[2]));
	}
	timer = setInterval(function() {
		parseFn();
	}, 1000);
	var toDouble = function(num) {
		return num < 10 ? '0' + num : num;
	};
	parseFn();
}

