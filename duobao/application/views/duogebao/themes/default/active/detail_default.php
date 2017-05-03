<div class="viewport v-detail">
    <div class="detail">
        <!-- thumb -->
        <div class="grid thumb-prize swiper-container" id="goodsThumb">
            <?php if (empty($detail['sImgExt'])) {?>
                <ul class="swiper-wrapper">
                    <li class="swiper-slide"><img src="<?=$detail['sImg']?>" width="270" height="270" alt=""></li>
                </ul>
                <div class="swiper-pagination"></div>
            <?php } else {?>
                <ul class="swiper-wrapper">
                    <li class="swiper-slide"><img src="<?=$detail['sImg']?>" width="270" height="270" alt=""></li>
                    <?php foreach (json_decode($detail['sImgExt']) as $v) {?>
                        <li class="swiper-slide"><img src="<?=$v?>" width="270" height="270" alt=""></li>
                    <?php }?>
                </ul>
                <div class="swiper-pagination"></div>
            <?php }?>
            <?php if (Lib_Constants::ACTIVE_TYPE_CUSTOM==$detail['iActType']) {?>
                <label class="corner corner-custom corner-detail"><?=$detail['sActName']?></label>
            <?php }?>
        </div>

        <!-- prize info -->
        <div class="grid prize-info mt-10">
            <div class="detail-info-basic" data-lott-schedule="<?=$detail['iProcess']?>%">
                <div class="detail-prize-name"><?=$detail['sGoodsName']?></div>
                <div class="detail-prize-desc"><!-- 粉红色 特别版 包邮到家 -->单次用券：<?=$detail['iCodePrice']/Lib_Constants::COUPON_UNIT_PRICE?></div>
                <div class="detail-prize-no">期号：<?=period_code_encode($detail['iActId'],$detail['iPeroid'])?></div>
                <div class="progress-bar">
                    <span class="progress-bar-on"></span>
                </div>
                <div class="detail-prize-txt clearfix">
                    <span class="need-persons fl">总需人次：<?=$detail['iLotCount']?></span>
                    <span class="person-remain fr">还需人次<strong><?=($detail['iLotCount']-$detail['iSoldCount'])?></strong></span>
                </div>
                <?php
                    if(empty($my_code))
                    {
                ?>
                        <div class="parti-empty">您还没有参与本期活动,赶快参与,幸运之神就是你!</div>
                <?php
                    }
                    else
                    {
                ?>
                        <div class="indiana-code">
                            <div class="indiana-code-inner">
                                <h3>我的参与码（共参与<strong class="parti-num"><?=count($my_code)?></strong>人次）</h3>
                                <p><?=(empty($my_code) ? '暂无记录' : implode(',',$my_code))?></p>
                            </div>
                            <a href="javascript:;" class="btn-toggle btn-toggle-down">展开<i></i></a>
                        </div>
                <?php
                    }
                ?>
                <a href="<?=gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($detail['iActId'],$detail['iPeroid']),'id'=>'buyOut'),'payment')?>" class="btn-buyout">我要包尾</a>
                <em class="schedule sche-underway">进行<br>ING</em>
            </div>

            <div class="prize-info-item">
                <a href="<?=gen_uri('/active/goods_detail',array('goods_id'=>$detail['iGoodsId'],'act_id'=>$detail['iActId'],'peroid_str'=>$peroid_str))?>"><i class="icon-detail icon-d-graphic"></i>图文详情<i class="arrow-rgt"></i></a>
            </div>
            <div class="prize-info-item">
                <a href="<?=gen_uri('/active/active_past',array('peroid_str'=>$peroid_str))?>"><i class="icon-detail icon-d-announced"></i>往期揭晓<i class="arrow-rgt"></i></a>
            </div>
            <div class="prize-info-item">
                <a href="<?=gen_uri('/share/active_list',array('act_id'=>$detail['iActId']))?>"><i class="icon-detail icon-d-share"></i>幸运晒单<i class="arrow-rgt"></i></a>
            </div>
        </div>
        <?php
            if(empty($summary))
            {
        ?>
                <!-- participate record -->
                <div class="parti-record-empty">
                    <i class="icon-e-search"></i>
                    <p>快来参与吧~</p>
                </div>
        <?php
            }
            else
            {
        ?>
                <div class="grid parti-record mt-10">
                    <div class="parti-record-hd">
                        <h3>本期参与记录<!-- <strong>（2016-1-16）</strong> --></h3>
                    </div>
                    <div class="parti-record-con">
                        <dl id="listMore" data-url="<?=gen_uri('/active/ajax_summary_list',array('id'=>period_code_encode($detail['iActId'],$detail['iPeroid'])))?>" data-load="<?=(!empty($log_list['page_count']) && $log_list['page_index']>=$log_list['page_count']) ? 'false' : 'true'?>">
                            <?php foreach($summary as $li){ ?>
                                <dd>
                                    <div class="user-info">
                                        <img class="user-avatar" src="<?=$li['sHeadImg']?>" width="52" height="52" alt="">
                                            <span class="parti-cell-1">
                                                <p class="user-name"><?=$li['sNickName']?></p>
                                                <p class="ip">IP：<?=(!empty($li['iIP']) && is_numeric($li['iIP'])?long2ip($li['iIP']):$li['iIP'])?></p>
                                            </span>
                                            <span class="parti-cell-2">
                                                <p class="parti-num-1">参与<strong><?=$li['iLotCount']?></strong>人次</p>
                                                <p class="parti-time"><?=$li['iCreateTime']?></p>
                                            </span>
                                    </div>
                                </dd>
                            <?php } ?>
                        </dl>

                    </div>
                </div>
        <?php
            }
        ?>

        <!-- bottom -->
        <?php $this->widget('cart_bottom', array('peroid_str'=>$peroid_str))?>
    </div>
</div>
<?php $this->widget('right_icon', array('act_id'=>$detail['iActId'])) ?>

<script>
    $(function(){

        // 商品轮播
        var swiper = new Swiper('#goodsThumb', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            autoplay: 5000
        });

        $('.progress-bar').each(function () {
            var _percent = $(this).parent().attr('data-lott-schedule');
            _percent = _percent.substring(0, _percent.length - 1);
            $(this).find('span.progress-bar-on').animate({'width': _percent + '%'}, 600);
        });

        DUOBAO.codeShowHide();
        // 夺宝码展开折叠
        $('.btn-toggle').on('click', function(){
            DUOBAO.codeToggle(this);
        });

        //分页
        DUOBAO.loadMore('#listMore',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
                $.each(rs.retData.list,function(i,li){
                    $('#listMore').append(Temp(li));
                })
            }
        });
        function Temp(li){
            var html = [];

            html.push('<dd>');
            html.push('<div class="user-info">');
            html.push('<img class="user-avatar" src="'+li.sHeadImg+'" width="52" height="52" alt="">');
            html.push('<span class="parti-cell-1">');
            html.push('<p class="user-name">'+li.sNickName+'</p>');
            html.push('<p class="ip">IP：'+li.iIP+'</p>');
            html.push('</span>');
            html.push('<span class="parti-cell-2">');
            html.push('<p class="parti-num-1">参与<strong>'+li.iLotCount+'</strong>人次</p>');
            html.push('<p class="parti-time">'+li.iCreateTime+'</p>');
            html.push('</span>');
            html.push('</div>');
            html.push('</dd>');
            return html.join('');
        }
    })
</script>
<script>
    $(function () {
        DUOBAO.toTop();
    })
</script>
<?php if($share){ ?>
    <div class="mask mask-share" id="maskCustomDetail" style="display: block; z-index: 101;"></div>
    <div class="pop-share" id="customShare">
        <img src="<?=$resource_url?>images/popWin/share.png" width="100%" alt="">
    </div>
    <script>
        $('#maskCustomDetail, #customShare').on('click', function(){
            $('#maskCustomDetail, #customShare').hide();
        });
        $('.btn-custom-share').on('click', function(){
            $('#maskCustomDetail, #customShare').show();
        });
        $('#maskCustomDetail, #customShare').show();
    </script>
<?php } ?>