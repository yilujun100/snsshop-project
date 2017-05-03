<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">添加/编辑晒单</h3>
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
            <textarea name="shareContent" id="shareContent" placeholder="晒单内容"><?=empty($item['sContent'])?'':$item['sContent']?></textarea>
            <p class="form-item-tips">不少于10个字，最多500字</p>
        </div>
        <div class="f-item">
            <label for="shareImg">晒单图<b class="required">*</b></label>
            <div class="pics">
                <?php for($i=1; $i < 6; $i ++) {?>
                    <div class="pic-upload-item" data-img="share_img<?=$i?>">
                        <div class="upload-pic">
                            <img src="<?=empty($item['share_img'][strval($i)])?'/'.$theme_dir.'images/pic_default.jpg':$item['share_img'][strval($i)]?>" width="100" height="100" alt="">
                            <span>图<?=cn_int($i)?></span>
                        </div>
                        <div class="pic-actions fileinput-button" style="display: none">
                            <a href="javascript:;" class="btn-form btn-pic-modify">修改</a>
                            <input class="btn-pic-upload" type="file" name="upload_img">
                            <em class="btn-pic-del"></em>
                        </div>
                        <div class="pic-actions fileinput-button pic-actions-add">
                            <a href="javascript:;" class="btn-form btn-pic-upload">上传</a>
                            <input class="btn-pic-upload" type="file" name="upload_img">
                        </div>
                    </div>
                <?php }?>
                <p class="form-item-tips">图一必传；每张图片大小不超过1M，宽度不小于320px，不大于960px，高度不大于960px</p>
            </div>
            <input type="hidden" name="share_img1" value="<?=empty($item['share_img']['1'])?'':$item['share_img']['1']?>">
            <input type="hidden" name="share_img2" value="<?=empty($item['share_img']['2'])?'':$item['share_img']['2']?>">
            <input type="hidden" name="share_img3" value="<?=empty($item['share_img']['3'])?'':$item['share_img']['3']?>">
            <input type="hidden" name="share_img4" value="<?=empty($item['share_img']['4'])?'':$item['share_img']['4']?>">
            <input type="hidden" name="share_img5" value="<?=empty($item['share_img']['5'])?'':$item['share_img']['5']?>">
        </div>
        <div class="f-item">
            <label for="onlineTime">发布时间<b class="required">*</b></label>
            <span>
                <p><input type="radio" name="onlineType" value="1" checked="checked"><label>立即发布</label></p>
                <p><input type="radio" name="onlineType" value="2"><label>定时发布</label></p>
                <p><input type="text" name="onlineTime" id="onlineTime" class="date datetime" value="<?=empty($item['iOnlineTime'])?date(TIME_FORMATTER,strtotime('+1 hour')):date(TIME_FORMATTER, $item['iOnlineTime'])?>" style="background-position: 155px center;"></p>
            </span>
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
            <input type="hidden" name="op" value="<?=empty($item)?'add':'edit'?>">
            <input type="hidden" name="act" value="<?=empty($period['iActId'])?'':$period['iActId']?>">
            <input type="hidden" name="period" value="<?=empty($period['iPeroid'])?'':$period['iPeroid']?>">
            <input type="hidden" name="share_id" value="<?=empty($share_id)?'':$share_id?>">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $form = $('#share-form'),
            $online_time = $('#onlineTime'),
            $cancel = $('.btn-form-cancel'),
            $btn_img_upload = $('.btn-pic-upload'),
            $btn_img_del = $('.btn-pic-del');

        $online_time.datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });

        $btn_img_upload.each(function () {
            var $this = $(this),
                $pic_actions = $(this).parent(),
                $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                $pic_preview = $pic_actions.siblings('.upload-pic').children('img'),
                $data_img = $this.closest('.pic-upload-item').attr('data-img');
            $this.fileupload({
                url: '<?=node_url('robot/share_upload_img')?>',
                dataType: 'json',
                done: function (e, data) {
                    var res = data.result;
                    if (0 == res.retCode) {
                        if ($pic_actions.hasClass('pic-actions-add')) {
                            $pic_actions.hide();
                            $pic_actions_other.show();
                        }
                        $pic_preview.attr('src', res.retData.uri);
                        $form.find('[name="' + $data_img + '"]').val(res.retData.uri);
                    } else {
                        $.yError(res.retMsg, '上传失败');
                    }
                }
            });
        });
        $btn_img_del.click(function () {
            var $this = $(this),
                $pic_actions = $(this).parent(),
                $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                $pic_preview = $pic_actions.siblings('.upload-pic').children('img'),
                $data_img = $this.closest('.pic-upload-item').attr('data-img'),
                def_img = '/admin/themes/default/images/pic_default.jpg';
            $pic_preview.attr('src', def_img);
            $form.find('[name="' + $data_img + '"]').val('');
            $pic_actions.hide();
            $pic_actions_other.show();
        });

        $form.validate({
            rules: {
                onlineTime: {onlineTime: true},
                shareContent: {required: true, maxlength: 500, minlength: 10}
            },
            messages: {
                shareContent: {required: '「晒单内容」不能为空', 'maxlength': '「晒单内容」不超过512个字符'}
            },
            submitHandler: function () {
                var op = $form.find('input[name="op"]').val(),
                    share_img1 = $form.find('input[name="share_img1"]').val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if (! share_img1 || /pic_default/.test(share_img1)) {
                    $.yError('必须上传图一');
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('robot/share_add')?>';
                } else {
                    url = '<?=node_url('robot/share_edit')?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('robot/share')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
        $cancel.click(function () {
            $.yRedirect('<?=node_url('robot/share')?>');
        });

        $.validator.addMethod('onlineTime', function (value) {
            if (1 == $(':radio[name="onlineType"]:checked', $form).val()) {
                return value && /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/.test(value);
            }
            return true;
        }, '请选择正确的「发布时间」');
    });
</script>
