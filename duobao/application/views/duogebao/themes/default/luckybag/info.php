<link rel="stylesheet" href="<?=$luckybag_url?>js/swiper/swiper.min.css">
<div class="viewport v-open-bag">
    <!-- open bag start -->
    <div class="open-bag">
        <div class="banner-open-bag">
            <?php if($bag_info['iIsDone'] == 1 || $bag_info['iUsed'] >= $bag_info['iCoupon'] || $bag_info['iUsedPerson'] >= $bag_info['iPerson']){ ?>
                <img src="<?=$luckybag_url?>images/bag_2.jpg" width="100%" alt="" />
            <?php }elseif($bag_info['iIsTimeOut'] == 1 || $bag_info['iEndTime'] < time()){ ?>
                <img src="<?=$luckybag_url?>images/bag_2.jpg" width="100%" alt="" />
            <?php }elseif($is_log == 'true' && $is_my == 'true'){ ?>
                <img src="<?=$luckybag_url?>images/bag_3.jpg" width="100%" alt="" />
            <?php }elseif($is_log == 'true'){ ?>
                <img src="<?=$luckybag_url?>images/bag_2.jpg" width="100%" alt="" />
            <?php }else{ ?>
                <img src="<?=$luckybag_url?>images/bag_1.jpg" width="100%" alt="" />
            <?php } ?>
            <div class="banner">
                好友一掷千金，给你发了价值<br>千元的新年福袋，快拆开看看！
            </div>

            <?php if($bag_info['iIsTimeOut'] == 1 || $bag_info['iEndTime'] < time()){ ?>
                <div class="opera-open" style="top: 46%; margin-left: -65px;">
                    <p><strong id="issuer"><?=$bag_user['nick_name']?></strong>送你一份千元豪礼！</p>
                    <label class="expired">已过期</label>
                </div>
                <p class="tips-countdown" style="bottom:62px;">*福袋中未被领取的夺宝券已退还至您的账户</p>
                <div class="btn-groups btn-groups-1">
                    <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-group btn-group-2">朕要再发个福袋</a>
                </div>
            <?php }elseif($bag_info['iIsDone'] == 1 || $bag_info['iUsed'] >= $bag_info['iCoupon'] || $bag_info['iUsedPerson'] >= $bag_info['iPerson']){ ?>
                <div class="opera-open opera-open-1">
                    <p><strong id="issuer"><?=$bag_user['nick_name']?></strong>送你一份千元豪礼！</p>
                    <label class="receive-all">全部领完</label>
                </div>
                <div class="btn-groups btn-groups-1">
                    <?php if($is_my == 'true'){ ?>
                        <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-group btn-group-2">朕要再发个福袋</a>
                    <?php }else{ ?>
                        <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-group btn-group-1">朕也要发福袋</a>
                    <?php } ?>
                </div>
            <?php }elseif($is_log == 'true'){ ?>
                <?php if($is_my == 'true'){ ?>
                    <div class="opera-open opera-open-1">
                        <p><strong id="issuer"><?=$bag_user['nick_name']?></strong>送你一份千元豪礼！</p>
                        <label class="receive-in">领取中</label>
                    </div>
                    <div class="count-down-1">
                        <ul class="count clearfix" id="fnTimeCountDown">
                            <li class="time t-hour hour" id="1_hour">00</li>
                            <li class="separator">:</li>
                            <li class="time t-min mini" id="1_mini">00</li>
                            <li class="separator">:</li>
                            <li class="time t-sec sec" id="1_sec">00</li>
                            <li class="separator">:</li>
                            <li class="time t-millsec hm" id="1_hm">000</li>
                        </ul>
                        <p class="tips-countdown">*福袋有效期48小时，夺宝券如未被领取将退还至您的账户</p>
                    </div>
                    <div class="btn-groups"  style="bottom:10px;">
                        <a href="javascript:;" class="btn-group btn-group-1" id="shareBtn">继续发此福袋</a>
                        <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-group btn-group-2">朕要再发个福袋</a>
                    </div>
                <?php }else{ ?>
                    <div class="opera-open opera-open-1">
                        <p><strong id="issuer"><?=$bag_user['nick_name']?></strong>送你一份千元豪礼！</p>
                        <label class="brought-up">已领过</label>
                    </div>
                    <div class="btn-groups">
                        <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-group btn-group-1">朕也要发福袋</a>
                        <a href="<?=gen_uri('/active/lists')?>" class="btn-group btn-group-2">带朕去选豪礼</a>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <div class="opera-open">
                    <p><strong id="issuer"><?=$bag_user['nick_name']?></strong><?=$bag_info['sWish']?></p>
                    <a href="#" class="btn-open-bag" id="btnOpenBag">开福袋</a>
                </div>
            <?php } ?>
        </div>
        <!-- win info -->
        <div class="win-info clearfix" id="scrollInfo">
            <i class="icon-horn"></i>
            <ul>
                <?php if(!empty($active_msg)){ ?>
                    <?php foreach($active_msg as $v){ ?>
                        <li><a href="#">恭喜<b class="winner"><?=$v['sWinnerNickname']?></b><?=tran_time($v['iLotTime'])?>获得<strong class="prize"><?=$v['sGoodsName']?></strong></a></li>
                    <?php } ?>
                <?php }else{ ?>
                    <li>还没小伙伴获得奖品~赶紧来参与吧~</li>
                <?php } ?>
            </ul>
        </div>
        <!-- receive list -->
        <div class="receive">
            <div class="receive-hd clearfix">
                <?php if($bag_info['iType'] == Lib_Constants::BAG_TYPE_NORMAL){ ?>
                    <h3 class="already-receive fl">共<b id="totalNum"><?=$bag_info['iPerson']?></b>个福袋，已领取<strong id="receivedNum"><?=$bag_info['iUsedPerson']?></strong>/<b id="totalNum"><?=$bag_info['iPerson']?></b>个</h3>
                <?php }else{ ?>
                    <h3 class="already-receive fl">共<b id="totalNum"><?=$bag_info['iPerson']?></b>个福袋，已领取<strong id="receivedNum"><?=$bag_info['iUsedPerson']?></strong>/<b id="totalNum"><?=$bag_info['iPerson']?></b>个</h3>
                <?php } ?>
            </div>
            <div class="receive-con">
                <ul>
                    <?php if($log_list['count']>0){  ?>
                        <?php foreach($log_list['list'] as $list){  ?>
                            <li>
                                <em><?=$list['toUser']['nick_name']?></em>
                                <span>领到<?=$list['iNum']?>张夺宝券</span>
                                <b>恭喜发财</b>
                            </li>
                        <?php } ?>
                    <?php }else{ ?>
                        <li>暂无记录</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <!-- goods recommend -->
        <?php if(!empty($recommend)) {   ?>
            <div class="goods-recommend">
                <div class="g-recommend-hd clearfix">
                    <h3 class="fl">领福袋，兑豪礼  Lets go~</h3>
                    <a href="<?=gen_uri('/active/lists')?>" class="view-more fr">查看更多豪礼</a>
                </div>
                <div class="g-recommend-con swiper-container" id="swiper-1">
                    <ul class="swiper-wrapper clearfix">
                        <?php
                        if(!empty($recommend)) {
                        foreach($recommend as $rec){  ?>
                            <li class="swiper-slide">
                                <a href="<?=gen_uri('/active/detail', array('id'=>period_code_encode($rec['iActId'], $rec['iPeroid'])))?>">
                                    <img src="<?=$rec['sImg']?>" width="100%" height="" alt="<?=$rec['sGoodsName']?>" />
                                    <h3 class="goods-name"><?=cn_substr($rec['sGoodsName'], 5, '...')?></h3>
                                </a>
                            </li>
                        <?php }} ?>
                    </ul>
                </div>
            </div>
        <?php }  ?>

        <!-- activity intro -->
        <div class="acti-intro">
            <div class="acti-intro-hd clearfix">
                <h3>福袋活动介绍图</h3>
                <i class="arrow-down"></i>
            </div>
            <div class="acti-intro-con">
                <div class="h-acti-intro-con">
                    <ul class="clearfix">
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_2.png" width="100%" alt="">
                            <h3>1张夺宝券</h3>
                        </li>
                        <li class="sepera-equal">
                            <img src="<?=$luckybag_url?>images/acti_5.png" width="100%" alt="">
                        </li>
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_3.png" width="100%" alt="">
                            <h3>1个夺宝码</h3>
                        </li>
                        <li class="sepera-equal">
                            <img src="<?=$luckybag_url?>images/acti_5.png" width="100%" alt="">
                        </li>
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_4.png" width="100%" alt="">
                            <h3>1份千元豪礼</h3>
                        </li>
                    </ul>
                </div>

                <div class="process-con mt-15">
                    <ul class="clearfix">
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_6.png" width="100%" alt="">
                            <h3>装福袋</h3>
                        </li>
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_7.png" width="100%" alt="">
                            <h3>发福袋</h3>
                        </li>
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_8.png" width="100%" alt="">
                            <h3>领福袋</h3>
                        </li>
                        <li>
                            <img src="<?=$luckybag_url?>images/acti_9.png" width="100%" alt="">
                            <h3>夺宝领豪礼</h3>
                        </li>
                    </ul>
                </div>

                <p><i>1.</i>每个用户可购买夺宝券作为礼物赠予好友，用户在赠送好友夺宝券时可个性化选择普通福袋和拼手气福袋（操作类似于红包），自主编辑赠予的人数、夺宝券张数，并填写祝福语；</p>
                <p><i>2.</i>获得夺宝券的用户可在夺宝奇兵活动中购买时抵扣现金，例如： 1元夺宝券可抵扣1元。也可用现有的夺宝券直接兑换奖品；</p>
                <p><i>3.</i>48小时之内，已发送出去的福袋中如有夺宝券未被及时领取，可在【个人中心】中找到【可发放福袋】入口，选择继续发放；48小时之后，已发送出去的福袋中如有夺宝券未被及时领取，则系统将会把未被领取的夺宝券退回至您的账户中 ；</p>
                <p><i>4.</i>夺宝券从购买到账之日起永久有效；</p>
                <p><i>5.</i>1张夺宝券可兑换一个夺宝码， 更多夺宝及相关计算规则请详见夺宝奇兵活动页面；</p>
                <p><i>6.</i>本活动最终解释权归微团购所有。</p>
            </div>
        </div>
    </div>
    <!-- open bag end -->
</div>

<!-- 开福袋提示层 start -->
<div class="mask"></div>
<div class="popup popup-open-bag">
    <div class="popup-tit">大王真是手气爆棚！<br>领到<span class="showCoupon">0</span>张夺宝券</div>
    <div class="popup-con">
        <p class="popup-desc">快去选个千元豪礼！</p>
        <div class="popup-btns">
            <a href="<?=gen_uri('/active/lists')?>" class="btn-popup btn-popup-1">带朕去选豪礼</a>
            <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-popup btn-popup-2 mt-10">朕也要发福袋</a>
        </div>
        <!--<p class="popup-tips">*福袋中的夺宝券如未被领取, 将于48小时内退还至您的账户</p>-->
    </div>
    <a href="javascript:;" class="btn-popup-close" id="btnPopupClose">关闭</a>
</div>
<!-- 开福袋提示层 end -->

<!-- 福袋已领取提示层 start -->
<div class="popup popup-bag-received">
    <div class="popup-tit">大王已领过此福袋了</div>
    <div class="popup-con">
        <div class="popup-btns">
            <a href="#" class="btn-popup btn-popup-1">带朕去选豪礼</a>
            <a href="#" class="btn-popup btn-popup-2 mt-10">朕也要发福袋</a>
        </div>
        <!--<p class="popup-tips">*福袋中的夺宝券如未被领取, 将于48小时内退还至您的账户</p>-->
    </div>
    <a href="javascript:;" class="btn-popup-close" id="btnPopupClose1">关闭</a>
</div>
<!-- 福袋已领取提示层 end -->

<!--分享层-->
<div class="popup popup-share" style="display: none;"></div>
<!--分享层-->
<script>
     DUOBAO.menuShow = '<?=($is_my == 'true' ? 1 : 0)?>';

    $(function(){
        // 分享引导提示层
        var sharePop = function(){
            $('.mask, .popup-share').show();
            $('.mask, .popup-share').on('click', function(){
                $('.mask, .popup-share').hide();
            });
        }
        $('#shareBtn').click(function(){
            sharePop();
        })

        //刚进来显示
        <?php if($is_my == 'true' && $bag_info['iIsDone'] != 1 && $bag_info['iUsed'] < $bag_info['iCoupon'] && !$isload){ ?>
        sharePop();
        <?php } ?>

        // 折叠菜单
        toggleMenu('.acti-intro i.arrow-down');

        // 倒计时
        <?php if($bag_info['iEndTime'] > time()){ ?>
        var leftTime = new Date('<?=(date('Y/m/d H:i:s',$bag_info['iEndTime']))?>').getTime() - new Date('<?=(date('Y/m/d H:i:s',time()))?>').getTime();
        var fnCountDown = function (str) {
            var timeDistance = str;
            var hm = Math.floor(timeDistance%1000);
            var sec = Math.floor(timeDistance/1000%60);
            var min = Math.floor(timeDistance/1000/60%60);
            var hour = Math.floor(timeDistance/1000/60/60);

            $('#1_hour').html(toDouble(hour));
            $('#1_mini').html(toDouble(min));
            $('#1_sec').html(toDouble(sec));
            $('#1_hm').html(haomiao(hm));
            leftTime = leftTime - 50;
        }

        var toDouble = function(num){
            return num < 10 ? '0'+num : num;
        };

        var haomiao = function(num) {
            if (num < 10) return '00' + num.toString();
            if (num < 100) return '0' + num.toString();
            return num.toString();
        };

        setInterval(function(){
            fnCountDown(leftTime);
        }, 50);
        <?php } ?>

        // 推荐商品
        var swiper1 = new Swiper('#swiper-1', {
            slidesPerView: 3.5,
            spaceBetween: 10,
            freeMode: true
        });

        // 中奖信息
        setInterval(function(){
            scrollInfo1('#scrollInfo');
        }, 3000);

        // 开福袋提示层
        var isClick = false;
        $('#btnOpenBag').on('click', function(){
            if(isClick) return;
            isClick = true;
            DUOBAO._post('<?=gen_uri('/luckybag/open')?>',{uin:'<?=$bag_user['uin']?>','bagId':'<?=$bag_info['iBagId']?>',sign:'<?=$sign?>'},function(rs){
                try{
                    if(typeof rs === 'string') rs = $.parseJSON(rs);
                    if(rs.retCode == 0){
                        $('.showCoupon').html(rs.retData);
                        $('.mask, .popup-open-bag').show();
                    }else{
                        layer.msg(typeof rs.retMsg != 'undefined' && rs.retMsg != '' ? rs.retMsg : '领取失败,请稍侯再领！',function(){
                            if(rs.retMsg == '您已经领取过了!'){
                                location.reload();
                            }
                        });
                    }
                }catch (e){
                    layer.msg('服务异常！');
                }
                isClick = false;
            })
            return false;
        });
        $('.btn-popup-close').on('click', function(){
            $('.mask, .popup').hide();
            location.reload();
        });
    })
</script>