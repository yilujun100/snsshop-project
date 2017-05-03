<div class="viewport v-list">

    <!-- indiana list -->
    <div class="list-container">
        <div class="topbar list-bar list-bar-1">
            <?php $map = array('热门精选'=>'icon-hot','手机数码'=>'icon-phone','电脑平板'=>'icon-pc','家用电器'=>'icon-electric','品牌箱包'=>'icon-bag','钟表饰品'=>'icon-ornament','其他商品'=>'icon-others','运动保健'=>'icon-sports','个人美妆'=>'icon-beauty');?>
            <?php if(!empty($goods_cate)){  ?>
                <div class="mask"  id="mask"></div>
                <div class="category list-category list-category-1" id="category">
                    <a href="javascript:;">夺宝分类</a>
                    <div class="cate-sub">
                        <ul>
                            <li><a href="<?=gen_uri('/active/lists',array('keyword'=>'热门精选'))?>"><i class="icon-category-sub icon-hot"></i>热门精选</a></li>
                            <li><a href="<?=gen_uri('/active/lists',array('keyword'=>'苹果专区'))?>"><i class="icon-category-sub icon-phone"></i>苹果专区</a></li>
                            <?php foreach($goods_cate as $cate){   ?>
                                <li><a href="<?=gen_uri('/active/lists',array('cls'=>$cate['iCateId']))?>"><i class="icon-category-sub <?=(isset($map[$cate['sName']])?$map[$cate['sName']]:'icon-others')?>"></i><?=$cate['sName']?></a></li>
                            <?php } ?>
                            <li><a href="<?=gen_uri('/active/lists')?>"><i class="icon-category-sub icon-view-all"></i>查看全部</a></li>
                        </ul>
                    </div>
                </div>
            <?php } ?>


            <div class="list-sort">
                <a href="javascript:;" <?=($where['orderby'] == 'review' ? 'class="curr"' : '')?> order-by="review" ><i class="icon-list-tab icon-hot"></i>人气</a>
                <a href="javascript:;" <?=($where['orderby'] == 'new' ? 'class="curr"' : '')?> order-by="new"><i class="icon-list-tab icon-new"></i>最新</a>
                <a href="javascript:;" <?=($where['orderby'] == 'progress' ? 'class="curr"' : '')?> order-by="progress"><i class="icon-list-tab icon-progress"></i>进度<i class="ordertype <?=($where['orderby'] == 'progress' && $where['ordertype'] == 'asc' ? 'arrow-desc' : 'arrow-asc')?>"></i></a>
                <a href="javascript:;" <?=($where['orderby'] == 'lotCount' ? 'class="list-sort-needs curr"' : '')?> order-by="lotCount" class="list-sort-needs"><i class="icon-list-tab icon-needs"></i>总需人次<i class="ordertype <?=($where['orderby'] == 'lotCount' && $where['ordertype'] == 'asc' ? 'arrow-desc' : 'arrow-asc')?>"></i></a>
            </div>
        </div>

        <!-- search -->
        <div class="search-wrap">
            <form method="post" id="formTopSearch" action="<?=gen_uri('/active/lists')?>">
                <div class="list-search">
                    <input type="text" id="listKeywords" name="listKeywords" placeholder="搜索关键词" value="<?=(isset($where['keyword'])?$where['keyword']:'')?>">
                    <input type="submit" class="btn-search" value="">
                    <a href="javascript:;" class="btn-keyword-clear" style="top: 5px; right: 32px; display: none;">清除关键字</a>
                </div>
            </form>
        </div>
        <?php if(!empty($list['list'])){ ?>
            <!-- list wrap -->
            <div class="list-wrap" style="padding-top: 86px;" id="listMore" data-url="<?=gen_uri('/active/ajax_lists',$where)?>"  data-load="true">
                <ul class="list clearfix" id="more">
                        <?php foreach($list['list'] as $li){ ?>
                            <li>
                                <div class="prize-pic">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>"><img src="<?=get_img_resize_url($li['sImg'], Lib_Constants::SHARE_IMG_LIST, Lib_Constants::SHARE_IMG_LIST)?>" width="100%" alt="活动<?=$li['iActId']?>第<?=$li['iPeroid']?>期"></a>
                                    <?php if(!empty($li['iCornerMark'])){ ?>
                                        <div class="goods-tag tag-new">
                                            <?=(!empty(Lib_Constants::$corner_mark[$li['iCornerMark']]['img']) ? '' : Lib_Constants::$corner_mark[$li['iCornerMark']]['text'])?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="prize-name"><?=$li['sGoodsName']?></div>
                                <div class="list-goods-bott">
                                    <div class="list-schedule" data-lott-schedule="<?=$li['iProcess']?>%">
                                        <div class="list-progress-txt"><label>开奖进度</label><em class="progress-bubble"><?=$li['iProcess']?>%</em></div>
                                        <div class="list-progress-bar">
                                            <span class="list-progress-bar-on"></span>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" onclick="DUOBAO.addCart($(this))" class="btn btn-warning btn-add-to-cart" peroid-str="<?=period_code_encode($li['iActId'],$li['iPeroid'])?>"><span>加入清单</span></a>
                                </div>
                            </li>
                        <?php } ?>
                </ul>
            </div>
        <?php }else{ ?>
            <!-- search empty -->
            <div class="search-empty">
                <i class="icon-search-empty"></i>
                <p>暂无查询记录</p>
            </div>
        <?php } ?>

        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more">没有更多,逛逛其他吧!</div>
    </div>
</div>
<script type="text/javascript" src="<?=$resource_url?>js/list.js"></script>
<script>
    $(function(){

        DUOBAO.config = {
            'marktag' : <?=json_encode(Lib_Constants::$corner_mark)?>
        }

        $('.btn-search').click(function(){
            $("#formTopSearch").submit();
        });

        $('.list-sort>a').click(function(){
            var ordertype = $(this).find('i').next();
            //$(this).parent().find('a').removeClass('curr');
            //$(this).addClass('curr');
            var url = location.href;

            url = url.indexOf('?') == -1 ? (url+'?') : url;
            url = url.replace('&orderby='+getQueryVal('orderby'),'');
            url = url.replace('&ordertype='+getQueryVal('ordertype'),'');
            url = url+'&orderby='+$(this).attr('order-by');

            if(ordertype.hasClass('ordertype')){
                if(ordertype.hasClass('arrow-asc')){
                    ordertype.removeClass('arrow-asc').addClass('arrow-desc');
                    url = url+'&ordertype=asc';
                }else{
                    ordertype.removeClass('arrow-desc').addClass('arrow-asc');
                    url = url+'&ordertype=desc';
                }
            }

            location.href = url;
        })

        function getQueryVal(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }

        // loadMore
        DUOBAO.loadMore('#listMore',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
                $.each(rs.retData.list,function(i,li){
                    $('#listMore ul').append(listTemp(li));
                })
            }
        });

        function listTemp(li){
            var conf = DUOBAO.config.marktag;
            var html = [];
            html.push('<li>');
            html.push('<div class="prize-pic">');
            html.push('<a href="'+li.url+'"><img src="'+ li.sImg +'" width="100%" alt=""></a>');
            if(li.iCornerMark && li.iCornerMark > 0 && typeof conf[li.iCornerMark] != 'undefined'){
                if(conf[li.iCornerMark]['img'] != ''){
                    html.push('<div class="goods-tag tag-new"><img src="'+conf[li.iCornerMark]['img']+'" width="40" height="36" alt=""></div>');
                }else{
                    html.push('<div class="goods-tag tag-new">'+conf[li.iCornerMark]['text']+'</div>');
                }
            }
            html.push('</div>');
            html.push('<div class="prize-name">'+ li.sGoodsName +'</div>');
            html.push('<div class="list-goods-bott">');
            html.push('<div class="list-schedule" data-lott-schedule="'+ li.iProcess +'%">');
            html.push('<div class="list-progress-txt"><label>开奖进度</label><em class="progress-bubble">'+ li.iProcess +'%</em></div>');
            html.push('<div class="list-progress-bar">');
            html.push('<span class="list-progress-bar-on"></span>');
            html.push('</div>');
            html.push('</div>');
            html.push('<a href="javascript:void(0);" onclick="DUOBAO.addCart(this)" class="btn btn-warning btn-add-to-cart" ><span>加入清单</span></a></div>');
            html.push('</li>');

            return html.join('');
        }
    })

    DUOBAO.url = {
        'add_cart': '<?=gen_uri('/cart/ajax_add')?>'
    }
</script>