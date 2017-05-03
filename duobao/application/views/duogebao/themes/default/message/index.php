<div class="viewport v-info-center">
    <div class="info-center">
        <div class="grid-1 info-center-hd clearfix">
            <h3 class="fl">信息中心</h3>
            <a href="javascript:;" class="info-clear fr msg-clean" data-clean="<?=gen_uri('/message/clean')?>">全部已读</a>
        </div>
        <div class="info-center-con" id="message-list" data-url="<?=gen_uri('/message/fetch_list')?>" data-read="<?=gen_uri('/message/read')?>" <?=empty($res_list['count'])?'data-load="false"':''?>>
            <?php if (empty($res_list['count'])) {?>
                <div class="grid info-item clearfix mb-10 msg-item">
                    <div class="msg-content">暂无信息</div>
                </div>
            <?php } else { foreach ($res_list['list'] as $v) {?>
                <div class="grid info-item clearfix mb-10 <?=1==$v['iRead']?'msg-read':'msg-unread'?>" data-id="<?=$v['iMsgId']?>" data-url="<?=$v['sUrl']?>">
                    <div class="msg-content" ><?=$v['sContent']?></div>
                    <i class="info-time"><?=date(TIME_FORMATTER, $v['iCreateTime'])?></i>
                </div>
            <?php }}?>
        </div>
        <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
        <div id="no-more" class="no-more" style="display:none;">没有更多,逛逛其他吧!</div>
    </div>
</div>
<script type="text/javascript" src="<?=$resource_url?>js/message.js"></script>