(function($){	
	// var id = 0;
	var prev = $(".dleft");  //上一张
	var next = $(".dright");  //下一张		
	var del = $(".del");    //删除  
	/*$(".mimg").each(function(i){
		var _this = $(this);
		_this.on('click', function(){
			DUOBAO.parsePhoto(i);
		});
	});*/
	// 上一张
	prev.on("click",function(){ 
		DUOBAO.photo.id--;
		if (DUOBAO.photo.id >= 0) {
			$(".dimg").find("div").hide();
			$(".dimg").find("div").eq(DUOBAO.photo.id).show(); 
		}else{
			DUOBAO.photo.id = 0;
		}
	});
	// 下一张
	next.on("click",function(){
		DUOBAO.photo.id++;
		if (DUOBAO.photo.id < $(".mimg").length) {
			$(".dimg").find("div").hide();
			$(".dimg").find("div").eq(DUOBAO.photo.id).show(); 
		}else{
			DUOBAO.photo.id = $(".mimg").length-1;
		}
	});
	// 删除
	del.on("click",function(){
		console.info(DUOBAO.photo.id);
		$(".mimg").eq(DUOBAO.photo.id).remove();
		$(".dimg").find("div").eq(DUOBAO.photo.id).remove();
		var length = $(".mimg").length; 
		if (length == 0) {
			$(".pic-large,.bg").hide();  //没有图片了
			return false;
		};
		if (DUOBAO.photo.id >= length) {DUOBAO.photo.id--};//显示最后一张图片
		$(".dimg").find("div").eq(DUOBAO.photo.id).show();
	});
	$(".bg, .back-edit").on("click",function(){
		$(".pic-large,.bg").hide();
	});

})(jQuery);
DUOBAO.photo = {
	id: 0
}
DUOBAO.photo.parsePhoto = function (i) {
	var index = i;  
	DUOBAO.photo.id = i;
	$(".dimg").find("div").removeClass("active").hide();
	$(".dimg").find("div").eq(index).addClass("active").show(); 
	$(".pic-large,.bg").show();
}