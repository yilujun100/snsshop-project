<style>
.custom-goods-list-empty { padding: 20px 0; border-bottom: 0; text-align: center; }
</style>

<div class="viewport v-india-custom">
    <div class="banner-custom">
        <img src="<?=$resource_url?>images/v2/banner_custom_lottery.jpg" width="100%" alt="">
        <a href="<?=gen_uri('/help/index', array('item'=>'rules_custom'))?>" class="btn-view-rules">查看规则</a>
    </div>

    <div class="grid custom-process mt-10">
        <ul class="clearfix">
            <li class="step-on"><i class="icon-custom-pro icon-custom-gift"></i><h3>1.选择奖品</h3></li>
            <li class="step-on"><i class="icon-custom-pro icon-custom-users"></i><h3>2.立即参与</h3></li>
            <li class="step-on"><i class="icon-custom-pro icon-custom-cup"></i><h3>3.等待人数集齐开奖</h3></li>
        </ul>
    </div>

    <a href="<?=gen_uri('/active_custom/choose')?>" class="btn-custom-lottery">发起订制中奖</a>
    <div class="tab-custom mt-10">
        <div class="grid tab-tit clearfix" id="tabIndianaCustom">
            <a href="javascript:;" data-url="<?=gen_uri('/active_custom/custom_list')?>" class="tab-active">最热</a>
            <a href="javascript:;" data-url="<?=gen_uri('/active_custom/custom_list')?>">最新</a>
        </div>
        <div class="tab-con" id="actCustomCon">
            <!-- 最热 -->
            <div class="custom-hot" style="display: block;">
                <ul class="custom-goods-list">
                    <?php foreach($list['list'] as $li){ ?>
                    <li>
                        <div class="custom-tag"><?=$li['sActName']?></div>
                        <div class="custom-goods-pic"><img src="<?=$li['sImg']?>" width="100%" alt=""></div>
                        <div class="custom-progress" data-lott-schedule="<?=$li['iProcess']?>%">
                            <div class="custom-progress-bar">
                                <span class="c-progress-bar-on" style="width: <?=$li['iProcess']?>%"></span>
                            </div>
                            <em class="custom-pro-txt"><?=$li['iProcess']?>%</em>
                        </div>
                        <div class="custom-goods-name"><?=$li['sGoodsName']?></div>
                        <a href="<?=gen_uri('/active/detail', array('id'=>$li['iPeroidCode']))?>" class="btn-custom-join">立即参与</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- 最新 -->
            <div class="custom-latest">
                <ul class="custom-goods-list custom-goods-list-empty">
                    <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                    <h3>暂无相关记录，敬请期待哦~</h3>
                </ul>
            </div>
        </div>
        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more" style="display:none;">没有更多,逛逛其他吧!</div>
    </div>
</div>

<script>
    $(function(){
        $('#tabIndianaCustom > a').on('click', function() {
            var $this = $(this);
            var index = $this.parent().find('a').index($this);
            var url = $this.attr('data-url');

            if(! $('#actCustomCon > div').eq(index).hasClass('empty')){
                $this.parent().find('a').removeClass('tab-active');
                $this.addClass('tab-active');

                $('#actCustomCon > div').hide();
                $('#actCustomCon > div').eq(index).show();

                return;
            }
            DUOBAO._get(url, function (rs) {
                var html = [];
                if (! rs) {
                    return ;
                }
                $.each(rs.retData.list, function(i, record) {
                    html.push(record_tmpl(record));
                });
                $('#actCustomCon > div').eq(index).html(html.join(''));

                $this.parent().find('a').removeClass('tab-active');
                $this.addClass('tab-active');

                $('#actCustomCon > div').hide();
                $('#actCustomCon > div').eq(index).removeClass('empty').show();

                //doCountDown();
            });
        });

        DUOBAO.loadMore('#tabIndianaCustom > a', function (rs, index, $this) {
            if (! rs || rs.retCode != 0) {
                $this.attr('data-load', 'false');
                return ;
            }
            if(rs.retData.page_index >= rs.retData.page_count) {
                $this.attr('data-load', 'false');
            }
            $.each(rs.retData.list,function(i, record) {
                $('#actCustomCon > div').eq(index).append(record_tmpl(record));
            });
        });
    })

    function record_tmpl(record){
        var html = '';
        html.push('<li>');
        html.push('<div class="custom-tag">'+record.sActName+'</div>');
        html.push('<div class="custom-goods-pic"><img src="'+record.sImg+'" width="100%" alt=""></div>');
        html.push('<div class="custom-progress" data-lott-schedule="'+record.iProcess+'%">');
        html.push('<div class="custom-progress-bar">');
        html.push('<span class="c-progress-bar-on" style="width: '+record.iProcess+'%"></span>');
        html.push('</div><em class="custom-pro-txt">'+record.iProcess+'%</em>');
        html.push('</div><div class="custom-goods-name">'+record.sGoodsName+'</div>');
        html.push('<a href="'+record.detailUrl+'" class="btn-custom-join">立即参与</a>');
        html.push('</li>');

        return html.join('\n');
    }
</script>
<?php $this->widget('menus', array('menus_show'=>true)) ?>