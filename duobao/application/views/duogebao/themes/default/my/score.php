<div class="viewport v-score-record">
    <div class="grid score-record">
        <div class="grid-1 score-record-hd">
            <h3>积分记录</h3>
        </div>
        <div class="score-record-con">
            <p class="jf-residue"><label>积分余额</label><strong><?=empty($user_ext['score'])?0:intval($user_ext['score'])?></strong><a href="<?=gen_uri('/help/index', array('item'=>'rules_score'))?>" class="view-record-rules">查看积分规则</a></p>
            <p class="jf-max"><label>累积消费积分</label><strong><?=empty($user_ext['his_used_score'])?0:intval($user_ext['his_used_score'])?></strong></p>
        </div>
    </div>

    <div class="grid record-detail mt-10">
        <div class="grid-1 record-detail-hd">
            <h3>详细记录</h3>
        </div>
        <div class="record-detail-con">
            <?php
                if(!empty($log_list['list']))
                {
            ?>
                    <ul id="listMore" data-url="<?=gen_uri('/my/ajax_score')?>" data-load="<?=(!empty($log_list['page_count']) && $log_list['page_index']>=$log_list['page_count']) ? 'false' : 'true'?>">
            <?php
                    foreach ($log_list['list'] as $item)
                    {
            ?>

                        <li>
                            <i><?=date('Y-m-d H:i:s',$item['iExchangeTime'])?></i>
                            <span><?=$item['sAwardsName']?></span>
                            <em><?=(Lib_Constants::ACTION_INCOME == $item['iType']) ? '+' : '-'?><?=$item['iScoreCount']?></em>
                        </li>
            <?php
                    }
            ?>
                    </ul>
            <?php


                } else {
            ?>
                    <div class="record-detail-empty-1">
                        <i class="icon-card"></i>
                        <p>暂无积分记录<br>参与越多积分越多哦~</p>
                        <a href="<?=gen_uri('/home/index')?>" class="btn btn-error btn-e-join-now"><span>立即参与</span></a>
                    </div>
            <?php
                }
            ?>

        </div>
        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
    </div>
</div>
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
                    $('#listMore').append(zoonTemp(li));
                })
            }
        });

        function zoonTemp(li){
            var html = [];

            html.push('<li>');
            html.push('<i>'+li.exchange_time+'</i>');
            html.push('<span>'+li.awards+'</span>');
            html.push('<em>'+li.score+'积分</em>');
            html.push('</li>');
            return html.join('');
        }
    })
</script>