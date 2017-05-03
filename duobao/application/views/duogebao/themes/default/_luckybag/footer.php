<script type="text/javascript" src="<?=$resource_url?>js/lib.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/layer/layer.js"></script>
<!-- swiper script -->
<script src="<?=$resource_url?>js/swiper/swiper.min.js" type="text/javascript"></script>
        <script>
            // 跳转控制
            function tabInit() {
                var str = window.location.search;
                if (str.indexOf('tab') != -1) {
                    var tabNum = str.substring(5);
                    setTab(tabNum);
                }
            }

            function setTab(n) {
                var aTabTitle = $('.tab-tit a');
                var aTabCon = $('.tab-con > div');
                aTabTitle.each(function(n){
                    aTabTitle.removeClass('active');
                    aTabCon.hide();
                });

                aTabTitle.eq(n).addClass('active');
                aTabCon.eq(n).show();

            }
        </script>
