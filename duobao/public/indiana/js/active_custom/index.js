$(function () {

	$('#actCustomTab > a').on('click', function() {
		var $this = $(this);
		var index = $this.parent().find('a').index($this);
		var url = $this.attr('data-url');

		if(! $('#actCustomCon > div').eq(index).hasClass('empty')){
			$this.parent().find('a').removeClass('tab-active');
			$this.addClass('tab-active');

			$('#actCustomCon > div').hide();
			$('#actCustomCon > div').eq(index).show();

			return;
		}
		DUOBAO._get(url, function (rs) {
			var html = [];
			if (! rs || rs.retCode != 0) {
				return ;
			}
			$.each(rs.retData.list, function(i, record) {
				html.push(record_tmpl(record));
			});
			$('#actCustomCon > div').eq(index).html(html.join(''));

			$this.parent().find('a').removeClass('tab-active');
			$this.addClass('tab-active');

			$('#actCustomCon > div').hide();
			$('#actCustomCon > div').eq(index).removeClass('empty').show();

			doCountDown();
		});
	});

	DUOBAO.loadMore('#actCustomTab > a', function (rs, index, $this) {
		if (! rs || rs.retCode != 0) {
			$this.attr('data-load', 'false');
			return ;
		}
		if(rs.retData.page_index >= rs.retData.page_count) {
			$this.attr('data-load', 'false');
		}
		$.each(rs.retData.list,function(i, record) {
			$('#actCustomCon > div').eq(index).append(record_tmpl(record));
		});

		doCountDown();
	});

	function record_tmpl (record) {
		var html = [
			'<div class="grid-1 record-item">',
			'<div class="prize-pic">',
			'<img src="' + record.sImg + '" width="84" height="84" alt="">'
		];
		if (1 == record.iLotState) {
			html.push('<label class="label-record label-success">即将揭晓</label>');
		} else if (2 == record.iLotState && record.uin == record.iWinnerUin) {
			html.push('<em class="tag-prize-win"></em>');
			//html.push('<a href="' + record.detailUrl + '" class="prize-detail">奖品详情</a>');
		} else {
			html.push('<label class="corner corner-custom">定制</label>');
		}
		html.push('</div>');
		html.push('<div class="record-core-cell-1">');

		if (record.sGoodsName.length > 20) {
			html.push('<div class="prize-name">' + record.sGoodsName.substring(0, 21) + '...</div>');
		} else {
			html.push('<div class="prize-name">' + record.sGoodsName + '</div>');
		}

		html.push('<div class="record-core-cell-2 clearfix">');
		html.push('<em class="fl">期号：' + record.periodNum + '</em>');
		if (0 == record.iLotState) {
			html.push('<span class="fr">' + record.iProcess + '%</span>');
		}
		html.push('</div>');

		if(0 == record.iLotState) {
			html.push('<div class="lott-schedule" data-lott-schedule="' + record.iProcess + '%">');
			html.push('<div class="progress-bar">');
			html.push('<span class="progress-bar-on" style="width: ' + record.iProcess + '%;"></span>');
			html.push('</div>');
			html.push('</div>');
		}

		html.push('<div class="record-core-cell-3 clearfix">');
		if (1 != record.iLotState) {
			html.push(' <span class="fl">您已参与<strong>'+record.joinCount+'</strong>人次</span>');
		} else {
			html.push('<div class="fl record-countDown">揭晓倒计时：<div class="countDown" data-start-time="'+record.sCurTime+'" data-end-time="'+record.sEndTime+'">14:34:133<span class="ms"></span></div></div>');
		}
		if (0 != record.iLotState) {
			html.push('<a href="' + record.detailUrl + '" class="btn-parti-detail fr">参与详情</a>');
		}
		html.push('</div>');

		if (0 == record.iLotState) {
			html.push('<div class="record-core-opt clearfix">');
			html.push('<a href="' + record.detailUrl + '">参与</a>');
			html.push('<a href="' + record.shareUrl + '">分享</a>');
			html.push('</div>');
		} else if (2 == record.iLotState) {
			html.push('<div class="record-core-cell-4">');
			html.push('<p>获奖者：<b>' + record.sWinnerNickname + '</b></p>');
			html.push('<p>幸运码：<strong>' + record.sWinnerCode + '</strong></p>');
			html.push('<p>本期购买：<strong>' + record.iWinnerCount + '</strong>人次</p>');
			html.push('<p>揭晓时间：<b>' + record.sLotDateTime + '</b></p>');
			html.push('</div>');
			if (2 == record.iLotState && record.uin == record.iWinnerUin) {
				html.push('<div class="record-core-opt-1 clearfix">');
				html.push('<a href="'+record.winDetailUrl+'">查询中奖详情</a>');
				html.push('</div>');
			}
		}
		html.push('</div>');
		html.push('</div>');
		html.push('</div>');
		return html.join('\n');
	}

	function doCountDown() {
		var aCountDown = $('.countDown');
		console.log(aCountDown);
		for (var i = 0; i < aCountDown.length; i++) {
			if ($(aCountDown[i]).hasClass('init')) {
				continue;
			}
			var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime()) / 1000;
			var ele = aCountDown[i];
			DUOBAO.countDown(ele, leftTime);
			$(aCountDown[i]).addClass('init')
		}
	}

	doCountDown();

	DUOBAO.chooseQty.init();
});
