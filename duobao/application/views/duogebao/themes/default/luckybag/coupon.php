<div class="viewport v-stamps-use-record">
    <!-- stamps use record start -->
    <div class="stamps-use-record">
        <div class="stamps-use-record-hd clearfix">
            <h3 class="tit-buy-indiana-stamps fl">夺宝券使用记录</h3>
            <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-send-bag fr">大家一起来夺宝，去发福袋吧！&gt;</a>
        </div>
        <div class="stamps-use-record-con">
            <ul class="user-record-list" id="listMore" data-url="<?=gen_uri('/luckybag/ajax_coupon')?>" data-load="<?=(!empty($log_list['page_count']) && $log_list['page_index']>=$log_list['page_count']) ? 'false' : 'true'?>">
                <?php if(!empty($log_list['list'])){ ?>
                    <?php foreach($log_list['list'] as $item){ ?>
                        <li>
                            <span>
                                <p class="record-desc"><?=empty(Lib_Constants::$coupon_actions[$item['iAction']]) ? '--' : Lib_Constants::$coupon_actions[$item['iAction']]?></p>
                                <p class="record-time"><?=date('Y-m-d H:i:s',$item['iAddTime'])?></p>
                            </span>
                            <em>
                                <?=(Lib_Constants::ACTION_INCOME == $item['iType']) ? '+' : '-'?><?=$item['iNum']?>张夺宝券
                            </em>
                        </li>
                    <?php } ?>
                <?php }else{ ?>
                    <li>暂无记录</li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
    <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
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
            console.log(li);
            var html = [];

            html.push('<li>');
            html.push('<span>');
            html.push('<p class="record-desc">'+li.action+'</p>');
            html.push('<p class="record-time">'+li.add_time+'</p>');
            html.push('</span>');
            html.push('<em>');
            html.push(''+li.num+'张夺宝券');
            html.push('</em>');
            html.push('</li>');
            return html.join('');
        }
    })
</script>