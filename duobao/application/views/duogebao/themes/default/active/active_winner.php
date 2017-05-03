<div class="viewport v-past-announced" data-url="<?=gen_uri('/active/active_winner')?>"  data-load="true" id="listMore">
    <?php if(!empty($list)){ ?>
        <?php foreach($list as $li){ ?>
            <?php if($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_GOING){ ?>
                <div class="top-tips">
                    <p>期 号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?><strong>即将揭晓</strong></p>
                </div>
            <?php }elseif($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_OPENED){ ?>
                <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" style="display:block;">
                    <div class="grid announced-item mt-10">
                        <div class="announced-item-con">
                            <dl>
                                <dt>
                                    <img src="<?=$li['sWinnerHeadImg']?>" width="52" height="52" alt="">
                                </dt>
                                <dd>
                                    <p><?=$li['sWinnerNickname']?></p>
                                    <p>用户编号：<?=$li['iWinnerUin']?></p>
                                    <p>本期购买：<strong><?=$li['iWinnerCount']?></strong>人次</p>
                                    <p>幸运码：<?=$li['sWinnerCode']?></p>
    <!--                                <div class="position"><i class="icon-location"></i><span class="location">广东省深圳市</span></div>-->
                                </dd>
                            </dl>
                        </div>
                        <div class="announced-item-bott">
                            <div class="prize-info-past">
                                <p><label>中奖商品：</label><?=$li['sGoodsName']?></p>
                                <p><label>揭晓时间：</label><?=date('Y-m-d H:i:s',$li['iLotTime'])?></p>
                                <p><label>期号：</label><?=period_code_encode($li['iActId'],$li['iPeroid'])?></p>
                                <img src="<?=$li['sImg']?>" width="60" height="60" class="prize-past-pic" alt="">
                            </div>
                        </div>
                    </div>
                 </a>
            <?php } ?>
        <?php } ?>
    <?php }else{ ?>
        <div class="win-list-empty">
            <i class="icon-e-search"></i>
            <p>暂无中奖信息<br>快去参与活动吧</p>
            <a href="<?=gen_uri('/home/index')?>" class="btn btn-error btn-e-join"><span>立即参与</span></a>
        </div>
    <?php } ?>
    <div>
        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
    </div>
</div>
<script>
    $(function(){
        // loadMore
        DUOBAO.loadMore('#listMore',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData.length <= 0)  _this.attr('data-load','false');
                $.each(rs.retData,function(i,li){
                    if(li.iLotState == 2){ //已揭晓
                        $('#listMore').append(openTemp(li));
                    }else{ //即将揭晓
                        $('#listMore').append(goTemp(li));
                    }
                })
            }
        });

        function openTemp(li){
            var html = [];
            html.push('<div class="grid announced-item mt-10">');
            html.push('<div class="announced-item-con">');
            html.push('<dl><dt>');
            html.push('<img src="'+li.sWinnerHeadImg+'" width="52" height="52" alt="">');
            html.push('</dt><dd>');
            html.push('<p>'+li.sWinnerNickname+'</p>');
            html.push('<p>用户编号：'+li.sWinnerCode+'</p>');
            html.push('<p>本期购买：<strong>'+li.iWinnerCount+'</strong>人次</p>');
            html.push('<p>幸运码：'+li.sWinnerCode+'</p>');
            html.push('</dd></dl></div>');
            html.push('<div class="announced-item-bott">');
            html.push('<div class="prize-info-past">');
            html.push('<p><label>中奖商品：</label>'+li.sGoodsName+'</p>');
            html.push('<p><label>揭晓时间：</label>'+li.iLotDate+'</p>');
            html.push('<p><label>期号：</label>'+li.iPeroidCode+'</p>');
            html.push('<img src="'+li.sImg+'" width="60" height="60" class="prize-past-pic" alt="">');
            html.push('</div></div></div>');

            return html.join('');
        }

        function goTemp(){
            var html = [];
            html.push('<div class="top-tips">');
            html.push('<p>期 号：'+li.iPeroidCode+'<strong>（即将揭晓）</strong></p>');
            html.push('</div>');

            return html.join('');
        }
    })
</script>