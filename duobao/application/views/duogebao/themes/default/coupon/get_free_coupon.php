<script src="<?=$resource_url?>share/js/mobile.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?=$resource_url?>share/style/bfhl.css">

<div id="container">
    <div class="bfhl-infos">
        <img src="<?=$resource_url?>share/images/bfhl2_01.jpg" alt="">
        <img src="<?=$resource_url?>share/images/bfhl2_02.jpg" alt="">
        <img src="<?=$resource_url?>share/images/bfhl2_03.jpg" alt="">
        <div class="bfhl-btns" id="get-bfhl-js">
            <img src="<?=$resource_url?>share/images/bfhl2_04.jpg" alt="">
            <a href="javascript:;" class="bfhl-btn"></a>
        </div>
        <img src="<?=$resource_url?>share/images/bfhl2_05.jpg" alt="">
        <img src="<?=$resource_url?>share/images/bfhl2_06.jpg" alt="">
        <img src="<?=$resource_url?>share/images/bfhl2_07.jpg" alt="">
        <img src="<?=$resource_url?>share/images/bfhl2_08.jpg" alt="">
        <img src="<?=$resource_url?>share/images/bfhl2_09.jpg" alt="">
    </div>

    <form id="myform" action="" method="post">
        <input type="hidden" name="peroid_str" value="<?=$peroid_str?>">
        <input type="hidden" name="share_id" value="<?=$share_id?>">
        <input type="hidden" name="active_type" value="<?=$active_type?>">
    </form>
</div>

<div class="masked" style="display:none"></div>
<div class="popup" style="display:none">
    <!-- 成功 -->
    <div class="popup_load1 bfhl-layer-table " style="display:none">
        <div class="bfhl-layer-close"></div>
        <div class="bfhl-layer-tcell">
            <div class="bfhl-layer-title ok">领取成功！</div>
            <div class="bfhl-layer-btn">快来夺宝吧 </div>
        </div>
    </div>
    <!-- 失败 -->
    <div class="popup_load2 bfhl-layer-table " style="display:none">
        <div class="bfhl-layer-close" ></div>
        <div class="bfhl-layer-tcell">
            <div class="bfhl-layer-title">领取失败！</div>
            <div class="bfhl-layer-desc">晒单成功才可领取哦~</div>
            <div class="bfhl-layer-btn">快来夺宝吧 </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("touchstart", function(){}, true);
    $("#get-bfhl-js").on("click",function(event){
        event.preventDefault()
        var data = $('#myform').serialize();
        DUOBAO._post('<?=(gen_uri('/coupon/ajax_free_coupon'))?>',data,function(rs){
            rs = typeof(rs) === 'string' ? $.parseJSON(rs) : rs;
            if(rs.retCode == 0){
                $('.bfhl-layer-table').hide();
                $('.popup_load1').show();
                $(".masked,.popup").show();
            }else{
                $('.bfhl-layer-table').hide();
                $('.popup_load2').show();
                $(".masked,.popup").show();
            }
        });
    });
    $('.bfhl-layer-close').on("click",function(event){
        event.preventDefault()
        $(".masked,.popup").hide();
    });
</script>