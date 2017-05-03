/**
 * 本文件包含一些通用组件、函数等以供调用，依赖于 jquery，
 * 请不要加入直接运行的代码或者与具体页面相关的逻辑代码等
 */
if (typeof MAISHA == 'undefined') {
    var MAISHA = {};
}
(function ($) {
    MAISHA.AjaxUrl = MAISHA.AjaxUrl || {}; //用于存放各种AJAX地址
    MAISHA.OpenUrl = MAISHA.OpenUrl || {}; //用于存放外部链接

    /*
     * backToTop 返回顶部
     */
    MAISHA.backToTop = function () {
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
    MAISHA.changeTab = function (ele, con, cb) {
        var aTit = $(ele);
        aTit.each(function(i){
            var _this = $(this);
            _this.on('click', function(){
                _this.addClass('tab-active').siblings().removeClass('tab-active');
                $(con).eq(i).show().siblings(con).hide();
                if (typeof  cb == 'function') {
                    cb(_this);
                }
                return false;
            });
        })
    };

    /**
     * 公共函数
     * @type {{setViewHeight: Function, trim: Function, isEmpty: Function}}
     */
    MAISHA.Utils = {
        setViewHeight: function() { // 可视区高度
            var winH = $(window).height();
            $('.viewport').css('min-height', winH);
        },
        trim: function (ostr){ // 截取输入框首尾空白字符
            return ostr.replace(/^\s+|\s+$/g, '');
        },
        isEmpty: function (ele){ // 判断值是否为空
            var val = $(ele).val();
            if(val.length == 0){
                return true;
            }else{
                return false;
            }
        }
    };

    /**
     * 登陆
     * @type {{check: Function}}
     */
    MAISHA.passport = {
        check: function(code) { //校验登陆
            if(code == -100002) { //未登录 跳转oauth2授权
                location.href = MAISHA.OpenUrl.passport+'?ref='+encodeURIComponent(location.href);
            }
        }
    };

	/*
	 * ajax请求
	 * 调用: MAISHA.ajax.loading(url, type, callback)
	 * MAISHA.ajax.loading('http://localhost/more.json', 'post', function(result){console.log(result);})
	 * 参数: url: ajax请求地址 type: ajax请求类型 callback: ajax请求成功回调函数
	 */
	MAISHA.ajax = {
		rootHtml: function(){
			var html = [];
			html.push('<div class="loading-mask"></div>');
			html.push('<div class="loading"><i class="icon-loading"></i>加载中...</div>');
			return html.join('');
		},
		show: function(){
			$('body').append(this.rootHtml());
		},
		hide: function(){
			$('body').find('.loading-mask, .loading').remove();
		},
		loading: function(url, data,callback, dataType,type){
			$.ajax({
				url: url,
				type: type ? type : 'get',
				data : data,
				dataType: dataType,
				beforeSend: function(){
					MAISHA.ajax.show();
				},
				success: function(result) {
                    MAISHA.passport.check(result.retCode);//校验登陆
                    if (typeof callback == 'function') {
                        callback(result);
                    }
				},
				complete: function(){
					MAISHA.ajax.hide();
				},
				error: function(){
					layer.msg('数据返回失败，请稍侯再试~~');
				}
			})
		}
	};
	MAISHA._post = function(url,data,callback,dataType){
		dataType = dataType || 'JSON';
		MAISHA.ajax.loading(url, data,callback,dataType, 'post');
	};
	MAISHA._get = function(url,callback,dataType){
		dataType = dataType || 'JSON';
		MAISHA.ajax.loading(url, {},callback,dataType, 'get');
	};

	/*
	 * 加载更多 loadMore
	 * 调用 MAISHA.loadMore(url)
	 * 参数: url:下一页链接地址 dataType:返回的数据类型
	 */
	MAISHA.loadMore = function (tab, callback, data) {
		$(window).on('scroll', function() {
			var _this = $(tab).size() == 1 ? $(tab) : $(tab).parent().find('.tab-active');
			var noMore = $('#no-more'),
				loadMore = $('#loadMore'),
				index = $(tab).size() == 1 ? 0 : $(tab).index(_this),
				pageIndex = _this.attr('page-index') ? parseInt(_this.attr('page-index')) + 1 : 2,
				nextHref = _this.attr('data-url'),
				isLoad = _this.attr('data-load') ? _this.attr('data-load') : 'true';
			if (_this.attr('data-processing')) {
				return;
			}
			//判断是否能加载更多，这里的isLoad控制是否加载，可以让给外边来控制
			if (isLoad == 'false') {
				loadMore.hide();
				noMore.show();
				setTimeout(function() {noMore.hide();}, 1000);
				return;
			}
			var _win = $(window);
			var viewH = _win.height();
			var contentH = $(document).height();
			var scrollTop = _win.scrollTop();
			if ((contentH - viewH) - scrollTop <= 180) {
				if (nextHref != undefined) {
					// 翻页
					_this.attr('page-index', pageIndex);
					_this.attr('data-processing', 'true');
					$.ajax({
						url: (nextHref).indexOf('?') == -1 ? nextHref+'?p_index='+pageIndex : nextHref+'&p_index='+pageIndex,
						type: 'get',
						data:data,
						dataType: 'json',
						success: function(data) {
							if (typeof callback == 'function') {
								callback(data,index,_this);
							}
						},
						beforeSend: function() {
							loadMore.show();
						},
						complete: function() {
							loadMore.hide();
							setTimeout(function () {_this.removeAttr('data-processing');}, 600);
						},
						error: function(){
							layer.msg('数据返回失败，请稍侯再试~~');
						}
					});
				}
			}
		});
	};

	/**
	 * 消息提示，依赖于 layer
	 */
	if (typeof layer !== 'undefined') {
		MAISHA.success = function (msg, options, callback) {
			if (! callback) {
				callback = options;
				options = {};
			}
			if (! callback) {
				callback = function () {};
			}
			if (! options) {
				options = {};
			}
			options.icon = 1;
			options.time = 1500;
			layer.msg(msg, options, callback);
		};
		MAISHA.error = function (msg, options, callback) {
			if (! callback) {
				callback = options;
				options = {};
			}
			if (! callback) {
				callback = function () {};
			}
			if (! options) {
				options = {};
			}
			options.icon = 2;
			options.time = 2500;
			layer.msg(msg, options, callback);
		}
	}

    /*
     * share 分享
     * 调用 MAISHA.share()
     */
    MAISHA.share = {
        isInit: false,
        init: function(){
            if (!MAISHA.share.isInit) {
                $('body').append(MAISHA.share.rootHtml());
                $('#maskShare').on('click', function(){
                    MAISHA.share.hide();
                });
                MAISHA.share.isInit = false;
            }
        },
        rootHtml: function(){
            var html = [];
            html.push('<div class="pop-mask" id="maskShare"></div>');
            html.push('<div class="pop-share" id="shareCon"><img src="'+MAISHA.OpenUrl.shareImg+'" width="100%" alt=""></div>');
            return html.join('');
        },
        show: function(){
            MAISHA.share.init();
            $('#maskShare, #shareCon').show();
        },
        hide: function(){
            $('#maskShare, #shareCon').remove();
        }
    };

    /*
     * countDown 倒计时
     * @param: ele=>指定元素id或class time=>剩余时间 tag=>状态(0or1) 1:显示天
     */
    MAISHA.countDown = function(ele, time, tag){
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

	window.MAISHA = MAISHA;
}(jQuery));