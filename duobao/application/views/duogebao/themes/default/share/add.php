<div class="viewport v-share">
    <div class="grid column">
        <div class="column-hd share-prize-hd">
            <h3>奖品信息</h3>
        </div>
        <div class="share-prize-1">
            <div class="share-prize-info">
                <div class="share-prize-pic">
                    <img src="<?=$active_detail['sImg']?>" width="52" height="52" alt="">
                </div>
                <div class="share-prize-cell">
                    <h3 class="share-prize-name"><?=$active_detail['sGoodsName']?></h3>
                    <p>期号：<strong><?=period_code_encode($active_detail['iActId'], $active_detail['iPeroid'])?></strong></p>
                </div>
            </div>

            <form action="" class="form-share" id="formShare">
                <textarea name="shareContent" id="shareContent" cols="30" rows="10" placeholder="请填写晒单内容（不少于10个字，最多500字）"></textarea>
                <?php
                if(!empty($share_detail['imgs'])) {
                    foreach ($share_detail['imgs'] as $img) {
                        ?>
                        <img class="mimg" onclick="DUOBAO.photo.parsePhoto($(this).index());" data-url="<?=$img?>" onclick="DUOBAO.photo.parsePhoto($(this).index());" src="<?=get_img_resize_url($img, Lib_Constants::SHARE_IMG_SMALL, Lib_Constants::SHARE_IMG_SMALL)?>" width="62" height="62" alt="">
                    <?php
                    }
                }
                ?>
                <a href="javascript:;" class="btn-input-file">
                    <input type="file" name="upload-pic" class="input-file" id="upload-pic" accept="image/*">
                </a>
            </form>

<!--            <form action="" class="form-share-edit" id="formShare">-->
<!--                <textarea name="shareContent" id="shareContent" cols="30" rows="10" placeholder="请填写晒单内容（不少于10个字，最多500字）">--><?//=empty($share_detail['content'])?'':$share_detail['content']?><!--</textarea>-->
<!--                <div class="pics clearfix">-->
<!--                    --><?php
//                        if(!empty($share_detail['imgs'])) {
//                            foreach ($share_detail['imgs'] as $img) {
//                    ?>
<!--                        <img class="mimg" onclick="DUOBAO.photo.parsePhoto($(this).index());" data-url="--><?//=$img?><!--" onclick="DUOBAO.photo.parsePhoto($(this).index());" src="--><?//=get_img_resize_url($img, Lib_Constants::SHARE_IMG_SMALL, Lib_Constants::SHARE_IMG_SMALL)?><!--" width="62" height="62" alt="">-->
<!--                    --><?php
//                            }
//                        }
//                    ?>
<!--                    <!-- <a href="#" class="btn-pic-add">新增图片</a> -->
<!--                    <div class="file-box" style="margin-top: 0;">-->
<!--                        <a href="javascript:;" class="btn-pic-add">新增图片</a>-->
<!--                        <input type="file" name="upload-pic" id="upload-pic" accept="image/*">-->
<!--                    </div>-->
<!--                </div>-->
<!--                <em class="word-limit"></em>-->
<!--            </form>-->
        </div>
    </div>
    <input type="submit" id="shareEditConfirm" class="btn btn-block btn-red btn-share-submit" form="formShare" value="提交">
    <div class="tips-share"><a href="<?=gen_uri('/help/index', array('item'=>'rules_score'))?>">晒单赢积分 查看规则</a></div>
    <!-- photo start -->
    <div class="bg" style="display: none;"></div>
    <div class="pic-large" style="display: none;">
        <div class="container">
            <div class="dleft">上一张</div>
            <div class="dimg">
                <?php
                    if(!empty($share_detail['imgs'])) {
                        foreach ($share_detail['imgs'] as $img) {
                ?>
                    <div style="display: none;"><img src="<?=get_img_resize_url($img, Lib_Constants::SHARE_IMG_MIDDLE, Lib_Constants::SHARE_IMG_MIDDLE)?>" width="220" height="220"></div>
                <?php
                        }
                    }
                ?>
            </div>
            <div class="dright">下一张</div>
        </div>
        <div class="clearfix"></div>
        <div class="del">删除此图</div>
        <div class="back-edit">返回编辑</div>
    </div>
    <!-- photo end -->
</div>

<script type="text/javascript" src="<?=$resource_url?>js/photo.js"></script>
<?php foreach ($third as $v) {
    if ('.css' === strrchr($v, '.css')) {
        ?>
        <link rel="stylesheet" href="/<?=$v?>">
    <?php } else if ('.js' === strrchr($v, '.js')) { ?>
        <script type="text/javascript" src="/<?=$v?>"></script>
    <?php }}?>
<script>
    (function($){
        $('#upload-pic').on('click', function(){
            var $this = $(this);
            $this.fileupload({
                url: '<?=gen_uri('/upload/share_img')?>',
                formData : {'path' : 'share_img'},
                dataType: 'json',
                add:function(e, data) {
                    DUOBAO.ajax.show();
                    data.submit();
                },
                done: function (e, data) {
                    DUOBAO.ajax.hide();
                    var res = data.result;
                    if (res.error==0) {
                        var small_url = typeof (res.small_url) == 'undefined' ? res.url : res.small_url;
                        var mid_url = typeof (res.mid_url) == 'undefined' ? res.url : res.mid_url;
                        $('#upload-pic').parent().before('<img src="'+small_url+'" data-url="'+res.url+'" onclick="DUOBAO.photo.parsePhoto($(this).index());" width="62" height="62" alt="" class="share-img mimg">');
                        $('.pic-large').find('.dimg').append('<div style="display: none;"><img src="'+mid_url+'" width="220" height="220" alt="" class="share-bag-img" /></div>');
                    } else {
                        layer.msg('图片上传失败', {icon: 2});
                    }
                }
            })
        })

        $('#formShare').on('submit', function(){
            var con = $('#shareContent').val();
            var share_imgs = [];
            $('.share-img').each(function(){
                share_imgs.push($(this).attr('data-url'));
            })
            if(!con) {
                layer.msg('请填写晒单内容');
                return false;
            }
            if(con.length <10) {
                layer.msg('晒单内容不能少于10个字');
                return false;
            }
            if(con.length >500) {
                layer.msg('晒单内容不能多于500个字');
                return false;
            }

            if(share_imgs.length==0) {
                layer.msg('请上传晒单图片');
                return false;
            }
            if(share_imgs.length > 6)
            {
                layer.msg('晒单图片不能超过6张');
                return false;
            }
            DUOBAO._post("<?=gen_uri('/share/add')?>", {con:con, imgs:share_imgs.join(','), period_code:"<?=$period_code?>",id:<?=$share_id?>}, function(ret){
                if(ret.retCode == 0) {
                    $('.btn-share-submit').attr('disabled', 'disabled');
                    if (ret.retData && ret.retData.num) {
                        DUOBAO.reword.init(ret.retData.num, '恭喜获得'+ret.retData.awards+ret.retData.type+'!', function(){
                            location.href = "<?=gen_uri('/share/index')?>";
                        });
                    } else {
                        layer.msg('晒单成功', {icon: 1}, function(){
                            location.href = "<?=gen_uri('/share/index')?>";
                        });
                    }
                } else {
                    layer.msg('晒单失败', {icon: 2});
                }
            });
            return false;
        })
    })(jQuery)
</script>