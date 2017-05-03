$(function () {

    var vv_key = 'duogebao_visit',
        vv_timeout = 60000;
    
    $.ajax({
        url: '/duogebao/visitor_data/index',
        data: {
            vv: vv(),
            url: encodeURI(location.href),
            referrer: encodeURI(document.referrer),
            agent: encodeURI(navigator.userAgent)
        }
    });

    function vv() {
        if ('undefined' !== typeof sessionStorage) {
            return sessionVV();
        }
        return cookieVV();
    }

    function sessionVV () {
        var vv_s = sessionStorage.getItem(vv_key),
            now = new Date().getTime(),
            vvCount = 0;
        if (! vv_s || now - vv_s > vv_timeout) {
            vvCount = 1;
        }
        sessionStorage.setItem(vv_key, now);
        return vvCount;
    }

    function cookieVV () {
        var vv_c = getCookie(vv_key),
            now = new Date().getTime(),
            vvCount = 0;
        if (! vv_c || now - vv_c > vv_timeout) {
            vvCount = 1;
        }
        setCookie(vv_key, now, vv_timeout);
        return vvCount;
    }

    function getCookie(name) {
        var arr,
            reg = new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr = document.cookie.match(reg))
            return decodeURIComponent(arr[2]);
        else
            return null;
    }

    function setCookie(name, value, time) {
        var exp = new Date();
        exp.setTime(exp.getTime() + time*1);
        document.cookie = name + "="+ encodeURIComponent(value) + ";expires=" + exp.toGMTString();
    }
});