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
            <div class="detail-info-basic">
                <div class="detail-prize-name"><?=$detail['sGoodsName']?></div>
                <div class="detail-prize-desc"> &nbsp;</div>
                <em class="schedule sche-announced">已揭晓<br>!!!</em>
            </div>
            <div class="announced">
                <div class="announced-hd clearfix">
                    <div class="winner fl">
                        <i class="icon-cup"></i>
							<span>
								<p>获奖者：<strong><?=$detail['sWinnerNickname']?></strong></p>
								<p>幸运码：<strong><?=$detail['sWinnerCode']?></strong></p>
							</span>
                    </div>
                    <a href="<?=gen_uri('/active/calc_detail',array('peroid_str'=>$peroid_str))?>" class="btn btn-default btn-calculate-detail fr"><span>计算详情</span></a>
                </div>
                <div class="announced-con">
                    <p>期号：<?=period_code_encode($detail['iActId'],$detail['iPeroid'])?></p>
                    <p>用户编号：<?=$detail['iWinnerUin']?></p>
                    <p>本期购买：<strong><?=$detail['iWinnerCount']?></strong>人次</p>
                    <p>揭晓时间：<?=date('Y-m-d H:i:s',$detail['iLotTime'])?></p>
                </div>
            </div>
            <div class="indiana-code indiana-code-2">
                <?php if($is_winner){ ?>
                    <label class="tag-winning">恭喜您中奖啦！您就是幸运之神！</label>

                <?php }else{ ?>
                    <label class="tag-not-winning">很遗憾您未能中奖,希望下次的幸运之神就是你!</label>
                <?php } ?>
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

        <!-- bottom -->
        <?php $this->widget('cart_bottom', array('peroid_str'=>$peroid_str))?>
    </div>
    <?php $this->widget('right_icon', array('act_id'=>$detail['iActId'])) ?>
</div>
<script>
    $(function(){
        DUOBAO.controlFixedBtn();

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