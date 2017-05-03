/*
 * 公共类 lib.js
 * author:yilj@snsshop.cn
 * date:2016-1-27
*/

$(function(){
	setViewHeight();
	DUOBAO.toTop();
	DUOBAO.codeToggle();
	DUOBAO.controlFixedBtn();
});

if(typeof DUOBAO == 'undefined') {
    var DUOBAO = DUOBAO || {};
}

DUOBAO.url = DUOBAO.url || {}; //用于存放各种AJAX地址

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
// tab 选项卡
var fnBagTab = function (ele, con) {

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

// tab 选项卡
var fnTab = function (ele, con) {
	
	var aTit = $(ele);
	aTit.each(function(i){
		
		var _this = $(this);
		_this.on('click', function(){
			if(_this.hasClass('tab-more')) return true;
			_this.addClass('tab-active').siblings().removeClass('tab-active');
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

// 中奖信息
var scrollInfo = function(scrollObj) {
	var f = 0;
	var c = $(scrollObj).find('li').length;
	if (c > 1) {
		$(scrollObj).find('ul').append($(scrollObj).find('li:eq(0)').clone());
		var e = $(scrollObj).height() / c;
		setInterval(function(){
			f++;
			$(scrollObj).find('ul').addClass('ani').css('-webkit-transform', 'translateY(-'+ 20*(f) +'px)');
			setTimeout(function(){
				if (f == c) {
					$(scrollObj).find('ul').removeClass('ani').css('-webkit-transform', 'translateY(0)');
					f = 0;
				}
			}, 300);
		}, 4000);
	}
};

// 中奖信息1
var scrollInfo1 = function (scrollObj) {
    $(scrollObj).find('ul').animate({'marginTop': '-22px'}, 100, function(){
        $(this).css({marginTop: '0px'}).find('li:first').appendTo(this);
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
 * 购物车
 * @param ele
 */
DUOBAO.addOneParseFn = function (ele) {
    ele.append('<s class="cart-add-one">+1</s>');
	ele.parent().find('s.cart-add-one').addClass('add-one-animate');
	setTimeout(function(){
		ele.find('s').remove();
	}, 1000);
}

DUOBAO.addCart = function(_this){
    this.cartCallBack(_this,function(){
        DUOBAO.addOneParseFn($(_this));
		DUOBAO.initCartNum = $('#cartNum').text();
		DUOBAO.initCartNum++;
		$('#cartNum').text(DUOBAO.initCartNum);
    })
}

DUOBAO.cartCallBack = function(_this,callback)
{
    var peroid_str = _this.attr('peroid-str');
    if(peroid_str != ''){
        DUOBAO._get(DUOBAO.url.add_cart+'?peroid_str='+peroid_str,function($res){
            if($res.retCode == -100002){
                layer.msg('亲，获取用户信息失败~~');
                return false;
            }else if($res.retCode == 0){
                //操作成功
                callback(_this);
                return true;
            }else{
                layer.msg($res.retMsg ? $res.retMsg : '购买车操作失败');
                return false;
            }
        })
    }
    return false;
}



/*
 * 选择数量 chooseQty
 * 调用 DUOBAO.chooseQty()
 * 参数: 
*/
DUOBAO.chooseQty = {
	min: 1,
	max: null,
	initTxt: 1,
	regInt: /^[0-9]\d*$/,
    callback:null,
	increase: function(ele){
		var number = $(ele).parent().find('input.quantity').val();
		number++;
		if (this.max && number > this.max) {
			number = this.max;
			// layer.tips('不能大于' + this.max, $(ele).parent().find('input.quantity'), {tips: 1});
			layer.alert('本奖品最多可由'+ this.max +'人参与,参与人数已达最大值哦', {title: false, closeBtn: 0}, function(index){
				layer.close(index);
			});
		}
		$(ele).parent().find('input.quantity').val(number);
        if("function" == typeof this.callback){
            this.callback(number);
        }

	},
	decrease: function(ele){
		var number = $(ele).parent().find('input.quantity').val();
		number--;
		if (number < this.min) {
			number = this.min;
			// layer.tips('不能小于' + this.min, $(ele).parent().find('input.quantity'), {tips: 1});
			layer.alert('本奖品最少需'+ this.min +'人参与', {title: false, closeBtn: 0}, function(index){
				layer.close(index);
			});
		}
		$(ele).parent().find('input.quantity').val(number);
        if("function" == typeof this.callback){
            this.callback(number);
        }
	},
	txtEntry: function(ele, empty){
		var number = $(ele).val();
		if (empty && ! number) {
			return;
		}
		if (! this.regInt.test(trim(number))) {
			// layer.tips('格式错误', $(ele), {tips: 1});
			layer.alert('格式错误', {title: false, closeBtn: 0}, function(index){
				layer.close(index);
			});
			$(ele).val(number=this.initTxt).focus();
		} else if (this.min && number < this.min) {
			// layer.tips('不能小于' + this.min, $(ele), {tips: 1});
			layer.alert('本奖品最少需'+ this.min +'人参与', {title: false, closeBtn: 0}, function(index){
				layer.close(index);
			});
			$(ele).val(number=this.min).focus();
		} else if (this.max && number > this.max) {
			// layer.tips('不能大于' + this.max, $(ele), {tips: 1});
			layer.alert('本奖品最多可由'+ this.max +'人参与,参与人数已达最大值哦', {title: false, closeBtn: 0}, function(index){
				layer.close(index);
			});
			$(ele).val(number=this.max).focus();
		}
        if("function" == typeof this.callback){
            this.callback(number);
        }
	},
	init: function(callback) {
        this.callback = callback;
		var qtyBox = $('.quantity-wrap');
		var aBtnIncrease = qtyBox.find('.quantity-increase');
		var aBtnDecrease = qtyBox.find('.quantity-decrease');
		var aInputTxt = qtyBox.find('input.quantity');
		var e = this;
		if (qtyBox.attr('data-max')) {
			this.max = parseInt(qtyBox.attr('data-max'),10);
		}
		if (aInputTxt.val()) {
			this.initTxt = parseInt(aInputTxt.val(),10);
		}
		aBtnIncrease.each(function(){
			var _this = $(this);
			_this.on('click', function(){
				e.increase(this);
			})
		});
		aBtnDecrease.each(function(){
			var _this = $(this);
			_this.on('click', function(){
				e.decrease(this);
			})
		});
		aInputTxt.each(function(){
			var _this = $(this);
			_this.on('keyup', function(){
				e.txtEntry(this, true);
			});
			_this.on('blur', function(){
				e.txtEntry(this);
			});
		});
	}
}

/*
 * 倒计时 countDown
 * 调用 DUOBAO.countDown()
 * 参数: ele:指定元素 time:剩余时间
*/
DUOBAO.countDown = function(ele, time){
	var el = ele;
	var originalTime = time;

	function getTimerString(time) {   
            d = Math.floor(time / 86400),     
            h = Math.floor((time % 86400) / 3600),     
            m = Math.floor(((time % 86400) % 3600) / 60),     
            s = Math.floor(((time % 86400) % 3600) % 60); 
            ms = Math.floor(time%1000)


        if (originalTime > 3600) {
	        if (time>0) {
        		return  '<label class="hour">'+ toDouble(h) +'</label>:<label class="min">'+ toDouble(m) +'</label>:<label class="sec">'+ toDouble(s) +'</label>:'+ '<label class="ms">'+ haomiao(ms) +'</label>';
	        } else {
				clearInterval(b);
	        	return '00:00:00:000';
	        }  

        } else {
	        if (time>0) {
	        		return  '<label class="min">'+ toDouble(m) +'</label>:<label class="sec">'+ toDouble(s) +'</label>:'+ '<label class="ms">'+ haomiao(ms) +'</label>';
	        } else {
				clearInterval(b);
	        	return '00:00:000';
	        }        	

        }   
    }  
	
	function parseFn() {
		$(el).html(getTimerString(time-=1)); 
		var ms = $(el).find('.ms');
		var a  = setInterval(function(){
			ms.html(haomiao(new Date().getMilliseconds()));
		},10);
    }  
	var b= setInterval(function(){
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
/*var startTime = $('#countDown').attr('data-start-time');
var endTime = $('#countDown').attr('data-end-time')
var leftTime = new Date(endTime).getTime() - new Date(startTime).getTime();
var fnCountDown = function (str) {
	var timeDistance = str;
	var hm = Math.floor(timeDistance%1000);
	var sec = Math.floor(timeDistance/1000%60);
	var min = Math.floor(timeDistance/1000/60%60);
	var hour = Math.floor(timeDistance/1000/60/60%24);
	
	// $('#1_hour').html(toDouble(hour));
	$('#min').html(toDouble(min));
	$('#sec').html(toDouble(sec));
	$('#ms').html(haomiao(hm));
	leftTime = leftTime - 50;
}

function setTime() {
	setInterval(function(){
		fnCountDown(leftTime);
	}, 50);						
};			
				
var toDouble = function(num){

	return num < 10 ? '0'+num : num;

};

var haomiao = function(num) {
	if (num < 10) return '00' + num.toString();
	if (num < 100) return '0' + num.toString();
	return num.toString();
};*/

/*
 * 夺宝码展开折叠 codeToggle
 * 调用 DUOBAO.codeToggle()
 * 参数:
*/
DUOBAO.codeToggle = function(){
	$('.btn-toggle').on('click', function(){
		$(this).prev().find('p').toggleClass('code-toggle');
	});
};

/*
 * 返回顶部 toTop
 * 调用 DUOBAO.toTop()
 * 参数:
*/
DUOBAO.toTop = function(){
	$('.btn-to-top').on('click', function(){
		var speed = 200;
		$('body, html').animate({scrollTop: 0}, speed);
		return false;
	});
};

/*
 * inputClear 清空文本输入框
 * 调用 DUOBAO.fnInputClear(ele)
 * 参数: ele: input输入框
*/
DUOBAO.fnInputClear = function (ele) {
	var inputVal = $(ele).val();
	if (inputVal == '') {
		$(ele).parent().find('.btn-keyword-clear').hide();
	} else {
		$(ele).parent().find('.btn-keyword-clear').show();
	}

	var oBtnClear = $('.btn-keyword-clear');
	oBtnClear.on('click', function () {
		$(ele).val('');
		$(ele).parent().find('.btn-keyword-clear').hide();
	});

	$(ele).on('keyup', function () {		
		var inputVal = $(ele).val();
		if (inputVal == '') {
			$(ele).parent().find('.btn-keyword-clear').hide();
		} else {
			$(ele).parent().find('.btn-keyword-clear').show();
		}
	});

};

/*
 * fnSwitchButton 转换开关
 * 调用 DUOBAO.fnSwitchButton()
*/
DUOBAO.fnSwitchButton = function () {
	$('.slider-button').on('click', function(){
		if($(this).hasClass('on')){
			$(this).removeClass('on').parent().removeClass('switch-on').addClass('switch-off');
			$(this).next('input[type="hidden"]').val(0);
		}else{
			$(this).addClass('on').parent().removeClass('switch-off').addClass('switch-on');
			$(this).next('input[type="hidden"]').val(1);
		}		
	})
};

/*
 * clickCollect 点击收藏
 * 调用 DUOBAO.clickCollect()
*/
DUOBAO.clickCollect = function (ele) {
	$(ele).on('click', function(){
		if (!$(this).find('i').hasClass('icon-star-on')) {
			$(this).addClass('btn-collected').removeClass('btn-collection');
			$(this).text('已收藏');
			$(this).prepend('<i class="icon-star-on"></i>');
			layer.msg('收藏成功', {icon: 1});
		}
	}); 
};

/*
 * clickLike 晒单点赞
 * 调用 DUOBAO.clickLike()
*/
DUOBAO.clickLike = function(obj) {
    var _this = $(obj);
    if (_this.hasClass('icon-like-on')) {
        layer.msg('已点过赞哦');
    } else {
        var id = _this.parents('div.actions').attr('data-shareid');
        console.log(id);
        DUOBAO._post(DUOBAO.url.share_operate,{share_id:id,type:2}, function(ret){
            if(ret.retCode ==0) {
                if (ret.retData && ret.retData.num) {
                    DUOBAO.reword.init(ret.retData.num, '恭喜获得'+ret.retData.awards+ret.retData.type+'!');
                } else {
                    layer.msg('点赞成功',{icon:1});
                }
                var likeNum = _this.next('strong').text();
                likeNum++;

                DUOBAO.likeParseFn(_this);
                _this.addClass('icon-like-on').removeClass('icon-like');
                _this.next('strong').text(likeNum);

            } else{
                if(ret.retMsg) {
                    layer.msg(ret.retMsg,{icon:2});
                } else {
                    layer.msg('点赞失败',{icon:2});
                }
            }
        })
	}
};

DUOBAO.sign = function(ele) {
    var _this = $(ele);
    _this.on('click', function(){
        DUOBAO._post(DUOBAO.url.sign,{},function(ret){
            if(ret.retCode == 0) {
                if (ret.retData && ret.retData.num) {
                    DUOBAO.reword.init(ret.retData.num, '恭喜获得'+ret.retData.awards+ret.retData.type+'!');
                    if (ret.retData.score){
                        $('#user_score').text(ret.retData.score)
                    }
                    if (ret.retData.coupon){
                        $('#user_coupon').text(ret.retData.coupon)
                    }
                } else {
                    if(ret.retMsg) {
                        layer.msg(ret.retMsg,{icon:1});
                    } else {
                        layer.msg('签到成功',{icon:1});
                    }
                }

                $('.sign .btn-sign span').text('已签到');
                $('.user-sign').removeClass('user-sign');
            } else {
                layer.msg(ret.retMsg,{icon:2});
            }
        });
    })
};

DUOBAO.likeParseFn = function (ele) {
	$(ele).append('<s class="cart-add-one">+1</s>');
	$(ele).parent().find('s.cart-add-one').addClass('add-one-animate');	
	setTimeout(function(){
		$(ele).find('s').remove();
	}, 1000);
};

/*
 * share 分享
 * 调用 DUOBAO.share()
*/
DUOBAO.share = {
	init: function(){
		var e = this;
		$('body').append(this.rootHtml);
		this.show();
		$('#maskShare').on('click', function(){
			e.hide();
		});
	},
	rootHtml: function(){
		var html = [];
		html.push('<div class="mask mask-share" id="maskShare"></div>');
		html.push('<div class="pop-share" id="shareCon"><img src="'+DUOBAO.url.imgcache+'/images/share_weixin_1.png" width="100%" alt=""></div>');
		return html.join('');
	},
	show: function(){
		$('#maskShare, #shareCon').show();
	},
	hide: function(){
		$('#maskShare, #shareCon').remove();
	}
};

/*
 * reword 奖励
 * 调用 DUOBAO.share.init(score, str)
 * score:奖励积分 str:描述
*/
DUOBAO.reword = {
	init: function(score, str, cb){
		var e = this;
		$('body').append(this.rootHtml(score, str));
		this.show();
		setTimeout(function(){
            if(typeof cb == 'function') {
                cb();
            }
			e.hide();
		}, 3000);
	},
	rootHtml: function(score, str){
		var html = [];
		html.push('<div class="mask mask-reword" id="maskReword"></div>');
		html.push('<div class="pop-reword" id="rewordCon"><h3><i class="icon-medals"></i>+'+ score +'</h3><div class="pop-bott">'+ str +'</div></div>');
		return html.join('');
	},
	show: function(){
		$('#maskReword, #rewordCon').show();
	},
	hide: function(){
		$('#maskReword, #rewordCon').remove();
	}
};

/*
 * controlFixedBtn 夺宝详情固定按钮显隐
 * 调用 DUOBAO.controlFixedBtn()
*/
DUOBAO.controlFixedBtn = function(){
	$(window).on('scroll', function(){
		$('.fixed-btns').fadeIn(300);
	});
	setInterval(function(){
		$('.fixed-btns').fadeOut(300);
	}, 5000);
};

/*
 * popWin 首页中奖提示
 * 调用 DUOBAO.popWin.init(prizeName, no, prizePic, url)
 * @param: prizeName:奖品名称 no:期号 prizePic:奖品图片地址 url:奖品兑换详情地址
*/
DUOBAO.popWin = {
	init: function(prizeName, no, prizePic, url){
		var e = this;
		$('body').append(this.rootHtml(prizeName, no, prizePic, url));
		this.show();
		$('#btnPopWinClose').on('click', function(){
			e.hide();
		});
	},
	rootHtml: function(prizeName, no, prizePic, url){
		var html = [];
		html.push('<div class="pop-win-info">');
		html.push('<div class="pop-win-info-content">');
		html.push('<img src="images/bg_win.jpg" width="100%" alt="">');
		html.push('<div class="prize-wrap"><img src="'+ prizePic +'" width="100%" alt=""><div class="banner-win"></div><div class="pop-prize-info"><p class="prize-no">期号：'+ no +'</p><p class="prize-name">'+ prizeName +'</p></div></div>')
		html.push('</div>');
		html.push('<div class="pop-win-info-bott"><a href="'+ url +'" class="btn-exchange-prize">前往个人中心兑奖</a></div>');
		html.push('<a href="javascript:;" id="btnPopWinClose" class="btn-pop-win-close">关闭</a>');
		html.push('</div>');
		return html.join('');
	},
	show: function(){
		$('.mask-win, .pop-win-info').show();
	},
	hide: function(){
		$('.mask-win, .pop-win-info').hide();
	}
};
