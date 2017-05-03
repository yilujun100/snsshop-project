<script type="text/javascript" src="<?=$resource_url?>js/home.js"></script>
<style>
    .u-flyer{display: block;width: 100px;height: 100px;border-radius: 50px;position: fixed;z-index: 9999;}
    /*.nav-cart { padding-top: 24px; }*/
    #end { position: fixed; bottom: 24px; left: 70%; margin-left: -10px; }
</style>
<div class="viewport v-home">
    <div class="home">
        <!-- banner -->
        <?php if (! empty($banner_advert)) {?>
            <!-- banner -->
            <div class="swiper-container banner" id="slider">
                <ul class="swiper-wrapper">
                    <?php foreach ($banner_advert as $v) {?>
                        <li class="swiper-slide"><a href="<?=$v['sTarget']?>"><img src="<?=$v['sImg']?>" width="100%" alt=""></a></li>
                    <?php }?>
                </ul>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        <?php }?>

        <!-- scroll info -->
        <div class="scroll-info">
            <em class="icon-horn"></em>
            <div class="msgs" id="scrollInfo">
                <ul>
                    <?php foreach($active_msg as $msg){ ?>
                        <li><a href="<?=gen_uri('/active/active_winner',array('id'=>period_code_encode($msg['iActId'],$msg['iPeroid'])))?>">恭喜<strong><?=$msg['sWinnerNickname']?></strong><?=tran_time($msg['iLotTime'])?>获得<b><?=$msg['sGoodsName']?></b></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- quick entry -->
        <div class="quick-entry-nav clearfix">
            <a href="javascript:"><i class="icon-entry-nav icon-sign"></i>签到</a>
            <a href="<?=gen_uri('/pay/pull',array(),'payment')?>"><i class="icon-entry-nav icon-lucky-bag"></i>发福袋</a>
            <a href="<?=gen_uri('/pay/buy_coupon',array(),'payment')?>"><i class="icon-entry-nav icon-ticket"></i>购买券</a>
<!--            <a href="#"><i class="icon-entry-nav icon-custom"></i>私人定制</a>-->
            <a href="<?=gen_uri('/active/lists')?>"><i class="icon-entry-nav icon-category"></i>分类</a>
        </div>

        <!-- new reveal -->
        <div class="tab index-newReveal mt-10">
            <div class="tab-tit clearfix" id="tab1">
                <a href="javascript:;" <?=($show_tab == 1 ? 'class="tab-active"' : '')?>>已揭晓</a>
                <a href="javascript:;" <?=($show_tab == 2 ? 'class="tab-active"' : '')?>>即将揭晓</a>
                <a href="<?=gen_uri('/active/active_winner',array())?>" class="tab-more">更多&gt;&gt;</a>
            </div>
            <div class="tab-con" id="tabCon1">
                <!-- 已揭晓 -->
                <div class="swiper-container already-revealed" id="swiper-1" <?=($show_tab == 1 ? 'style="display: block;"' : '')?>>
                    <?php if(!empty($history['opened'])){ ?>
                        <ul class="swiper-wrapper clearfix">
                            <?php foreach($history['opened'] as $opened){ ?>
                                <li class="swiper-slide">
                                    <div class="prize-pic">
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($opened['iActId'],$opened['iPeroid'])))?>"><img src="<?=get_img_resize_url($opened['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt=""></a>
                                    </div>
<!--                                    <div class="status status-danger">已揭晓</div>-->
                                    <div class="prize-name"><?=$opened['sGoodsName']?></div>
                                    <div class="winners">中奖者：<?=$opened['sWinnerNickname']?></div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }else{  ?>
                        <div  class="empty">
                            <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                            <h3>暂无相关记录，敬请期待哦~</h3>
                        </div>
                    <?php } ?>
                </div>
                <!-- 即将揭晓 -->
                <div class="swiper-container to-revealed" id="swiper-3" style="padding: 0;<?=($show_tab == 2 ? 'display: block;' : '')?>">
                    <!-- <img src="images/empty_pic.png" width="82" height="82" alt="">
                    <h3>暂无相关记录，敬请期待哦~</h3> -->
                    <?php if(!empty($history['soon'])){ ?>
                        <ul class="swiper-wrapper clearfix">
                            <?php foreach($history['soon'] as $soon){ ?>
                                <li class="swiper-slide">
                                    <div class="prize-pic">
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($soon['iActId'],$soon['iPeroid'])))?>"><img src="<?=get_img_resize_url($soon['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt=""></a>
<!--                                        <div class="status status-warning">即将揭晓</div>-->
                                    </div>
                                    <div class="prize-name"><?=$soon['sGoodsName']?></div>
                                    <div class="countDown" data-start-time="<?=date('Y/m/d H:i:s')?>" data-end-time="<?=date('Y/m/d H:i:s',$soon['iLotTime'])?>">
                                        <span class="ms"></span>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php }else{  ?>
                        <div  class="empty">
                            <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                            <h3>暂无相关记录，敬请期待哦~</h3>
                        </div>
                    <?php } ?>
                </div>
                <!-- 更多 -->
                <!-- <div>更多</div> -->
            </div>
        </div>

        <!-- snap up -->
        <div class="snap-up mt-10">
            <div class="snap-up-hd clearfix">
                <h3 class="fl"><i class="icon-alarm"></i>最后疯抢</h3>
                <span class="slogon fl">天下大奖唯快不破！一个字，抢！</span>
                <a href="<?=gen_uri('/active/lists',array('crazy'=>1))?>" class="view-all fr">更多&gt;&gt;</a>
            </div>
            <div class="swiper-container snap-up-con" id="swiper-2">
                <?php if(!empty($active_crazy)){  ?>
                    <ul class="swiper-wrapper clearfix">
                        <?php foreach($active_crazy as $crazy){  ?>
                            <li class="swiper-slide">
                                <div class="prize-pic">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($crazy['iActId'],$crazy['iPeroid'])))?>"><img src="<?=get_img_resize_url($crazy['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt=""></a>
                                </div>
                                <div class="prize-name"><a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($crazy['iActId'],$crazy['iPeroid'])))?>"><?=$crazy['sGoodsName']?></a></div>
                                <div class="lott-schedule" data-lott-schedule="<?=$crazy['iProcess']?>%">
                                    <div class="progress-bar">
                                        <span class="progress-bar-on" style="width: <?=$crazy['iProcess']?>%"></span>
                                    </div>
                                    <em><?=$crazy['iProcess']?>%</em>
                                </div>
                                <div class="prize-bott">
                                    <a href="<?=gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($crazy['iActId'],$crazy['iPeroid'])),'payment')?>" class="btn btn-error btn-parti-immediately"><span>立即参与</span></a>
<!--                                    <a href="javascript:void(0)" class="add-to-cart" onclick="DUOBAO.addCart($(this))" peroid-str="--><?//=period_code_encode($crazy['iActId'],$crazy['iPeroid'])?><!--">添加到购物车<span class="icon-plus"></span></a>-->
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php }else{ ?>
                    <div  class="empty">
                        <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                        <h3>暂无相关记录，敬请期待哦~</h3>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- indiana list -->
        <div class="list-container mt-10">
            <div class="topbar list-bar">
                <div class="category list-category" id="list-category">
                    <a href="javascript:;">夺宝分类</a>
                </div>
                <div class="list-sort" id="tab2">
                    <a href="javascript:;" data-url="<?=(gen_uri('/active/ajax_lists',array('orderby'=>'review','ordertype'=>'desc')))?>" data-load="true" class="tab-active"><i class="icon-list-tab icon-hot"></i>人气</a>
                    <a href="javascript:;" data-url="<?=(gen_uri('/active/ajax_lists',array('orderby'=>'new','ordertype'=>'desc')))?>" data-load="true"><i class="icon-list-tab icon-new"></i>最新</a>
                    <a href="javascript:;" data-url="<?=(gen_uri('/active/ajax_lists',array('orderby'=>'progress','ordertype'=>'desc')))?>" data-load="true"><i class="icon-list-tab icon-progress"></i>进度</i><i class="arrow-asc"></i></a>
                    <a href="javascript:;" data-url="<?=(gen_uri('/active/ajax_lists',array('orderby'=>'lotCount','ordertype'=>'asc')))?>" data-load="true" class="list-sort-needs"><i class="icon-list-tab icon-needs"></i>总需人次<i class="arrow-asc"></i></a>
                </div>
            </div>

            <!-- list wrap -->
            <div class="list-wrap" id="tabCon2">
                <?php if(!empty($active_list)){ ?>
                    <ul class="list clearfix" id="more">
                        <?php foreach($active_list as $list){ ?>
                            <?php if($list['iCodePrice'] == 0){ ?>
                                <li>
                                    <div class="prize-pic">
<!--                                        <a href="--><?//=gen_uri('/free/index',array('peroid_str'=>period_code_encode($list['iActId'],$list['iPeroid'])))?><!--"> -->
                                            <img src="<?=get_img_resize_url($list['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt="">
<!--                                        </a>-->
                                        <?php if(!empty($list['iCornerMark'])){ ?>
                                            <div class="goods-tag tag-ten">
                                                <?php if(!empty(Lib_Constants::$corner_mark[$list['iCornerMark']]['img'])){ ?>
                                                    <img src="<?=Lib_Constants::$corner_mark[$list['iCornerMark']]['img']?>" width="40" height="36" alt="">
                                                <?php }else{ ?>
                                                    <?=Lib_Constants::$corner_mark[$list['iCornerMark']]['text']?>
                                                <?php }?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="prize-name"><?=$list['sGoodsName']?></div>
                                    <div class="lott-schedule">
                                        <span  style="text-align: center;padding:0;margin:0;font-size:13px;color:red;width:100%;height:15px;overflow:hidden;display:block;">*活动商品可0元参与</span>
                                    </div>
                                    <div class="prize-bott" style="padding-bottom:10px;">
                                        <a href="<?=gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($list['iActId'],$list['iPeroid'])),'payment')?>" class="btn btn-warning btn-parti btn-parti-l" style="height:38px;"><span>立即参与</span></a>
                                    </div>
                                </li>

                            <?php }else{ ?>
                                <li>
                                    <div class="prize-pic">
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($list['iActId'],$list['iPeroid'])))?>"><img src="<?=get_img_resize_url($list['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt=""></a>
                                        <?php if(!empty($list['iCornerMark'])){ ?>
                                            <div class="goods-tag tag-new">
                                                <?php if(!empty(Lib_Constants::$corner_mark[$list['iCornerMark']]['img'])){ ?>
                                                    <img src="<?=Lib_Constants::$corner_mark[$list['iCornerMark']]['img']?>" width="40" height="36" alt="">
                                                <?php }else{ ?>
                                                    <?=Lib_Constants::$corner_mark[$list['iCornerMark']]['text']?>
                                                <?php }?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="prize-name"><?=$list['sGoodsName']?></div>
                                    <div class="list-goods-bott">
                                        <div class="list-schedule" data-lott-schedule="<?=$list['iProcess']?>%">
                                            <div class="list-progress-txt"><label>开奖进度</label><em class="progress-bubble"><?=$list['iProcess']?>%</em></div>
                                            <div class="list-progress-bar">
                                                <span class="list-progress-bar-on" style="width: <?=$list['iProcess']?>%"></span>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)" class="btn btn-warning btn-add-to-cart" peroid-str="<?=period_code_encode($list['iActId'],$list['iPeroid'])?>" onclick="DUOBAO.addCart($(this))"><span>加入清单</span></a>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                <?php }else{ ?>
                    <div class="empty">
                        <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                        <h3>暂无相关记录，敬请期待哦~</h3>
                    </div>
                <?php } ?>
            </div>

            <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
            <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
        </div>
    </div>
    <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
    <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
    <!-- fixed top start -->
    <?php $this->widget('goods_cate', array('goods_cate'=>isset($goods_cate)?$goods_cate:array())) ?>
    <!-- fixed top end -->
</div>
<script>
    $(function(){
        DUOBAO.url = {
            'add_cart': '<?=gen_uri('/cart/ajax_add')?>',
            'resource_url' : '<?=$resource_url?>',
            'sign' : '<?=gen_uri('/sign/add')?>'
        }
        DUOBAO.config = {
            'marktag' : <?=json_encode(Lib_Constants::$corner_mark)?>
        }

        $('.btn-search').click(function(){
            location.href = '<?=gen_uri('/active/lists')?>?keyword='+$('#listKeywords').val();
        });

//        $('#listKeywords').on('focus', function(){
//            $('.fixed-top').css({'position': 'absolute', 'top': $(window).scrollTop(), 'left': 0, 'margin-left': 0});
//        });
//        $('#listKeywords').on('blur', function(){
//            $('.fixed-top').css({'position': 'absolute', 'top': $(window).scrollTop(), 'left': '0', 'margin-left': 0});
//        });
//        $(window).on('scroll', function(){
//            $('.fixed-top').css({'position': 'absolute', 'top': $(window).scrollTop(), 'left': 0, 'margin-left': 0});
//        });



//        $("#formTopSearch").submit(function(){
//            location.href = '<?//=gen_uri('/active/lists')?>//?keyword='+$('#listKeywords').val();
//        });

        //签到
        DUOBAO.sign('.icon-sign');

        DUOBAO.fnInputClear('#listKeywords');


        $('#tab2>a').on('click', function(){
            var _this = $(this);
            var index = _this.parent().find('a').index($(this));
            var url = _this.attr('data-url');

            DUOBAO._get(url,function(rs){
                if(!rs || rs.retCode != 0){
                    alert('加载数据失败~');
                    return ;
                }else{

                    if(rs.retData == '')
                    {
                        var html = [];
                        html.push('<div class="empty">');
                        html.push('<img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">');
                        html.push('<h3>暂无相关记录，敬请期待哦~</h3>');
                        html.push('</div>');
                        $("#tabCon2").html('');
                        $("#tabCon2").html(html.join(''));
                        return false;
                    }
                    if(rs.retData.index_where.orderby == 'lotCount' || rs.retData.index_where.orderby ==  'progress')
                    {
                        var url = '<?=gen_uri('/active/ajax_lists')?>';
                        var des = rs.retData.index_where.ordertype;
                        if(des == 'desc')
                        {
                            des = 'asc';
                        }
                        else
                        {
                            des = 'desc';
                        }
                        url += '?orderby='+rs.retData.index_where.orderby+'&ordertype='+des;
                        _this.attr('data-url',url);
                    }

                    var html = [];
                    html.push('<ul class="list clearfix" id="more">');
                    html.push('');
                    $.each(rs.retData.list,function(i,li){
                        html.push(listTemp(li));
                    });
                    html.push('</ul>');
                    $("#tabCon2").html('');
                    $("#tabCon2").html(html.join(''));
                }

                _this.parent().find('a').removeClass('tab-active');
                _this.addClass('tab-active');
            });

        });

        function listTemp(li)
        {
            var conf = DUOBAO.config.marktag;
            var html = [];
            if(li.iCodePrice == 0)
            {
                html.push('<li>');
                html.push('<div class="prize-pic">');
                html.push('<a href="'+li.url+'"><img src="'+li.sImg+'" width="100%" alt="" /></a>');
                if(li.iCornerMark && li.iCornerMark > 0 && typeof conf[li.iCornerMark] != 'undefined'){
                    if(conf[li.iCornerMark]['img'] != ''){
                        html.push('<div class="goods-tag tag-ten"><img src="'+conf[li.iCornerMark]['img']+'" width="40" height="36" alt=""></div>');
                    }else{
                        html.push('<div class="goods-tag tag-ten">'+conf[li.iCornerMark]['text']+'</div>');
                    }
                }
                html.push('</div>');
                html.push('<div class="prize-name">'+li.sGoodsName+'</div>');
                html.push('<div class="lott-schedule">');
                html.push('<span  style="text-align: center;padding:0;margin:0;font-size:13px;color:red;width:100%;height:15px;overflow:hidden;display:block;">*活动商品可0元参与</span>');
                html.push('</div>');
                html.push('<div class="prize-bott" style="padding-bottom:10px;">');
                html.push('<a href="'+li.buy_url+'" class="btn btn-warning btn-parti btn-parti-l" style="height:38px;"><span>立即参与</span></a>');
                html.push('</div>');
                html.push('</li>');
            }
            else
            {
                html.push('<li>');
                html.push('<div class="prize-pic">');
                html.push('<a href="'+li.url+'"><img src="'+li.sImg+'" width="100%" alt=""></a>');
                if(li.iCornerMark && li.iCornerMark > 0 && typeof conf[li.iCornerMark] != 'undefined'){
                    if(conf[li.iCornerMark]['img'] != ''){
                        html.push('<div class="goods-tag tag-new"><img src="'+conf[li.iCornerMark]['img']+'" width="40" height="36" alt=""></div>');
                    }else{
                        html.push('<div class="goods-tag tag-new">'+conf[li.iCornerMark]['text']+'</div>');
                    }
                }
                html.push('</div>');
                html.push('<div class="prize-name">'+li.sGoodsName+'</div>');
                html.push('<div class="list-goods-bott">');
                html.push('<div class="list-schedule" data-lott-schedule="'+li.iProcess+'%">');
                html.push('<div class="list-progress-txt"><label>开奖进度</label><em class="progress-bubble">'+li.iProcess+'%</em></div>');
                html.push('<div class="list-progress-bar">');
                html.push('<span class="list-progress-bar-on" style="width: '+li.iProcess+'%"></span>');
                html.push('</div>');
                html.push('</div>');
                html.push('<a href="javascript:void(0)" class="btn btn-warning btn-add-to-cart" peroid-str="'+li.peroid_str+'" onclick="DUOBAO.addCart($(this))"><span>加入清单</span></a>');
                html.push('</div>');
                html.push('</li>');
            }
            return html.join('');
        }


        DUOBAO.loadMore('#tab2>a',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                alert('加载数据失败~');
                return ;
            }else{
                if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
                $.each(rs.retData.list,function(i,li){
                    $('#more').append(zoonTemp(li));
                })
            }
        });

        function zoonTemp(li){

            var conf = DUOBAO.config.marktag;
            var html = [];
            if(li.iCodePrice == 0)
            {
                html.push('<li>');
                html.push('<div class="prize-pic">');
                html.push('<a href="'+li.url+'"><img src="'+li.sImg+'" width="100%" alt="" /></a>');
                if(li.iCornerMark && li.iCornerMark > 0 && typeof conf[li.iCornerMark] != 'undefined'){
                    if(conf[li.iCornerMark]['img'] != ''){
                        html.push('<div class="goods-tag tag-new"><img src="'+conf[li.iCornerMark]['img']+'" width="40" height="36" alt=""></div>');
                    }else{
                        html.push('<div class="goods-tag tag-new">'+conf[li.iCornerMark]['text']+'</div>');
                    }
                }
                html.push('</div>');
                html.push('<div class="prize-name">'+li.sGoodsName+'</div>');
                html.push('<div class="lott-schedule">');
                html.push('<span  style="text-align: center;padding:0;margin:0;font-size:13px;color:red;width:100%;height:15px;overflow:hidden;display:block;">*活动商品可0元参与</span>');
                html.push('</div>');
                html.push('<div class="prize-bott" style="padding-bottom:10px;">');
                html.push('<a href="'+li.buy_url+'" class="btn btn-warning btn-parti btn-parti-l" style="height:38px;"><span>立即参与</span></a>');
                html.push('</div>');
                html.push('</li>');
            }
            else
            {
                html.push('<li>');
                html.push('<div class="prize-pic">');
                html.push('<a href="'+li.url+'"><img src="'+li.sImg+'" width="100%" alt=""></a>');
                if(li.iCornerMark && li.iCornerMark > 0 && typeof conf[li.iCornerMark] != 'undefined'){
                    if(conf[li.iCornerMark]['img'] != ''){
                        html.push('<div class="goods-tag tag-new"><img src="'+conf[li.iCornerMark]['img']+'" width="40" height="36" alt=""></div>');
                    }else{
                        html.push('<div class="goods-tag tag-new">'+conf[li.iCornerMark]['text']+'</div>');
                    }
                }
                html.push('</div>');
                html.push('<div class="prize-name">'+li.sGoodsName+'</div>');
                html.push('<div class="list-goods-bott">');
                html.push('<div class="list-schedule" data-lott-schedule="'+li.iProcess+'%">');
                html.push('<div class="list-progress-txt"><label>开奖进度</label><em class="progress-bubble">'+li.iProcess+'%</em></div>');
                html.push('<div class="list-progress-bar">');
                html.push('<span class="list-progress-bar-on" style="width: '+li.iProcess+'%"></span>');
                html.push('</div>');
                html.push('</div>');
                html.push('<a href="javascript:void(0)" class="btn btn-warning btn-add-to-cart" peroid-str="'+li.peroid_str+'" onclick="DUOBAO.addCart($(this))"><span>加入清单</span></a>');
                html.push('</div>');
                html.push('</li>');
            }

            return html.join('');
        }



        // 活动规则
        /*if (!getCookie('activity_rules')) {
         $('.mask-rules, .pop-acti-rules').show();
         var d = new  Date();
         d.setTime(d.getTime() + (30*24*60*60*1000));
         var expires = 'expires='+ d.toUTCString();
         document.cookie = 'activity_rules=activity_rules_cookie;'+ expires;
         }else{
         show_prize();
         }
         $('#btnPopClose').on('click', function(){
         $('.mask-rules, .pop-acti-rules').hide();
         show_prize();
         });

         $('#btnPopWinClose').on('click', function(){
         DUOBAO.popWin.hide();
         });*/


        // 活动规则
        $('.quick-entry-nav a:last-child').on('click', function(){
            $('.pop-mask, .pop-acti-rules-new').show();
        });
        $('.btn-know').on('click', function(){
            $('.pop-mask, .pop-acti-rules-new').hide();
        });

        // 如果用户中奖
        <?php if(!empty($empty_deliver)){ ?>
        var prize = {
            no: '<?=period_code_encode($empty_deliver['iActId'],$empty_deliver['iPeroid'])?>',
            name: '<?=$empty_deliver['sGoodsName']?>',
            pic: '<?=$empty_deliver['sImg']?>',
            url: '<?=gen_uri('/my/active_win_order',array('order_id'=>$empty_deliver['sWinnerOrder'],'peroid_str'=>period_code_encode($empty_deliver['iActId'],$empty_deliver['iPeroid'])))?>'
        }
        DUOBAO.popWin.init(prize.name, prize.no, prize.pic, prize.url);
        <?php } ?>

//        function show_prize(){// 如果用户中奖
//            <?php //if(!empty($empty_deliver)){ ?>
//            $('.mask-rules').show();
//            var prize = {
//                no: '<?//=period_code_encode($empty_deliver['iActId'],$empty_deliver['iPeroid'])?>//',
//                name: '<?//=$empty_deliver['sGoodsName']?>//',
//                pic: '<?//=$empty_deliver['sImg']?>//',
//                url:'<?//=gen_uri('/my/active_win_order',array('order_id'=>$empty_deliver['sWinnerOrder'],'peroid_str'=>period_code_encode($empty_deliver['iActId'],$empty_deliver['iPeroid'])))?>//'
//            }
//            DUOBAO.popWin.init(prize.name, prize.no, prize.pic,prize.url);
//            <?php //} ?>
//        }

    })

    function getCookie(cname){
        var name = cname+ '=';
        var ca = document.cookie.split(';')
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
        }
        return '';
    }
</script>