$(function () {
	
	$('#actChooseTab > a').on('click', function() {
		var $this = $(this);
		var index = $this.parent().find('a').index($this);
		var $con = $('#actChooseCon > div').eq(index);
		var url = $this.attr('data-url');

		if(! $('#actChooseCon > div').eq(index).hasClass('empty')){
			$this.parent().find('a').removeClass('tab-active');
			$this.addClass('tab-active');
			$('#actChooseCon > div').hide();
			$('#actChooseCon > div').eq(index).show();
			return;
		}

		$this.attr('data-processing','true')
			.removeAttr('data-load')
			.removeAttr('page-index');

		DUOBAO._get(url, function (rs) {
			var html = [];

			$this.removeAttr('data-processing');

			if (! rs || rs.retCode != 0) {
				return ;
			}

			if(rs.retData.page_index >= rs.retData.page_count) {
				$this.attr('data-load', 'false');
			}

			html.push('<ul class="clearfix">');
			$.each(rs.retData.list, function(i, record) {
				html.push(record_tmpl(record));
			});
			html.push('</ul>');

			$this.parent().find('a').removeClass('tab-active');
			$this.addClass('tab-active');

			$('#actChooseCon > div').hide();
			$con.html(html.join('')).removeClass('empty').show();
		});
	});

	$('#search-do').on('click', function () {
		var val = $.trim($('#goodsKey').val()),
			escape = ['', '请输入搜索关键词', $('#goodsKey').attr('data-origin') || ''];
		if (-1 !== $.inArray(val, escape)) {
			$('#goodsKey').focus();
			return;
		}
		$('#goodsKey').attr('data-origin', val);
		$('#actChooseTab > a').each(function () {
			var $this = $(this),
				base = $this.attr('data-base'),
				url;
			if (-1 == base.indexOf('?')) {
				url = base + '?k=' + val;
			} else {
				url = base + '&k=' + val;
			}
			$this.attr('data-url', url);
		});
		$('#actChooseCon > div').addClass('empty').empty();
		$('#actChooseTab > a.tab-active').click();
	});

	$('#search-clean').on('click', function () {
		$('#actChooseTab > a').each(function () {
			var $this = $(this);
			$this.attr('data-url', $this.attr('data-base'));
		});
		$('#goodsKey').removeAttr('data-origin');
		$('#actChooseCon > div').addClass('empty').empty();
		$('#actChooseTab > a.tab-active').click();
	});

	$('#goodsKey').on('keyup', function (event) {
		if(13 == event.keyCode) {
			$('#search-do').click();
		}
	});

	DUOBAO.fnInputClear('#goodsKey');

	DUOBAO.loadMore('#actChooseTab > a', function (rs, index, $this) {
		if (! rs || rs.retCode != 0) {
			return ;
		}
		if(rs.retData.page_index >= rs.retData.page_count) {
			$this.attr('data-load', 'false');
		} else {
			$this.removeAttr('data-load');
		}
		$.each(rs.retData.list,function(i, record) {
			$('#actChooseCon > div').eq(index).find('ul').append(record_tmpl(record));
		});
	});

	new Swiper('#swiperCategory', {
		slidesPerView: 5.5,
		freeMode: true
	});

	$('#actChooseCon').on('click', '.btn-prize-choose', function() {
		$(this).closest('#actChooseCon').find('.selected-box').remove();
		$(this).closest('#actChooseCon').find('.prize-actions').css({'visibility': 'visible'});
		$(this).closest('#actChooseCon').find('.btn-prize-choose').text('选择');
		//$(this).parent().css({'visibility': 'hidden'});
		$(this).parent().parent().append('<div class="selected-box"><em class="icon-selected"></em></div>');
		$(this).text('已选定');
	});

	$('.btn-prize-cancel').click(function () {
		history.back();
	});

	$('.btn-prize-confirm').click(function () {
		var $sel = $('.selected-box', '#actChooseCon');
		if (1 != $sel.length) {
			layer.msg('请选择且仅选择一个奖品', {icon:2,time:1000});
			return;
		}
		location.href = $('#settingBase').val() + $sel.parent('li').attr('data-id');
	});

	function record_tmpl (record) {
		var html = [
			'<li data-id="' + record.iGoodsId + '">',
			'<div class="prize-pic">'
		];
		html.push('<img src="' + record.sImg + '" width="100%" alt="">');
		html.push('</div>');
		html.push('<div class="prize-name">' + record.sName.substring(0, 11) + '</div>');
		html.push('<div class="prize-actions">');
		html.push('<a href="javascript:;" class="btn-prize-opera btn-prize-choose">选择</a>');
		html.push('</div>');
		html.push('</li>');
		return html.join('\n');
	}
});
