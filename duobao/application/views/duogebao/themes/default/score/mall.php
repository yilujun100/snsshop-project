<div class="viewport v-score-mall">
    <div class="score-balance clearfix">
        <span class="score-remain fl">您的积分余额：<strong><?=empty($user_ext['score']) ? 0 : intval($user_ext['score'])?></strong></span>
        <a href="<?=gen_uri('/help/index', array('item'=>'rules_score'))?>" class="fr"><i class="icon-question">?</i>积分规则</a>
    </div>

    <div class="mall-list">
        <ul class="list clearfix">
            <?php
            if (!empty($activity_list['list'])) {
                foreach ($activity_list['list'] as $item) {
                    if($item['iPreScore']) {
            ?>
            <li>
                <div class="prize-pic">
                    <img src="<?=$item['sImg']?>" width="100%" alt="">
                    <div class="goods-tag tag-discount">优惠</div>
                </div>
                <div class="prize-name"><?=$item['sGiftName']?></div>
                <div class="score-needs">总需积分<strong><?=$item['iOriScore']?></strong><strong><?=$item['iPreScore']?></strong></div>
                <div class="mall-list-bott">
                    <a href="javascript:void(0)" class="btn btn-warning btn-mall-exchange" data-score="<?=$item['iPreScore']?>" data-actid="<?=$item['iActivityId']?>" onclick="DUOBAO.Score_Exchange(this,<?=$item['iActivityId']?>,<?=$item['iPreScore']?>)"><span>立即兑换</span></a>
                </div>
            </li>
            <?php
                    } else {
            ?>
            <li>
                <div class="prize-pic">
                    <img src="<?=$item['sImg']?>" width="100%" alt="">

                </div>
                <div class="prize-name"><?=$item['sGiftName']?></div>
                <div class="score-needs">总需积分<strong><?=$item['iOriScore']?></strong></div>
                <div class="mall-list-bott">
                    <a href="javascript:void(0)" class="btn btn-warning btn-mall-exchange" data-score="<?=$item['iOriScore']?>" data-actid="<?=$item['iActivityId']?>"  onclick="DUOBAO.Score_Exchange(this,<?=$item['iActivityId']?>,<?=$item['iOriScore']?>)"><span>立即兑换</span></a>
                </div>
            </li>
                    <?php
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>

<script>
    $(function(){
        DUOBAO.Score_Exchange = function(obj, act_id, score){
            DUOBAO.popWinConfirm.init('确认使用 积分兑换吗?', '确认兑换', '取消', function(){
                DUOBAO._post('<?=gen_uri('/score/exchange')?>', {act_id:act_id, uin:'<?=$user['uin']?>',score:score}, function(ret){
                    if(ret.retCode == 0) {
                        if(ret.retData.score >= 0) {
                            $('#user-score').html(parseInt(ret.retData.score));
                        }
                        layer.msg('兑换成功', {icon: 1, time:1000}, function(){
                            location.reload();
                        });
                    } else {
                        layer.msg(ret.retMsg, {icon: 2, time:1000});
                    }
                });
            });
//            layer.confirm('确认使用 '+ score +' 积分兑换吗?', {
//                title: false,
//                closeBtn: false,
//                btn: ['确认兑换', '&nbsp;&nbsp;&nbsp;取消&nbsp;&nbsp;&nbsp;']
//            }, function(){ // 确认回调
//                DUOBAO._post('<?//=gen_uri('/score/exchange')?>//', {act_id:act_id, uin:'<?//=$user['uin']?>//',score:score}, function(ret){
//                    if(ret.retCode == 0) {
//                        if(ret.retData.score >= 0) {
//                            $('#user-score').html(parseInt(ret.retData.score));
//                        }
//                        layer.msg('兑换成功', {icon: 1, time:1000}, function(){
//                            location.reload();
//                        });
//                    } else {
//                        layer.msg(ret.retMsg, {icon: 2, time:1000});
//                    }
//                });
//            }, function(){ // 取消回调
//            });
        }


        //分页
        DUOBAO.loadMore('#listMore',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
                $.each(rs.retData.list,function(i,li){
                    $('#listMore').append(zoonTemp(li));
                })
            }
        });

        function zoonTemp(li){
            var html = [];

            html.push('<li class="mall-list-item">');
            html.push('<div class="goods-pic">');
            html.push('<img src="'+li.img+'" width="100%" alt="">');
            html.push('</div>');
            html.push('<div class="goods-name">'+li.goods_name+'</div>');
            if(li.pre_score > 0) {
                html.push('<div class="need-score">总需积分<s>'+li.ori_score+'</s><strong>'+li.pre_score+'</strong></div>');
                html.push('<a href="javascript:" data-score="'+li.pre_score+'" data-actid="'+li.id+'" onclick="DUOBAO.Score_Exchange(this,'+li.id+','+li.pre_score+')" class="btn btn-mall-exchange">立即兑换</a>');
            } else {
                html.push('<div class="need-score">总需积分<strong>'+li.ori_score+'</strong></div>');
                html.push('<a href="javascript:" data-score="'+li.ori_score+'" data-actid="'+li.id+'" onclick="DUOBAO.Score_Exchange(this,'+li.id+','+li.ori_score+')" class="btn btn-mall-exchange">立即兑换</a>');
            }
            html.push('</li>');
            return html.join('');
        }
    })
</script>