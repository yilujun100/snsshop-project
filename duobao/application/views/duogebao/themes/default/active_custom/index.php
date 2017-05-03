<style>
.lott-schedule { padding: 0; }
.progress-bar { width: 100%; }
.progress-bar .progress-bar-on { background: #f9a505; }
.record-item .record-core-opt a { display: block; height: 32px; line-height: 32px; text-align: center; border: 1px solid #f03e3c; font-size: 13px; color: #f03e3c; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
.record-item .record-core-cell-4 { border: 0; background: #f3f3f3; }
.record-item .record-core-cell-4 p { margin: 0; font-size: 11px; color: #999; }
.record-item .record-core-cell-4 b { color: #999; }
.record-item .record-core-cell-3 .record-countDown .countDown { color: #f03e3c; }
.record-item .prize-pic label.label-record { position: absolute; bottom: 0; left: 0; width: 100%; height: 24px; line-height: 24px; text-align: center; font-size: 12px; color: #fff; border: 0; -webkit-border-radius: 0; -moz-border-radius: 0; border-radius: 0; }
.label-record:before,
.label-record:after { border-width: 0; }
.label-success { background: rgba(58, 201, 57, .88); }
.tag-prize-win { position: absolute; top: -51px; left: -50px; display: block; width: 0; height: 0; border-style: dashed dashed solid; border-width: 40px; border-color: transparent transparent #f03e3c; -webkit-transform: rotate(-45deg); -moz-transform: rotate(-45deg); transform: rotate(-45deg); padding: 0; background: none; -webkit-border-radius: 0; -moz-border-radius: 0; border-radius: 0; }
.tag-prize-win:after { position: absolute; top: 20px; left: -25px; display: block; width: 57px; content: '恭喜中奖'; font-size: 12px; color: #fff; }
.tag-prize-win:before { border-width: 0; }
.record-item .prize-detail { position: absolute; bottom: -26px; left: 0; display: block; width: 100%; height: 30px; line-height: 30px; text-align: center; font-size: 12px; color: #f03e3c; background: none; }
.record-item .prize-detail:after { border-width: 0; position: absolute; top: 11px; right: 10px; display: block; content: ''; width: 6px; height: 6px; border-top: 1px solid #f03e3c; border-right: 1px solid #f03e3c; -webkit-transform: rotate(45deg); -moz-transform: rotate(45deg); transform: rotate(45deg); }
.record-item .record-core-opt-1 a { display: inline-block; width: 120px; height: 32px; line-height: 32px; text-align: center; border: 1px solid #f03e3c; font-size: 13px; color: #f03e3c; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
</style>


<div class="viewport v-india-custom">
    <div class="india-custom">
        <p>婚礼没互动？晚会没高潮? Party不刺激？<br>定制您的私人夺宝，秒秒钟High起来</p>
        <a href="<?=gen_uri('/help/index', array('item'=>'rules_custom'))?>" class="custom-rules">查看规则</a>
        <a href="<?=gen_uri('/active_custom/choose')?>" class="btn-custom">立即定制夺宝</a>
    </div>

    <div class="tab tab-1 tab-custom" id="tabIndianaCustom">
        <div class="grid tab-tit clearfix" id="actCustomTab">
            <a data-url="<?=gen_uri('/active_custom/ajax_my_list')?>" href="javascript:;" class="tab-active">全部定制</a>
            <a data-url="<?=gen_uri('/active_custom/ajax_my_list', array('win'=>1))?>" href="javascript:;">中奖纪录</a>
        </div>
        <div class="tab-con" id="actCustomCon" data-resource="<?=$resource_url?>">
            <!-- 全部定制 -->
            <div class="custom-all <?=$result_data['count'] < 1?'empty':''?>" style="display: block;">
                <?php if (empty($result_data) || $result_data['count'] < 1) {?>
                    <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                    <h3>暂无相关记录，敬请期待哦~</h3>
                <?php } else { foreach ($result_data['list'] as $v) {?>
                    <div class="grid-1 record-item">
                        <div class="prize-pic">
                            <img src="<?=$v['sImg']?>" width="84" height="84" alt="">
                            <?php if (Lib_Constants::ACTIVE_LOT_ING == $v['iLotState']) {?>
                                <label class="label-record label-success">即将揭晓</label>
                            <?php } else if (Lib_Constants::ACTIVE_LOT_DONE == $v['iLotState'] && $v['iWinnerUin'] == $user['uin']) {?>
                                <em class="tag-prize-win"></em>
                                <!--<a href="<?/*=$v['detailUrl']*/?>" class="prize-detail">奖品详情</a>-->
                            <?php } else {?>
                                <label class="corner corner-custom">定制</label>
                            <?php }?>
                        </div>
                        <div class="record-core-cell-1">
                            <div class="prize-name"><?=cn_substr($v['sGoodsName'], 20)?></div>
                            <div class="record-core-cell-2 clearfix">
                                <em class="fl">期号：<?=$v['periodNum']?></em>
                                <?php if (Lib_Constants::ACTIVE_LOT_NOT == $v['iLotState']) {?>
                                <span class="fr"><?=$v['iProcess']?>%</span>
                                <?php }?>
                            </div>
                            <?php if (Lib_Constants::ACTIVE_LOT_NOT == $v['iLotState']) {?>
                            <div class="lott-schedule" data-lott-schedule="<?=$v['iProcess']?>%">
                                <div class="progress-bar">
                                    <span class="progress-bar-on" style="width: <?=$v['iProcess']?>%;"></span>
                                </div>
                            </div>
                            <?php }?>
                            <div class="record-core-cell-3 clearfix">
                                <?php if (Lib_Constants::ACTIVE_LOT_ING != $v['iLotState']) {?>
                                    <span class="fl">您已参与<strong><?=$v['joinCount']?></strong>人次</span>
                                <?php } else {?>
                                    <!--<span class="fl record-countDown">揭晓倒计时：14:34:133</span>-->
                                    <div class="fl record-countDown">揭晓倒计时：<div class="countDown" data-start-time="<?=$v['sCurTime']?>" data-end-time="<?=$v['sEndTime']?>">14:34:133<span class="ms"></span></div></div>
                                <?php }?>
                                <?php if (Lib_Constants::ACTIVE_LOT_NOT != $v['iLotState']) {?>
                                    <a href="<?=$v['detailUrl']?>" class="btn-parti-detail fr">参与详情</a>
                                <?php }?>
                            </div>
                            <?php if (Lib_Constants::ACTIVE_LOT_NOT == $v['iLotState']) {?>
                            <div class="record-core-opt clearfix">
                                <a href="<?=$v['detailUrl']?>">参与</a>
                                <a href="<?=$v['shareUrl']?>">分享</a>
                            </div>
                            <?php } else if (Lib_Constants::ACTIVE_LOT_DONE == $v['iLotState']) {?>
                                <div class="record-core-cell-4">
                                    <p>获奖者：<b><?=$v['sWinnerNickname']?></b></p>
                                    <p>幸运码：<strong><?=$v['sWinnerCode']?></strong></p>
                                    <p>本期购买：<strong><?=$v['iWinnerCount']?></strong>人次</p>
                                    <p>揭晓时间：<b><?=date(TIME_FORMATTER, $v['iLotTime'])?></b></p>
                                </div>
                                <?php if ($v['iWinnerUin'] == $user['uin']) {?>
                                    <div class="record-core-opt-1 clearfix">
                                        <a href="<?=$v['winDetailUrl']?>">查询中奖详情</a>
                                    </div>
                                <?php }?>
                            <?php }?>
                        </div>
                    </div>
                <?php }}?>
            </div>
            <div class="custom-win empty" style="display: none;">
                <img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt="">
                <h3>暂无相关记录，敬请期待哦~</h3>
            </div>
        </div>
        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more" style="display:none;">没有更多,逛逛其他吧!</div>
    </div>
</div>
<script type="text/javascript" src="<?=$resource_url?>js/active_custom/index.js"></script>
