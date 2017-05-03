<div class="viewport v-choose-prize">
    <div class="custom-process">
        <div class="grid-1 custom-process-hd clearfix">
            <h3 class="fl"><i class="icon-diamond"></i>定制私人夺宝</h3>
            <a href="<?=gen_uri('/help/index')?>" class="view-rule fr">查看规则</a>
        </div>
        <div class="grid custom-process-con">
            <ul class="clearfix">
                <li class="step-on"><i class="icon-custom-pro icon-custom-gift"></i><h3>1.选择奖品</h3></li>
                <li class="step-on"><i class="icon-custom-pro icon-custom-users"></i><h3>2.选择参与人数</h3></li>
                <li><i class="icon-custom-pro icon-custom-share-alt"></i><h3>3.定制成功<br>分享给好友参与</h3></li>
                <li><i class="icon-custom-pro icon-custom-cup"></i><h3>4.集齐人数开奖</h3></li>
            </ul>
        </div>
    </div>

    <!-- prize selected -->
    <div class="prize-selected mt-10">
        <div class="grid-1 prize-selected-hd clearfix">
            <h3 class="fl">选择奖品</h3>
            <a href="<?=gen_uri('/active_custom/choose')?>" class="reselect fr">重新选择</a>
        </div>
        <div class="grid-1 prize-selected-con">
            <div class="prize-selected-pic"><img src="<?=$item['sImg']?>" width="54" height="54" alt=""></div>
            <div class="prize-selected-info">
                <p><?=cn_substr($item['sName'], 48)?></p>
            </div>
        </div>
    </div>

    <!-- participate number -->
    <div class="parti-number-1 mt-10">
        <div class="grid-1 parti-number-hd clearfix">
            <h3>输入参与总人数</h3>
        </div>
        <div class="grid-1 parti-number-con">
            <div class="quantity-wrap" data-max="<?=ceil($item['iLowestPrice'] / 100)?>">
                <a href="javascript:;" class="quantity-decrease">-</a>
                <input type="number" class="quantity" value="<?=$default_people?>" name="num" id="codeNum">
                <a href="javascript:;" class="quantity-increase">+</a>
            </div>
            <em class="each-limit">（每人次：<span class="code-price"><?=ceil($item['iLowestPrice'] / $default_people / 100)?></span>夺宝券）</em>
            <input type="hidden" id="goodsPrice" value="<?=$item['iLowestPrice']?>">
        </div>
    </div>

    <div class="custom-mode mt-10">
        <div class="grid-1 custom-mode-hd">
            <h3>选择订制方式</h3>
        </div>
        <div class="grid-1 custom-mode-con">
            <ul class="clearfix">
                <li class="active" attr-val="1">
                    <a href="javascript:void(0)">我要公开</a>
                    <p>可以更快凑齐人数哦</p>
                </li>
                <li attr-val="0">
                    <a href="javascript:void(0)">我要私密</a>
                    <p>朋友多我任性，自己吆喝</p>
                </li>
            </ul>
            <input type="hidden" id="isOpen" name="isOpen" value="1">
        </div>
    </div>

    <!-- activity name -->
    <div class="acti-name mt-10">
        <div class="grid-1 acti-name-hd">
            <h3>输入活动名称</h3>
        </div>
        <div class="grid-1 acti-name-con">
            <input type="text" id="activeName" data-max="<?=$active_length_max?>" placeholder="请输入活动名称">
        </div>
    </div>

    <div class="bott-prize-actions">
        <a href="<?=gen_uri('/active_custom/choose')?>" class="btn-prize-action btn-prize-cancel">取消</a>
        <a href="javascript:;" class="btn-prize-action btn-prize-confirm" data-url="<?=gen_uri('/active_custom/generate')?>" data-detail="<?=gen_uri('/active/detail')?>" data-id="<?=$item['iGoodsId']?>">确认</a>
    </div>
</div>
<link rel="stylesheet" href="<?=$resource_url?>css/layer_skin_extend.css">
<script>
    $(function(){
        $('.custom-mode-con li').click(function(){
            $(this).parent().find('li').removeClass('active');
            $(this).addClass('active');
            $('#isOpen').val($(this).attr('attr-val'));
        })
    })
</script>
<script src="<?=$resource_url?>js/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$resource_url?>js/active_custom/setting.js?v=3"></script>