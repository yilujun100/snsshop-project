<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">晒单详情</h3>
</div>
<div class="form-con">
    <form class="form" id="share-form">
        <div class="f-item">
            <label for="shareContent">中奖订单ID</label>
            <span class="f-value"><?=empty($period['sWinnerOrder'])?'':$period['sWinnerOrder']?></span>
        </div>
        <div class="f-item">
            <label for="shareContent">中奖时间</label>
            <span class="f-value"><?=empty($period['iLotTime'])?'':date(TIME_FORMATTER, $period['iLotTime'])?></span>
        </div>
        <div class="f-item">
            <label for="shareContent">类别</label>
            <span class="f-value"><?=empty($period['iActType'])?'':Lib_Constants::$active_type[$period['iActType']]?></span>
        </div>
        <div class="f-item">
            <label for="shareContent">夺宝单ID</label>
            <span class="f-value"><?=empty($period['iActId'])?'':$period['iActId']?></span>
        </div>
        <div class="f-item">
            <label for="shareContent">机器人昵称</label>
            <span class="f-value"><?=empty($period['sWinnerNickname'])?'':$period['sWinnerNickname']?></span>
        </div>
        <div class="f-item">
            <label for="shareContent">机器人 Uin</label>
            <span class="f-value"><?=empty($period['iWinnerUin'])?'':$period['iWinnerUin']?></span>
        </div>
        <div class="form-hd clearfix"></div>
        <div class="f-item">
            <label for="shareContent">晒单文字</label>
            <span class="f-value"><?=empty($item['sContent'])?'':$item['sContent']?></span>
        </div>
        <div class="f-item">
            <label for="shareImg">晒单图</label>
            <div class="pics">
                <?php $amount = count($item['share_img']); for($i=1; $i <= $amount; $i ++) {?>
                    <div class="pic-upload-item" data-img="share_img<?=$i?>">
                        <div class="upload-pic">
                            <img src="<?=empty($item['share_img'][strval($i)])?'/'.$theme_dir.'images/pic_default.jpg':$item['share_img'][strval($i)]?>" width="100" height="100" alt="">
                            <span>图<?=cn_int($i)?></span>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
        <div class="f-item">
            <label for="onlineTime">发布时间</label>
            <span class="f-value"><?=empty($item['iOnlineTime'])?'':date(TIME_FORMATTER, $item['iOnlineTime'])?></span>
        </div>
        <div class="f-item">
            <label></label>
            <button type="button" class="btn-form btn-back">返回</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        $('.btn-back').click(function () {
            $.yBack()
        });
    });
</script>
