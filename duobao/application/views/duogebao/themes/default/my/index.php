<div class="viewport v-personal-center">
    <div class="personal-center">
        <!-- personal head -->
        <div class="personal-head">
            <div class="my-img"><img src="<?=$user['head_img']?>" width="60" height="60" alt="" onerror="javascript:this.src='<?=$resource_url?>images/user_avatar_1.jpg';"></div>
            <p class="user-nick"><?=$user['nick_name']?></p>
            <p class="user-id">ID：<?=$user['uin']?></p>
            <div class="personal-head-list">
                <ul class="clearfix">
                    <li>
                        <em><?=intval($act_bag_count)?></em>
                        <p>可发放福袋</p>
                        <a href="<?=gen_uri('/luckybag/records')?>">福袋记录></a>
                    </li>
                    <li>
                        <em><?=isset($user_ext['coupon']) ? intval($user_ext['coupon']) : 0 ?></em>
                        <p>可用券</p>
                        <a href="<?=gen_uri('/luckybag/coupon')?>">券记录></a>
                    </li>
                    <li>
                        <em><?=isset($user_ext['score']) ? intval($user_ext['score']) : 0 ?></em>
                        <p>积分</p>
                        <a href="<?=gen_uri('/my/score')?>">积分记录></a>
                    </li>
                </ul>
            </div>
            <div class="sign us-pic-bg user-sign">
                <a href="javascript:" class="btn-sign"><?=!empty($user_ext['sign_time']) && is_today($user_ext['sign_time']) ? '已签到' : '签到' ?><i class="icon-pencil"></i></a>
            </div>
        </div>

        <!-- menu list -->
        <div class="grid-1 menu-list  clearfix">
            <a href="<?=gen_uri('/pay/pull',array(),'payment')?>"><i class="icon-menu icon-gold-color"></i>发福袋</a>
            <a href="<?=gen_uri('/pay/buy_coupon',array(),'payment')?>"><i class="icon-menu icon-ticket-color"></i>购买券</a>
            <a href="<?=gen_uri('/score/mall')?>"><i class="icon-menu icon-store-color"></i>积分商城</a>
            <a href="<?=gen_uri('/my/collect')?>"><i class="icon-menu icon-star-color"></i>我的收藏</a>
        </div>

        <!-- my indiana record -->
        <div class="grid my-indiana-record mt-10">
            <div class="grid-1 my-indiana-record-hd clearfix">
                <h3 class="fl"><i class="icon-indiana-record"></i>我的记录</h3>
                <a href="<?=gen_uri('/my/active')?>" class="record-all fr">全部记录</a>
            </div>
            <div class="my-indiana-record-con clearfix">
                <a href="<?=gen_uri('/my/active',array('cls'=>'going'))?>"><i class="icon-india-record icon-record-clock"></i>进行中</a>
                <a href="<?=gen_uri('/my/active',array('cls'=>'opened'))?>"><i class="icon-india-record icon-record-alarm"></i>已揭晓</a>
                <a href="<?=gen_uri('/my/active',array('cls'=>'winner'))?>"><i class="icon-india-record icon-record-cup"></i>已中奖</a>
                <a href="<?=gen_uri('/my/active',array('cls'=>'exchange'))?>"><i class="icon-india-record icon-record-gift"></i>奖品兑换</a>
            </div>
        </div>

        <div class="grid-1 mt-10">
<!--            <div class="personal-item"><a href="--><?//=gen_uri('/active_custom/index')?><!--"><i class="icon-per-item icon-diamond-color"></i>我的私人定制</a></div>-->
            <div class="personal-item"><a href="<?=gen_uri('/my/share')?>"><i class="icon-per-item icon-camera-color"></i>我的晒单</a></div>
            <div class="personal-item"><a href="<?=gen_uri('/my/address')?>"><i class="icon-per-item icon-addr-color"></i>收货地址</a></div>
<!--            <div class="personal-item"><a href="--><?//=gen_uri('/message/index')?><!--"><i class="icon-per-item icon-msg-color"></i>信息中心<span class="icon-email"><em>--><?//=$msg_count?><!--</em></span></a></div>-->
<!--            <div class="personal-item"><a href="#"><i class="icon-per-item icon-glass-color"></i>个人信息</a></div>-->
        </div>

        <div class="grid-1 mt-10">
<!--            <div class="personal-item"><a href="#"><i class="icon-per-item icon-set-color"></i>设置<span class="set-tips">手机邮箱绑定、保密</span></a></div>-->
            <div class="personal-item"><a href="<?=gen_uri('/help/index')?>"><i class="icon-per-item icon-help-color"></i>帮助中心</a></div>
        </div>

    </div>
</div>

<script>
    $(function(){
        DUOBAO.url.sign = '<?=gen_uri('/sign/add')?>';
        //签到
        DUOBAO.sign('.user-sign');
    })
</script>