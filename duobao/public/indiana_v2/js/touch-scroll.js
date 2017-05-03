
var scrolling = function(scroll,htext){
    var topmove, sy, iny = 0;
    scroll.on({
        "touchstart": function(e) {
            e.preventDefault();
            var touch = e.touches[0] || e.changedTouches[0];
            sy = touch.pageY;
         },
        "touchmove": function(e) {
            e.preventDefault();
            var touch = e.touches[0] || e.changedTouches[0];
            var my = touch.pageY;
            topmove = my - sy + iny;
            $(this).css({
                'transform': 'translateY(' + topmove + 'px)',
                '-webkit-transform': 'translateY(' + topmove + 'px)',
                'transition': 'all 0s linear',
                '-webkit-transition': 'all 0s linear'
            });
        },
        "touchend": function(e) {
            e.preventDefault();
            var touch = e.touches[0] || e.changedTouches[0];
            var endy = touch.pageY; 
            var topend = topmove;
            if (topend > 0||($(this).height() - htext.height()) <= 0) { 
                iny = 0;
                $(this).css({
                    'transform': 'translateY(' + 0 + 'px)',
                    '-webkit-transform': 'translateY(' + 0 + 'px)',
                    'transition': 'all 0s linear',
                    '-webkit-transition': 'all 0s linear'
                });
            }
            else if(topend<-($(this).height() - htext.height())){
                var y=-($(this).height() - htext.height())
                iny = y;
                $(this).css({
                    'transform': 'translateY(' + y + 'px)',
                    '-webkit-transform': 'translateY(' + y + 'px)',
                    'transition': 'all 0s linear',
                    '-webkit-transition': 'all 0s linear'
                });
            }
            else { //当前移动距离
                iny = topend;
                $(this).css({
                    'transform': 'translateY(' + topend + 'px)',
                    '-webkit-transform': 'translateY(' + topend + 'px)',
                    'transition': 'all 0s linear',
                    '-webkit-transition': 'all 0s linear'
                });
            }
        }
    });
} 