<div class="viewport v-detail">
    <div class="detail">
        <!-- thumb -->
        <div class="grid thumb-prize swiper-container" id="goodsThumb">
            <!--            <img src="--><?//=$detail['sImg']?><!--" width="100%" alt="">-->
            <?php if (! $detail['sImgExt']) {?>
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
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <?php if (Lib_Constants::ACTIVE_TYPE_CUSTOM==$detail['iActType']) {?>
                <label class="corner corner-custom corner-detail"><?=$detail['sActName']?></label>
            <?php }?>
        </div>

        <!-- prize info -->
        <div class="grid prize-info mt-10">
            <div class="detail-info-basic">
                <div class="detail-prize-name"><?=$detail['sGoodsName']?></div>
                <div class="detail-prize-desc">&nbsp;</div>
                <em class="schedule sche-reveal"></em>
            </div>
            <div class="about-revealed clearfix">
                <div class="fl">
                    <p class="detail-prize-no-1">期号：<?=period_code_encode($detail['iActId'],$detail['iPeroid'])?></p>
                    <div class="reveal-countDown">
                        <em>揭晓倒计时</em>
                        <div class="countDown" data-start-time="<?=date('Y/m/d H:i:s')?>" data-end-time="<?=date('Y/m/d H:i:s',$detail['iLotTime'])?>">
                            <label class="hour">00</label>:
                            <label class="min">00</label>:
                            <label class="sec">000</label>
                            <span class="ms"></span>
                        </div>
                        <!-- <ul>
                            <li class="min" id="min">04</li>
                            <li>:</li>
                            <li class="sec" id="sec">35</li>
                            <li>:</li>
                            <li class="ms" id="ms">620</li>
                        </ul> -->
                    </div>
                </div>
                <a href="<?=gen_uri('/active/calc_detail',array('peroid_str'=>$peroid_str))?>" class="btn btn-default btn-calculate-detail fr"><span>计算详情</span></a>
            </div>
            <div class="indiana-code">
                <div class="indiana-code-inner">
                    <h3>我的参与码（共参与<strong class="parti-num"><?=count($my_code)?></strong>人次）</h3>
                    <p><?=(empty($my_code) ? '暂无记录' : implode(',',$my_code))?></p>
                </div>
                <a href="javascript:;" class="btn-toggle btn-toggle-down">展开<i></i></a>
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

        <!-- participate record -->
        <div class="grid parti-record mt-10">
            <div class="parti-record-hd">
                <h3>本期参与记录</h3>
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

        <!-- bottom -->
        <?php $this->widget('cart_bottom', array('peroid_str'=>$peroid_str))?>
    </div>
</div>
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

<?php $this->widget('right_icon', array('act_id'=>$detail['iActId'])) ?>

<script>
    $(function(){
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
    $(function(){
        DUOBAO.codeToggle();
        var aCountDown = $('.countDown'),
            time = (new Date($(aCountDown).attr('data-end-time')).getTime() - new Date($(aCountDown).attr('data-start-time')).getTime())/1000;
        DUOBAO.countDown('.countDown', time);

        // 商品轮播
        var swiper = new Swiper('#goodsThumb', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            autoplay: 5000
        });

        DUOBAO.codeShowHide();
        // 夺宝码展开折叠
        $('.btn-toggle').on('click', function(){
            DUOBAO.codeToggle(this);
        });
    })
</script>