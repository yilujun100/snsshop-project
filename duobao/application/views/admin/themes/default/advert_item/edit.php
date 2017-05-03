<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">添加/编辑广告</h3>
</div>
<div class="form-con">
    <form class="form" id="advert_item-form">
        <div class="f-item">
            <label for="advert_position">广告位<b class="required">*</b></label>
            <select name="advert_position" id="advert_position" <?=!empty($item)?'disabled="disabled"':''?> class="<?=!empty($item)?'disabled':''?>">
                <option value="-1">请选择</option>
                <?php foreach ($position_list as $v) {?>
                    <option value="<?=$v['iPositionId']?>" <?=isset($item['iPositionId'])&&$item['iPositionId']==$v['iPositionId']?'selected="selected"':''?>><?=$v['sName']?></option>
                <?php }?>
            </select>
        </div>
        <div class="f-item f-item-tips advert-pos-tips hide">
            <label></label>
            <p><span>图片尺寸：</span><span style="margin-left: 0;" class="advert-img-size"></span></p>
        </div>
        <div class="f-item">
            <label for="advert_title">标题<b class="required">*</b></label>
            <input type="text" name="advert_title" id="advert_title" placeholder="标题" value="<?=empty($item['sTitle'])?'':$item['sTitle']?>" style="width: 600px">
        </div>
        <div class="f-item">
            <label for="advert_img">图片<b class="required">*</b></label>
            <div class="pics">
                <div class="pic-upload-item" data-img="goods_img_primary">
                    <div class="upload-pic">
                        <img src="<?=empty($item['sImg'])?'/'.$theme_dir.'images/pic_default.jpg':$item['sImg']?>" width="100" height="100">
                        <span>图片</span>
                    </div>
                    <div class="pic-actions fileinput-button" style="display: none">
                        <a href="javascript:;" class="btn-form btn-pic-modify">修改</a>
                        <input class="advert_img_file btn-upload" name="advert_img_file" type="file">
                        <em class="btn-pic-del"></em>
                    </div>
                    <div class="pic-actions fileinput-button pic-actions-add">
                        <a href="javascript:;" class="btn-form btn-pic-upload">上传</a>
                        <input name="advert_img_file"  id="advert_img_file" class="btn-upload" type="file">
                    </div>
                </div>
            </div>
            <input type="hidden" name="advert_img" id="advert_img" value="<?=empty($item['sImg'])?'':$item['sImg']?>">
        </div>
        <div class="f-item">
            <label for="advert_target">链接<b class="required">*</b></label>
            <input type="text" name="advert_target" id="advert_target" placeholder="链接" value="<?=empty($item['sTarget'])?'':$item['sTarget']?>" style="width: 600px;">
        </div>
        <div class="f-item">
            <label for="advert_desc">描述/副标题</label>
            <textarea name="advert_desc" id="advert_desc" placeholder="描述/副标题" style="width: 600px; height: 150px;"><?=empty($item['sDesc'])?'':$item['sDesc']?></textarea>
        </div>
        <div class="f-item">
            <label for="advert_sort">排序</label>
            <input type="text" name="advert_sort" id="advert_sort" placeholder="排序" value="<?=empty($item['iSort'])?'':$item['iSort']?>">
            <p class="form-item-tips">注：数值越大排序越靠前</p>
        </div>
        <div class="f-item">
            <label for="advert_begin">上线时间<b class="required">*</b></label>
            <input type="text" name="advert_begin" id="advert_begin" placeholder="上线时间" value="<?=empty($item['iBeginTime'])?date(DATE_FORMATTER):date(DATE_FORMATTER, $item['iBeginTime'])?>" class="date">
        </div>
        <div class="f-item">
            <label for="advert_end">下线时间<b class="required">*</b></label>
            <input type="text" name="advert_end" id="advert_end" placeholder="下线时间" value="<?=empty($item['iEndTime'])?date(DATE_FORMATTER, time()+86400*7):date(DATE_FORMATTER, $item['iEndTime'])?>" class="date">
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
            <input type="hidden" name="op" value="<?=empty($item)?'add':'edit'?>">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $form = $('#advert_item-form'),
            $position = $('#advert_position', $form),
            $begin = $('#advert_begin', $form),
            $end = $('#advert_end', $form),
            $upload = $('#advert_img_file', $form),
            $img = $('#advert_img', $form),
            $img_del = $('.btn-pic-del', $form),
            $cancel = $('.btn-form-cancel', $form),
            upload_url = '<?=node_url('advert_item/img_upload')?>';

        $cancel.click(function () {
            $.yRedirect('<?=node_url('advert_item/index')?>');
        });

        $begin.datepicker({
            minDate: new Date(),
            changeMonth: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function(selectedDate) {
                $end.datepicker( "option", "minDate", selectedDate );
            }
        });

        $end.datepicker({
            defaultDate: '+1w',
            minDate: new Date(),
            changeMonth: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function(selectedDate) {
                $begin.datepicker( "option", "maxDate", selectedDate );
            }
        });

        $upload.fileupload({
            url: upload_url + '<?=isset($item['iPositionId'])?'?pos='.$item['iPositionId']:''?>',
            dataType: 'json',
            done: function (event, data) {
                var $this = $(this),
                    $pic_actions = $this.parent(),
                    $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                    $pic_preview = $pic_actions.siblings('.upload-pic').children('img'),
                    res = data.result;
                if (0 === res.retCode) {
                    if ($pic_actions.hasClass('pic-actions-add')) {
                        $pic_actions.hide();
                        $pic_actions_other.show();
                    }
                    $pic_preview.attr('src', res.retData.uri);
                    $img.val(res.retData.uri);
                } else {
                    $.yError(res.retMsg, '上传失败');
                }
            }
        }).on('click', function (event) {
            var pos = $position.val();
            if (pos < 1) {
                event.preventDefault();
                $.yError('请先选择广告位');
                return false;
            }
        });

        $position.on('change', function () {
            if (-1 == $position.val()) {
                $('.advert-pos-tips').yHide();
                return;
            }
            $('.pic-upload-item .upload-pic img').attr('src', '/admin/themes/default/images/pic_default.jpg');
            $img.val('');
            $upload.fileupload('option', 'url', upload_url + '?pos=' + $position.val());
            $position.yAjax({
                url: '<?=node_url('advert_item/ad_pos')?>',
                data: {pos: $position.val()},
                success: function (data) {
                    var position;
                    if (0 === data.retCode) {
                        position = data.retData;
                        $('.advert-img-size').text(position.width + ' * ' + position.height);
                        $('.advert-pos-tips').yShow();
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        });

        $img_del.click(function () {
            var $this = $(this),
                $pic_actions = $this.parent(),
                $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                $pic_preview = $pic_actions.siblings('.upload-pic').children('img'),
                def_img = '/admin/themes/default/images/pic_default.jpg';
            $pic_preview.attr('src', def_img);
            $img.val(def_img);
            $pic_actions.hide();
            $pic_actions_other.show();
        });

        $form.validate({
            rules: {
                advert_position: {required: true, select: true},
                advert_title: {required: true, minlength: 5, maxlength: 50},
                advert_target: {required: true, minlength: 5, maxlength: 200, url: true},
                advert_desc: {maxlength: 160},
                advert_sort: {digits: true, min: 0},
                advert_begin: {required: true, date: true},
                advert_end: {required: true, date: true}
            },
            submitHandler: function () {
                var op = $form.find('input[name="op"]').val(),
                    advert_img = $img.val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if (! advert_img) {
                    $.yError('请先上传广告图片');
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('advert_item/add')?>';
                } else {
                    url = '<?=node_url('advert_item/edit')?>' + '<?=empty($item['iAdId'])?'':'/'.$item['iAdId']?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('advert_item/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
    });
</script>
