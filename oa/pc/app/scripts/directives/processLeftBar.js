angular.module('changeLeftBar', [])
    .directive('leftBar', ['longStatus', function(longStatus) {
        return function(scope, element, attrs) {
            element.on('click', function() {
                longStatus.setStatus(1);
                $(".l-nav").animate({ width: "200px" });
                $(".l-nav li a").animate({ width: "200px" });
                $(".content-page").animate({ marginLeft: "200px" });
                $(".sm").css('display', 'none');
                $(".lb-icon").animate({ opacity: '0' });
                $(".lb-icons").animate({ opacity: '1', zIndex: '101' });
                $(".lg").css('display', 'block');
                $(".lg img").css({ 'position': 'absolute', 'top': '28px', 'left': '26px' });
                $(".l-nav .lg .head-pic img").css('left', '-120px');
            });
        }
    }])
    .directive('leftBarBack', ['longStatus', function(longStatus) {
        return function(scope, element, attrs) {
            element.on('click', function() {
                longStatus.setStatus(0);
                $(".l-nav").animate({ width: "80px" });
                $(".l-nav li a").animate({ width: "80px" });
                $(".content-page").animate({ marginLeft: "80px" });
                $(".leftstatus").animate({ marginLeft: "0px" });
                $(".sm").css('display', 'block');
                $(".lb-icon").animate({ opacity: '1' });
                $(".lb-icons").animate({ opacity: '0', zIndex: '99' });
                $(".lg").css('display', 'none');
                $(".l-nav .lg .head-pic img").css('left', '-120px');
            });
        }
    }])
    .factory('longStatus', [function() {
        var status = false;

        return {
            setStatus: function(value) {
                if (value)
                    status = true;
                else
                    status = false;
                // console.log(this.getStatus());
            },
            getStatus: function() {
                return status;
            }
        }
    }]);
