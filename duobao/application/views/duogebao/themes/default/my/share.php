<div class="viewport v-share v-my-share">
    <!-- share start -->
    <div class="share my-share">
        <div class="my-share-hd">
            <div class="user-avatar us-pic-bg"><img src="<?=$user['head_img']?>" alt=""></div>
            <span class="user-name"><?=$user['nick_name']?>的夺宝晒单</span>
        </div>
        <div class="my-share-con">
            <?php if (empty($share_list['list'])) { ?>
                <div class="share-empty">
                    <i class="icon-camera-1"></i>
                    <h3>暂无晒单记录</h3>
                    <p>夺宝得大奖后就能晒单啦！</p>
                    <a href="<?=gen_uri('/home/index')?>"><button><img src="<?=$resource_url?>images/btn/sdno-ljcy.png" alt=""/></button></a>
                </div>
            <?php
                } else {
                if($have_share_count > 0){
            ?>
                <div class="tips-my-share">
                    <a href="<?=gen_uri('/my/active',array('cls'=>'winner'))?>"><i class="icon-bell"></i>你还有<?=$have_share_count?>个奖品未晒单，快去晒单得积分吧！<em></em></a>
                </div>
            <?php
                }
                $i = 0;
                foreach ($share_list['list'] as $item) {
                $period_code = period_code_encode($item['act_id'], $item['period']);
            ?>
                <div class="my-share-item">
                    <div class="my-share-item-date">2016-1-16</div>
                    <div class="line-track"></div>
                    <div class="grid-1 share-item">
                        <div class="pd10">
                            <div class="share-content">
                                <div class="author">
                                    <p><?=$user['nick_name']?></p>
                                    <p class="clearfix"><em class="fl"><?=$item['area']?> IP：<?=$item['ip']?></em><span class="time fr"><?=$item['share_time'] ? date('Y-m-d H:i:s', $item['share_time']) : ''?></span></p>
                                </div>
                                <div class="share-prize">
                                    <div class="pic">
                                        <img src="<?=$item['goods_img']?>" width="42" height="42" alt="">
                                    </div>
                                    <div class="name">
                                        <p><?=$item['goods_name']?></p>
                                        <p>期号：<?=$period_code?></p>
                                    </div>
                                </div>
                                <div class="abbr">
                                    <a href="<?=gen_uri('/share/detail', array('id'=>$item['share_id']))?>">
                                        <p class="redbg-1"><?=$item['content']?></p>
                                        <?php if($item['imgs']) { ?>
                                            <ul class="clearfix">
                                                <?php foreach($item['imgs'] as $img) { ?>
                                                    <li><img src="<?=get_img_resize_url($img, Lib_Constants::SHARE_IMG_SMALL, Lib_Constants::SHARE_IMG_SMALL)?>" width="64" height="64" alt=""></li>
                                                <?php } ?>
                                            </ul>
                                        <?php } ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="actions us-pic-bg">
                            <div class="actions-bg clearfix">
                                <ul class="clearfix">
                                    <li class="like"><i class="icon-like"></i><strong><?=$item['like_count']?></strong>人已赞</li>
                                    <li class="views"><strong><?=$item['view_count']?></strong>人已查看</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                    $i++;
                }
            ?>
        </div>
        <?php
        }
        ?>
    </div>

    <?php if (empty($share_list['list'])) { ?>
        <div class="grid hot mt-10" id="swiper">
            <ul class="swiper-wrapper clearfix">
                <?php
                if(!empty($active_list))
                {
                    foreach($active_list as $v)
                    {
                        ?>
                        <li class="swiper-slide">
                            <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($v['iActId'],$v['iPeroid'])))?>">
                                <div class="goods-pic">
                                    <img src="<?=get_img_resize_url($v['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt="">
                                    <label class="tag-hot">热门</label>
                                </div>
                                <div class="goods-name"><?=$v['sGoodsName']?></div>
                            </a>
                        </li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
    <?php } ?>
</div>
<!-- hot -->

<!-- share end -->
<script>
    $(function(){
        // 热门
        var swiper = new Swiper('#swiper', {
            slidesPerView: 3,
            spaceBetween: 10,
            slidesOffsetBefore: 10,
            freeMode: true
        });
    })
</script>