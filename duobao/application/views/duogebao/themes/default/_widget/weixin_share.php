<?php if(!empty($signPackage)){ ?>
    <script>
        //JSSDK 接口配置
        <?php
        if(!empty($signPackage)){
            foreach ($signPackage AS $k => $v){
        ?>
        var <?=$k?> = '<?=$v?>';
        <?php  }} ?>

        /*分享发送数据*/
        var shareTitle = '<?=isset($shareData['shareTitle']) ? $shareData['shareTitle'] : ''?>';
        var sendFriendTitle = '<?=isset($shareData['sendFriendTitle']) ? $shareData['sendFriendTitle'] : ''?>';
        var sendFriendDesc = '<?=isset($shareData['sendFriendDesc']) ? $shareData['sendFriendDesc'] : ''?>';
        var shareUrl =  '<?=isset($shareData['shareUrl']) ? $shareData['shareUrl'] : current_url()?>';
        var shareImg =  '<?=isset($shareData['shareImg']) ? $shareData['shareImg'] : ''?>';

        $(function(){
            //微信分享配置
            wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: appId, // 必填，公众号的唯一标识
                timestamp:timestamp, // 必填，生成签名的时间戳
                nonceStr: nonceStr, // 必填，生成签名的随机串
                signature: signature,// 必填，签名，见附录1
                jsApiList: [
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'showOptionMenu',
                    'hideOptionMenu',
                    'hideAllNonBaseMenuItem',
                    'showMenuItems'
                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
            //微信分享
            wx.ready(function() {
                //屏蔽分享及刷新菜单
                if (typeof DUOBAO.menuShow !== 'undefined' && DUOBAO.menuShow == '0') {
                    wx.hideOptionMenu();
                } else {
                    wx.showOptionMenu();
                    wx.hideAllNonBaseMenuItem();
                    wx.showMenuItems({
                        menuList: [
                            'menuItem:share:appMessage',
                            'menuItem:share:timeline',
                            'menuItem:favorite',
                            'menuItem:copyUrl',
                            'menuItem:share:email',
                            'menuItem:share:brand'
                        ]
                    });
                    //分享到朋友圈
                    wx.onMenuShareTimeline({
                        title:shareTitle, // 分享标题
                        link:shareUrl, // 分享链接
                        imgUrl:shareImg,// 分享图标
                        success: function () {
                            if(url.indexOf('?') == -1){
                                url = url + '?isload=1';
                            }else{
                                url = url + '&isload=1';
                            }
                            location.href = url;
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        }
                    });
                    //分享给朋友
                    wx.onMenuShareAppMessage({
                        title:sendFriendTitle, // 分享标题
                        desc:sendFriendDesc, // 分享描述
                        link:shareUrl, // 分享链接
                        imgUrl:shareImg, // 分享图标
                        type: '', // 分享类型,music、video或link，不填默认为link
                        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                        success: function () {
                            if(url.indexOf('?') == -1){
                                url = url + '?isload=1';
                            }else{
                                url = url + '&isload=1';
                            }
                            location.href = url;
                        },
                        cancel: function () {
                        }
                    });
                }

            });
        })
    </script>
<?php } ?>