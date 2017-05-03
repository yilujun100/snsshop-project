$(function(){

	// 首页轮播
	var swiper = new Swiper('#slider', {
        pagination: '.swiper-pagination',
        paginationClickable: true
    });

	// 中奖信息	
	scrollInfo('#scrollInfo');
	

	// tab
	fnTab('#tab1 a', '#tabCon1 > div');
	//fnTab('#tab2 a', '#tabCon2 > div');
    $('#tab2>a').on('click', function(){
        var _this = $(this);
        var index = _this.parent().find('a').index($(this));
        var url = _this.attr('data-url');

        if(!$('#tabCon2 > div').eq(index).hasClass('empty')){
            _this.parent().find('a').removeClass('tab-active');
            _this.addClass('tab-active');

            $('#tabCon2 > div').hide();
            $('#tabCon2 > div').eq(index).show();
        }else{
            DUOBAO._get(url,function(rs){
                if(!rs || rs.retCode != 0){
                    alert('加载数据失败~');
                    return ;
                }else{
                    var html = [];
                    html.push('<ul class="list clearfix">');
                    $.each(rs.retData.list,function(i,li){
                        html.push(zoonTemp(li));
                    });
                    html.push('</ul>');
                    $('#tabCon2 > div').eq(index).html(html.join(''));
                }

                _this.parent().find('a').removeClass('tab-active');
                _this.addClass('tab-active');

                $('#tabCon2 > div').hide();
                $('#tabCon2 > div').eq(index).removeClass('empty').show();
            });
        }
    });

    DUOBAO.loadMore('#tab2>a',function(rs,index,_this){
        if(!rs || rs.retCode != 0){
            alert('加载数据失败~');
            return ;
        }else{
            if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
            $.each(rs.retData.list,function(i,li){
                $('#tabCon2>div').eq(index).find('ul').append(zoonTemp(li));
            })
        }
    });

    function zoonTemp(li){
        var conf = DUOBAO.config.marktag;
        var html = [];
		html.push('<li>');
		html.push('<div class="prize-pic"><a href="'+li.url+'"><img src="'+ li.sImg +'" width="100%" alt=""></a>');
        if(li.iCornerMark && li.iCornerMark > 0 && typeof conf[li.iCornerMark] != 'undefined'){
            if(conf[li.iCornerMark]['img'] != ''){
                html.push('<div class="goods-tag"><img src="'+conf[li.iCornerMark]['img']+'" width="40" height="36" alt=""></div>');
            }else{
                html.push('<div class="goods-tag"><img src="'+DUOBAO.url.resource_url+'images/goods_tag_default.png" width="40" height="36" alt=""><span class="goods-tag-txt">'+conf[li.iCornerMark]['text']+'</span></div>');
            }
        }
        html.push('</div>');
		html.push('<div class="prize-name">'+ li.sGoodsName +'</div>');
		html.push('<div class="lott-schedule" data-lott-schedule="'+ li.iProcess +'%">');
		html.push('<div class="progress-bar"><span class="progress-bar-on" style="width:'+ li.iProcess +'%"></span></div><em>'+ li.iProcess +'%</em>');
		html.push('</div>');
		html.push('<div class="prize-bott"><a href="'+li.buy_url+'" class="btn-parti btn-parti-1">立即参与</a><a href="javascript:;" onclick="DUOBAO.addCart($(this))" class="add-to-cart" peroid-str="'+li.peroid_str+'">添加到购物车<span class="icon-plus">+</span></a></div>')
		html.push('</li>');

        return html.join('');
    }


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
		slidesPerView: 2.5,
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

	// 进度条
	$('.progress-bar').each(function(){
		var _percent = $(this).parent().attr('data-lott-schedule');
		_percent = _percent.substring(0, _percent.length - 1);
		$(this).find('span.progress-bar-on').animate({'width': _percent + '%'}, 600);
	});



	// 商品分类
	$('#category > a').on('click', function(){
		if ($(this).next('.cate-sub').is(':hidden')) {
			$('.cate-sub').css({'height': $(window).height() - 47});
			$('.mask, .cate-sub').show();
		} else {			
			$('.mask, .cate-sub').hide();
		}
	});
	$('#homeMask').on('click', function(){
		$('.mask, .cate-sub').hide();
	});
	
	// 关键字文本框清空
	DUOBAO.fnInputClear('#keyword');

})