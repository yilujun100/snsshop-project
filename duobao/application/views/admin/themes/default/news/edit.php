<div class="form-hd clearfix">
    <h3 class="form-tit">添加/编辑信息</h3>
</div>
<div class="form-con">
    <form class="form" id="news-form">
        
        <div class="f-item">
            <label for="sTitle">标题</label>
            <input style="width: 600px" type="text" name="sTitle" id="sTitle" placeholder="标题" value="<?=empty($news['sTitle'])?'':$news['sTitle']?>">
        </div>

        <div class="f-item"></div>
        <div class="f-item"></div>
        <div class="f-item">
            <label for="newsIntro">图片</label>
            <div class="pics">
                <div class="pic-upload-item" data-img="news_img_primary">
                    <div class="upload-pic">
                        <img src="<?=empty($news['sImg'])?'/'.$theme_dir.'images/pic_default.jpg':$news['sImg']?>" width="100" height="100" alt="">
                        <span>1:1 主图</span>
                    </div>
                    <div class="pic-actions fileinput-button" <?=empty($news['sImg'])?'style="display: none"':''?>>
                        <a href="javascript:;" class="btn-form btn-pic-modify">修改</a>
                        <input class="btn-goods-pic-upload" type="file" name="news_img">
                        <em class="btn-pic-del"></em>
                    </div>
                    <div class="pic-actions fileinput-button pic-actions-add" <?=empty($news['sImg'])?'':'style="display: none"'?>>
                        <a href="javascript:;" class="btn-form btn-pic-upload">上传</a>
                        <input class="btn-goods-pic-upload" type="file" name="news_img">
                    </div>

                </div>

            </div>
            <input type="hidden" id="news_img_primary" name="news_img_primary" value="<?=empty($news['sImg'])?'':$news['sImg']?>">
        </div>
        <div class="f-item"></div>
        <div class="f-item"></div>
        <div class="f-item">
            <label for="sContent">图文详情</label>
            <script type="text/plain" id="sContent" style="width:960px;height:500px;"></script>
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
            <input type="hidden" name="op" value="<?=empty($news)?'add':'edit'?>">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var ue = UE.getEditor('sContent', {textarea: 'sContent'}),
            $form = $('#news-form'),
            $cancel = $('.btn-form-cancel'),
            $btn_img_upload = $('.btn-goods-pic-upload'),
            $btn_img_del = $('.btn-pic-del'),
            validator;
        $btn_img_upload.each(function () {
            var $this = $(this),
                $pic_actions = $(this).parent(),
                $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                $pic_preview = $pic_actions.siblings('.upload-pic').children('img');

            $this.fileupload({
                url: '<?=node_url('news/img_upload')?>',
                formData : {'path' : 'news'},
                dataType: 'json',
                done: function (e, data) {
                    var res = data.result;
                    if (res.error==0) {
                        if ($pic_actions.hasClass('pic-actions-add')) {
                            $pic_actions.hide();
                            $pic_actions_other.show();
                        }
                        $pic_preview.attr('src', res.url);
                        $('#news_img_primary').val(res.url);
                    } else {
                            $.yError(res.msg, '上传失败');
                    }
                }
            });
        });
        $btn_img_del.click(function () {
            var $this = $(this),
                $pic_actions = $(this).parent(),
                $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                $pic_preview = $pic_actions.siblings('.upload-pic').children('img'),
                def_img = '';
            $pic_preview.attr('src', def_img);
            $('#news_img_primary').val(def_img);
            $pic_actions.hide();
            $pic_actions_other.show();
        });

        validator = $form.validate({
            rules: {
                sTitle: {required: true, maxlength: 60}
            },
            messages: {
                sTitle: {required: '「标题」不能为空', maxlength: '「标题」不超过60个字符'}
            },
            submitHandler: function () {
                var op = $form.find('input[name="op"]').val(),
                    primary_img = $form.find('input[name="news_img_primary"]').val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if (! primary_img) {
                    $.yError('请上传「图片」');
                    return;
                }
                if (! ue.getContent().replace(/\s*!/, '')) {
                    $.yError('请先填写「图文详情」');
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('news/add')?>';
                } else {
                    url = '<?=node_url('news/edit')?>' + '/<?=empty($news['iNewsId'])?'':$news['iNewsId']?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('news/index')?>');
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
        $cancel.click(function () {
            $.yRedirect('<?=node_url('news/index')?>');
        });
        var content = '<?=empty($news['sContent']) ? '' : $news['sContent']?>';
        ue.ready(function() {
            if (content) {
                ue.setContent(content);
            }
        });

    });
</script>
