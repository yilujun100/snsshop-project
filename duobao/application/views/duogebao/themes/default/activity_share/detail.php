<div class="viewport v-share">
    <!-- share start -->
    <div class="share">
        <div class="share-bg">
            <img src="<?=$cdn_project_url?>images/bg_share.jpg" width="100%" alt="">
        </div>

        <div class="coupon-receive">
            <div class="coupon">
                <img src="<?=$cdn_project_url?>images/ticket.png" width="100%" alt="">
                <div class="txt-1">
                    <img src="<?=$cdn_project_url?>images/txt_2.png" width="100%" alt="">
                </div>
                <?php if(!empty($is_new) && !empty($is_awards)) {?>
                <div class="btn-groups">
                    <a href="javascript:;" class="btn-received" id="btnReceived">已领取</a>
                    <a href="<?=gen_uri('/home/index')?>" class="btn-go-1" id="btnGo1">去百分好礼</a>
                </div>
                <?php } else {?>
                <a href="javascript:;" class="btn-coupon" id="btnCoupon">免费领券</a>
                <?php } ?>
            </div>
            <img src="<?=$cdn_project_url?>images/hands.png" class="hands" width="100%" alt="">
            <div class="screen" id="dShow">
                <?php
                if(!empty($invite_succ_list)) {
                    foreach ($invite_succ_list as $item) {
                        ?>
                        <div><?=$item['sNickName']?>邀请<?=$item['sToNickName']?>两人各得一张夺宝券</div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="intro">
            <img src="<?=$cdn_project_url?>images/bg_intro.png" width="96%" alt="">
            <ul>
                <li class="track"></li>
                <li class="intro-item">
                    <em>1</em>
						<span>
							<p><strong>活动时间：2016.5.26-2016.5.29</strong></p>
						</span>
                </li>
                <li class="intro-item">
                    <em>2</em>
						<span>
							<p><strong>怎么获得券？</strong></p>
							<p>您分享当前活动页面 ，邀请新用户首次进入“百分好礼”参与成功后，两人均可获得一张券。</p>
						</span>
                </li>
                <li class="intro-item">
                    <em>3</em>
						<span>
							<p>本活动只对分享新用户有效，同一微信号视为同一个用户；</p>
						</span>
                </li>
                <li class="intro-item">
                    <em>4</em>
						<span>
							<p>用户所获券可在个人中心"可用券"进行查看；仅用于参加百分好礼</p>
						</span>
                </li>
            </ul>
            <p class="intro-other">在法律允许的范围内，微团购保留最终解释权</p>
        </div>
    </div>
    <!-- share end -->
</div>
<!-- popup start -->
<div class="mask"></div>

<div class="popup popup-coupon"  id="popup-use">
    <div class="popup-content">
        <img src="<?=$cdn_project_url?>images/txt_4.png" class="popup-txt" width="244" height="50" alt="">
        <div class="pop-btns">
            <a href="<?=gen_uri('/activity_share/index')?>" class="btn-share-immedia">立即分享</a>
            <a href="<?=gen_uri('/home/index')?>" class="btn-go">去百分好礼</a>
        </div>
    </div>
</div>

<div class="popup popup-coupon" id="popup-coupon">
    <div class="popup-content">
        <img src="<?=$cdn_project_url?>images/txt_3.png" class="popup-txt" width="202" height="33" alt="">
        <a href="<?=gen_uri('/my/index')?>" class="btn-use">马上使用</a>
        <a href="<?=gen_uri('/guide/course')?>" class="btn-view-rules">查看"葵花宝典"&gt;&gt;</a>
    </div>
</div>
<!-- popup end -->
<script>
    $(function(){
        <?php if(empty($is_new)) { //老用户?>
            $('.mask, #popup-use').show();
        <?php }else { ?>
        $('#btnCoupon').on('click', function(){
            MAISHA._post('<?=gen_uri('/activity_share/ajax_get_coupon')?>',{uin:'<?=$uin?>',sign:'<?=$sign?>'}, function(rs){
                if(!rs || rs.retCode != 0){
                    if(rs.retMsg) {
                        layer.msg(rs.retMsg);
                    } else {
                        layer.msg('操作失败~');
                    }
                } else {
                    $('.mask, #popup-coupon').show();
                }
            })
        });
       <?php } ?>
        // 弹幕
        initScreen();
		setInterval(function(){
			initScreen();
		}, 6000);
    })
    
	function initScreen(){
		var _top = 0;
		var arr = [];
		var spaceTime = 0;
		for (var i=0, len=$('#dShow').find('div').length; i<len; i++) {
			spaceTime = spaceTime + parseInt(Math.random()*1500 + 100);
			arr[i] = spaceTime;
		}
		$('#dShow').find('div').show().each(function(i){
			var _left = $(window).width() - $(this).width() + 300;
			var _height = $(window).height();
			var _index;
			
			_top = _top + 20;
			if (i % 4 == 0) {
				_top = 0;
			}
			$(this).css({left: _left, top: _top, color: getColor(i)});
			
			setTimeout(function(){
				$('#dShow').find('div').eq(i).animate({left: '-' + _left + 'px'}, 10000, function(){
					$(this).css('left', _left);
				});							
			}, arr[i]);
		});
		
	}
    function getColor(index){
        var arrColor = ['#f00', '#0f0', '#00f', '#000', '#8a2be2'];
        switch(index){
            case 5:
                index = 0;
                break;
            case 6:
                index = 1;
                break;
            case 7:
                index = 2;
                break;
            case 8:
                index = 3;
                break;
            case 9:
                index = 4;
                break;
        }
        return arrColor[index];
    }
</script>