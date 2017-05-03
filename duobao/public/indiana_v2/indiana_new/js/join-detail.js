var isOpen = false;//参与记录是否打开状态
var isOpen2 = false;//夺宝码是否打开状态
var allText = $(".code-list").text();
var ele = $('.count-down');
var leftTime = (new Date(ele.attr('data-end-time')).getTime() - new Date(ele.attr('data-start-time')).getTime()) / 1000;

//分享好友事件
$(".share-btn").click(function() {
	$(".share-box").show();
	 bodyHidden();
})
$(".share-box").click(function() {
	$(".share-box").hide();
	bodyAuto();
})
$(".open-all").click(function() {
	if(isOpen) {
		$(".head-ul").show();
		$(".open-head-ul").hide();
		$(".open-all span").text('查看全部参与记录')
		$(".open-all img").css({
			"transform": "rotate(360deg)"
		});
		isOpen = false;
	} else {
		$(".head-ul").hide();
		$(".open-head-ul").show();
		$(".open-all span").text('收起全部参与记录')
		$(".open-all img").css({
			"transform": "rotate(180deg)"
		});
		isOpen = true;
	}
})
$(".code-list").wordLimit(50);
$(".code-all div").click(function() {
		if(isOpen2) {
			$(".code-list").wordLimit(50);
			$(".code-all p").text('展开');
			$(".code-all img").css({
				"transform": "rotate(360deg)"
			});
			isOpen2 = false;
		} else {
			$(".code-list").text(allText);
			$(".code-all p").text('折叠');
			$(".code-all img").css({
				"transform": "rotate(180deg)"
			});
			isOpen2 = true;
		}
	})

function bodyHidden() {
	$("body").css('overflow', 'hidden');
	$("html").css('overflow', 'hidden');
}

function bodyAuto() {
	$("body").css('overflow', 'auto');
	$("html").css('overflow', 'auto');
}
