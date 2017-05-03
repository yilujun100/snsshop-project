<style>
.btn-keyword-clear { position: absolute;
    top: 13px;
    right: 50px;
    display: block;
    width: 20px;
    height: 20px;
    text-indent: -999em;
    background-repeat: no-repeat;
    background-image: url(<?=$resource_url?>images/sprite_icons.png);
    background-size: 200px 300px;    
    background-position: -132px -88px; }
/*====================== indiana custom style =======================*/
.v-india-custom { padding-bottom: 72px; }
.india-custom { padding: 30px 0; }
.india-custom p { margin: 0; text-align: center; font-size: 15px; color: #333; }
.india-custom .custom-rules { position: relative; display: block;  margin-top: 20px; padding-right: 20px; text-align: right; font-size: 13px; color: #f03e3c; }
.india-custom .custom-rules:after { position: absolute; top: 5px; right: 12px; display: block; content: ''; width: 6px; height: 6px; border-top: 1px solid #f03e3c; border-right: 1px solid #f03e3c; -webkit-transform: rotate(45deg); -moz-transform: rotate(45deg); transform: rotate(45deg); }
.india-custom .btn-custom { clear: both; }


/*====================== choose prize style =======================*/
.v-choose-prize { padding-bottom: 63px; }
.custom-process .custom-process-hd { border-bottom: 1px solid #cdcdcd; }
.custom-process .custom-process-hd h3 { font-size: 13px; color: #333; }
.custom-process .custom-process-hd h3 i.icon-diamond { display: inline-block; vertical-align: -5px; margin-right: 4px; width: 22px; height: 22px; }
.custom-process .custom-process-hd .view-rule { position: relative; padding-right: 10px; line-height: 22px; font-size: 12px; color: #017aff; }
.custom-process .custom-process-hd .view-rule:after { position: absolute; top: 7px; right: 2px; display: block; content: ''; width: 6px; height: 6px; border-top: 1px solid #017aff; border-right: 1px solid #017aff; -webkit-transform: rotate(45deg); -moz-transform: rotate(45deg); transform: rotate(45deg); }
.custom-process .custom-process-con { padding: 10px 0; }
.custom-process-con li { float: left; width: 25%; }
.custom-process-con li i.icon-custom-pro { display: block; margin: 0 auto 4px; width: 36px; height: 36px; background-color: #f3f3f3; -webkit-border-radius: 50%; -moz-border-radius: 50%; border-radius: 50%; }
.custom-process-con li h3 { text-align: center; font-size: 12px; color: #666; }
.custom-process-con li.step-on h3 { color: #f03e3c; }

.search-prize { position: relative; padding: 8px; display: -webkit-box; display: -moz-box; display: box; }
.search-prize .search-prize-keyword { display: block; padding: 4px; border: 0; height: 22px; line-height: 22px; font-size: 13px; color: #333; -webkit-box-flex: 1; -moz-box-flex: 1; box-flex: 1; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; }
.search-prize .btn-search-prize { display: block; margin-left: 10px; width: 64px; height: 30px; line-height: 30px; text-align: center; font-size: 13px;     background: #f03e3c;
    color: #fff;    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px; }

.tab-choose-prize .tab-tit { position: relative; width: 100%; height: 42px;  /*border-bottom: 1px solid #cdcdcd; overflow: hidden;*/ }
/*.tab-choose-prize .tab-tit:before { position: absolute; bottom: 0; left: 0; display: block; content: ''; width: 100%; height: 1px; border-bottom: 1px solid #cdcdcd; }*/
.tab-choose-prize .tab-tit a { position: relative; /*float: left;*/ display: block; /*margin-left: 12px; padding: 0 4px;*/ text-align: center; line-height: 42px; border-bottom: 1px solid #cdcdcd; font-size: 13px; color: #333; }
.tab-choose-prize .tab-tit a.tab-active { color: #f03e3c; }
.tab-choose-prize .tab-tit a.tab-active:after { position: absolute; bottom: 0; left: 0; display: block; content: ''; width: 100%; height: 2px; border-bottom: 2px solid #f03e3c; }
.tab-choose-prize .tab-con { padding: 10px; background: #f3f3f3; }
.tab-choose-prize .tab-con ul { margin-top: -10px; }
.tab-choose-prize .tab-con li { position: relative; float: left; margin-top: 10px; padding: 6px; margin-left: 4%; width: 48%; background: #fff; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
.tab-choose-prize .tab-con li:nth-child(2n-1) { margin-left: 0; }
.tab-choose-prize .tab-con li .prize-name { height: 30px; line-height: 30px; text-align: center; font-size: 13px; color: #333; overflow: hidden; }
.tab-choose-prize .tab-con li .btn-prize-opera { display: block; width: 100%; height: 30px; line-height: 30px; text-align: center; border-width: 1px; border-style: solid; font-size: 13px; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
/*.btn-prize-selected { background: #f03e3c; border-color: #f03e3c; color: #fff; }*/
.btn-prize-choose { background: #fff; border-color: #f03e3c; color: #f03e3c; }

.bott-prize-actions { position: fixed; bottom: 0; left: 0; z-index: 100; padding: 10px; width: 100%; border-top: 1px solid #cdcdcd; background: #fff; display: -webkit-box; display: -moz-box; display: box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
.bott-prize-actions .btn-prize-action { display: block; float: none; margin-left: 10px; width: 46%; height: 40px; line-height: 40px; text-align: center; font-size: 15px; border-width: 1px; border-style: solid; -webkit-box-flex: 1; -moz-box-flex: 1; box-flex: 1; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; }
.btn-prize-cancel,
.btn-parti-immedia { margin-left: 0 !important; border-color: #333; color: #333; }
.btn-prize-confirm,
.btn-custom-share { border-color: #f03e3c; background: #f03e3c; color: #fff; } 

.selected-box { position: absolute; top: 0; left: 0; z-index: 10; display: block; width: 100%; height: 100%; border: 3px solid #f03e3c; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
.selected-box em.icon-selected { position: absolute; bottom: -1px; right: -1px; z-index: 10; display: block; width: 30px; height: 30px; }
</style>

<div class="viewport v-choose-prize">
    <div class="custom-process">
        <div class="grid-1 custom-process-hd clearfix">
            <h3 class="fl"><i class="icon-diamond"></i>定制私人夺宝</h3>
            <a href="<?=gen_uri('/help/index', array('item'=>'rules_custom'))?>" class="view-rule fr">查看规则</a>
        </div>
        <div class="grid custom-process-con">
            <ul class="clearfix">
                <li class="step-on"><i class="icon-custom-pro icon-custom-gift"></i><h3>1.选择奖品</h3></li>
                <li><i class="icon-custom-pro icon-custom-users"></i><h3>2.选择参与人数</h3></li>
                <li><i class="icon-custom-pro icon-custom-share-alt"></i><h3>3.定制成功<br>分享给好友参与</h3></li>
                <li><i class="icon-custom-pro icon-custom-cup"></i><h3>4.集齐人数开奖</h3></li>
            </ul>
        </div>
    </div>

    <div class="search-prize">
        <input type="text" class="search-prize-keyword" id="goodsKey" name="k" value="" placeholder="请输入搜索关键词">
        <a href="javascript:;" class="btn-keyword-clear" id="search-clean" style="display: none; right: 86px;">清除关键字</a>
        <a href="javascript:;" class="btn-search-prize" id="search-do">搜索奖品</a>
    </div>

    <div class="grid tab-choose-prize swiper-container" id="swiperCategory">
        <div class="tab-tit swiper-wrapper clearfix" id="actChooseTab">
            <a data-base="<?=gen_uri('/active_custom/choose_list')?>" data-url="<?=gen_uri('/active_custom/choose_list')?>" href="javascript:;" class="swiper-slide tab-active">全部</a>
            <?php foreach ($category_list as $v) {?>
                <a data-base="<?=gen_uri('/active_custom/choose_list',array('cate'=>$v['iCateId']))?>" data-url="<?=gen_uri('/active_custom/choose_list',array('cate'=>$v['iCateId']))?>" href="javascript:;" class="swiper-slide"><?=$v['sName']?></a>
            <?php }?>
        </div>
        <div class="tab-con" id="actChooseCon">
            <!-- 全部 -->
            <div class="category-all <?=(empty($result_data) || $result_data['count'] < 1)?'empty':''?>" style="display: block;">
                <?php if (empty($result_data) || $result_data['count'] < 1) {?>
                    <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                    <h3>暂无相关记录，敬请期待哦~</h3>
                <?php } else { ?>
                <ul class="clearfix">
                    <?php foreach ($result_data['list'] as $v) {?>
                        <li data-id="<?=$v['iGoodsId']?>">
                            <div class="prize-pic">
                                <img src="<?=$v['sImg']?>" width="100%" alt="">
                            </div>
                            <div class="prize-name"><?=cn_substr($v['sName'], 11)?></div>
                            <div class="prize-actions">
                                <a href="javascript:;" class="btn-prize-opera btn-prize-choose">选择</a>
                            </div>
                        </li>
                    <?php }?>
                </ul>
                <?php }?>
            </div>
            <?php foreach ($category_list as $v) {?>
            <div class="empty">
                <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                <h3>暂无相关记录，敬请期待哦~</h3>
            </div>
            <?php }?>
        </div>
        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more" style="display:none;">没有更多,逛逛其他吧!</div>
    </div>
    <div class="bott-prize-actions">
        <a href="javascript:;" class="btn-prize-action btn-prize-cancel">取消</a>
        <a href="javascript:;" class="btn-prize-action btn-prize-confirm">确认奖品</a>
        <input type="hidden" id="settingBase" value="<?=gen_uri('/active_custom/setting/')?>">
    </div>
</div>
<link rel="stylesheet" href="<?=$resource_url?>css/layer_skin_extend.css">
<script src="<?=$resource_url?>js/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$resource_url?>js/active_custom/choose.js"></script>