<div class="viewport v-home">
<div class="home">
<div class="mask" id="homeMask">
</div>
<!-- topbar -->
<div class="topbar">
    <div class="category" id="category">
        <a href="javascript:;">
            <i class="icon-category">
            </i>
            夺宝分类
        </a>
        <div class="cate-sub">
            <ul>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-hot">
                        </i>
                        热门精选
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-phone">
                        </i>
                        手机数码
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-pc">
                        </i>
                        电脑平板
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-electric">
                        </i>
                        家用电器
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-bag">
                        </i>
                        品牌箱包
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-ornament">
                        </i>
                        钟表饰品
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-others">
                        </i>
                        其他商品
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-category-sub icon-view-all">
                        </i>
                        查看全部
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="search">
        <p>
            <input type="button" class="btn-search" />
            <input type="text" class="keyword" placeholder="请输入搜索关键词">
        </p>
    </div>
</div>
<!-- banner -->
<div class="swiper-container banner" id="slider">
    <ul class="swiper-wrapper">
        <li class="swiper-slide">
            <a href="#">
                <img src="<?=$resource_url?>images/banner.jpg" width="100%" alt="">
            </a>
        </li>
        <li class="swiper-slide">
            <a href="#">
                <img src="<?=$resource_url?>images/banner.jpg" width="100%" alt="">
            </a>
        </li>
        <li class="swiper-slide">
            <a href="#">
                <img src="<?=$resource_url?>images/banner.jpg" width="100%" alt="">
            </a>
        </li>
        <li class="swiper-slide">
            <a href="#">
                <img src="<?=$resource_url?>images/banner.jpg" width="100%" alt="">
            </a>
        </li>
    </ul>
    <!-- Add Pagination -->
    <div class="swiper-pagination">
    </div>
</div>
<!-- scroll info -->
<div class="scroll-info" id="scrollInfo">
    <em class="icon-horn">
    </em>
    <ul>
        <li>
            <a href="#">
                恭喜
                <strong>
                    天上的月亮
                </strong>
                3分钟前获得
                <b>
                    爱玛快速折叠自行车
                </b>
            </a>
        </li>
        <li>
            <a href="#">
                恭喜
                <strong>
                    天上的月亮
                </strong>
                3分钟前获得
                <b>
                    爱玛快速折叠自行车
                </b>
            </a>
        </li>
        <li>
            <a href="#">
                恭喜
                <strong>
                    天上的月亮
                </strong>
                3分钟前获得
                <b>
                    爱玛快速折叠自行车
                </b>
            </a>
        </li>
    </ul>
</div>
<!-- quick entry -->
<div class="quick-entry-nav">
    <a href="#">
        <i class="icon-entry-nav icon-sign">
        </i>
        签到
    </a>
    <a href="#">
        <i class="icon-entry-nav icon-lucky-bag">
        </i>
        发福袋
    </a>
    <a href="buy_stamps.html">
        <i class="icon-entry-nav icon-indiana">
        </i>
        购买夺宝券
    </a>
    <a href="personal_center.html">
        <i class="icon-entry-nav icon-personal-center">
        </i>
        个人中心
    </a>
</div>
<!-- new reveal -->
<div class="tab index-newReveal mt-10">
    <div class="tab-tit" id="tab1">
        <a href="javascript:;" class="tab-active">
            已揭晓
        </a>
        <a href="javascript:;">
            即将揭晓
        </a>
        <a href="javascript:;">
            更多&gt;&gt;
        </a>
    </div>
    <div class="tab-con" id="tabCon1">
        <!-- 已揭晓 -->
        <div class="swiper-container already-revealed" id="swiper-1" style="display: block;">
            <ul class="swiper-wrapper clearfix">
                <li class="swiper-slide">
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_1.jpg" width="100%" alt="">
                    </div>
                    <div class="status status-success">
                        即将揭晓
                    </div>
                    <div class="prize-name">
                        多功能榨汁机
                    </div>
                    <div class="countDown">
                        <label class="hour" id="min">
                            01
                        </label>
                        :
                        <label class="min" id="sec">
                            28
                        </label>
                        :
                        <label class="sec" id="ms">
                            216
                        </label>
                    </div>
                </li>
                <li class="swiper-slide">
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_2.jpg" width="100%" alt="">
                    </div>
                    <div class="status status-danger">
                        已揭晓
                    </div>
                    <div class="prize-name">
                        多功能榨汁机
                    </div>
                    <div class="winners">
                        中奖者：金三胖嘟嘟
                    </div>
                </li>
                <li class="swiper-slide">
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_2.jpg" width="100%" alt="">
                    </div>
                    <div class="status status-danger">
                        已揭晓
                    </div>
                    <div class="prize-name">
                        多功能榨汁机
                    </div>
                    <div class="winners">
                        中奖者：金三胖嘟嘟
                    </div>
                </li>
                <li class="swiper-slide">
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_2.jpg" width="100%" alt="">
                    </div>
                    <div class="status status-danger">
                        已揭晓
                    </div>
                    <div class="prize-name">
                        多功能榨汁机
                    </div>
                    <div class="winners">
                        中奖者：金三胖嘟嘟
                    </div>
                </li>
                <li class="swiper-slide">
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_2.jpg" width="100%" alt="">
                    </div>
                    <div class="status status-danger">
                        已揭晓
                    </div>
                    <div class="prize-name">
                        多功能榨汁机
                    </div>
                    <div class="winners">
                        中奖者：金三胖嘟嘟
                    </div>
                </li>
            </ul>
        </div>
        <!-- 即将揭晓 -->
        <div>
            即将揭晓
        </div>
        <!-- 更多 -->
        <div>
            更多
        </div>
    </div>
</div>
<!-- snap up -->
<div class="snap-up mt-10">
    <div class="snap-up-hd clearfix">
        <h3 class="fl">
            <i class="icon-alarm">
            </i>
            最后疯抢
        </h3>
        <a href="#" class="view-all fr">
            查看全部
        </a>
    </div>
    <div class="swiper-container snap-up-con" id="swiper-2">
        <ul class="swiper-wrapper clearfix">
            <li class="swiper-slide">
                <div class="prize-pic">
                    <img src="<?=$resource_url?>images/product_3.jpg" width="100%" alt="">
                </div>
                <div class="prize-name">
                    多功能榨汁机
                </div>
                <div class="lott-schedule" data-lott-schedule="50%">
                    <div class="progress-bar">
                                <span class="progress-bar-on">
                                </span>
                    </div>
                    <em>
                        50%
                    </em>
                </div>
                <div class="prize-bott">
                    <a href="#" class="btn-parti">
                        立即参与
                    </a>
                    <a href="#" class="add-to-cart">
                        添加到购物车
                                <span class="icon-plus">
                                    +
                                </span>
                    </a>
                </div>
            </li>
            <li class="swiper-slide">
                <div class="prize-pic">
                    <img src="<?=$resource_url?>images/product_4.jpg" width="100%" alt="">
                </div>
                <div class="prize-name">
                    多功能榨汁机
                </div>
                <div class="lott-schedule" data-lott-schedule="50%">
                    <div class="progress-bar">
                                <span class="progress-bar-on">
                                </span>
                    </div>
                    <em>
                        50%
                    </em>
                </div>
                <div class="prize-bott">
                    <a href="#" class="btn-parti btn-parti-1">
                        立即参与
                    </a>
                    <a href="#" class="add-to-cart">
                        添加到购物车
                                <span class="icon-plus">
                                    +
                                </span>
                    </a>
                </div>
            </li>
            <li class="swiper-slide">
                <div class="prize-pic">
                    <img src="<?=$resource_url?>images/product_3.jpg" width="100%" alt="">
                </div>
                <div class="prize-name">
                    多功能榨汁机
                </div>
                <div class="lott-schedule" data-lott-schedule="50%">
                    <div class="progress-bar">
                                <span class="progress-bar-on">
                                </span>
                    </div>
                    <em>
                        50%
                    </em>
                </div>
                <div class="prize-bott">
                    <a href="#" class="btn-parti">
                        立即参与
                    </a>
                    <a href="#" class="add-to-cart">
                        添加到购物车
                                <span class="icon-plus">
                                    +
                                </span>
                    </a>
                </div>
            </li>
            <li class="swiper-slide">
                <div class="prize-pic">
                    <img src="<?=$resource_url?>images/product_4.jpg" width="100%" alt="">
                </div>
                <div class="prize-name">
                    多功能榨汁机
                </div>
                <div class="lott-schedule" data-lott-schedule="50%">
                    <div class="progress-bar">
                                <span class="progress-bar-on">
                                </span>
                    </div>
                    <em>
                        50%
                    </em>
                </div>
                <div class="prize-bott">
                    <a href="#" class="btn-parti btn-parti-1">
                        立即参与
                    </a>
                    <a href="#" class="add-to-cart">
                        添加到购物车
                                <span class="icon-plus">
                                    +
                                </span>
                    </a>
                </div>
            </li>
            <li class="swiper-slide">
                <div class="prize-pic">
                    <img src="<?=$resource_url?>images/product_3.jpg" width="100%" alt="">
                </div>
                <div class="prize-name">
                    多功能榨汁机
                </div>
                <div class="lott-schedule" data-lott-schedule="50%">
                    <div class="progress-bar">
                                <span class="progress-bar-on">
                                </span>
                    </div>
                    <em>
                        50%
                    </em>
                </div>
                <div class="prize-bott">
                    <a href="#" class="btn-parti">
                        立即参与
                    </a>
                    <a href="#" class="add-to-cart">
                        添加到购物车
                                <span class="icon-plus">
                                    +
                                </span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- indiana area -->
<div class="tab indiana-area mt-10">
    <div class="tab-tit" id="tab2">
        <a href="javascript:;" class="tab-active">
            一元专区
        </a>
        <a href="javascript:;">
            十元专区
        </a>
        <a href="javascript:;">
            苹果专区
        </a>
        <a href="javascript:;">
            最后疯抢
        </a>
        <a href="javascript:;">
            更多&gt;&gt;
        </a>
    </div>
    <div class="tab-con indiana-area-con" id="tabCon2">
        <!-- 一元专区 -->
        <div class="area-one" style="display: block;">
            <ul class="clearfix">
                <li>
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_5.jpg" width="100%" alt="">
                    </div>
                    <div class="prize-name">
                        美的 4升智能柴火饭电饭锅
                    </div>
                    <div class="prize-bott-1">
                        <div class="lott-schedule-1" data-lott-schedule="45%">
                            <p class="clearfix">
                                <em class="fl">
                                    开奖进度
                                </em>
                                        <span class="fr">
                                            45%
                                        </span>
                            </p>
                            <div class="progress-bar">
                                        <span class="progress-bar-on">
                                        </span>
                            </div>
                        </div>
                        <a href="#" class="add-to-cart-1">
                            加入夺宝车
                        </a>
                    </div>
                </li>
                <li>
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_6.jpg" width="100%" alt="">
                    </div>
                    <div class="prize-name">
                        美的 4升智能柴火饭电饭锅
                    </div>
                    <div class="prize-bott-1">
                        <div class="lott-schedule-1" data-lott-schedule="45%">
                            <p class="clearfix">
                                <em class="fl">
                                    开奖进度
                                </em>
                                        <span class="fr">
                                            45%
                                        </span>
                            </p>
                            <div class="progress-bar">
                                        <span class="progress-bar-on">
                                        </span>
                            </div>
                        </div>
                        <a href="#" class="add-to-cart-1">
                            加入夺宝车
                        </a>
                    </div>
                </li>
                <li>
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_7.jpg" width="100%" alt="">
                    </div>
                    <div class="prize-name">
                        美的 4升智能柴火饭电饭锅
                    </div>
                    <div class="prize-bott-1">
                        <div class="lott-schedule-1" data-lott-schedule="45%">
                            <p class="clearfix">
                                <em class="fl">
                                    开奖进度
                                </em>
                                        <span class="fr">
                                            45%
                                        </span>
                            </p>
                            <div class="progress-bar">
                                        <span class="progress-bar-on">
                                        </span>
                            </div>
                        </div>
                        <a href="#" class="add-to-cart-1">
                            加入夺宝车
                        </a>
                    </div>
                </li>
                <li>
                    <div class="prize-pic">
                        <img src="<?=$resource_url?>images/product_8.jpg" width="100%" alt="">
                    </div>
                    <div class="prize-name">
                        美的 4升智能柴火饭电饭锅
                    </div>
                    <div class="prize-bott-1">
                        <div class="lott-schedule-1" data-lott-schedule="45%">
                            <p class="clearfix">
                                <em class="fl">
                                    开奖进度
                                </em>
                                        <span class="fr">
                                            45%
                                        </span>
                            </p>
                            <div class="progress-bar">
                                        <span class="progress-bar-on">
                                        </span>
                            </div>
                        </div>
                        <a href="#" class="add-to-cart-1">
                            加入夺宝车
                        </a>
                    </div>
                </li>
            </ul>
        </div>
        <!-- 十元专区 -->
        <div class="area-ten">
            这里是十元区相关产品
        </div>
        <!-- 苹果专区 -->
        <div class="area-apple">
            这里是苹果专区相关产品
        </div>
        <!-- 最后疯抢 -->
        <div class="area-seckill">
            这里是最后疯抢相关产品
        </div>
        <!-- 更多 -->
        <div>
            更多
        </div>
    </div>
</div>
</div>
</div>