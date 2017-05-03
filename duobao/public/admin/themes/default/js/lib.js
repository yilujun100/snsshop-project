//============================
//    侧边栏与主体内容区高度等高
//============================
function setHeight(){
	var winH = $(window).height();
	$('.sidebar').css('height', winH - 46);
}

// tab 选项卡
var fnTab = function (ele, con) {

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

// 侧边栏折叠菜单
var toggleMenu = function (){
	var sideBar = $('.sidebar');
	var aDt = sideBar.find('dt');
	aDt.each(function(){
		var _this = $(this);
		_this.on('click', function(){
			$(this).next('dd').slideToggle('fast');
		});
	});
};


$(function(){
	// toggleMenu
	toggleMenu();
})

//全局统一弹出提示框
if (typeof(ADMIN) === 'undefined') {
    var ADMIN = {};
}

(function($){
    /*
     * ajax请求
     * 调用: DUOBAO.ajax.loading(url, type, callback)
     * DUOBAO.ajax.loading('http://localhost/more.json', 'post', function(result){console.log(result);})
     * 参数: url: ajax请求地址 type: ajax请求类型 callback: ajax请求成功回调函数
     */
    ADMIN.ajax = {
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
                    ADMIN.ajax.show();
                },
                success: function(result) {
                    if(typeof callback =='function') {
                        callback(result);
                    } else {
                        if(result.retCode==0){
                            ADMIN.UI.showSucc('操作成功');
                        } else {
                            ADMIN.UI.showError(result.retMsg);
                        }
                    }
                },
                complete: function(){
                    ADMIN.ajax.hide();
                }
            })
        }
    };
    ADMIN._post = function(url,data,callback,dataType){
        dataType = dataType || 'JSON';
        ADMIN.ajax.loading(url, data,callback,dataType, 'post');
    };

    ADMIN._get = function(url,callback,dataType){
        dataType = dataType || 'JSON';
        ADMIN.ajax.loading(url, {},callback,dataType, 'get');
    };

    ADMIN.UI = {
        init: function($conf) {
            var $defaultConf = {autoOpen:true, closeText:"",height:550,width:550,modal:true,dialogClass:'popup'};
            if (typeof ($conf) == 'object') {
                for(var $i in $conf) {
                    $defaultConf[$i]= $conf[$i]
                }
            }
            return $defaultConf;
        },
        /*$id:模板ID; $conf:弹窗配置;$*/
        popForm : function($id, $conf){
            $conf = ADMIN.UI.init($conf);
            $($id).dialog(this.init($conf));
        },
        popAlert : function($id, $conf){
            $($id).dialog(this.init($conf));
        },
        showError : function(title, cb) {
            layer.msg(title, {icon: 2, time: 2000}, function(){
                if(typeof cb == 'function') {
                    cb();
                }
            });
        },
        showSucc : function(title, cb) {
            layer.msg(title, {icon: 1, time: 1000}, function(){
                if(typeof cb == 'function') {
                    cb();
                } else {
                    window.location.reload();
                }
            });
        }
    };

    ADMIN.Opts = {
        audit : function($id, $class, $opt, $cb) {
            layer.confirm('确认要上/下线此记录吗?', {
                title: false,
                closeBtn: false,
                btn: ['确认', '取消']
            }, function () {
                $uri = '/admin/'+$class+'/audit';
                var $params = {id:$id, opt:$opt};
                ADMIN._post($uri, $params, $cb);
            });

        },
        del : function($id, $class) {
            layer.confirm('确认要删除此记录吗?', {
                title: false,
                closeBtn: false,
                btn: ['确认', '取消']
            }, function(){ // 确认回调
                $uri = '/admin/'+$class+'/delete';
                var $params = {id:$id};
                ADMIN._post($uri, $params);
            }, function(){ // 取消回调
            });
        },
        edit : function($id, $class, $tid, $title) {
            if(typeof(ADMIN[$class]) == 'object'){
                var $conf = $id ? ADMIN[$class].dialogConfE : ADMIN[$class].dialogConfA;
                $conf.open = function() {
                    var $this = this;
                    ADMIN[$class].resetInit($this, $tid, $id);
                };
                $conf.close = function (event, ui){
                    $($tid).dialog('destroy');
                };
                ADMIN.UI.popForm($tid, $conf);
            } else {
                var $conf = {};
                if ($title) $conf = {title:$title};
                ADMIN.UI.popForm($tid, $conf);
            }
        }
    };

    ADMIN.page = function (page_index, page_count, sort, order) {
        var base_url = location.href.replace(/\?.*/, '');

        var page_search = {
            page: page_index
        };
        if (sort) {
            page_search.sort = sort;
        }
        if (order) {
            page_search.sort = order;
        }

        function redirect () {
            var origin_search, kv, new_search = {};
            if (! location.search) {
                new_search = $.extend({}, page_search);
            } else {
                origin_search = location.search.substr(1).split('&');
                for (var i = 0, len = origin_search.length; i < len; i ++) {
                    kv = origin_search[i].split('=');
                    var key = decodeURIComponent(kv[0]).replace('+', ' '),
                        value = decodeURIComponent(kv[1]).replace('+', ' ');
                    if (page_search[key]) {
                        continue ;
                    }
                    new_search[key] = value;
                }
                $.extend(new_search, page_search);
            }
            location.href = base_url + '?' + $.param(new_search);
        }

        return {
            next: function () {
                if (page_search.page >= page_count) {
                    return;
                }
                page_search.page ++;
                redirect();
            },
            prev: function () {
                if (page_search.page <= 1) {
                    return;
                }
                page_search.page --;
                redirect();
            },
            go: function (index) {
                if (index < 1 || index > page_count) {
                    return ;
                }
                page_search.page = index;
                redirect();
            },
            sort: function (sort) {
                page_search.sort = sort;
            },
            order: function (order) {
                page_search.order = order;
            }
        }
    };
})(jQuery);
