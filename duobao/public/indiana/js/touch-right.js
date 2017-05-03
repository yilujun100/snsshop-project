var right = function(demo) {
    var sx = 0,
        sy = 0,
        ey = 0,
        moveX = 0,
        moveY = 0,
        top = parseInt(demo.css("top"));  
        var w_height = $(window).height();  
        var me_height = 0; 
        var bol = false;
    demo.on({
        "touchstart": function(e) {
            e.preventDefault();
            var touch = e.touches[0] || e.changedTouches[0];
            sx = touch.pageX;
            sy = touch.pageY;
        },
        "touchmove": function(e) {
            e.preventDefault();
            var touch = e.touches[0] || e.changedTouches[0];
            var mx = touch.pageX;
            var my = touch.pageY;
            moveX = mx - sx;
            if (ey == 0) {
                ey = top; 
                top = 0;
            }  
            moveY = my - sy + ey;
            me_height = $(this).height();
            if(moveY < 0){
                moveY = 0; 
            }else if(moveY + me_height >= w_height){
                moveY = w_height - me_height; 
            }  
            $(this).css({ right: -moveX + "px", top: moveY + "px" });
            bol = true;
        },
        "touchend": function(e) {
            e.preventDefault();
            var touch = e.touches[0] || e.changedTouches[0];
            if(moveY + me_height >= w_height){
                moveY = w_height - me_height; 
            }  
            ey = moveY;
            if (bol) {
            	$(this).animate({ right: 10 + "px", top: moveY + "px" }, 50);
            }; 
        },
    })
}
