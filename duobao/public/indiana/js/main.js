// html判断页面加载完毕
var start = setInterval(updataVal,100);   
function updataVal(){  
  if(document.readyState == "complete"){  
    clearInterval(start);     //执行成功则清除监听 
    $(".loading,.loadingbg").hide();  
  } else{
    $(".loading,.loadingbg").show();
  }
};
// 阻止浏览器默认行为
demo($(".loading,.loadingbg"));
function demo(name){
    name.on({
        // "touchstart": function(e){e.preventDefault();},
        "touchmove": function(e){e.preventDefault();},
        // "touchend": function(e){e.preventDefault();}
    })
};  
// 初始化设置返回顶部按钮位置
$(".back").css("top", $(window).height() -100 + "px"); 
// 初始化设置小伙伴排名高度
if ($(".boys .boys_table .boys_list .boys_cons").length > 5) {
  $(".boys .boys_table .boys_list").css("height",250+"px");
}; 
// 规则弹窗
$(".rule").on("tap", function(){ 
  var otop = $(".pic8").offset().top; 
  $(window).scrollTop(otop); 
});
// 关闭弹窗
$(".close").on("tap", function(){
  var that = $(this);
  //判断是否活动未开始：nstart
  if (!$(".p6").hasClass("nstart")) {
    // 活动未开始
    $(".bg").hide();
  }; 
  that.closest(".popup").hide();
});
// 返回顶部
$(window).scroll(function(){ 
  $(window).scrollTop() > 0 && ($(".back,.detail").show(),$(".dt img").hide());
  $(window).scrollTop() == 0 && $(".back").hide();
});
$(".back").on("tap", function(){
  $(window).scrollTop(0);
});