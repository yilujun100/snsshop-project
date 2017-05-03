/*
 * 公共类 lib.js
 * author:yilj@snsshop.cn
 * date:2016-1-27
*/

// 滚动监听
var fnScrollListen = function () {
    var tabTitOffsetTop = $('#fixedTabTitle').offset().top;
    $(window).on('scroll', function(){
        var docTop = $(window).scrollTop();
        if (docTop >= tabTitOffsetTop) {
            $('#fixedTabTitle').addClass('scroll-fixed');
            $('.tab-con').css('padding-top', '42px');
        } else {
            $('#fixedTabTitle').removeClass('scroll-fixed');
            $('.tab-con').css('padding-top', 0);
        }
    });
};

$(function(){
	setViewHeight();
});

if(typeof DUOBAO == 'undefined') {
    var DUOBAO = DUOBAO || {};
}

DUOBAO.url = DUOBAO.url || {}; //用于存放各种AJAX地址
// tab 选项卡
var fnTab = function (ele, con) {
	
	var aTit = $(ele);
	aTit.each(function(i){
		
		var _this = $(this);
		_this.on('click', function(){
			_this.addClass('active').siblings().removeClass('active');
			$(con).eq(i).show().siblings(con).hide();
			return false;
		});
		
	})
	
};

// 可视区高度
var setViewHeight = function () {
	var winH = $(window).height();
	$('.viewport').css('min-height', winH);
};

// 判断值是否为空		
var isEmpty = function (ele){
	var val = $(ele).val();
	if(val.length == 0){
		return true;
	}else{
		return false;
	}
};

// 截取输入框首尾空白字符		
var trim = function (ostr){
	return ostr.replace(/^\s+|\s+$/g, '');
};

// 折叠菜单
var toggleMenu = function (ele) {
	$(ele).on('click', function(){
		$(this).parent().next().slideToggle('fast');
	});
};

/*
 * ajax请求
 * 调用: DUOBAO.ajax.loading(url, type, callback)
 * DUOBAO.ajax.loading('http://localhost/more.json', 'post', function(result){console.log(result);})
 * 参数: url: ajax请求地址 type: ajax请求类型 callback: ajax请求成功回调函数
*/
DUOBAO.ajax = {
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
                DUOBAO.ajax.show();
			},
			success: function(result) {
                if(result.retCode == -100002) {
                    //未登录 跳转oauth2授权
                    location.href = DUOBAO.redirect_uri.wxuser+'?ref='+encodeURIComponent(location.href);
                } else {
                    if (typeof callback == 'function') {
                        callback(result);
                    }
                }
			},
			complete: function(){
                DUOBAO.ajax.hide();
			},
            error: function(){
                layer.msg('数据返回失败，请稍侯再试~~');
            }
		})
	}
};
DUOBAO._post = function(url,data,callback,dataType){
    dataType = dataType || 'JSON';
    DUOBAO.ajax.loading(url, data,callback,dataType, 'post');
};
DUOBAO._get = function(url,callback,dataType){
    dataType = dataType || 'JSON';
    DUOBAO.ajax.loading(url, {},callback,dataType, 'get');
}


/*
 * 加载更多 loadMore
 * 调用 DUOBAO.loadMore(url)
 * 参数: url:下一页链接地址 dataType:返回的数据类型
*/
DUOBAO.loadMore = function (tab,callback,data) {
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
		if ((contentH - viewH) - scrollTop <= 120) {
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