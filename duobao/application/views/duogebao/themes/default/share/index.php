<div class="viewport v-share">
    <!-- share start -->
    <div class="share" id="listMore" data-url="<?=gen_uri('/share/ajax_share_list')?>" data-load="<?=(!empty($share_list['page_count']) && $share_list['page_index']>=$share_list['page_count']) ? 'false' : 'true'?>">
        <?php
        if(!empty($share_list)) {
            $i = 0;
            foreach ($share_list['list'] as $share_item)
            {
                $period_code = period_code_encode($share_item['act_id'], $share_item['period']);
                ?>
                <div class="grid-1 share-item<?=$i? ' mt-10' : ''?>">
                    <div class="pd10">
                        <div class="user-avatar">
                            <img src="<?=$share_item['headimg']?>" width="50" height="50" alt="">
                        </div>
                        <div class="share-content">
                            <div class="author">
                                <p><?=$share_item['nickname']?></p>
                                <p class="clearfix"><em class="fl">IP：<?=$share_item['ip']?></em><span class="time fr"><?=$share_item['share_time'] ? date('Y-m-d H:i:s', $share_item['share_time']) : ''?></span></p>
                            </div>
                            <div class="share-prize">
                                <div class="pic">
                                    <a href="<?=gen_uri('/active/detail', array('id'=>$period_code))?>"><img src="<?=$share_item['goods_img']?>" width="42" height="42" alt="" /></a>
                                </div>
                                <div class="name">
                                    <p><?=$share_item['goods_name']?></p>
                                    <p>期号：<?=$period_code?></p>
                                </div>
                            </div>
                            <div class="abbr">
                                <a href="<?=gen_uri('/share/detail', array('id'=>$share_item['share_id']))?>">
                                    <p class="redbg"><?=$share_item['content']?></p>
                                    <?php if($share_item['imgs']) {
                                        if(is_string($share_item['imgs'])) {
                                            $share_item['imgs'] = json_decode($share_item['imgs'], true);
                                        }
                                        ?>
                                        <ul class="clearfix">
                                            <?php
                                                $i = 1;
                                                foreach($share_item['imgs'] as $img) {
                                                    if($i <= 3){
                                            ?>
                                                <li><img src="<?=get_img_resize_url($img, Lib_Constants::SHARE_IMG_SMALL, Lib_Constants::SHARE_IMG_SMALL)?>" width="64" height="64" alt=""></li>
                                            <?php }$i++;} ?>
                                        </ul>
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="actions us-pic-bg">
                        <div class="actions-bg clearfix" data-shareid="<?=$share_item['share_id']?>">
                                <ul class="clearfix">
                                    <li class="like"><i class="<?=$share_item['is_liked'] ? 'icon-like-on' : 'icon-like'?>"></i><strong><?=$share_item['like_count']?></strong>人已赞</li>
                                    <li class="views"><strong><?=$share_item['view_count']?></strong>人已查看</li>
                                </ul>

                        </div>
                    </div>
                </div>
                <?php
                $i++;
            }
        } else {
            ?>
            <div class="grid-1 share-item">暂无记录</div>
        <?php
        }
        ?>
    </div>
    <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
    <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
    <!-- share end -->
</div>
<script>
    $(function(){
        DUOBAO.url.share_operate = '<?=gen_uri('/share/operate')?>';


        $('.icon-like').on('click', function(){
            DUOBAO.clickLike(this);
        });
        <?php
            if(!empty($share_list)) {
        ?>
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
    <?php }?>
        function zoonTemp(li){
            console.log(li);
            var html = [];

            html.push('<div class="grid-1 share-item mt-10">');
            html.push('<div class="pd10">');
            html.push('<div class="user-avatar">');
            html.push('<img src="'+li.headimg+'" width="50" height="50" alt="">');
            html.push('</div>');
            html.push('<div class="share-content">');
            html.push('<div class="author">');
            html.push('<p>'+li.nickname+'</p>');
            html.push('<p class="clearfix"><em class="fl">'+li.area+' IP：'+li.ip+'</em><span class="time fr">'+li.share_time+'</span></p>');
            html.push('</div>');
            html.push('<div class="share-prize">');
            html.push('<div class="pic">');
            html.push('<a href="'+li.active_url+'"><img src="'+li.goods_img+'" width="42" height="42" alt=""></a>');
            html.push('</div>');
            html.push('<div class="name">');
            html.push('<p>'+li.goods_name+'</p>');
            html.push('<p>期号：'+li.period_code+'</p>');
            html.push('</div>');
            html.push('</div>');
            html.push('<div class="abbr">');
            html.push('<a href="'+li.share_detail_url+'">');
            html.push('<p class="redbg">'+li.content+'</p>');
            html.push('<ul class="clearfix">');
            for(var i in li.imgs) {
                html.push('<li><img src="'+li.imgs[i]+'" width="64" height="64" alt=""></li>');
            }
            html.push('</ul>');
            html.push('</a>');
            html.push('</div>');
            html.push('</div>');
            html.push('</div>');
            html.push('<div class="actions us-pic-bg">');
            html.push('<div class="actions-bg clearfix" data-shareid="'+li.share_id+'">');
            html.push('<ul class="clearfix">');
            var ss = li.is_liked ? 'icon-like-on': 'icon-like';
            html.push('<li class="like"><i class="'+ss+'" onclick="DUOBAO.clickLike(this)"></i><strong>'+li.like_count+'</strong>人已赞</li>');
            html.push('<li class="views"><strong>'+li.view_count+'</strong>人已查看</li>');
            html.push('</ul>');
            html.push('</div>');
            html.push('</div>');
            html.push('</div>');

            return html.join('');
        }
    })
</script>