<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="form-hd clearfix">
    <h3 class="form-tit">添加/编辑商品</h3>
</div>
<div class="form-con">
    <form class="form" id="goods-form">
        <?php $this->widget('category', empty($goods['cate']) ? array('top_cate'=>$top_cate) : array_merge(array('top_cate'=>$top_cate), $goods['cate']))?>
        <div class="f-item">
            <label for="goodsName">商品名称<b class="required">*</b></label>
            <input style="width: 600px" type="text" name="goodsName" id="goodsName" placeholder="商品名称" value="<?=empty($goods['sName'])?'':$goods['sName']?>">
        </div>
        <div class="f-item">
            <label for="goodsType">商品类型<b class="required">*</b></label>
            <select name="goodsType">
                <?php foreach (Lib_Constants::$goods_type as $k => $v) {?>
                    <option value="<?=$k?>" <?=isset($goods['iType'])&&$k==$goods['iType']?'selected="selected"':''?>><?=$v?></option>
                <?php }?>
            </select>
        </div>
        <div class="f-item">
            <label for="goodsCostPrice">成本价<b class="required">*</b></label>
            <input type="text" name="goodsCostPrice" id="goodsCostPrice" placeholder="成本价" value="<?=empty($goods['iCostPrice'])?'':price_format($goods['iCostPrice'])?>">
        </div>
        <div class="f-item">
            <label for="goodsLowestPrice">最低售价<b class="required">*</b></label>
            <input type="text" name="goodsLowestPrice" id="goodsLowestPrice" placeholder="最低售价" value="<?=empty($goods['iLowestPrice'])?'':price_format($goods['iLowestPrice'])?>">
        </div>
        <div class="f-item">
            <label for="goodsIntro">简介</label>
            <textarea name="goodsIntro" id="goodsIntro" cols="30" rows="10" placeholder="简介"><?=empty($goods['sIntro'])?'':$goods['sIntro']?></textarea>
        </div>
        <div class="f-item"></div>
        <div class="f-item"></div>
        <div class="f-item">
            <label for="goodsIntro">商品图片<b class="required">*</b></label>
            <div class="pics">
                <div class="pic-upload-item" data-img="goods_img_primary">
                    <div class="upload-pic">
                        <img src="<?=empty($goods['sImg'])?'/'.$theme_dir.'images/pic_default.jpg':$goods['sImg']?>" width="100" height="100" alt="">
                        <span>640*640 主图</span>
                    </div>
                    <div class="pic-actions fileinput-button" <?=empty($goods['sImg'])?'style="display: none"':''?>>
                        <a href="javascript:;" class="btn-form btn-pic-modify">修改</a>
                        <input class="btn-goods-pic-upload" type="file" name="goods_img">
                        <em class="btn-pic-del"></em>
                    </div>
                    <div class="pic-actions fileinput-button pic-actions-add" <?=empty($goods['sImg'])?'':'style="display: none"'?>>
                        <a href="javascript:;" class="btn-form btn-pic-upload">上传</a>
                        <input class="btn-goods-pic-upload" type="file" name="goods_img">
                    </div>
                </div>
                <?php for($i=1; $i < 6; $i ++) {?>
                    <div class="pic-upload-item" data-img="goods_img_ext<?=$i?>">
                        <div class="upload-pic">
                            <img src="<?=empty($goods['img_ext'][strval($i)])?'/'.$theme_dir.'images/pic_default.jpg':$goods['img_ext'][strval($i)]?>" width="100" height="100" alt="">
                            <span>640*640 副图<?=cn_int($i)?></span>
                        </div>
                        <div class="pic-actions fileinput-button" style="display: none">
                            <a href="javascript:;" class="btn-form btn-pic-modify">修改</a>
                            <input class="btn-goods-pic-upload" type="file" name="goods_img">
                            <em class="btn-pic-del"></em>
                        </div>
                        <div class="pic-actions fileinput-button pic-actions-add">
                            <a href="javascript:;" class="btn-form btn-pic-upload">上传</a>
                            <input class="btn-goods-pic-upload" type="file" name="goods_img">
                        </div>
                    </div>
                <?php }?>
            </div>
            <input type="hidden" name="goods_img_primary" value="<?=empty($goods['sImg'])?'':$goods['sImg']?>">
            <input type="hidden" name="goods_img_ext1" value="<?=empty($goods['img_ext']['1'])?'':$goods['img_ext']['1']?>">
            <input type="hidden" name="goods_img_ext2" value="<?=empty($goods['img_ext']['2'])?'':$goods['img_ext']['2']?>">
            <input type="hidden" name="goods_img_ext3" value="<?=empty($goods['img_ext']['3'])?'':$goods['img_ext']['3']?>">
            <input type="hidden" name="goods_img_ext4" value="<?=empty($goods['img_ext']['4'])?'':$goods['img_ext']['4']?>">
            <input type="hidden" name="goods_img_ext5" value="<?=empty($goods['img_ext']['5'])?'':$goods['img_ext']['5']?>">
        </div>
        <div class="f-item"></div>
        <div class="f-item"></div>
        <div class="f-item">
            <label for="goodsContent">图文详情<b class="required">*</b></label>
            <script type="text/plain" id="goodsContent" style="width:960px;height:500px;"></script>
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
            <input type="hidden" name="op" value="<?=empty($goods)?'add':'edit'?>">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var ue = UE.getEditor('goodsContent', {textarea: 'goodsContent'}),
            $form = $('#goods-form'),
            $cancel = $('.btn-form-cancel'),
            $btn_img_upload = $('.btn-goods-pic-upload'),
            $btn_img_del = $('.btn-pic-del');
        $btn_img_upload.each(function () {
            var $this = $(this),
                $pic_actions = $(this).parent(),
                $pic_actions_other = $pic_actions.siblings('.pic-actions'),
                $pic_preview = $pic_actions.siblings('.upload-pic').children('img'),
                $data_img = $this.closest('.pic-upload-item').attr('data-img');
            $this.fileupload({
                url: '<?=node_url('goods/img_upload')?>',
                dataType: 'json',
                done: function (e, data) {
                    var res = data.result;
                    if (0 === res.retCode) {
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
            $form.find('[name="' + $data_img + '"]').val(def_img);
            $pic_actions.hide();
            $pic_actions_other.show();
        });

        $form.validate({
            rules: {
                cateLvl1: {required: true, min: 1},
                goodsName: {required: true, maxlength: 60},
                goodsCostPrice: {required: true, price: true},
                goodsLowestPrice: {required: true, price: true, lowestPrice: true},
                goodsIntro: {maxlength: 200}
            },
            messages: {
                cateLvl1: {required: '请选择「商品类目」', min: '请选择「商品类目」'},
                goodsName: {required: '「商品名称」不能为空', maxlength: '「商品名称」不超过60个字符'},
                goodsCostPrice: {required: '「成本价」不能为空'},
                goodsLowestPrice: {required: '「最低售价」不能为空'},
                goodsIntro: {maxlength: '「简介」不超过200个字符'}
            },
            submitHandler: function () {
                var op = $form.find('input[name="op"]').val(),
                    primary_img = $form.find('input[name="goods_img_primary"]').val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if (! primary_img || /pic_default/.test(primary_img)) {
                    $.yError('请先上传商品「主图」');
                    return;
                }
                if (! ue.getContent().replace(/\s*!/, '')) {
                    $.yError('请先填写商品「图文详情」');
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('goods/add')?>';
                } else {
                    url = '<?=node_url('goods/edit')?>' + '/<?=empty($goods['iGoodsId'])?'':$goods['iGoodsId']?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('goods/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });
        $cancel.click(function () {
            $.yRedirect('<?=node_url('goods/index')?>');
        });
        var content = '<?=empty($goods['sContent']) ? '' : $goods['sContent']?>';
        ue.ready(function() {
            if (content) {
                ue.setContent(content);
            }
        });
        $.validator.addMethod("lowestPrice", function(value) {
            var costPrice = parseInt($form.find('#goodsCostPrice').val(), 10);
            return value >= costPrice;
        }, "「最低售价」不能小于成本价");
    });
</script>
