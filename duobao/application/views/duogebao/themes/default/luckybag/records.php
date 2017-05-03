<div class="viewport v-my-bag">
    <!-- my bag start -->
    <div class="my-bag">
        <div class="user-info">
            <img src="<?=$luckybag_url?>images/bg.jpg" width="100%" alt="" />
            <div class="user-info-inner user-info-inner-1">
                <div class="avatar">
                    <img src="<?=$user['head_img']?>" width="33" height="33" alt="" />
                </div>
                <h3 class="nickname"><?=$user['nick_name']?></h3>
                <a href="<?=gen_uri('/luckybag/pull')?>" class="btn-my-send-bag">发福袋<i></i></a>
            </div>
        </div>

        <div class="tab tab-my mt-10">
            <div class="tab-tit" id="fixedTabTitle">
                <a href="javascript:;" class="tab-active" data-tem="zoonTempAct" data-url="<?=gen_uri('/luckybag/act_bags')?>" data-load="<?=(!empty($act_bag['page_count']) && $act_bag['page_index']>=$act_bag['page_count']) ? 'false' : 'true'?>">可发放福袋<br>（<?=isset($act_bag['count'])?$act_bag['count']:0?>）</a>
                <a href="javascript:;" data-tem="zoonTempMy" data-url="<?=gen_uri('/luckybag/my_bags')?>" data-load="<?=(!empty($my_bag['page_count']) && $my_bag['page_index']>=$my_bag['page_count']) ? 'false' : 'true'?>">我收到的福袋<br>（<?=isset($my_bag['count'])?$my_bag['count']:0?>）</a>
                <a href="javascript:;" data-tem="zoonTempPull" data-url="<?=gen_uri('/luckybag/pull_bags')?>" data-load="<?=(!empty($pull_bag['page_count']) && $pull_bag['page_index']>=$pull_bag['page_count']) ? 'false' : 'true'?>">我送出的福袋<br>（<?=isset($pull_bag['count'])?$pull_bag['count']:0?>）</a>
            </div>
            <div class="tab-con" id="tab-con" style="padding-bottom: 0;">
                <!-- 可发放福袋 -->
                <div class="my-bag-content my-bag-available" style="display: block">
                    <div class="tab-con-tit">可发放福袋<?=isset($act_bag['count'])?$act_bag['count']:0?>个</div>
                    <ul class="user-record-list my-list my-list-bag-available">
                        <?php
                        if (!empty($act_bag['list'])) {
                            foreach ($act_bag['list'] as $item) {
                        ?>
                            <li>
                                <span>
                                    <p class="record-desc"><?=($item['iType']==Lib_Constants::BAG_TYPE_NORMAL?'普通福袋':'拼手气福袋')?></p>
                                    <p class="record-time"><?=(date('Y-m-d H:i:s',$item['iStartTime']))?></p>
                                </span>
                                <em><?=($item['iCoupon']-$item['iUsed'])?>张夺宝券未领</em>
                                <a href="<?=gen_uri('/luckybag/info', array('uin'=>$item['iUin'], 'bag_id'=>$item['iBagId'], 'sign'=>gen_sign($item['iUin'], $item['iBagId'])))?>">继续发&gt;</a>
                            </li>
                        <?php
                            }
                        } else {
                        ?>
                            <li>
                                <span>
                                    暂无记录!
                                </span>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <!-- 我收到的福袋 -->
                <div class="my-bag-content my-bag-received">
                    <div class="tab-con-tit">共收到福袋<?=isset($my_bag['count'])?$my_bag['count']:0?>个</div>
                    <ul class="user-record-list my-list my-list-bag-received" >
                        <?php
                        if (!empty($my_bag['list'])) {
                            foreach ($my_bag['list'] as $item) {
                               $bag_info = json_decode($item['sExt'], true);
                                if (is_array($bag_info)) {
                                ?>
                                <li>
                                    <a href="<?=gen_uri('/luckybag/info', array('uin'=>$bag_info['uin'], 'bag_id'=>$bag_info['bag_id'],  'sign'=>gen_sign($bag_info['uin'], $bag_info['bag_id'])))?>">
                                <span>
                                    <p class="record-desc"><?=isset($bag_info['nickname']) ? $bag_info['nickname'] : ''?><?=(isset($bag_info['type']) && $bag_info['type']==Lib_Constants::BAG_TYPE_NORMAL?'':'<label class="tag-fight-luck">拼手气</label>')?></p>
                                    <p class="record-time"><?=(date('Y-m-d H:i:s',$item['iAddTime']))?></p>
                                </span>
                                    <em><?=$item['iNum']?>张夺宝券</em>
                                    </a>
                                </li>
                            <?php
                                }
                            }
                        } else {
                            ?>
                            <li>
                                <span>
                                    暂无记录!
                                </span>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <!-- 我送出的福袋 -->
                <div class="my-bag-invoice">
                    <div class="tab-con-tit">共送出福袋<?=isset($pull_bag['count'])?$pull_bag['count']:0?>个</div>
                    <ul class="user-record-list my-list my-list-bag-send">
                        <?php
                        if (!empty($pull_bag['list'])) {
                            foreach ($pull_bag['list'] as $item) {
                                ?>
                                <li>
                                    <a href="<?=gen_uri('/luckybag/info', array('uin'=>$item['iUin'], 'bag_id'=>$item['iBagId'], 'sign'=>gen_sign($item['iUin'], $item['iBagId'])))?>">
                                        <span>
                                            <p class="record-desc"><?=($item['iType']==Lib_Constants::BAG_TYPE_NORMAL?'普通福袋':'拼手气福袋')?></p>
                                            <p class="record-time"><?=(date('Y-m-d H:i:s',$item['iStartTime']))?></p>
                                        </span>
                                    <em><?=$item['iCoupon']?>张夺宝券</em>
                                    </a>
                                </li>
                            <?php
                            }
                        } else {
                            ?>
                            <li>
                                <span>
                                    暂无记录!
                                </span>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- <div class="load-more">下拉加载更多</div> -->
    </div>
    <!-- my bag end -->

    <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
    <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
</div>

<script>
    $(function(){
        // 滚动控制
        fnScrollListen();
        // tab
        fnBagTab('.tab-tit a', '.tab-con > div');

        //分页
        DUOBAO.loadMore('#fixedTabTitle>a ',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
                var tem = _this.attr('data-tem');
                $.each(rs.retData.list,function(i,li){
                    $('#tab-con>div').eq(index).find('ul').append(zoonTemp(li, tem));
                })
            }
        });

        function zoonTemp(li,tem) {
            switch (tem) {
                case 'zoonTempMy':
                    return zoonTempMy(li);
                    break;
                case 'zoonTempAct':
                    return zoonTempAct(li);
                    break;
                case 'zoonTempPull':
                    return zoonTempPull(li);
                    break
            }
        }

        function zoonTempMy(li){
            var html = [];

            html.push('<li>');
            html.push('<a href="'+li.url+'">');
            html.push('<span>');
            html.push('<p class="record-desc">'+li.nickname);
            if(li.type!=1) {
                html.push('<label class="tag-fight-luck">拼手气</label>');
            }
            html.push('</p>');
            html.push('<p class="record-time">'+li.add_time+'</p>');
            html.push('</span>');
            html.push('<em>'+li.num+'张夺宝券</em>');
            html.push('</a>');
            html.push('</li>');

            return html.join('');
        }


        function zoonTempAct(li){
            var html = [];

            html.push('<li>');
            html.push('<span>');
            html.push('<p class="record-desc">'+li.bag+'</p>');
            html.push('<p class="record-time">'+li.start_time+'</p>');
            html.push('</span>');
            html.push('<em>'+li.not_use+'张夺宝券未领</em>');
            html.push('<a href="'+li.url+'">继续发&gt;</a>');
            html.push('</li>');

            return html.join('');
        }

        function zoonTempPull(li){
            var html = [];

            html.push('<li>');
            html.push('<a href="'+li.url+'">');
            html.push('<span>');
            html.push('<p class="record-desc">'+li.bag+'</p>');
            html.push('<p class="record-time">'+li.start_time+'</p>');
            html.push('</span>');
            html.push('<em>');
            html.push(''+li.coupon+'张夺宝券');
            html.push('</em>');
            html.push('</a>');
            html.push('</li>');
            return html.join('');
        }
    })
</script>